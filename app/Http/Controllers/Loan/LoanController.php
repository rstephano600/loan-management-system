<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\Client;
use App\Models\Group;
use App\Models\GroupCenter;
use App\Models\LoanCategory;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of loans.
     */
    public function index()
    {
        $loans = Loan::with(['client', 'group', 'groupCenter', 'loanCategory'])
            ->orderByDesc('created_at')
            ->where('is_active', true)
            ->paginate(15);

        return view('in.loans.loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $categories = LoanCategory::where('is_active', true)->get();
        $groups = Group::all();
        $groupCenters = GroupCenter::all();

        return view('in.loans.loans.create', compact('clients', 'categories', 'groups', 'groupCenters'));
    }

    /**
     * Store a newly created loan request (before approval).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_center_id' => 'nullable|integer|exists:group_centers,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'client_id' => 'required|integer|exists:clients,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'amount_requested' => 'required|numeric|min:0',
            'client_payable_frequency' => 'required|numeric|min:0',
        ]);

        $category = LoanCategory::findOrFail($validated['loan_category_id']);

        // Generate a unique loan number
        $validated['loan_number'] = 'LN-' . strtoupper(Str::random(6));

        // Default loan setup (pending approval)
        $validated['status'] = 'pending';
        $validated['interest_rate'] = $category->interest_rate;
        $validated['amount_disbursed'] = 0;
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;
        $validated['is_new_client'] = true;

        Loan::create($validated);

        return redirect()
            ->route('loans.index')
            ->with('success', 'Loan request created successfully and is awaiting approval.');
    }

    /**
     * Display the specified loan details.
     */
public function show(Loan $loan)
{
    $loan->load(['client', 'group', 'groupCenter', 'loanCategory']);
    
$schedules = RepaymentSchedule::where('loan_id', $loan->id)
    ->where('is_paid', false)
    ->orderBy('due_day_number', 'asc')
    ->get();

$schedulesPaids = RepaymentSchedule::where('loan_id', $loan->id)
    ->where('is_paid', true)
    ->orderBy('due_day_number', 'asc')
    ->get();

    
    return view('in.loans.loans.show', compact('loan', 'schedules', 'schedulesPaids'));
}


    /**
     * Show the form for editing the specified loan (before approval).
     */
    public function edit(Loan $loan)
    {
        $clients = Client::where('status', 'active')->get();
        $categories = LoanCategory::where('is_active', true)->get();
        $groups = Group::all();
        $groupCenters = GroupCenter::all();

        return view('in.loans.loans.edit', compact('loan', 'clients', 'categories', 'groups', 'groupCenters'));
    }

    /**
     * Update loan details.
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'group_center_id' => 'nullable|integer|exists:group_centers,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'client_id' => 'required|integer|exists:clients,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'amount_requested' => 'required|numeric|min:0',
            'client_payable_frequency' => 'required|numeric|min:0',
        ]);

        $loan->update($validated + ['updated_by' => Auth::id()]);

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', 'Loan request updated successfully.');
    }


// PRE CLOSURE FEE


public function setPreclosureFee(Request $request, $id)
{
    $validated = $request->validate([
        'preclosure_fee' => 'required|numeric|min:0',
    ]);

    $loan = Loan::findOrFail($id);

    if ($loan->status !== 'approved' && $loan->status !== 'active') {
        return back()->with('error', 'Only active loans can be assigned a preclosure fee.');
    }


    $loan->update([
        'preclosure_fee' => $validated['preclosure_fee'],
    ]);

    return back()->with('success', 'Preclosure fee of ' . number_format($validated['preclosure_fee'], 2) . ' set successfully.');
}


public function markPreclosurePaid($id)
{
    $loan = Loan::findOrFail($id);

    if ($loan->preclosure_fee <= 0) {
        return back()->with('error', 'No preclosure fee set for this loan.');
    }

    if ($loan->preclosure_fee_paid >= $loan->preclosure_fee) {
        return back()->with('info', 'Preclosure fee already paid.');
    }

    DB::transaction(function () use ($loan) {
        // 🔹 1. Mark all unpaid schedules as paid
        $schedules = RepaymentSchedule::where('loan_id', $loan->id)
            ->where('status', '!=', 'paid')
            ->get();

        foreach ($schedules as $schedule) {
            $installmentNumber = 'INST-DAY' . $schedule->due_day_number . '-' . strtoupper(Str::random(4)) . '-' . now()->format('Ymd');

            $schedule->update([
                'installment_number' => $installmentNumber,
                'principal_paid' => $schedule->principal_due,
                'interest_paid' => $schedule->interest_due,
                'penalty_paid' => $schedule->penalty_due,
                'paid_date' => now(),
                'status' => 'paid', // ✅ should be 'paid' not 'closed'
                'paid_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_paid' => true,
            ]);
        }

        // 🔹 2. Update the loan
        $loan->update([
            'amount_paid' => $loan->repayable_amount,
            'preclosure_fee_paid' => $loan->preclosure_fee,
            'status' => 'closed',
            'closed_by' => Auth::id(),
            'closed_at' => now(),
            'closure_reason' => 'Preclosure reasons',
        ]);
    });

    return back()->with('success', 'All schedules marked as paid and loan closed successfully.');
}



    /**
     * Remove a loan (soft delete or deactivate).
     */
    public function destroy(Loan $loan)
    {
        $loan->update([
            'is_active' => false,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('loans.index')
            ->with('success', 'Loan has been deactivated successfully.');
    }
}
