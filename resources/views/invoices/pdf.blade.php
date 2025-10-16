<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة #{{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            direction: rtl;
            text-align: right;
            background: white;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            unicode-bidi: embed;
        }

        .container {
            width: 190mm;
            height: 277mm;
            margin: 10mm auto;
            padding: 8mm;
            background: white;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8mm;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5mm;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            text-align: center;
            margin: 8mm 0 6mm 0;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6mm;
        }

        .info-section {
            flex: 1;
            margin: 0 3mm;
        }

        .info-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3mm;
            border-bottom: 1px solid #ccc;
            padding-bottom: 1mm;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
            font-size: 10px;
        }

        .info-label {
            font-weight: bold;
        }

        .info-value {
            color: #000;
        }

        .financial-section {
            margin: 6mm 0;
        }

        .financial-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3mm;
            text-align: center;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4mm;
        }

        .financial-table th,
        .financial-table td {
            padding: 2mm;
            text-align: right;
            border: 1px solid #ccc;
            font-size: 10px;
        }

        .financial-table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .total-row {
            background: #f5f5f5 !important;
            font-weight: bold;
        }

        .shipments-section {
            margin: 4mm 0;
        }

        .shipments-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3mm;
            text-align: center;
        }

        .shipments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4mm;
        }

        .shipments-table th,
        .shipments-table td {
            padding: 1mm;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 9px;
        }

        .shipments-table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .summary-section {
            margin: 4mm 0;
            text-align: left;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
            padding: 1mm 0;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-value {
            font-weight: bold;
        }

        .footer {
            margin-top: 6mm;
            padding-top: 3mm;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .payment-info {
            margin: 3mm 0;
            padding: 2mm;
            border: 1px solid #ccc;
            background: #f9f9f9;
            font-size: 10px;
        }

        .status-badge {
            padding: 1mm 2mm;
            border-radius: 2px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-paid {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .status-unpaid {
            background: #fff3cd;
            color: #856404;
        }

        @media print {
            body {
                font-size: 10px;
                width: 210mm;
                height: 297mm;
            }

            .container {
                width: 190mm;
                height: 277mm;
                margin: 10mm auto;
                padding: 8mm;
            }

            .invoice-title {
                font-size: 20px;
            }
        }

        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company->name }}</div>
            <div style="text-align: left; font-size: 10px;">
                <div>العراق، بغداد</div>
                <div>info@alzubaidgroup.com</div>
                <div>+964 (0) 770 123 4567</div>
            </div>
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">فاتورة</div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <div class="info-section">
                <h3>معلومات الفاتورة</h3>
                <div class="info-row">
                    <span class="info-label">رقم الفاتورة:</span>
                    <span class="info-value">#{{ $invoice->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">تاريخ الإصدار:</span>
                    <span class="info-value">{{ $invoice->invoice_date->format('Y-m-d') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الحالة:</span>
                    <span class="info-value">
                        @if($invoice->status === 'paid')
                            <span class="status-badge status-paid">مدفوعة</span>
                        @else
                            <span class="status-badge status-unpaid">غير مدفوعة</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="info-section">
                <h3>معلومات المستفيد</h3>
                <div class="info-row">
                    <span class="info-label">اسم المستفيد:</span>
                    <span class="info-value">{{ $invoice->beneficiary_company }}</span>
                </div>
                @if($invoice->beneficiary)
                <div class="info-row">
                    <span class="info-label">المستفيد المسجل:</span>
                    <span class="info-value">{{ $invoice->beneficiary->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Details -->
        <div class="financial-section">
            <h3>التفاصيل المالية</h3>
            <table class="financial-table">
                <thead>
                    <tr>
                        <th>الوصف</th>
                        <th>المبلغ</th>
                        <th>العملة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>المبلغ الأصلي</td>
                        <td>{{ number_format($invoice->amount_usd ?? 0, 2) }}</td>
                        <td>USD</td>
                    </tr>
                    <tr>
                        <td>سعر الصرف</td>
                        <td>{{ number_format($invoice->exchange_rate ?? 0, 2) }}</td>
                        <td>دينار/دولار</td>
                    </tr>
                    <tr>
                        <td>المبلغ بعد التحويل</td>
                        <td>{{ number_format(($invoice->amount_usd ?? 0) * ($invoice->exchange_rate ?? 0), 2) }}</td>
                        <td>دينار عراقي</td>
                    </tr>
                    <tr>
                        <td>عمولة المصرف</td>
                        <td>{{ number_format($invoice->bank_commission ?? 0, 2) }}</td>
                        <td>دينار عراقي</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>المبلغ الإجمالي</strong></td>
                        <td><strong>{{ number_format($invoice->total_amount_iqd ?? $invoice->amount ?? 0, 2) }}</strong></td>
                        <td><strong>دينار عراقي</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bank Information -->
        @if($invoice->bank)
        <div class="payment-info">
            <strong>معلومات المصرف:</strong><br>
            اسم المصرف: {{ $invoice->bank->name }}<br>
            العملة: {{ $invoice->bank->currency }}
        </div>
        @endif

        <!-- Shipments Section -->
        @if($invoice->shipments && $invoice->shipments->count() > 0)
        <div class="shipments-section">
            <h3>الشحنات المرتبطة</h3>
            <table class="shipments-table">
                <thead>
                    <tr>
                        <th>م.</th>
                        <th>رقم الحاوية</th>
                        <th>رقم البوليصة</th>
                        <th>الحالة</th>
                        <th>الوزن</th>
                        <th>حجم الحاوية</th>
                        <th>عدد الكراتين</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->shipments as $index => $shipment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $shipment->container_number }}</td>
                        <td>{{ $shipment->policy_number }}</td>
                        <td>
                            @if($shipment->status === 'shipped')
                                مشحون
                            @else
                                غير مشحون
                            @endif
                        </td>
                        <td>{{ $shipment->weight ?? 'غير محدد' }}</td>
                        <td>
                            @if($shipment->container_size === 'C')
                                20 قدم
                            @elseif($shipment->container_size === 'B')
                                40 قدم
                            @elseif($shipment->container_size === 'M')
                                45 قدم
                            @else
                                {{ $shipment->container_size ?? 'غير محدد' }}
                            @endif
                        </td>
                        <td>{{ $shipment->carton_count ?? 'غير محدد' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">المجموع الفرعي:</span>
                <span class="summary-value">{{ number_format(($invoice->amount_usd ?? 0) * ($invoice->exchange_rate ?? 0), 2) }} دينار</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">عمولة المصرف:</span>
                <span class="summary-value">{{ number_format($invoice->bank_commission ?? 0, 2) }} دينار</span>
            </div>
            <div class="summary-row" style="border-top: 2px solid #000; font-size: 12px;">
                <span class="summary-label">الإجمالي:</span>
                <span class="summary-value">{{ number_format($invoice->total_amount_iqd ?? $invoice->amount ?? 0, 2) }} دينار عراقي</span>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="payment-info">
            <strong>تعليمات الدفع:</strong><br>
            يرجى دفع هذا المبلغ في حسابنا المصرفي المحدد أعلاه عند الاستلام.
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>شكراً لاختياركم خدماتنا</strong></p>
            <p>هذه الفاتورة صادرة من نظام إدارة الفواتير - مجموعة الزبيدي</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</body>
</html>
