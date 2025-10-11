<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RepaymentScheduleController extends Controller
{
    public function show($loanId)
    {
        $loan = Loan::with('client')->findOrFail($loanId);
        $schedules = RepaymentSchedule::where('loan_id', $loanId)
            ->orderBy('installment_number', 'asc')
            ->get();

        return view('repayments.show', compact('loan', 'schedules'));
    }

public function pay($id)
{
    $schedule = RepaymentSchedule::findOrFail($id);

    if ($schedule->status === 'paid') {
        return back()->with('info', 'This installment is already paid.');
    }

    // Generate unique installment reference
    $installmentNumber = 'INST-' . 'DAY' . $schedule->due_day_number . '-' . strtoupper(Str::random(4)) . '-' . now()->format('Ymd');

    // Mark this schedule as paid
    $schedule->update([
        'installment_number' => $installmentNumber,
        'principal_paid' => $schedule->principal_due,
        'interest_paid' => $schedule->interest_due,
        'penalty_paid' => $schedule->penalty_due,
        'paid_date' => now(),
        'status' => 'paid',
        'paid_by' => Auth::id(),
        'is_paid' => true
    ]);

    // ✅ Update parent loan totals
    $loan = Loan::findOrFail($schedule->loan_id);

    $loan->update([
        'amount_paid' => $loan->amount_paid + $schedule->principal_due,
        'penalty_fee_paid' => $loan->penalty_fee_paid + $schedule->penalty_due,
        'other_fee_paid' => $loan->other_fee_paid, // keep as-is unless you add per-installment other fees
    ]);

    // Optionally check if all installments are paid, mark loan as completed
    $allPaid = $loan->repaymentSchedules()->where('status', '!=', 'paid')->count() === 0;
    if ($allPaid) {
        $loan->update(['status' => 'completed', 'closed_at' => now()]);
    }

    return back()->with('success', "Installment {$installmentNumber} paid successfully.");
}

public function addPenalty($id)
{
    $schedule = RepaymentSchedule::findOrFail($id);
    $loan = $schedule->loan;

    // check if already paid
    if ($schedule->status === 'paid') {
        return back()->with('info', 'This installment is already paid. No penalty applied.');
    }

    // constant daily penalty
    $penaltyPerDay = 2000;

    // calculate how many days late
    $dueDate = Carbon::parse($schedule->paid_date);
    $daysLate = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;

    if ($daysLate <= 0) {
        return back()->with('info', 'This installment is not late yet.');
    }

    // compute new penalty
    // $penaltyAmount = $daysLate * $penaltyPerDay;
    $penaltyAmount = $penaltyPerDay;

    // update schedule
    $schedule->update([
        'penalty_due' => $penaltyAmount,
        'status' => 'overdue',
        'updated_by' => Auth::id(),
    ]);

    // update loan totals
    $loan->increment('penalty_fee', $penaltyAmount);
    // $loan->increment('outstanding_balance', $penaltyAmount);

    return back()->with('success', "Penalty of {$penaltyAmount} applied for {$daysLate} day(s) late.");
}
}

