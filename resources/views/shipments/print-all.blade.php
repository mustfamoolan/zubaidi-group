<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف جميع الشحنات - {{ $company->name }}</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .page-break { page-break-before: always; }
        }

        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .report-title {
            font-size: 20px;
            color: #666;
            margin-bottom: 5px;
        }

        .report-date {
            font-size: 14px;
            color: #888;
        }

        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1976d2;
        }

        .shipments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        .shipments-table th,
        .shipments-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        .shipments-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }

        .shipments-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-not-shipped {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-received {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-not-received {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .weight {
            color: #007bff;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .print-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ طباعة الكشف</button>

    <div class="header">
        <div class="company-name">{{ $company->name }}</div>
        <div class="report-title">كشف جميع الشحنات</div>
        <div class="report-date">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">إجمالي الشحنات</div>
            <div class="summary-value">{{ $shipments->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">الشحنات المشحونة</div>
            <div class="summary-value">{{ $shippedCount }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">الشحنات غير المشحونة</div>
            <div class="summary-value">{{ $notShippedCount }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">المستلمة</div>
            <div class="summary-value">{{ $shipments->where('received_status', 'received')->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">غير المستلمة</div>
            <div class="summary-value">{{ $shipments->where('received_status', 'not_received')->count() }}</div>
        </div>
    </div>

    <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333;">جدول الشحنات</h3>

    <table class="shipments-table">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الحاوية</th>
                <th>رقم البوليصة</th>
                <th>تاريخ الشحن</th>
                <th>الوزن (كغ)</th>
                <th>حجم الحاوية</th>
                <th>عدد الكراتين</th>
                <th>حالة الشحن</th>
                <th>حالة الاستلام</th>
                <th>حالة الدخول</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shipments as $index => $shipment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $shipment->container_number }}</td>
                    <td>{{ $shipment->policy_number }}</td>
                    <td>{{ $shipment->shipping_date->format('Y-m-d') }}</td>
                    <td class="weight">{{ $shipment->weight ? number_format($shipment->weight, 2) : '-' }}</td>
                    <td>{{ $shipment->container_size ?? '-' }}</td>
                    <td>{{ $shipment->carton_count ?? '-' }}</td>
                    <td>
                        @if($shipment->status === 'shipped')
                            <span class="status-shipped">مشحون</span>
                        @else
                            <span class="status-not-shipped">غير مشحون</span>
                        @endif
                    </td>
                    <td>
                        @if($shipment->received_status === 'received')
                            <span class="status-received">مستلمة</span>
                        @elseif($shipment->received_status === 'not_received')
                            <span class="status-not-received">غير مستلمة</span>
                        @else
                            <span style="color: #666;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($shipment->entry_permit_status === 'received')
                            <span class="status-received">مستلمة</span>
                        @elseif($shipment->entry_permit_status === 'not_received')
                            <span class="status-not-received">غير مستلمة</span>
                        @else
                            <span style="color: #666;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #666;">لا توجد شحنات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>تم إنشاء هذا الكشف تلقائياً من نظام إدارة الشركات</p>
        <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
