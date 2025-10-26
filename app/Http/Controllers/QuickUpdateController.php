<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickUpdateController extends Controller
{
    /**
     * عرض صفحة التحديث السريع
     */
    public function index(Company $company)
    {
        $shipments = $company->shipments()
            ->with('invoices')
            ->orderBy('shipping_date', 'desc')
            ->get();

        return view('shipments.quick-update', compact('company', 'shipments'));
    }

    /**
     * تحديث حالة الشحن
     */
    public function updateShippingStatus(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:shipped,not_shipped',
        ]);

        $shipment->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الشحن بنجاح',
        ]);
    }

    /**
     * تحديث حالة الاستلام
     */
    public function updateReceivedStatusQuick(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'received_status' => 'required|in:received,not_received',
        ]);

        $shipment->updateReceivedStatus($request->received_status, Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الاستلام بنجاح',
        ]);
    }

    /**
     * تحديث حالة الدخول
     */
    public function updateEntryStatusQuick(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'entry_status' => 'required|in:entered,not_entered',
        ]);

        $shipment->updateEntryStatus($request->entry_status, Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الدخول بنجاح',
        ]);
    }

    /**
     * تحديث حالة تصريح الدخول
     */
    public function updateEntryPermitStatusQuick(Request $request, Company $company, Shipment $shipment)
    {
        $request->validate([
            'entry_permit_status' => 'required|in:received,not_received',
        ]);

        $shipment->updateEntryPermitStatus($request->entry_permit_status, Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة تصريح الدخول بنجاح',
        ]);
    }
}
