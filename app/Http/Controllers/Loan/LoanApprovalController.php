<?php

namespace App\Http\Controllers\Loan;
use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanApprovalController extends Controller
{
    /**
     * Display list of pending loan requests.
     */
    public function index()
    {
        $loans = Loan::with(['client', 'loanCategory'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('in.loans.requests.loan_approvals.index', compact('loans'));
    }

    /**
     * Show a single pending loan for approval.
     */
    public function show($id)
    {
        $loan = Loan::with(['client', 'loanCategory'])->findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->route('loan-approvals.index')
                ->with('error', 'This loan has already been processed.');
        }

        return view('in.loans.requests.loan_approvals.show', compact('loan'));
    }


    public function approve(Request $request, $id)
    {
    $loan = Loan::findOrFail($id);
    $category = LoanCategory::findOrFail($loan->loan_category_id);

    if ($loan->status !== 'pending') {
        return redirect()->back()->with('error', 'Loan already approved or processed.');
    }

    // Step 1: Approve the loan
    $loan->update([
        'membership_fee_paid' =>$loan->membership_fee,
        'insurance_fee_paid' =>$loan->insurance_fee,
        'officer_visit_fee_paid' =>$loan->officer_visit_fee,
        'status'            => 'approved',
        'approved_by'       => Auth::id(),
        'disbursement_date' => now(),
    ]);

    // Step 2: Generate repayment schedule

    $totalDays = $category->total_days_due ?? 0;
    // $installmentNumber = 'INST' . $client->last_name . strtoupper(Str::random(4)) . '-' . now()->format('YmdHis');

    for ($i = 1; $i <= $totalDays; $i++) {
        RepaymentSchedule::create([
            'loan_id'           => $loan->id,
            'due_day_number'    => $i,
            'principal_due'     => $category->principal_due ?? 0,
            'interest_due'      => $category->interest_due ?? 0,
            'days_left'         => $totalDays - $i,
            'status'            => 'pending',
            'created_by'            => Auth::id(),
        ]);
    }
        return redirect()->route('loan-approvals.index')->with('success', 'Loan approved and repayment schedule generated successfully.');

    }

    /**
     * Reject a loan.
     */
    public function reject(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Loan already processed.');
        }

        $loan->update([
            'status' => 'closed',
            'closure_reason' => $request->input('reason', 'Rejected by manager'),
            'approved_by' => Auth::id(),
            'closed_at' => now(),
        ]);

        return redirect()->route('loan-approvals.index')->with('success', 'Loan request rejected.');
    }
}
