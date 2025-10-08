<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\LoanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanCategoryController extends Controller
{
    /**
     * Display a listing of the resource with search and filters.
     */
    public function index(Request $request)
    {
        $query = LoanCategory::query();

        // 🔍 Search by name or conditions
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('conditions', 'like', "%{$search}%");
            });
        }

        // 🎯 Filter by active status
        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        // 💱 Filter by currency
        if ($request->filled('currency')) {
            $query->where('currency', $request->input('currency'));
        }

        // 🔁 Filter by repayment frequency
        if ($request->filled('frequency')) {
            $query->where('repayment_frequency', $request->input('frequency'));
        }

        // 📄 Paginate results
        $loanCategories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('in.loans.loan_categories.index', compact('loanCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('in.loans.loan_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'max_term_months' => 'required|integer|min:1',
            'max_term_days' => 'nullable|integer|min:1',
            'principal_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gte:min_amount',
            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'installment_amount' => 'required|numeric|min:0',
            'total_repayable_amount' => 'required|numeric|min:0',
            'conditions' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;

        LoanCategory::create($validated);

        return redirect()->route('loan_categories.index')->with('success', 'Loan category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanCategory $loanCategory)
    {
        return view('in.loans.loan_categories.show', compact('loanCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanCategory $loanCategory)
    {
        return view('in.loans.loan_categories.edit', compact('loanCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanCategory $loanCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'max_term_months' => 'required|integer|min:1',
            'max_term_days' => 'nullable|integer|min:1',
            'principal_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gte:min_amount',
            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'installment_amount' => 'required|numeric|min:0',
            'total_repayable_amount' => 'required|numeric|min:0',
            'conditions' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = Auth::id();

        $loanCategory->update($validated);

        return redirect()->route('loan_categories.index')->with('success', 'Loan category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanCategory $loanCategory)
    {
        $loanCategory->delete();

        return redirect()->route('loan_categories.index')->with('success', 'Loan category deleted successfully.');
    }

    /**
     * Toggle loan category active status.
     */
    public function toggleStatus(LoanCategory $loanCategory)
    {
        $loanCategory->is_active = !$loanCategory->is_active;
        $loanCategory->updated_by = Auth::id();
        $loanCategory->save();

        return redirect()->route('loan_categories.index')->with('success', 'Loan category status updated.');
    }
}
