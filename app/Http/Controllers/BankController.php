<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Company;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $banks = $company->banks()->with('transactions')->get();
        return view('banks.index', compact('company', 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company)
    {
        return view('banks.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'opening_balance' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        $bank = $company->banks()->create([
            'name' => $request->name,
            'opening_balance' => $request->opening_balance,
            'current_balance' => $request->opening_balance,
            'currency' => $request->currency,
        ]);

        return redirect()->route('companies.banks.index', $company)
            ->with('success', 'تم إنشاء المصرف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Bank $bank)
    {
        $bank->load(['transactions' => function($query) {
            $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        }, 'transactions.reference']);

        return view('banks.show', compact('company', 'bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company, Bank $bank)
    {
        return view('banks.edit', compact('company', 'bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'opening_balance' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        $bank->update([
            'name' => $request->name,
            'opening_balance' => $request->opening_balance,
            'currency' => $request->currency,
        ]);

        // إعادة حساب الرصيد الحالي
        $bank->updateBalance();

        return redirect()->route('companies.banks.index', $company)
            ->with('success', 'تم تحديث المصرف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Bank $bank)
    {
        // التحقق من عدم وجود فواتير مرتبطة بالمصرف
        if ($bank->invoices()->count() > 0) {
            return redirect()->route('companies.banks.index', $company)
                ->with('error', 'لا يمكن حذف المصرف لوجود فواتير مرتبطة به');
        }

        $bank->delete();

        return redirect()->route('companies.banks.index', $company)
            ->with('success', 'تم حذف المصرف بنجاح');
    }

    /**
     * إضافة عملية إيداع
     */
    public function deposit(Request $request, Company $company, Bank $bank)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $bank->deposit(
            $request->amount,
            $request->description,
            $request->date
        );

        return redirect()->route('companies.banks.show', [$company, $bank])
            ->with('success', 'تم تسجيل عملية الإيداع بنجاح');
    }

    /**
     * إضافة عملية سحب
     */
    public function withdraw(Request $request, Company $company, Bank $bank)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // التحقق من كفاية الرصيد
        if ($bank->current_balance < $request->amount) {
            return redirect()->route('companies.banks.show', [$company, $bank])
                ->with('error', 'الرصيد الحالي غير كافٍ لإجراء عملية السحب');
        }

        $bank->withdraw(
            $request->amount,
            $request->description,
            $request->date
        );

        return redirect()->route('companies.banks.show', [$company, $bank])
            ->with('success', 'تم تسجيل عملية السحب بنجاح');
    }

    /**
     * طباعة كشف حساب المصرف
     */
    public function printStatement(Company $company, Bank $bank)
    {
        $bank->load(['transactions' => function($query) {
            $query->orderBy('date', 'asc')->orderBy('created_at', 'asc');
        }, 'transactions.reference']);

        return view('banks.print-statement', compact('company', 'bank'));
    }
}

