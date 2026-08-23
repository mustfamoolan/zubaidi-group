<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب المصرف - {{ $bank->name }}</title>
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

        .bank-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .info-value {
            color: #666;
        }

        .balance-summary {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .balance-item {
            text-align: center;
        }

        .balance-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .balance-value {
            font-size: 18px;
            font-weight: bold;
            color: #1976d2;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .transactions-table th,
        .transactions-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .transactions-table td.description-cell {
            font-size: 10px;
            text-align: right;
            white-space: normal;
            word-wrap: break-word;
            max-width: 150px;
            line-height: 1.3;
        }

        .transactions-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }

        .transactions-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .transaction-type {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .type-deposit {
            background-color: #d4edda;
            color: #155724;
        }

        .type-withdrawal {
            background-color: #f8d7da;
            color: #721c24;
        }

        .type-invoice {
            background-color: #fff3cd;
            color: #856404;
        }

        .amount-positive {
            color: #28a745;
            font-weight: bold;
        }

        .amount-negative {
            color: #dc3545;
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
        <div class="report-title">كشف حساب المصرف</div>
        <div class="report-date">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="bank-info">
        <div class="info-item">
            <span class="info-label">اسم المصرف:</span>
            <span class="info-value">{{ $bank->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">العملة:</span>
            <span class="info-value">{{ $bank->currency }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">الرصيد الافتتاحي:</span>
            <span class="info-value">{{ number_format($bank->opening_balance, 2) }} {{ $bank->currency }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">الرصيد الحالي:</span>
            <span class="info-value">{{ number_format($bank->current_balance, 2) }} {{ $bank->currency }}</span>
        </div>
    </div>

    <div class="balance-summary">
        <div class="balance-item">
            <div class="balance-label">إجمالي الإيداعات</div>
            <div class="balance-value">{{ number_format($bank->transactions->where('type', 'deposit')->sum('amount'), 2) }} {{ $bank->currency }}</div>
        </div>
        <div class="balance-item">
            <div class="balance-label">إجمالي السحوبات</div>
            <div class="balance-value">{{ number_format($bank->transactions->where('type', 'withdrawal')->sum('amount'), 2) }} {{ $bank->currency }}</div>
        </div>
        <div class="balance-item">
            <div class="balance-label">إجمالي خصم الفواتير</div>
            <div class="balance-value">{{ number_format($bank->transactions->where('type', 'invoice_deduction')->sum('amount'), 2) }} {{ $bank->currency }}</div>
        </div>
        <div class="balance-item">
            <div class="balance-label">عدد العمليات</div>
            <div class="balance-value">{{ $bank->transactions->count() }}</div>
        </div>
    </div>

    <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333;">سجل الحركات</h3>

    <table class="transactions-table">
        <thead>
            <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>النوع</th>
                <th>الوصف</th>
                <th>المبلغ الكلي</th>
                <th>بدون عمولة</th>
                <th>عمولة</th>
                <th>الرصيد بعد العملية</th>
            </tr>
        </thead>
        <tbody>
            @php
                $runningBalance = $bank->opening_balance;
                $transactionNumber = 1;
            @endphp

            @forelse($bank->transactions as $transaction)
                @php
                    if ($transaction->type === 'deposit') {
                        $runningBalance += $transaction->amount;
                    } else {
                        $runningBalance -= $transaction->amount;
                    }
                @endphp
                <tr>
                    <td>{{ $transactionNumber++ }}</td>
                    <td>{{ ($transaction->date ?? $transaction->created_at)->format('Y/n/j') }}</td>
                    <td>
                        @if($transaction->type === 'deposit')
                            <span class="transaction-type type-deposit">إيداع</span>
                        @elseif($transaction->type === 'withdrawal')
                            <span class="transaction-type type-withdrawal">سحب</span>
                        @else
                            <span class="transaction-type type-invoice">خصم فاتورة</span>
                        @endif
                    </td>
                    <td class="description-cell">{{ $transaction->description ?? '-' }}</td>
                    <td>
                        @if($transaction->type === 'deposit')
                            <span class="amount-positive">+ {{ number_format($transaction->amount, 2) }}</span>
                        @else
                            <span class="amount-negative">- {{ number_format($transaction->amount, 2) }}</span>
                        @endif
                        <span style="font-size: 12px;">{{ $bank->currency }}</span>
                    </td>
                    <td>
                        @if($transaction->reference_type === 'App\\Models\\Invoice' && $transaction->reference)
                            @php
                                $invoice = $transaction->reference;
                                $amountWithoutCommission = ($invoice->total_amount_iqd ?? $invoice->amount ?? 0) - ($invoice->bank_commission ?? 0);
                            @endphp
                            <span style="font-size: 12px;">{{ number_format($amountWithoutCommission, 2) }} {{ $bank->currency }}</span>
                        @else
                            <span style="font-size: 12px; color: #999;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->reference_type === 'App\\Models\\Invoice' && $transaction->reference)
                            @php
                                $invoice = $transaction->reference;
                                $commission = $invoice->bank_commission ?? 0;
                            @endphp
                            <span style="font-size: 12px;">{{ number_format($commission, 2) }} {{ $bank->currency }}</span>
                        @else
                            <span style="font-size: 12px; color: #999;">-</span>
                        @endif
                    </td>
                    <td class="amount-positive">{{ number_format($runningBalance, 2) }} {{ $bank->currency }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #666;">لا توجد حركات</td>
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
