<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Company;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $shipments = $company->shipments()->with('invoices')->orderBy('shipping_date', 'desc')->get();
        return view('shipments.index', compact('company', 'shipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company)
    {
        $invoices = $company->invoices;
        return view('shipments.create', compact('company', 'invoices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'status' => 'required|in:shipped,not_shipped',
            'container_number' => 'required|string|max:255',
            'policy_number' => 'required|string|max:255',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'weight' => 'nullable|numeric|min:0',
            'container_size' => 'nullable|string|max:255',
            'carton_count' => 'nullable|integer|min:0',
            'shipping_date' => 'required|date',
            'invoices' => 'nullable|array',
            'invoices.*' => 'exists:invoices,id',
        ]);

        $data = $request->except('invoice_file', 'invoices');

        // رفع ملف الفاتورة
        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('shipments', 'public');
        }

        $shipment = $company->shipments()->create($data);

        // ربط الشحنة بالفواتير
        if ($request->has('invoices')) {
            $shipment->invoices()->attach($request->invoices);
        }

        return redirect()->route('companies.shipments.index', $company)
            ->with('success', 'تم إنشاء الشحنة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Shipment $shipment)
    {
        $shipment->load('invoices');
        return view('shipments.show', compact('company', 'shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company, Shipment $shipment)
    {
        $invoices = $company->invoices;
        return view('shipments.edit', compact('company', 'shipment', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:shipped,not_shipped',
            'container_number' => 'required|string|max:255',
            'policy_number' => 'required|string|max:255',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'weight' => 'nullable|numeric|min:0',
            'container_size' => 'nullable|string|max:255',
            'carton_count' => 'nullable|integer|min:0',
            'shipping_date' => 'required|date',
            'received_status' => 'nullable|in:received,not_received',
            'entry_status' => 'nullable|in:entered,not_entered',
            'entry_permit_status' => 'nullable|in:received,not_received',
            'invoices' => 'nullable|array',
            'invoices.*' => 'exists:invoices,id',
            'delete_invoice_file' => 'nullable|boolean',
        ]);

        $data = $request->except('invoice_file', 'invoices', 'delete_invoice_file');

        // حذف الملف إذا تم طلب ذلك
        if ($request->delete_invoice_file && $shipment->invoice_file) {
            Storage::disk('public')->delete($shipment->invoice_file);
            $data['invoice_file'] = null;
        }

        // رفع ملف الفاتورة الجديد
        if ($request->hasFile('invoice_file')) {
            // حذف الملف القديم
            if ($shipment->invoice_file) {
                Storage::disk('public')->delete($shipment->invoice_file);
            }
            $data['invoice_file'] = $request->file('invoice_file')->store('shipments', 'public');
        }

        // تحديث الحالات مع الإشعارات
        $oldReceivedStatus = $shipment->received_status;
        $oldEntryStatus = $shipment->entry_status;
        $oldEntryPermitStatus = $shipment->entry_permit_status;

        $shipment->update($data);

        // إنشاء إشعارات إذا تم تغيير الحالات
        if ($request->has('received_status') && $request->received_status !== $oldReceivedStatus) {
            $shipment->updateReceivedStatus($request->received_status, Auth::id());
        }

        if ($request->has('entry_status') && $request->entry_status !== $oldEntryStatus) {
            $shipment->updateEntryStatus($request->entry_status, Auth::id());
        }

        if ($request->has('entry_permit_status') && $request->entry_permit_status !== $oldEntryPermitStatus) {
            $shipment->updateEntryPermitStatus($request->entry_permit_status, Auth::id());
        }

        // تحديث ربط الشحنة بالفواتير
        if ($request->has('invoices')) {
            $shipment->invoices()->sync($request->invoices);
        } else {
            $shipment->invoices()->detach();
        }

        return redirect()->route('companies.shipments.index', $company)
            ->with('success', 'تم تحديث الشحنة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Shipment $shipment)
    {
        // حذف ملف الفاتورة
        if ($shipment->invoice_file) {
            Storage::disk('public')->delete($shipment->invoice_file);
        }

        $shipment->delete();

        return redirect()->route('companies.shipments.index', $company)
            ->with('success', 'تم حذف الشحنة بنجاح');
    }

    /**
     * تحديث حالة الاستلام
     */
    public function updateReceivedStatus(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'received_status' => 'required|in:received,not_received',
        ]);

        $shipment->updateReceivedStatus($request->received_status, Auth::id());

        return redirect()->route('companies.shipments.show', [$company, $shipment])
            ->with('success', 'تم تحديث حالة الاستلام بنجاح');
    }

    /**
     * تحديث حالة الدخول
     */
    public function updateEntryStatus(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'entry_status' => 'required|in:entered,not_entered',
        ]);

        $shipment->updateEntryStatus($request->entry_status, Auth::id());

        return redirect()->route('companies.shipments.show', [$company, $shipment])
            ->with('success', 'تم تحديث حالة الدخول بنجاح');
    }

    /**
     * تحديث حالة تصريح الدخول
     */
    public function updateEntryPermitStatus(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'entry_permit_status' => 'required|in:received,not_received',
        ]);

        $shipment->updateEntryPermitStatus($request->entry_permit_status, Auth::id());

        return redirect()->route('companies.shipments.show', [$company, $shipment])
            ->with('success', 'تم تحديث حالة تصريح الدخول بنجاح');
    }

    /**
     * ربط الشحنة بفاتورة
     */
    public function attachInvoice(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $shipment->invoices()->attach($request->invoice_id);

        return redirect()->route('companies.shipments.show', [$company, $shipment])
            ->with('success', 'تم ربط الفاتورة بالشحنة بنجاح');
    }
}

