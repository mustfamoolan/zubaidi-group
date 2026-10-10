<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LedgerCalculatorController extends Controller
{
    /**
     * Display the exchange rate & excel-like ledger calculator page.
     */
    public function index()
    {
        return view('exchange-calculator.index');
    }
}
