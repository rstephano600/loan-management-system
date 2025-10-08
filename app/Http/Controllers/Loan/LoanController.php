<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\Client;
use App\Models\LoanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['client', 'category']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('loan_category_id')) {
            $query->where('loan_category_id', $request->loan_category_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('loan_number', 'like', '%' . $request->search . '%');
        }

        $loans = $query->latest()->paginate(10);
        $categories = LoanCategory::where('is_active', true)->get();

        return view('in.loans.loans.index', compact('loans', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $categories = LoanCategory::where('is_active', true)->get();

        return view('in.loans.loans.create', compact('clients', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_category_id' => 'required|exists:loan_categories,id',
            'disbursement_date' => 'nullable|date',
            'status' => 'required|in:pending,approved,active,completed,defaulted,closed',
        ]);

        $category = LoanCategory::findOrFail($request->loan_category_id);

        // Auto-generate loan number
        $loanNumber = 'LN-' . strtoupper(Str::random(6));

        // Calculate totals
        $principal = $category->principal_amount;
        $interest = ($principal * $category->interest_rate) / 100;
        $totalPayable = $principal + $interest;

        $loan = Loan::create([
            'client_id' => $request->client_id,
            'loan_category_id' => $request->loan_category_id,
            'loan_number' => $loanNumber,
            'disbursement_date' => $request->disbursement_date,
            'status' => $request->status,
            'first_payment_date' => $request->first_payment_date,
            'last_payment_date' => $request->last_payment_date,
            'total_interest' => $interest,
            'total_payable' => $totalPayable,
            'outstanding_principal' => $principal,
            'outstanding_interest' => $interest,
            'total_outstanding' => $totalPayable,
            'created_by' => Auth::id() ?? 1,
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['client', 'category']);
        return view('in.loans.loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        $clients = Client::all();
        $categories = LoanCategory::where('is_active', true)->get();

        return view('in.loans.loans.edit', compact('loan', 'clients', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_category_id' => 'required|exists:loan_categories,id',
            'disbursement_date' => 'nullable|date',
            'status' => 'required|in:pending,approved,active,completed,defaulted,closed',
        ]);

        $category = LoanCategory::findOrFail($request->loan_category_id);
        $principal = $category->principal_amount;
        $interest = ($principal * $category->interest_rate) / 100;
        $totalPayable = $principal + $interest;

        $loan->update([
            'client_id' => $request->client_id,
            'loan_category_id' => $request->loan_category_id,
            'disbursement_date' => $request->disbursement_date,
            'status' => $request->status,
            'first_payment_date' => $request->first_payment_date,
            'last_payment_date' => $request->last_payment_date,
            'total_interest' => $interest,
            'total_payable' => $totalPayable,
            'outstanding_principal' => $principal,
            'outstanding_interest' => $interest,
            'total_outstanding' => $totalPayable,
            'updated_by' => Auth::id() ?? 1,
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }
}
