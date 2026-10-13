<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\DinarCalculatorRecord;

class DinarCalculatorController extends Controller
{
    /**
     * Path to the persistent storage backup file.
     */
    protected $storageFile = 'dinar_calculator_sheet.json';

    /**
     * Display the dinar calculator page.
     */
    public function index()
    {
        $data = [
            'title' => 'حاسبة الصرف دينار',
            'items' => [],
        ];

        $userId = auth()->id();
        $companyId = auth()->user()?->company_id;

        // 1. محاولة القراءة من قاعدة البيانات أولاً
        try {
            $query = DinarCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }

            $records = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

            if ($records->isNotEmpty()) {
                $data['items'] = $records->map(function ($r, $idx) {
                    return [
                        'id'               => $r->id,
                        'transaction_no'   => $r->transaction_no ?: ($idx + 1),
                        'currency'         => $r->currency ?: 'USD',
                        'amount'           => $r->amount > 0 ? (float)$r->amount : '',
                        'exchange_rate'    => $r->exchange_rate > 0 ? (float)$r->exchange_rate : '',
                        'country_rate'     => $r->country_rate > 0 ? (float)$r->country_rate : '',
                        'iqd_base'         => (float)$r->iqd_base,
                        'commission_rate'  => (float)$r->commission_rate,
                        'commission_type'  => $r->commission_type ?: 'per_thousand',
                        'commission_iqd'   => (float)$r->commission_iqd,
                        'customs_rate'     => (float)$r->customs_rate,
                        'customs_type'     => $r->customs_type ?: 'percent',
                        'customs_iqd'      => (float)$r->customs_iqd,
                        'total_iqd'        => (float)$r->total_iqd,
                        'notes'            => $r->notes ?: '',
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {
            // في حال عدم عمل قاعدة البيانات أو عدم تشغيل الـ migration، يتم الاعتماد على ملف التخزين
        }

        // 2. إذا كانت قاعدة البيانات فارغة، نقرأ من ملف JSON الاحتياطي إن وُجد
        if (empty($data['items']) && Storage::disk('local')->exists($this->storageFile)) {
            $saved = json_decode(Storage::disk('local')->get($this->storageFile), true);
            if (is_array($saved) && !empty($saved['items'])) {
                $data = array_merge($data, $saved);
            }
        }

        // 3. إذا لم توجد أي بيانات سابقة، ننشئ 5 أسطر افتراضية
        if (empty($data['items'])) {
            $data['items'] = [];
            for ($i = 0; $i < 5; $i++) {
                $data['items'][] = [
                    'id'               => $i + 1,
                    'transaction_no'   => $i + 1,
                    'currency'         => 'USD',
                    'amount'           => '',
                    'exchange_rate'    => '',
                    'country_rate'     => '',
                    'iqd_base'         => 0,
                    'commission_rate'  => 1,
                    'commission_type'  => 'per_thousand',
                    'commission_iqd'   => 0,
                    'customs_rate'     => 0,
                    'customs_type'     => 'percent',
                    'customs_iqd'      => 0,
                    'total_iqd'        => 0,
                    'notes'            => '',
                ];
            }
        }

        return view('dinar-calculator.index', compact('data'));
    }

    /**
     * Save spreadsheet data to database & backup storage.
     */
    public function save(Request $request)
    {
        $userId = auth()->id();
        $companyId = auth()->user()?->company_id;
        $items = $request->input('items', []);
        $title = $request->input('title', 'حاسبة الصرف دينار');

        // 1. الحفظ في قاعدة البيانات
        $dbSaved = false;
        try {
            DB::beginTransaction();

            $query = DinarCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }
            $query->delete();

            foreach ($items as $idx => $item) {
                DinarCalculatorRecord::create([
                    'user_id'          => $userId,
                    'company_id'       => $companyId,
                    'transaction_no'   => $item['transaction_no'] ?? ($idx + 1),
                    'currency'         => $item['currency'] ?? 'USD',
                    'amount'           => floatval($item['amount'] ?? 0),
                    'exchange_rate'    => floatval($item['exchange_rate'] ?? 0),
                    'country_rate'     => !empty($item['country_rate']) ? floatval($item['country_rate']) : null,
                    'iqd_base'         => floatval($item['iqd_base'] ?? 0),
                    'commission_rate'  => floatval($item['commission_rate'] ?? 0),
                    'commission_type'  => $item['commission_type'] ?? 'per_thousand',
                    'commission_iqd'   => floatval($item['commission_iqd'] ?? 0),
                    'customs_rate'     => floatval($item['customs_rate'] ?? 0),
                    'customs_type'     => $item['customs_type'] ?? 'percent',
                    'customs_iqd'      => floatval($item['customs_iqd'] ?? 0),
                    'total_iqd'        => floatval($item['total_iqd'] ?? 0),
                    'notes'            => $item['notes'] ?? null,
                    'sort_order'       => $idx + 1,
                ]);
            }

            DB::commit();
            $dbSaved = true;
        } catch (\Exception $e) {
            DB::rollBack();
            // تسجيل الخطأ أو المتابعة للحفظ في ملف JSON
        }

        // 2. الحفظ في ملف JSON كنسخة احتياطية سريعة
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
            'message'  => $dbSaved ? 'تم الحفظ في قاعدة البيانات والملف بنجاح' : 'تم الحفظ بنجاح',
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
            $query = DinarCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }
            $query->delete();
        } catch (\Exception $e) {
            // تجاهل في حال عدم توفر الاتصال
        }

        if (Storage::disk('local')->exists($this->storageFile)) {
            Storage::disk('local')->delete($this->storageFile);
        }

        return response()->json(['success' => true]);
    }
}
