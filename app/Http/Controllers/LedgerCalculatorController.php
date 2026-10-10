<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerCalculatorController extends Controller
{
    /**
     * Display the exchange rate & excel-like ledger calculator page with real database data.
     */
    public function index(Request $request)
    {
        // 1. Fetch all real customers
        $customers = DB::connection('china')->table('Customers')
            ->orderBy('Name', 'asc')
            ->get();

        // 2. Fetch all real ledgers joined with customer info
        $ledgers = DB::connection('china')->table('CustomerLedgers')
            ->leftJoin('Customers', 'CustomerLedgers.CustomerId', '=', 'Customers.Id')
            ->select(
                'CustomerLedgers.Id',
                'CustomerLedgers.CustomerId',
                'CustomerLedgers.Title',
                'CustomerLedgers.Notes',
                'CustomerLedgers.CreatedAt',
                'Customers.Name as CustomerName',
                'Customers.Phone as CustomerPhone'
            )
            ->orderBy('CustomerLedgers.Id', 'desc')
            ->get();

        // 3. Determine selected ledger (default to the first ledger or ledger with most items)
        $selectedLedgerId = $request->query('ledger_id');
        if ($selectedLedgerId) {
            $selectedLedger = $ledgers->firstWhere('Id', (int) $selectedLedgerId);
        } else {
            // Default to ledger 2 (مؤسسة النور) or the first available
            $selectedLedger = $ledgers->firstWhere('Id', 2) ?? $ledgers->first();
        }

        if (!$selectedLedger && $ledgers->isNotEmpty()) {
            $selectedLedger = $ledgers->first();
        }

        // 4. Fetch all real items for the selected ledger
        $items = collect();
        if ($selectedLedger) {
            $items = DB::connection('china')->table('LedgerItems')
                ->where('CustomerLedgerId', $selectedLedger->Id)
                ->orderBy('Id', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => (int) $item->Id,
                        'customer_ledger_id' => (int) $item->CustomerLedgerId,
                        'details' => $item->Details ?? '',
                        'purchase_amount' => (float) $item->PurchaseAmount,
                        'store_payment_details' => $item->StorePaymentDetails ?? '',
                        'store_payment' => (float) $item->StorePayment,
                        'usd_amount' => (float) $item->UsdAmount,
                        'exchange_rate' => (float) $item->RmbExchangeRate,
                        'rmb_amount' => (float) $item->RmbAmount,
                        'transfer_date' => $item->TransferDate ? substr($item->TransferDate, 0, 10) : date('Y-m-d'),
                    ];
                });
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ledger' => $selectedLedger,
                'items' => $items,
            ]);
        }

        return view('exchange-calculator.index', compact('customers', 'ledgers', 'selectedLedger', 'items'));
    }

    /**
     * Store a newly created row directly in the real database.
     */
    public function storeItem(Request $request)
    {
        $ledgerId = (int) $request->input('CustomerLedgerId');
        $details = $request->input('Details', '');
        $purchaseAmount = (float) $request->input('PurchaseAmount', 0);
        $storePaymentDetails = $request->input('StorePaymentDetails', '');
        $storePayment = (float) $request->input('StorePayment', 0);
        $usdAmount = (float) $request->input('UsdAmount', 0);
        $exchangeRate = (float) $request->input('RmbExchangeRate', 7.25);
        $rmbAmount = round($usdAmount * $exchangeRate, 2);
        $transferDate = $request->input('TransferDate', date('Y-m-d'));

        $id = DB::connection('china')->table('LedgerItems')->insertGetId([
            'CustomerLedgerId' => $ledgerId,
            'Details' => $details,
            'PurchaseAmount' => $purchaseAmount,
            'StorePaymentDetails' => $storePaymentDetails,
            'StorePayment' => $storePayment,
            'UsdAmount' => $usdAmount,
            'RmbExchangeRate' => $exchangeRate,
            'RmbAmount' => $rmbAmount,
            'TransferDate' => $transferDate,
        ]);

        return response()->json([
            'success' => true,
            'id' => $id,
            'rmb_amount' => $rmbAmount,
        ]);
    }

    /**
     * Update an existing row in the real database immediately.
     */
    public function updateItem(Request $request, $id)
    {
        $purchaseAmount = (float) $request->input('PurchaseAmount', 0);
        $storePayment = (float) $request->input('StorePayment', 0);
        $usdAmount = (float) $request->input('UsdAmount', 0);
        $exchangeRate = (float) $request->input('RmbExchangeRate', 7.25);
        $rmbAmount = round($usdAmount * $exchangeRate, 2);
        $details = $request->input('Details', '');
        $storePaymentDetails = $request->input('StorePaymentDetails', '');
        $transferDate = $request->input('TransferDate');

        DB::connection('china')->table('LedgerItems')
            ->where('Id', (int) $id)
            ->update([
                'Details' => $details,
                'PurchaseAmount' => $purchaseAmount,
                'StorePaymentDetails' => $storePaymentDetails,
                'StorePayment' => $storePayment,
                'UsdAmount' => $usdAmount,
                'RmbExchangeRate' => $exchangeRate,
                'RmbAmount' => $rmbAmount,
                'TransferDate' => $transferDate,
            ]);

        return response()->json([
            'success' => true,
            'rmb_amount' => $rmbAmount,
        ]);
    }

    /**
     * Delete a row from the real database.
     */
    public function deleteItem($id)
    {
        DB::connection('china')->table('LedgerItems')
            ->where('Id', (int) $id)
            ->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Create a new ledger for a customer.
     */
    public function storeLedger(Request $request)
    {
        $request->validate([
            'CustomerId' => 'required',
            'Title' => 'required|string|max:255',
        ]);

        $id = DB::connection('china')->table('CustomerLedgers')->insertGetId([
            'CustomerId' => (int) $request->input('CustomerId'),
            'Title' => $request->input('Title'),
            'Notes' => $request->input('Notes', ''),
            'CreatedAt' => now()->toDateTimeString(),
        ]);

        return redirect()->route('exchange-calculator.index', ['ledger_id' => $id])
            ->with('success', 'تم إنشاء كشف الحساب الجديد بنجاح');
    }

    /**
     * Create a new customer if needed.
     */
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        $customerId = DB::connection('china')->table('Customers')->insertGetId([
            'Name' => $request->input('Name'),
            'Phone' => $request->input('Phone', ''),
            'Address' => $request->input('Address', ''),
            'Notes' => $request->input('Notes', ''),
        ]);

        // Auto create a first ledger
        $ledgerId = DB::connection('china')->table('CustomerLedgers')->insertGetId([
            'CustomerId' => $customerId,
            'Title' => 'سجل مشتريات وحسابات - ' . $request->input('Name'),
            'Notes' => '',
            'CreatedAt' => now()->toDateTimeString(),
        ]);

        return redirect()->route('exchange-calculator.index', ['ledger_id' => $ledgerId])
            ->with('success', 'تمت إضافة العميل الجديد بنجاح');
    }
}
