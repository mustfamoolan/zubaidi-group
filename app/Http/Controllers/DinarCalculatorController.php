<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DinarCalculatorController extends Controller
{
    /**
     * Path to the persistent storage file.
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

        if (Storage::disk('local')->exists($this->storageFile)) {
            $saved = json_decode(Storage::disk('local')->get($this->storageFile), true);
            if (is_array($saved)) {
                $data = array_merge($data, $saved);
            }
        }

        // Initialize with 5 fresh rows if empty
        if (empty($data['items'])) {
            $data['items'] = [];
            for ($i = 0; $i < 5; $i++) {
                $data['items'][] = [
                    'id'               => $i + 1,
                    'transaction_no'   => $i + 1,
                    'currency'         => 'USD',
                    'amount'           => '',
                    'exchange_rate'    => '',   // سعر الصرف الرئيسي (USD → IQD)
                    'country_rate'     => '',   // ضرب سعر الصرف بالبلد (فقط AED و RMB)
                    'iqd_base'         => 0,    // المبلغ العراقي قبل الإضافات
                    'commission_rate'  => 1,
                    'commission_type'  => 'per_thousand',
                    'commission_iqd'   => 0,
                    'customs_rate'     => 0,    // كمرك + ضريبة %
                    'customs_type'     => 'percent',
                    'customs_iqd'      => 0,
                    'total_iqd'        => 0,    // المجموع النهائي عراقي
                    'notes'            => '',
                ];
            }
        }

        return view('dinar-calculator.index', compact('data'));
    }

    /**
     * Save spreadsheet data to storage.
     */
    public function save(Request $request)
    {
        $payload = [
            'title'      => $request->input('title', 'حاسبة الصرف دينار'),
            'items'      => $request->input('items', []),
            'updated_at' => now()->toDateTimeString(),
        ];

        Storage::disk('local')->put(
            $this->storageFile,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return response()->json(['success' => true, 'message' => 'تم الحفظ بنجاح']);
    }

    /**
     * Clear the sheet data.
     */
    public function clear()
    {
        if (Storage::disk('local')->exists($this->storageFile)) {
            Storage::disk('local')->delete($this->storageFile);
        }

        return response()->json(['success' => true]);
    }
}
