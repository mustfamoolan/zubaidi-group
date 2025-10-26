<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف جميع الفواتير - {{ $company->name }}</title>
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
        
        .invoices-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .invoices-table th,
        .invoices-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        
        .invoices-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        
        .invoices-table tr:nth-child(even) {
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
        
        .amount-usd {
            color: #007bff;
            font-weight: bold;
        }
        
        .amount-iqd {
            color: #28a745;
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
        <div class="report-title">كشف جميع الفواتير</div>
        <div class="report-date">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>
    
    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">إجمالي الفواتير</div>
            <div class="summary-value">{{ $invoices->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">إجمالي المبلغ (دولار)</div>
            <div class="summary-value">{{ number_format($totalAmountUsd, 2) }} USD</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">إجمالي المبلغ (دينار)</div>
            <div class="summary-value">{{ number_format($totalAmountIqd, 2) }} IQD</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">الفواتير المشحونة</div>
            <div class="summary-value">{{ $invoices->where('shipping_status', 'shipped')->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">الفواتير غير المشحونة</div>
            <div class="summary-value">{{ $invoices->where('shipping_status', 'not_shipped')->count() }}</div>
        </div>
    </div>
    
    <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333;">جدول الفواتير</h3>
    
    <table class="invoices-table">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>الشركة المستفيدة</th>
                <th>المصرف</th>
                <th>التاريخ</th>
                <th>المبلغ (USD)</th>
                <th>سعر الصرف</th>
                <th>المبلغ النهائي (IQD)</th>
                <th>حالة الشحن</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $index => $invoice)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->beneficiary->name ?? $invoice->beneficiary_company }}</td>
                    <td>{{ $invoice->bank->name ?? 'غير محدد' }}</td>
                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    <td class="amount-usd">{{ number_format($invoice->amount_usd, 2) }}</td>
                    <td>{{ number_format($invoice->exchange_rate, 2) }}</td>
                    <td class="amount-iqd">{{ number_format($invoice->final_amount, 2) }}</td>
                    <td>
                        @if($invoice->shipping_status === 'shipped')
                            <span class="status-shipped">مشحونة</span>
                        @else
                            <span class="status-not-shipped">غير مشحونة</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #666;">لا توجد فواتير</td>
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
