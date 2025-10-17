<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;

use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses with search and pagination
     */
public function index(Request $request)
{
    $query = Expense::with(['category', 'creator']);

    // 🔍 Search by title or category
    if ($request->filled('search')) {
        $query->where('expense_title', 'LIKE', '%'.$request->search.'%')
              ->orWhereHas('category', function ($q) use ($request) {
                  $q->where('name', 'LIKE', '%'.$request->search.'%');
              });
    }

    // 📅 Filter by date range
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('expense_date', [
            $request->start_date, 
            $request->end_date
        ]);
    }

    // 🏷️ Filter by category
    if ($request->filled('category_id')) {
        $query->where('expense_category_id', $request->category_id);
    }

    // 🧾 Fetch filtered results
    $expenses = $query->latest()->paginate(20)->withQueryString();

    // 💰 Calculate total amount used in current filter
    $totalUsed = $query->sum('total_amount');

    // 🗂 Fetch categories for filter dropdown
    $categories = \App\Models\ExpenseCategory::all();

    return view('in.expenses.expenses.index', compact('expenses', 'totalUsed', 'categories'));
}


    /**
     * Show form for creating a new expense
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', 'active')->get();
        return view('in.expenses.expenses.create', compact('categories'));
    }

    /**
     * Store a new expense with items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
            'items.*.supplier_name' => 'nullable|string|max:255',
            'items.*.attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle main expense attachment
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expense_attachments', 'public');
        }

        DB::transaction(function () use ($validated) {
            $expense = Expense::create($validated);

            foreach ($validated['items'] as $item) {
                $itemAttachment = null;
                if (!empty($item['attachment']) && is_file($item['attachment'])) {
                    $itemAttachment = $item['attachment']->store('expense_item_attachments', 'public');
                }
                $totalCost = ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);

                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'] ?? 0,
                    'total_cost' => $totalCost,
                    'supplier_name' => $item['supplier_name'] ?? null,
                    'attachment' => $itemAttachment,
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Show a single expense with items
     */
    public function show(Expense $expense)
    {
        $expense->load('items', 'category', 'creator', 'editor');
        return view('in.expenses.expenses.show', compact('expense'));
    }

    /**
     * Show form to edit an expense with items
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('status', 'active')->get();
        $expense->load('items');
        return view('in.expenses.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update an expense and its items
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
            'items.*.supplier_name' => 'nullable|string|max:255',
            'items.*.attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle main attachment
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expense_attachments', 'public');
        }

        DB::transaction(function () use ($validated, $expense) {
            $expense->update([
                'expense_title' => $validated['expense_title'],
                'description' => $validated['description'] ?? null,
                'expense_date' => $validated['expense_date'],
                'total_amount' => $validated['total_amount'],
                'currency' => $validated['currency'],
                'expense_category_id' => $validated['expense_category_id'] ?? null,
                'attachment' => $validated['attachment'] ?? $expense->attachment,
                'status' => $validated['status'] ?? 'approved',
                'updated_by' => Auth::id(),
            ]);

            // Remove old items and re-insert
            $expense->items()->delete();

            foreach ($validated['items'] as $item) {
                $itemAttachment = null;
                if (!empty($item['attachment']) && is_file($item['attachment'])) {
                    $itemAttachment = $item['attachment']->store('expense_item_attachments', 'public');
                }
                $totalCost = ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);

                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'] ?? 0,
                    'total_cost' => $totalCost,
                    'supplier_name' => $item['supplier_name'] ?? null,
                    'attachment' => $itemAttachment,
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }
public function export(Request $request)
{
    $query = Expense::with('category');

    if ($request->filled('category_id')) {
        $query->where('expense_category_id', $request->category_id);
    }

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
    }

    $expenses = $query->get(['expense_title', 'total_amount', 'expense_date']);

    $filename = 'expenses_export_' . now()->format('Y_m_d_His') . '.csv';

    $handle = fopen('php://temp', 'r+');
    fputcsv($handle, ['Title', 'Amount', 'Date']);

    foreach ($expenses as $expense) {
        fputcsv($handle, [$expense->expense_title, $expense->total_amount, $expense->expense_date]);
    }

    rewind($handle);
    return response(stream_get_contents($handle), 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
    ]);
}

public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'expenses_by_category.xlsx');
    }

    public function exportPDF()
    {
        $expensesByCategory = Expense::with('items')->get()->groupBy('category');
        $pdf = Pdf::loadView('exports.expenses', compact('expensesByCategory'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('expenses_by_category.pdf');
    }
    /**
     * Delete expense with items
     */
    public function destroy(Expense $expense)
    {
        // $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
