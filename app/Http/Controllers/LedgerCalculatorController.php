<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if (Storage::disk('local')->exists($this->storageFile)) {
            $saved = json_decode(Storage::disk('local')->get($this->storageFile), true);
            if (is_array($saved)) {
                $data = array_merge($data, $saved);
            }
        }

        // If items are empty or contain old structure, initialize with 5 fresh rows
        $isOldStructure = !empty($data['items']) && !isset($data['items'][0]['currency']);
        if (empty($data['items']) || $isOldStructure) {
            $data['items'] = [];
            for ($i = 0; $i < 5; $i++) {
                $data['items'][] = [
                    'id' => $i + 1,
                    'transaction_no' => $i + 1,
                    'currency' => 'USD',
                    'amount' => '',
                    'exchange_rate' => '',
                    'iqd_amount' => 0,
                    'commission_rate' => 1,
                    'commission_type' => 'per_thousand', // 'per_thousand' (‰) or 'percent' (%)
                    'commission_iqd' => 0,
                    'total_iqd' => 0,
                    'notes' => '',
                ];
            }
        }

        return view('exchange-calculator.index', compact('data'));
    }

    /**
     * Save the entire spreadsheet data live to storage.
     */
    public function save(Request $request)
    {
        $payload = [
            'title' => $request->input('title', 'جدول الحسابات وسعر الصرف والعمولات'),
            'items' => $request->input('items', []),
            'updated_at' => now()->toDateTimeString(),
        ];

        Storage::disk('local')->put($this->storageFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح',
        ]);
    }

    /**
     * Clear the sheet data.
     */
    public function clear()
    {
        if (Storage::disk('local')->exists($this->storageFile)) {
            Storage::disk('local')->delete($this->storageFile);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
