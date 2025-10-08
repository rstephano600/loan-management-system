<?php

namespace App\Http\Controllers\Loan;


use App\Http\Controllers\Controller;
use App\Models\ClientLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanApprovalController extends Controller
{
    /**
     * Show the approval form for a specific loan.
     */
    public function edit(ClientLoan $clientLoan)
    {
        return view('in.loans.client_loans.approval.edit', compact('clientLoan'));
    }

    /**
     * Handle approval submission.
     */
    public function update(Request $request, ClientLoan $clientLoan)
    {
        $validated = $request->validate([
            'amount_disbursed' => 'required|numeric|min:0',
            'loan_fee' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
        ]);

        // Compute interest amount
        $interestAmount = ($validated['amount_disbursed'] * $validated['interest_rate']) / 100;

        $clientLoan->update([
            'amount_disbursed' => $validated['amount_disbursed'],
            'loan_fee' => $validated['loan_fee'] ?? 0,
            'other_fee' => $validated['other_fee'] ?? 0,
            'interest_rate' => $validated['interest_rate'],
            'interest_amount' => $interestAmount,
            'status' => 'approved',
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('client_loans.show', $clientLoan->id)
            ->with('success', 'Loan approved successfully.');
    }
}
