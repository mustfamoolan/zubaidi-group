<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ExchangeCalculatorRecord;

class LedgerCalculatorController extends Controller
{
    /**
     * Path to the persistent storage file.
     */
    protected $storageFile = 'exchange_calculator_sheet.json';

    /**
     * Display the independent excel-like ledger calculator page.
     */
    public function index()
    {
        $data = [
            'title' => 'جدول الحسابات وسعر الصرف والعمولات',
            'items' => [],
        ];

        $userId = auth()->id();
        $companyId = auth()->user()?->company_id;

        // 1. محاولة القراءة من قاعدة البيانات أولاً
        try {
            $query = ExchangeCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }

            $records = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

            if ($records->isNotEmpty()) {
                $data['items'] = $records->map(function ($r) {
                    return [
                        'id'               => $r->id,
                        'transaction_no'   => $r->transaction_no !== null ? (string)$r->transaction_no : '',
                        'currency'         => $r->currency ?: 'USD',
                        'amount'           => $r->amount > 0 ? (float)$r->amount : '',
                        'exchange_rate'    => $r->exchange_rate > 0 ? (float)$r->exchange_rate : '',
                        'iqd_amount'       => (float)$r->iqd_amount,
                        'commission_rate'  => (float)$r->commission_rate,
                        'commission_type'  => $r->commission_type ?: 'per_thousand',
                        'commission_iqd'   => (float)$r->commission_iqd,
                        'swift_commission' => isset($r->swift_commission) && $r->swift_commission > 0 ? (float)$r->swift_commission : '',
                        'total_iqd'        => (float)$r->total_iqd,
                        'notes'            => $r->notes ?: '',
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {
            // في حال عدم توفر الاتصال
        }

        // 2. القراءة من ملف JSON في حال عدم وجود سجلات في قاعدة البيانات
        if (empty($data['items']) && Storage::disk('local')->exists($this->storageFile)) {
            $saved = json_decode(Storage::disk('local')->get($this->storageFile), true);
            if (is_array($saved) && !empty($saved['items'])) {
                $data = array_merge($data, $saved);
            }
        }

        // 3. تهيئة 5 أسطر افتراضية إذا كانت البيانات فارغة
        $isOldStructure = !empty($data['items']) && !isset($data['items'][0]['currency']);
        if (empty($data['items']) || $isOldStructure) {
            $data['items'] = [];
            for ($i = 0; $i < 5; $i++) {
                $data['items'][] = [
                    'id'               => $i + 1,
                    'transaction_no'   => '',
                    'currency'         => 'USD',
                    'amount'           => '',
                    'exchange_rate'    => '',
                    'iqd_amount'       => 0,
                    'commission_rate'  => 1,
                    'commission_type'  => 'per_thousand',
                    'commission_iqd'   => 0,
                    'swift_commission' => '',
                    'total_iqd'        => 0,
                    'notes'            => '',
                ];
            }
        }

        return view('exchange-calculator.index', compact('data'));
    }

    /**
     * Save the entire spreadsheet data live to database & storage.
     */
    public function save(Request $request)
    {
        $userId = auth()->id();
        $companyId = auth()->user()?->company_id;
        $items = $request->input('items', []);
        $title = $request->input('title', 'جدول الحسابات وسعر الصرف والعمولات');

        // 1. الحفظ في قاعدة البيانات
        $dbSaved = false;
        try {
            DB::beginTransaction();

            $query = ExchangeCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }
            $query->delete();

            $cleanNum = function($v) {
                if ($v === null || $v === '') return 0.0;
                return floatval(str_replace(',', '', (string)$v));
            };

            foreach ($items as $idx => $item) {
                ExchangeCalculatorRecord::create([
                    'user_id'          => $userId,
                    'company_id'       => $companyId,
                    'transaction_no'   => isset($item['transaction_no']) ? (string)$item['transaction_no'] : null,
                    'currency'         => $item['currency'] ?? 'USD',
                    'amount'           => $cleanNum($item['amount'] ?? 0),
                    'exchange_rate'    => $cleanNum($item['exchange_rate'] ?? 0),
                    'iqd_amount'       => $cleanNum($item['iqd_amount'] ?? 0),
                    'commission_rate'  => $cleanNum($item['commission_rate'] ?? 0),
                    'commission_type'  => $item['commission_type'] ?? 'per_thousand',
                    'commission_iqd'   => $cleanNum($item['commission_iqd'] ?? 0),
                    'swift_commission' => $cleanNum($item['swift_commission'] ?? 0),
                    'total_iqd'        => $cleanNum($item['total_iqd'] ?? 0),
                    'notes'            => $item['notes'] ?? null,
                    'sort_order'       => $idx + 1,
                ]);
            }

            DB::commit();
            $dbSaved = true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        // 2. الحفظ في ملف JSON كنسخة احتياطية
        $payload = [
            'title'      => $title,
            'items'      => $items,
            'updated_at' => now()->toDateTimeString(),
            'db_saved'   => $dbSaved,
        ];

        Storage::disk('local')->put(
            $this->storageFile,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return response()->json([
            'success'  => true,
            'db_saved' => $dbSaved,
            'message'  => 'تم الحفظ بنجاح',
        ]);
    }

    /**
     * Clear the sheet data from database & storage.
     */
    public function clear()
    {
        $userId = auth()->id();
        $companyId = auth()->user()?->company_id;

        try {
            $query = ExchangeCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }
            $query->delete();
        } catch (\Exception $e) {
            // تجاهل
        }

        if (Storage::disk('local')->exists($this->storageFile)) {
            Storage::disk('local')->delete($this->storageFile);
        }

        return response()->json(['success' => true]);
    }
}
