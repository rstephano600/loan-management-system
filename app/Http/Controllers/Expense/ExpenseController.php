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
        $query = Expense::with('category', 'creator');

        if ($request->filled('search')) {
            $query->where('expense_title', 'LIKE', '%'.$request->search.'%')
                  ->orWhereHas('category', function ($q) use ($request) {
                      $q->where('name', 'LIKE', '%'.$request->search.'%');
                  });
        }

        $expenses = $query->latest()->paginate(10)->withQueryString();

        return view('in.expenses.expenses.index', compact('expenses'));
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

    /**
     * Delete expense with items
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
