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
                $data['items'] = $records->map(function ($r) {
                    return [
                        'id'                 => $r->id,
                        'currency'           => $r->currency ?: 'USD',
                        'exchange_rate'      => $r->exchange_rate > 0 ? (float)$r->exchange_rate : '',
                        'iqd_base'           => (float)$r->iqd_base,
                        'commission_rate'    => (float)$r->commission_rate,
                        'commission_type'    => $r->commission_type ?: 'per_thousand',
                        'commission_iqd'     => (float)$r->commission_iqd,
                        'customs_rate'       => (float)$r->customs_rate,
                        'customs_type'       => $r->customs_type ?: 'percent',
                        'customs_iqd'        => (float)$r->customs_iqd,
                        'total_iqd'          => (float)$r->total_iqd,
                        'country_rate'       => $r->country_rate > 0 ? (float)$r->country_rate : '',
                        'country_op'         => $r->country_op ?: 'mul',
                        'country_final_rate' => (float)$r->country_final_rate,
                    ];
                })->toArray();
            }
        } catch (\Exception $e) {
            // في حال عدم توفر الاتصال يتم الاعتماد على التخزين
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
                    'id'                 => $i + 1,
                    'currency'           => 'USD',
                    'exchange_rate'      => '',
                    'iqd_base'           => 0,
                    'commission_rate'    => 1,
                    'commission_type'    => 'per_thousand',
                    'commission_iqd'     => 0,
                    'customs_rate'       => 0,
                    'customs_type'       => 'percent',
                    'customs_iqd'        => 0,
                    'total_iqd'          => 0,
                    'country_rate'       => '',
                    'country_op'         => 'mul',
                    'country_final_rate' => 0,
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

            $cleanNum = function($v) {
                if ($v === null || $v === '') return 0.0;
                return floatval(str_replace(',', '', (string)$v));
            };

            foreach ($items as $idx => $item) {
                DinarCalculatorRecord::create([
                    'user_id'            => $userId,
                    'company_id'         => $companyId,
                    'currency'           => $item['currency'] ?? 'USD',
                    'exchange_rate'      => $cleanNum($item['exchange_rate'] ?? 0),
                    'iqd_base'           => $cleanNum($item['iqd_base'] ?? 0),
                    'commission_rate'    => $cleanNum($item['commission_rate'] ?? 0),
                    'commission_type'    => $item['commission_type'] ?? 'per_thousand',
                    'commission_iqd'     => $cleanNum($item['commission_iqd'] ?? 0),
                    'customs_rate'       => $cleanNum($item['customs_rate'] ?? 0),
                    'customs_type'       => $item['customs_type'] ?? 'percent',
                    'customs_iqd'        => $cleanNum($item['customs_iqd'] ?? 0),
                    'total_iqd'          => $cleanNum($item['total_iqd'] ?? 0),
                    'country_rate'       => !empty($item['country_rate']) ? $cleanNum($item['country_rate']) : null,
                    'country_op'         => $item['country_op'] ?? 'mul',
                    'country_final_rate' => $cleanNum($item['country_final_rate'] ?? 0),
                    'sort_order'         => $idx + 1,
                ]);
            }

            DB::commit();
            $dbSaved = true;
        } catch (\Exception $e) {
            DB::rollBack();
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
