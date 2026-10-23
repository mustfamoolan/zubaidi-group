<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalculatorArchive;
use App\Models\ExchangeCalculatorRecord;
use App\Models\DinarCalculatorRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CalculatorArchiveController extends Controller
{
    /**
     * Display listing of archives with tabs for both calculators.
     */
    public function index(Request $request)
    {
        $type = $request->input('type', 'exchange');
        if (!in_array($type, ['exchange', 'dinar'])) {
            $type = 'exchange';
        }

        $userId = auth()->id();
        $companyId = auth()->user()?->company_id;

        $baseQuery = CalculatorArchive::query();
        if ($companyId) {
            $baseQuery->where('company_id', $companyId);
        } elseif ($userId) {
            $baseQuery->where('user_id', $userId);
        }

        // إحصائيات الأعداد للتبويبات
        $exchangeCount = (clone $baseQuery)->where('type', 'exchange')->count();
        $dinarCount    = (clone $baseQuery)->where('type', 'dinar')->count();

        // استعلام الكشوفات مع التصفية
        $archivesQuery = (clone $baseQuery)->where('type', $type);

        if ($search = $request->input('search')) {
            $archivesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($fromDate = $request->input('from_date')) {
            $archivesQuery->whereDate('archive_date', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $archivesQuery->whereDate('archive_date', '<=', $toDate);
        }

        $archives = $archivesQuery->orderBy('archive_date', 'desc')
                                  ->orderBy('id', 'desc')
                                  ->paginate(15)
                                  ->withQueryString();

        return view('calculator-archives.index', compact(
            'archives',
            'type',
            'exchangeCount',
            'dinarCount'
        ));
    }

    /**
     * Shortcut for exchange calculator archives.
     */
    public function exchangeArchives(Request $request)
    {
        return redirect()->route('calculator-archives.index', ['type' => 'exchange']);
    }

    /**
     * Shortcut for dinar calculator archives.
     */
    public function dinarArchives(Request $request)
    {
        return redirect()->route('calculator-archives.index', ['type' => 'dinar']);
    }

    /**
     * Archive the current active exchange calculator sheet and reset active sheet.
     */
    public function archiveExchange(Request $request)
    {
        $userId    = auth()->id();
        $companyId = auth()->user()?->company_id;

        $title       = $request->input('title') ?: ('كشف حسابات صرف العملات - ' . date('Y-m-d'));
        $archiveDate = $request->input('archive_date') ?: date('Y-m-d');
        $notes       = $request->input('notes');
        $items       = $request->input('items', []);
        $totals      = $request->input('totals', []);

        // تصفية الأسطر الفارغة بالكامل
        $cleanNum = function($v) {
            if ($v === null || $v === '') return 0.0;
            return floatval(str_replace(',', '', (string)$v));
        };

        $validItems = array_values(array_filter($items, function ($it) use ($cleanNum) {
            $amount = $cleanNum($it['amount'] ?? 0);
            $rate   = $cleanNum($it['exchange_rate'] ?? 0);
            $notes  = trim($it['notes'] ?? '');
            $inv    = trim($it['transaction_no'] ?? '');
            return $amount > 0 || $rate > 0 || !empty($notes) || !empty($inv);
        }));

        $archive = null;
        DB::beginTransaction();
        try {
            // 1. إنشاء سجل الأرشيف
            $archive = CalculatorArchive::create([
                'company_id'   => $companyId,
                'user_id'      => $userId,
                'type'         => 'exchange',
                'title'        => $title,
                'archive_date' => $archiveDate,
                'rows_count'   => count($validItems),
                'totals'       => $totals,
                'items'        => $validItems,
                'notes'        => $notes,
            ]);

            // 2. تفريغ السجلات النشطة من جدول الحسابات الحالي
            $query = ExchangeCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }
            $query->delete();

            // 3. إعادة تعيين ملف التخزين المحلي الاحتياطي
            $resetData = [
                'title'      => 'جدول الحسابات وسعر الصرف والعمولات',
                'items'      => [],
                'updated_at' => now()->toDateTimeString(),
            ];
            Storage::disk('local')->put('exchange_calculator_sheet.json', json_encode($resetData, JSON_UNESCAPED_UNICODE));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء أرشفة الكشف: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success'      => true,
            'message'      => 'تمت أرشفة الكشف وتفريغ الجدول بنجاح للبدء بكشف جديد.',
            'archive_id'   => $archive->id,
            'show_url'     => route('calculator-archives.show', $archive->id),
            'archives_url' => route('calculator-archives.index', ['type' => 'exchange']),
        ]);
    }

    /**
     * Archive the current active dinar calculator sheet and reset active sheet.
     */
    public function archiveDinar(Request $request)
    {
        $userId    = auth()->id();
        $companyId = auth()->user()?->company_id;

        $title       = $request->input('title') ?: ('حاسبة الصرف دينار - ' . date('Y-m-d'));
        $archiveDate = $request->input('archive_date') ?: date('Y-m-d');
        $notes       = $request->input('notes');
        $items       = $request->input('items', []);
        $totals      = $request->input('totals', []);

        // تصفية الأسطر الصالحة
        $cleanNum = function($v) {
            if ($v === null || $v === '') return 0.0;
            return floatval(str_replace(',', '', (string)$v));
        };

        $validItems = array_values(array_filter($items, function ($it) use ($cleanNum) {
            $rate = $cleanNum($it['exchange_rate'] ?? 0);
            return $rate > 0;
        }));

        $archive = null;
        DB::beginTransaction();
        try {
            // 1. إنشاء سجل الأرشيف
            $archive = CalculatorArchive::create([
                'company_id'   => $companyId,
                'user_id'      => $userId,
                'type'         => 'dinar',
                'title'        => $title,
                'archive_date' => $archiveDate,
                'rows_count'   => count($validItems),
                'totals'       => $totals,
                'items'        => $validItems,
                'notes'        => $notes,
            ]);

            // 2. تفريغ السجلات النشطة من جدول حاسبة الدينار
            $query = DinarCalculatorRecord::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            } elseif ($userId) {
                $query->where('user_id', $userId);
            }
            $query->delete();

            // 3. إعادة تعيين ملف التخزين المحلي الاحتياطي
            $resetData = [
                'title'      => 'حاسبة الصرف دينار',
                'items'      => [],
                'updated_at' => now()->toDateTimeString(),
            ];
            Storage::disk('local')->put('dinar_calculator_sheet.json', json_encode($resetData, JSON_UNESCAPED_UNICODE));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء أرشفة الكشف: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success'      => true,
            'message'      => 'تمت أرشفة كشف الدينار وتفريغ الجدول بنجاح للبدء بكشف جديد.',
            'archive_id'   => $archive->id,
            'show_url'     => route('calculator-archives.show', $archive->id),
            'archives_url' => route('calculator-archives.index', ['type' => 'dinar']),
        ]);
    }

    /**
     * Display a specific archived sheet in read-only mode with identical styling and print layout.
     */
    public function show($id)
    {
        $userId    = auth()->id();
        $companyId = auth()->user()?->company_id;

        $query = CalculatorArchive::with(['user', 'company']);
        if ($companyId) {
            $query->where('company_id', $companyId);
        } elseif ($userId) {
            $query->where('user_id', $userId);
        }

        $archive = $query->findOrFail($id);

        if ($archive->type === 'dinar') {
            return view('dinar-calculator.show-archive', compact('archive'));
        }

        return view('exchange-calculator.show-archive', compact('archive'));
    }

    /**
     * Delete an archived sheet.
     */
    public function destroy($id)
    {
        $userId    = auth()->id();
        $companyId = auth()->user()?->company_id;

        $query = CalculatorArchive::query();
        if ($companyId) {
            $query->where('company_id', $companyId);
        } elseif ($userId) {
            $query->where('user_id', $userId);
        }

        $archive = $query->findOrFail($id);
        $type = $archive->type;
        $archive->delete();

        return redirect()->route('calculator-archives.index', ['type' => $type])
                         ->with('success', 'تم حذف الكشف من الأرشيف بنجاح.');
    }
}
