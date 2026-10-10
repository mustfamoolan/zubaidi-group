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
            'title' => 'كشف حساب العملات والمشتريات',
            'default_rate' => 7.25,
            'items' => [],
        ];

        if (Storage::disk('local')->exists($this->storageFile)) {
            $saved = json_decode(Storage::disk('local')->get($this->storageFile), true);
            if (is_array($saved)) {
                $data = array_merge($data, $saved);
            }
        }

        // If items are completely empty, start with 5 empty rows ready for immediate typing like Excel
        if (empty($data['items'])) {
            $data['items'] = [];
            for ($i = 0; $i < 5; $i++) {
                $data['items'][] = [
                    'id' => $i + 1,
                    'details' => '',
                    'purchase_amount' => '',
                    'store_payment_details' => '',
                    'store_payment' => '',
                    'usd_amount' => '',
                    'exchange_rate' => 7.25,
                    'rmb_amount' => '',
                    'transfer_date' => date('Y-m-d'),
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
            'title' => $request->input('title', 'كشف حساب العملات والمشتريات'),
            'default_rate' => (float) $request->input('default_rate', 7.25),
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
