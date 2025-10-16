<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Company;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $beneficiaries = $company->beneficiaries()->withCount('invoices')->orderBy('name')->get();
        return view('beneficiaries.index', compact('company', 'beneficiaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company)
    {
        return view('beneficiaries.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company->beneficiaries()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('companies.beneficiaries.index', $company)
            ->with('success', 'تم إنشاء المستفيد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Beneficiary $beneficiary)
    {
        $beneficiary->load(['invoices.bank']);
        $statistics = $beneficiary->getStatistics();

        return view('beneficiaries.show', compact('company', 'beneficiary', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company, Beneficiary $beneficiary)
    {
        return view('beneficiaries.edit', compact('company', 'beneficiary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Beneficiary $beneficiary)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $beneficiary->update([
            'name' => $request->name,
        ]);

        return redirect()->route('companies.beneficiaries.index', $company)
            ->with('success', 'تم تحديث المستفيد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Beneficiary $beneficiary)
    {
        // التحقق من وجود فواتير مرتبطة
        if ($beneficiary->invoices()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'لا يمكن حذف المستفيد لأنه مرتبط بفواتير موجودة']);
        }

        $beneficiary->delete();

        return redirect()->route('companies.beneficiaries.index', $company)
            ->with('success', 'تم حذف المستفيد بنجاح');
    }
}
