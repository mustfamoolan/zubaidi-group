<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::paginate(10);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('companies', 'public');
            $data['image'] = $imagePath;
        }

        Company::create($data);

        return redirect()->route('companies.index')
            ->with('success', 'تم إنشاء الشركة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);

        // تخزين معرف الشركة في الجلسة
        session(['selected_company_id' => $company->id]);

        // توجيه المستخدم إلى لوحة تحكم الشركة
        return $this->dashboard($company);
    }

    /**
     * Display the company dashboard.
     */
    public function dashboard(Company $company)
    {
        // حفظ آخر شركة في الـ session
        session(['last_company_id' => $company->id]);

        // إحصائيات سريعة
        $totalBanks = $company->banks()->count();
        $totalBalance = $company->banks()->sum('current_balance');
        $totalInvoices = $company->invoices()->count();
        $paidInvoices = $company->invoices()->where('status', 'paid')->count();
        $unpaidInvoices = $company->invoices()->where('status', 'unpaid')->count();
        $totalShipments = $company->shipments()->count();
        $shippedCount = $company->shipments()->where('status', 'shipped')->count();
        $notShippedCount = $company->shipments()->where('status', 'not_shipped')->count();

        // أحدث الإشعارات (للشحنات التابعة لهذه الشركة)
        $recentNotifications = \App\Models\Notification::whereHasMorph(
            'notifiable',
            [\App\Models\Shipment::class],
            function ($query) use ($company) {
                $query->where('company_id', $company->id);
            }
        )
        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // عدد الإشعارات غير المقروءة
        $unreadNotificationsCount = \App\Models\Notification::whereHasMorph(
            'notifiable',
            [\App\Models\Shipment::class],
            function ($query) use ($company) {
                $query->where('company_id', $company->id);
            }
        )
        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->whereNull('read_at')
        ->count();

        return view('companies.dashboard', compact(
            'company',
            'totalBanks',
            'totalBalance',
            'totalInvoices',
            'paidInvoices',
            'unpaidInvoices',
            'totalShipments',
            'shippedCount',
            'notShippedCount',
            'recentNotifications',
            'unreadNotificationsCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($company->image) {
                Storage::disk('public')->delete($company->image);
            }

            $imagePath = $request->file('image')->store('companies', 'public');
            $data['image'] = $imagePath;
        }

        $company->update($data);

        return redirect()->route('companies.index')
            ->with('success', 'تم تحديث الشركة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);

        // حذف الصورة إذا كانت موجودة
        if ($company->image) {
            Storage::disk('public')->delete($company->image);
        }

        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'تم حذف الشركة بنجاح');
    }
}
