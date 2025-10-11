<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GroupCenter;
use App\Models\Group;


class LoanRequestNewClientController extends Controller
{
 /**
     * Display a listing of client loan requests (pending or active).
     */
    public function index(Request $request)
    {
        $query = Loan::with(['client', 'loanCategory'])
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->whereIn('is_new_client',[true]);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        $loans = $query->latest()->paginate(10);

        return view('in.loans.requests.new_clients.index', compact('loans'));
    }

    /**
     * Show form for creating a new loan request.
     */

     public function create()
     {
    // Active loan categories
         $categories = LoanCategory::where('is_active', true)->get();

    // Active clients
         $clients = Client::where('status', 'active')
             ->with(['group', 'groupCenter']) // eager-load relationships
             ->get();

    // Group centers and groups for dropdowns (optional)
         $centers = GroupCenter::where('is_active', true)->get();
         $groups = Group::where('is_active', true)->get();

         return view('in.loans.requests.new_clients.create', compact('categories', 'clients', 'groups', 'centers'));
     }

    /**
     * Store a newly created loan request in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_category_id' => 'required|exists:loan_categories,id',
        ]);

        // Fetch the chosen loan category
        $loanCategory = LoanCategory::findOrFail($validated['loan_category_id']);
        $client = Client::findOrFail($validated['client_id']);

        // Generate a unique loan number
        $loanNumber = 'LN-' . $client->last_name . strtoupper(Str::random(4)) . '-' . now()->format('YmdHis');

        // Create the pending loan request
        $loan = Loan::create([
            'group_id' => $client->group_id,
            'group_center_id' => $client->group_center_id,
            'client_id' => $validated['client_id'],
            'collection_officer_id' => $client->assigned_loan_officer_id,
            'loan_category_id' => $loanCategory->id,
            'loan_number' => $loanNumber,

            // client request details
            'amount_requested' => $loanCategory->amount_disbursed ?? 0,
            'client_payable_frequency' =>$loanCategory->principal_due ?? 0,
            'status' => 'pending',

            // preload from category
            'amount_disbursed' => $loanCategory->amount_disbursed ?? 0, // initially same as requested
            'membership_fee' => 2000,
            'insurance_fee' => $loanCategory->insurance_fee ?? 0,
            'officer_visit_fee' => $loanCategory->officer_visit_fee ?? 0,
            'interest_rate' => $loanCategory->interest_rate ?? 0,
            'interest_amount' => $loanCategory->interest_amount ?? 0,
            'repayment_frequency' => $loanCategory->repayment_frequency ?? 'daily',
            'max_term_days' => $loanCategory->max_term_days ?? 0,
            'max_term_months' => $loanCategory->max_term_months ?? 0,
            'total_days_due' => $loanCategory->total_days_due ?? 0,
            'principal_due' => $loanCategory->principal_due ?? 0,
            'interest_due' => $loanCategory->interest_due ?? 0,
            'currency' => $loanCategory->currency ?? 'TZS',

            // system
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('loan_request_new_client.index')
            ->with('success', 'Loan request submitted successfully and is awaiting approval.');
    }

    /**
     * Display details of a specific loan request.
     */
    public function show(Loan $loan)
    {
        $loan->load(['client', 'loanCategory']);
        return view('in.loans.requests.new_clients.show', compact('loan'));
    }

    /**
     * Edit loan request (optional before approval).
     */
    public function edit(Loan $loan)
    {
        if ($loan->status !== 'approved') {
            return redirect()->back()->with('error', 'Only pending loan requests can be edited.');
        }

        $loanCategories = LoanCategory::where('is_active', true)->get();
        $clients = Client::where('status', 'active')->get();

        return view('in.loans.requests.new_clients.edit', compact('loan', 'loanCategories', 'clients'));
    }

    /**
     * Update a pending loan request.
     */
    public function update(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending loans can be updated.');
        }

        $validated = $request->validate([
            'loan_category_id' => 'required|exists:loan_categories,id',
        ]);

        $loanCategory = LoanCategory::findOrFail($validated['loan_category_id']);

        $loan->update([
            // client request details
            'amount_requested' => $loanCategory->amount_disbursed ?? 0,
            'client_payable_frequency' =>$loanCategory->$repayment_frequency,

            // preload from category
            'amount_disbursed' => $loanCategory->amount_disbursed ?? 0, // initially same as requested
            'membership_fee' => 2000,
            'insurance_fee' => $loanCategory->insurance_fee ?? 0,
            'officer_visit_fee' => $loanCategory->officer_visit_fee ?? 0,
            'interest_rate' => $loanCategory->interest_rate ?? 0,
            'interest_amount' => $loanCategory->interest_amount ?? 0,
            'repayment_frequency' => $loanCategory->repayment_frequency ?? 'daily',
            'max_term_days' => $loanCategory->max_term_days ?? 0,
            'max_term_months' => $loanCategory->max_term_months ?? 0,
            'total_days_due' => $loanCategory->total_days_due ?? 0,
            'principal_due' => $loanCategory->principal_due ?? 0,
            'interest_due' => $loanCategory->interest_due ?? 0,
            'currency' => $loanCategory->currency ?? 'TZS',
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('loan_request_new_client.index')
            ->with('success', 'Loan request updated successfully.');
    }

    /**
     * Delete a pending loan request.
     */
public function destroy(Loan $loan)
{
    // Only allow delete if loan is pending
if (strtolower(trim($loan->status)) === 'pending') {
    return redirect()->back()->with('error', 'Only pending loans can be deleted.');
}
    $loan->delete();

    return redirect()
        ->route('loan_request_new_client.index')
        ->with('success', 'Loan request deleted successfully.');
}

}
