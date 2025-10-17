<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Shipment;
use App\Models\Bank;
use App\Models\Beneficiary;
use App\Models\Company;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('q');
        $companyId = session('last_company_id');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [
            'invoices' => [],
            'shipments' => [],
            'banks' => [],
            'beneficiaries' => [],
            'pages' => []
        ];

        // البحث في الفواتير
        if ($companyId) {
            $results['invoices'] = Invoice::where('company_id', $companyId)
                ->where(function($q) use ($query) {
                    $q->where('invoice_number', 'LIKE', "%{$query}%")
                      ->orWhere('beneficiary_company', 'LIKE', "%{$query}%");
                })
                ->with('bank')
                ->limit(5)
                ->get()
                ->map(function($invoice) use ($companyId) {
                    return [
                        'id' => $invoice->id,
                        'title' => "فاتورة #{$invoice->invoice_number}",
                        'subtitle' => $invoice->beneficiary_company,
                        'url' => route('companies.invoices.show', [$companyId, $invoice->id]),
                        'type' => 'invoice'
                    ];
                });

            // البحث في الشحنات
            $results['shipments'] = Shipment::where('company_id', $companyId)
                ->where(function($q) use ($query) {
                    $q->where('container_number', 'LIKE', "%{$query}%")
                      ->orWhere('policy_number', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function($shipment) use ($companyId) {
                    return [
                        'id' => $shipment->id,
                        'title' => "شحنة #{$shipment->container_number}",
                        'subtitle' => "البوليصة: {$shipment->policy_number}",
                        'url' => route('companies.shipments.show', [$companyId, $shipment->id]),
                        'type' => 'shipment'
                    ];
                });

            // البحث في المصارف
            $results['banks'] = Bank::where('company_id', $companyId)
                ->where('name', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function($bank) use ($companyId) {
                    return [
                        'id' => $bank->id,
                        'title' => $bank->name,
                        'subtitle' => "الرصيد: " . number_format($bank->current_balance, 2),
                        'url' => route('companies.banks.show', [$companyId, $bank->id]),
                        'type' => 'bank'
                    ];
                });

            // البحث في المستفيدين
            $results['beneficiaries'] = Beneficiary::where('company_id', $companyId)
                ->where('name', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function($beneficiary) use ($companyId) {
                    return [
                        'id' => $beneficiary->id,
                        'title' => $beneficiary->name,
                        'subtitle' => "مستفيد",
                        'url' => route('companies.beneficiaries.show', [$companyId, $beneficiary->id]),
                        'type' => 'beneficiary'
                    ];
                });
        }

        // البحث في الصفحات
        $pages = $this->getPages($companyId);
        $results['pages'] = collect($pages)->filter(function($page) use ($query) {
            return stripos($page['title'], $query) !== false;
        })->take(5)->values();

        return response()->json($results);
    }

    private function getPages($companyId)
    {
        if (!$companyId) {
            return [];
        }

        return [
            ['title' => 'لوحة التحكم', 'url' => route('companies.show', $companyId), 'type' => 'page'],
            ['title' => 'الفواتير', 'url' => route('companies.invoices.index', $companyId), 'type' => 'page'],
            ['title' => 'الشحنات', 'url' => route('companies.shipments.index', $companyId), 'type' => 'page'],
            ['title' => 'المصارف', 'url' => route('companies.banks.index', $companyId), 'type' => 'page'],
            ['title' => 'المستفيدين', 'url' => route('companies.beneficiaries.index', $companyId), 'type' => 'page'],
            ['title' => 'الإشعارات', 'url' => route('notifications.index'), 'type' => 'page'],
            ['title' => 'المستخدمين', 'url' => route('users.index'), 'type' => 'page'],
            ['title' => 'الأدوار', 'url' => route('roles.index'), 'type' => 'page'],
            ['title' => 'الصلاحيات', 'url' => route('permissions.index'), 'type' => 'page'],
        ];
    }
}
