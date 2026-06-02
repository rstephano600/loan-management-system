<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanCategory;
use App\Models\Loan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\NextOfKin;
use App\Models\Referee;
use App\Models\Client;
use App\Models\Group;
use App\Models\GroupCenter;
use App\Models\LoanRepayment;
use App\Models\LoanPenaltyCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Imports\LoanRepaymentImport;
use App\Exports\LoanRepaymentTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Guarantor;
use App\Models\LoanGuarantor;
use App\Models\LoanPenalty;
use App\Models\LoanRepaymentFee;

class LoanController extends Controller
{
    public function loancategories()
    {
        try{
        $data = LoanCategory::where('Status', 'Active')->get();
        return view('in.loans.loancategories', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeloancategory(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'amount_disbursed' => 'required|numeric|min:0',
            'principal_due' => 'required|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'officer_visit_fee' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',

            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'max_term_days' => 'nullable|integer|min:0',
            'max_term_months' => 'nullable|integer|min:0',

            'currency' => 'nullable|string|max:10',
            'conditions' => 'nullable|string',
            'descriptions' => 'nullable|string',
            'is_new_client' => 'boolean',

        ]);
        $amount_disbursed = (float) $request->input('amount_disbursed');
        $interest_rate = (float) $request->input('interest_rate', 20);
        $principal_due = (float) $request->input('principal_due');

        // ✅ Calculate interest and dues safely
        $interest = ($amount_disbursed * $interest_rate) / 100;
        $repayableAmount = $interest + $amount_disbursed;

        // Avoid division by zero
        if ($principal_due > 0) {
            $totalDaysDue = $repayableAmount / $principal_due;
            $interestDue = ($principal_due * $interest_rate) / 100;
        } else {
            $totalDaysDue = 0;
            $interestDue = 0;
        }

        try {

            LoanCategory::create([

                'name'                 => $request->name,
                'amount_disbursed'     => $request->amount_disbursed,
                'insurance_fee'        => $request->insurance_fee ?? 0,
                'officer_visit_fee'    => $request->officer_visit_fee ?? 0,
                'interest_rate'        => $request->interest_rate,
                'interest_amount'      => $interest,
                'repayment_frequency'  => $request->repayment_frequency,
                'total_days_due'       => $totalDaysDue,
                'max_term_days'        => $request->max_term_days,
                'max_term_months'      => $request->max_term_months,
                'principal_due'        => $request->principal_due,
                'interest_due'         => $interestDue,
                'currency'             => $request->currency,
                'conditions'           => $request->conditions,
                'descriptions'         => $request->descriptions,
                'is_active'            => true,
                'is_new_client'        => $request->is_new_client,

                'created_by'           => Auth::id(),
                'updated_by'           => Auth::id(),

                'User_id'              => Auth::id(),
                'Status'               => 'Active',
                'AuditingStatus'       => 'Pending',
                'ReportStatus'         => 'Pending'

            ]);

            Alert::success(
                'Success ' . ' ' . Auth()->user()->name, 'You\'ve Registered Loan Category Successfully' );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }



    public function editloancategory($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = LoanCategory::findOrFail($id);

            return view('in.loans.editloancategory', compact('data'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }


public function updateloancategory(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'name' => 'nullable|string|max:255',

        'amount_disbursed' => 'required|numeric|min:0',

        'principal_due' => 'required|numeric|min:0',

        'insurance_fee' => 'nullable|numeric|min:0',

        'officer_visit_fee' => 'nullable|numeric|min:0',

        'interest_rate' => 'nullable|numeric|min:0|max:100',

        'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',

        'max_term_days' => 'nullable|integer|min:0',

        'max_term_months' => 'nullable|integer|min:0',

        'currency' => 'nullable|string|max:10',

        'conditions' => 'nullable|string',

        'descriptions' => 'nullable|string',

        'is_new_client' => 'boolean',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Calculations
    |--------------------------------------------------------------------------
    */

    $amount_disbursed =
        (float) $request->input('amount_disbursed');

    $interest_rate =
        (float) $request->input('interest_rate', 20);

    $principal_due =
        (float) $request->input('principal_due');

    $insurance_fee =
        (float) ($request->insurance_fee ?? 0);

    $officer_visit_fee =
        (float) ($request->officer_visit_fee ?? 0);


    $interest =
        ($amount_disbursed * $interest_rate) / 100;

    $repayableAmount =
        $interest
        + $amount_disbursed
        + $insurance_fee
        + $officer_visit_fee;


    if ($principal_due > 0) {

        $totalDaysDue =
            ceil($repayableAmount / $principal_due);

        $interestDue =
            ($principal_due * $interest_rate) / 100;

    } else {

        $totalDaysDue = 0;

        $interestDue = 0;
    }


    try {

        $loan = LoanCategory::findOrFail($id);

        $loan->update([

            'name'                 => $request->name,

            'amount_disbursed'     => $amount_disbursed,

            'insurance_fee'        => $insurance_fee,

            'officer_visit_fee'    => $officer_visit_fee,

            'interest_rate'        => $interest_rate,

            'interest_amount'      => $interest,

            'repayment_frequency'  => $request->repayment_frequency,

            'total_days_due'       => $totalDaysDue,

            'max_term_days'        => $request->max_term_days,

            'max_term_months'      => $request->max_term_months,

            'principal_due'        => $principal_due,

            'interest_due'         => $interestDue,

            'currency'             => $request->currency,

            'conditions'           => $request->conditions,

            'descriptions'         => $request->descriptions,

            'is_new_client'        => $request->is_new_client,

            'updated_by'           => Auth::id(),

        ]);


        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Updated Loan Category Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}



public function destroyloancategory($id)
{
    try {

        $id = Crypt::decrypt($id);

        $loan = LoanCategory::findOrFail($id);

        $loan->update([
            'Status' => 'Deleted'
        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve removed Loan Category successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
} 

public function viewloancategory($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanCategory::findOrFail($id);

        return view(
            'in.loans.viewloancategory',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}


// Loan informations
public function loansinformations()
{
    try {

        $data = Loan::with([
            'client',
            'loanCategory',
            'group',
            'groupCenter'
        ])
        ->where('Status', 'Active')
        ->latest()
        ->get();
        $clients = Client::where('Status', 'Active')->get();
        $loanCategories = LoanCategory::where('Status', 'Active')->get();
        $groups = Group::where('Status', 'Active')->get();
        $groupCenters = GroupCenter::where('Status', 'Active')->get();
        return view( 'in.loans.loansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewloaninformation($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = Loan::with([
            'client',
            'loanCategory',
            'group',
            'groupCenter'
        ])->findOrFail($id);
        return view(
            'in.loans.viewloaninformation',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function registerloaninformation(Request $request)
{
    $validated = $request->validate([

        'group_id' => 'nullable|integer|exists:groups,id',
        'client_id' => 'required|integer|exists:clients,id',
        'loan_category_id' => 'required|integer|exists:loan_categories,id',
        'amount_requested' => 'required|numeric|min:0',
        'client_payable_frequency' => 'required|numeric|min:0',
    ]);

    try {
        $loanCategory = LoanCategory::findOrFail( $request->loan_category_id );
        $group = Group::findOrFail( $request->group_id );
        $groupCenter = $group->group_center_id;

        $amountRequested = (float) $request->amount_requested;

        $latestLoan = Loan::latest()->first();
        $client = Client::findOrFail($validated['client_id']);
        $loanNumber = 'ArB-LN-' . $client->client->LastName . '-'. date('Ymd') . '-' . str_pad(($latestLoan ? $latestLoan->id + 1 : 1), 4, '0', STR_PAD_LEFT );

        Loan::create([
            'loan_number' => $loanNumber,
            'group_center_id' =>$groupCenter,
            'group_id' =>$request->group_id,
            'client_id' =>$request->client_id,
            'loan_category_id' =>$groupCenter,
            'amount_requested' => $amountRequested,
            'client_payable_frequency' => $request->client_payable_frequency,
            'application_date' => now(),
            'is_active' => true,
            'ApprovalStatus' => 'Pending',
            'currency' => $loanCategory->currency,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'User_id' => Auth::id(),
        ]);

        Alert::success( 'Success ' . ' ' . Auth()->user()->name, 'Loan Information Registered Successfully');
        return back();
    } catch (\Throwable $th) {
        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );
        return back();
    }
}
public function editloaninformation($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = Loan::findOrFail($id);

        $clients = Client::where('Status', 'Active')->get();

        $groups = Group::where('Status', 'Active')->get();

        $loanCategories = LoanCategory::where('Status', 'Active')->get();

        return view(
            'in.loans.editloaninformation',
            compact(
                'data',
                'clients',
                'groups',
                'loanCategories'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateloaninformation(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $validated = $request->validate([

        'group_id' => 'nullable|integer|exists:groups,id',

        'client_id' => 'required|integer|exists:clients,id',

        'loan_category_id' => 'required|integer|exists:loan_categories,id',

        'amount_requested' => 'required|numeric|min:0',

        'client_payable_frequency' => 'required|numeric|min:0',

    ]);

    try {

        $loan = Loan::findOrFail($id);

        $loanCategory = LoanCategory::findOrFail(
            $request->loan_category_id
        );

        $groupCenter = null;

        if ($request->group_id) {

            $group = Group::findOrFail(
                $request->group_id
            );

            $groupCenter = $group->group_center_id;
        }

        $loan->update([

            'group_center_id' =>
                $groupCenter,

            'group_id' =>
                $request->group_id,

            'client_id' =>
                $request->client_id,

            'loan_category_id' =>
                $request->loan_category_id,

            'amount_requested' =>
                $request->amount_requested,

            'client_payable_frequency' =>
                $request->client_payable_frequency,

            'currency' =>
                $loanCategory->currency,

            'updated_by' =>
                Auth::id()

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Information Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
// LOAN PENALITIES
public function loanpenaltycategories()
{
    try {

        $data = LoanPenaltyCategory::where('Status', 'Active')->get();

        return view(
            'in.loans.loanpenaltycategories',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function storeloanpenaltycategory(Request $request)
{
    $request->validate([

        'name' => 'required|string|max:255',

        'conditions' => 'nullable|string',

        'descriptions' => 'nullable|string',

    ]);

    try {

        LoanPenaltyCategory::create([

            'name' => $request->name,

            'conditions' => $request->conditions,

            'descriptions' => $request->descriptions,

            'User_id' => Auth::id(),

            'Status' => 'Active',

            'AuditingStatus' => 'Pending',

            'ReportStatus' => 'Pending'

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Registered Loan Penalty Category Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function editloanpenaltycategory($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenaltyCategory::findOrFail($id);

        return view(
            'in.loans.editloanpenaltycategory',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}



public function updateloanpenaltycategory(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'name' => 'required|string|max:255',

        'conditions' => 'nullable|string',

        'descriptions' => 'nullable|string',

    ]);

    try {

        $category = LoanPenaltyCategory::findOrFail($id);

        $category->update([

            'name' => $request->name,

            'conditions' => $request->conditions,

            'descriptions' => $request->descriptions,

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Updated Loan Penalty Category Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function viewloanpenaltycategory($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenaltyCategory::findOrFail($id);

        return view(
            'in.loans.viewloanpenaltycategory',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function destroyloanpenaltycategory($id)
{
    try {

        $id = Crypt::decrypt($id);
        $category = LoanPenaltyCategory::findOrFail($id);
        $category->update([
            'Status' => 'Deleted'
        ]);
        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Removed Loan Penalty Category Successfully'
        );
        return back();
    } catch (\Throwable $th) {
        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );
        return back();
    }
}


    // LOAN PAYMENTS
    public function loansrepaymentsfees()
    {
        try {

            $data = LoanRepaymentFee::with([
                'loan',
                'client',
                'receiver'
            ])
            ->where('Status', 'Active')
            ->latest()
            ->get();

            $loans = Loan::where('Status', 'Active')
                ->whereIn('status', ['Approved', 'Active'])
                ->get();

            return view(
                'in.loans.fees.loansrepaymentsfees', compact( 'data', 'loans')
            );
        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storeloanrepaymentfee(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_date' => 'required|date',
            'membership_fee_paid' => 'nullable|numeric|min:0',
            'officer_visit_fee_paid' => 'nullable|numeric|min:0',
            'insurance_fee_paid' => 'nullable|numeric|min:0',
            'preclosure_fee_paid' => 'nullable|numeric|min:0',
            'penalty_fee_paid' => 'nullable|numeric|min:0',
            'other_fee_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);
        try {
            $loan = Loan::findOrFail( $request->loan_id );

            $membership_fee_paid = (float) $request->membership_fee_paid;
            $officer_visit_fee_paid = (float) $request->officer_visit_fee_paid;
            $insurance_fee_paid = (float) $request->insurance_fee_paid;
            $preclosure_fee_paid = (float) $request->preclosure_fee_paid;
            $penalty_fee_paid = (float) $request->penalty_fee_paid;
            $other_fee_paid = (float) $request->other_fee_paid;

            LoanRepaymentFee::create([
                'loan_id' =>$loan->id,
                'client_id' =>$loan->client_id,
                'payment_date' =>$request->payment_date,
                'membership_fee_paid' =>$request->membership_fee_paid,
                'officer_visit_fee_paid' =>$request->officer_visit_fee_paid,
                'insurance_fee_paid' =>$request->insurance_fee_paid,
                'preclosure_fee_paid' =>$request->preclosure_fee_paid,
                'penalty_fee_paid' =>$request->penalty_fee_paid,
                'other_fee_paid' =>$request->other_fee_paid,
                'payment_method' =>$request->payment_method,
                'reference_number' =>$request->reference_number,
                'remarks' =>$request->remarks,
                'received_by' =>Auth::id(),
                'User_id' =>Auth::id(),
            ]);

            $loan->increment( 'membership_fee_paid', $membership_fee_paid );
            $loan->increment( 'officer_visit_fee_paid', $officer_visit_fee_paid );
            $loan->increment( 'insurance_fee_paid', $insurance_fee_paid );
            $loan->increment( 'preclosure_fee_paid', $preclosure_fee_paid );
            $loan->increment( 'penalty_fee_paid', $penalty_fee_paid );
            $loan->increment( 'other_fee_paid', $other_fee_paid );
            $loan->refresh();

            Alert::success( 'Success ' . ' ' . Auth()->user()->name, 'Loan Fee Repayment Registered Successfully' );
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

public function editloanrepaymentfee($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanRepaymentFee::findOrFail($id);

        return view(
            'in.loans.editloanrepayment',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function updateloanrepaymentfee(Request $request, $id)
{
    $id = Crypt::decrypt($id);
    $request->validate([
        'payment_date' => 'required|date',
        'amount_paid' => 'required|numeric|min:1',
        'payment_method' => 'nullable|string|max:50',
        'reference_number' => 'nullable|string|max:100',
        'remarks' => 'nullable|string',
    ]);

    try {

        $repayment = LoanRepaymentFee::findOrFail($id);

        $loan = Loan::findOrFail(
            $repayment->loan_id
        );

        /*
        |--------------------------------------------------------------------------
        | Reverse Old Repayment
        |--------------------------------------------------------------------------
        */

        $loan->decrement(
            'amount_paid',
            $repayment->amount_paid
        );


        /*
        |--------------------------------------------------------------------------
        | New Allocation
        |--------------------------------------------------------------------------
        */

        $amountPaid =
            (float) $request->amount_paid;

        $principalPaid =
            min(
                $loan->principal_due,
                $amountPaid
            );

        $interestPaid =
            min(
                $loan->interest_due,
                max(
                    0,
                    $amountPaid - $principalPaid
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Update Repayment
        |--------------------------------------------------------------------------
        */

        $repayment->update([

            'payment_date' =>
                $request->payment_date,

            'amount_paid' =>
                $amountPaid,

            'principal_paid' =>
                $principalPaid,

            'interest_paid' =>
                $interestPaid,

            'payment_method' =>
                $request->payment_method,

            'reference_number' =>
                $request->reference_number,

            'remarks' =>
                $request->remarks,

            'received_by' =>
                Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Apply New Repayment
        |--------------------------------------------------------------------------
        */

        $loan->increment(
            'amount_paid',
            $amountPaid
        );

        $loan->refresh();


        /*
        |--------------------------------------------------------------------------
        | Loan Status Check
        |--------------------------------------------------------------------------
        */

        if (
            $loan->amount_paid >=
            $loan->repayable_amount
        ) {

            $loan->update([

                'status' => 'Completed',

                'closed_at' => now()

            ]);

        } else {

            $loan->update([

                'status' => 'Active',

                'closed_at' => null

            ]);
        }


        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Updated Loan Repayment Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function viewloanrepaymentfee($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanRepaymentFee::with([
            'loan',
            'client',
            'receiver'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanrepayment',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function destroyloanrepaymentfee($id)
{
    try {

        $id = Crypt::decrypt($id);

        $repayment =
            LoanRepaymentFee::findOrFail($id);

        $loan =
            Loan::findOrFail(
                $repayment->loan_id
            );

        $loan->decrement(
            'amount_paid',
            $repayment->amount_paid
        );

        $repayment->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'Loan Repayment Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function importloanrepaymentsfee(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        $import = new LoanRepaymentImportFee;

        Excel::import($import, $request->file('file'));

        // ✅ Show row-level errors if any rows failed
        if (!empty($import->errors)) {
            $errorList = implode('<br>', $import->errors);
            Alert::warning(
                'Imported with warnings - ' . Auth()->user()->name,
                $errorList
            );
            return back();
        }

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'Loan Repayments Imported Successfully'
        );

        return back();

    } catch (\Throwable $th) {
        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            $th->getMessage()  // ✅ Now actually surfaces real errors
        );

        return back();
    }
}


public function downloadloanrepaymenttemplatefee()
{
    try {

        return Excel::download(
            new LoanRepaymentTemplateExportFee,
            'loan_repayment_template_fee_' . date('Ymd') . '.xlsx'
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Template download failed: ' . $th->getMessage()
        );

        return back();
    }
}

    // LOAN PAYMENTS
    public function loansrepayments()
    {
        try {

            $data = LoanRepayment::with([
                'loan',
                'client',
                'receiver'
            ])
            ->where('Status', 'Active')
            ->latest()
            ->get();

            $loans = Loan::where('Status', 'Active')
                ->whereIn('status', ['Approved', 'Active'])
                ->get();

            return view(
                'in.loans.loansrepayments',
                compact(
                    'data',
                    'loans'
                )
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storeloanrepayment(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);
        try {
            $loan = Loan::findOrFail( $request->loan_id );

            /*
            |--------------------------------------------------------------------------
            | Calculate Allocation
            |--------------------------------------------------------------------------
            */

            $amountPaid = (float) $request->amount_paid;
            $principalPaid = min( $loan->principal_due, $amountPaid );
            $interestPaid = min( $loan->interest_due, max( 0, $amountPaid - $principalPaid ) );

            LoanRepayment::create([
                'loan_id' =>$loan->id,
                'client_id' =>$loan->client_id,
                'payment_date' =>$request->payment_date,
                'amount_paid' =>$amountPaid,
                'principal_paid' =>$principalPaid,
                'interest_paid' =>$interestPaid,
                'penalty_paid' =>0,
                'payment_method' =>$request->payment_method,
                'reference_number' =>$request->reference_number,
                'remarks' =>$request->remarks,
                'received_by' =>Auth::id(),
                'User_id' =>Auth::id(),
                'Status' =>'Active',
                'AuditingStatus' =>'Pending',
                'ReportStatus' =>'Pending'
            ]);
            $loan->increment( 'amount_paid', $amountPaid );
            $loan->refresh();

            // if (
            //     $loan->amount_paid >=
            //     $loan->repayable_amount
            // ) {

            //     $loan->update([

            //         'status' => 'Completed',

            //         'closed_at' => now()

            //     ]);
            // }


            Alert::success( 'Success ' . ' ' . Auth()->user()->name, 'Loan Repayment Registered Successfully' );
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

public function editloanrepayment($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanRepayment::findOrFail($id);

        return view(
            'in.loans.editloanrepayment',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function updateloanrepayment(Request $request, $id)
{
    $id = Crypt::decrypt($id);
    $request->validate([
        'payment_date' => 'required|date',
        'amount_paid' => 'required|numeric|min:1',
        'payment_method' => 'nullable|string|max:50',
        'reference_number' => 'nullable|string|max:100',
        'remarks' => 'nullable|string',
    ]);

    try {

        $repayment = LoanRepayment::findOrFail($id);

        $loan = Loan::findOrFail(
            $repayment->loan_id
        );

        /*
        |--------------------------------------------------------------------------
        | Reverse Old Repayment
        |--------------------------------------------------------------------------
        */

        $loan->decrement(
            'amount_paid',
            $repayment->amount_paid
        );


        /*
        |--------------------------------------------------------------------------
        | New Allocation
        |--------------------------------------------------------------------------
        */

        $amountPaid =
            (float) $request->amount_paid;

        $principalPaid =
            min(
                $loan->principal_due,
                $amountPaid
            );

        $interestPaid =
            min(
                $loan->interest_due,
                max(
                    0,
                    $amountPaid - $principalPaid
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Update Repayment
        |--------------------------------------------------------------------------
        */

        $repayment->update([

            'payment_date' =>
                $request->payment_date,

            'amount_paid' =>
                $amountPaid,

            'principal_paid' =>
                $principalPaid,

            'interest_paid' =>
                $interestPaid,

            'payment_method' =>
                $request->payment_method,

            'reference_number' =>
                $request->reference_number,

            'remarks' =>
                $request->remarks,

            'received_by' =>
                Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Apply New Repayment
        |--------------------------------------------------------------------------
        */

        $loan->increment(
            'amount_paid',
            $amountPaid
        );

        $loan->refresh();


        /*
        |--------------------------------------------------------------------------
        | Loan Status Check
        |--------------------------------------------------------------------------
        */

        if (
            $loan->amount_paid >=
            $loan->repayable_amount
        ) {

            $loan->update([

                'status' => 'Completed',

                'closed_at' => now()

            ]);

        } else {

            $loan->update([

                'status' => 'Active',

                'closed_at' => null

            ]);
        }


        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Updated Loan Repayment Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function viewloanrepayment($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanRepayment::with([
            'loan',
            'client',
            'receiver'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanrepayment',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function destroyloanrepayment($id)
{
    try {

        $id = Crypt::decrypt($id);

        $repayment =
            LoanRepayment::findOrFail($id);

        $loan =
            Loan::findOrFail(
                $repayment->loan_id
            );

        $loan->decrement(
            'amount_paid',
            $repayment->amount_paid
        );

        $repayment->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'Loan Repayment Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function importloanrepayments(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        $import = new LoanRepaymentImport;

        Excel::import($import, $request->file('file'));

        // ✅ Show row-level errors if any rows failed
        if (!empty($import->errors)) {
            $errorList = implode('<br>', $import->errors);
            Alert::warning(
                'Imported with warnings - ' . Auth()->user()->name,
                $errorList
            );
            return back();
        }

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'Loan Repayments Imported Successfully'
        );

        return back();

    } catch (\Throwable $th) {
        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            $th->getMessage()  // ✅ Now actually surfaces real errors
        );

        return back();
    }
}


public function downloadloanrepaymenttemplate()
{
    try {

        return Excel::download(
            new LoanRepaymentTemplateExport,
            'loan_repayment_template_' . date('Ymd') . '.xlsx'
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Template download failed: ' . $th->getMessage()
        );

        return back();
    }
}


// GUARANTOR
public function guarantors()
{
    try {

        $data = Guarantor::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.guarantors',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function storeguarantor(Request $request)
{
    $request->validate([

        'first_name' => 'required|string|max:100',

        'middle_name' => 'nullable|string|max:100',

        'last_name' => 'nullable|string|max:100',

        'gender' => 'nullable|string|max:20',

        'phone_number' => 'required|string|max:50',

        'alternative_phone' => 'nullable|string|max:50',

        'nida_number' => 'nullable|string|max:100',

        'email' => 'nullable|email|max:255',

        'occupation' => 'nullable|string|max:255',

        'physical_address' => 'nullable|string',

        'relationship_with_client' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $latest = Guarantor::latest()->first();

        $guarantorNumber =
            'GUA-' .
            date('Ymd') .
            '-' .
            str_pad(
                ($latest ? $latest->id + 1 : 1),
                4,
                '0',
                STR_PAD_LEFT
            );

        Guarantor::create([

            'guarantor_number' =>
                $guarantorNumber,

            'first_name' =>
                $request->first_name,

            'middle_name' =>
                $request->middle_name,

            'last_name' =>
                $request->last_name,

            'gender' =>
                $request->gender,

            'phone_number' =>
                $request->phone_number,

            'alternative_phone' =>
                $request->alternative_phone,

            'nida_number' =>
                $request->nida_number,

            'email' =>
                $request->email,

            'occupation' =>
                $request->occupation,

            'physical_address' =>
                $request->physical_address,

            'relationship_with_client' =>
                $request->relationship_with_client,

            'remarks' =>
                $request->remarks,

            'User_id' =>
                Auth::id(),

            'Status' =>
                'Active',

            'AuditingStatus' =>
                'Pending',

            'ReportStatus' =>
                'Pending'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Guarantor Registered Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function editguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = Guarantor::findOrFail($id);

        return view(
            'in.loans.editguarantor',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateguarantor(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'first_name' => 'required|string|max:100',

        'middle_name' => 'nullable|string|max:100',

        'last_name' => 'nullable|string|max:100',

        'gender' => 'nullable|string|max:20',

        'phone_number' => 'required|string|max:50',

        'alternative_phone' => 'nullable|string|max:50',

        'nida_number' => 'nullable|string|max:100',

        'email' => 'nullable|email|max:255',

        'occupation' => 'nullable|string|max:255',

        'physical_address' => 'nullable|string',

        'relationship_with_client' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $guarantor = Guarantor::findOrFail($id);

        $guarantor->update([

            'first_name' =>
                $request->first_name,

            'middle_name' =>
                $request->middle_name,

            'last_name' =>
                $request->last_name,

            'gender' =>
                $request->gender,

            'phone_number' =>
                $request->phone_number,

            'alternative_phone' =>
                $request->alternative_phone,

            'nida_number' =>
                $request->nida_number,

            'email' =>
                $request->email,

            'occupation' =>
                $request->occupation,

            'physical_address' =>
                $request->physical_address,

            'relationship_with_client' =>
                $request->relationship_with_client,

            'remarks' =>
                $request->remarks,

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Guarantor Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = Guarantor::findOrFail($id);

        return view(
            'in.loans.viewguarantor',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function destroyguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $guarantor = Guarantor::findOrFail($id);

        $guarantor->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Guarantor Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function loanguarantors()
{
    try {

        $data = LoanGuarantor::with([
            'loan',
            'client',
            'guarantor'
        ])
        ->where('Status', 'Active')
        ->get();

        $loans = Loan::where('Status', 'Active')->get();

        $guarantors = Guarantor::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.loanguarantors',
            compact(
                'data',
                'loans',
                'guarantors'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function storeloanguarantor(Request $request)
{
    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'guarantor_id' => 'required|exists:guarantors,id',

        'guarantee_amount' => 'required|numeric|min:0',

        'relationship_type' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $loan = Loan::findOrFail(
            $request->loan_id
        );
        // $exists = LoanGuarantor::where('loan_id', $request->loan_id)
        //     ->where('guarantor_id', $request->guarantor_id)
        //     ->where('Status', 'Active')
        //     ->exists();

        // if ($exists) {

        //     Alert::warning(
        //         'Warning '.Auth()->user()->name,
        //         'This guarantor is already assigned to the selected loan.'
        //     );

        //     return back();
        // }
        LoanGuarantor::create([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'guarantor_id' =>
                $request->guarantor_id,

            'guarantee_amount' =>
                $request->guarantee_amount,

            'relationship_type' =>
                $request->relationship_type,

            'remarks' =>
                $request->remarks,

            'User_id' =>
                Auth::id(),

            'Status' =>
                'Active',

            'AuditingStatus' =>
                'Pending',

            'ReportStatus' =>
                'Pending'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Loan Guarantor Registered Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function editloanguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanGuarantor::findOrFail($id);

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $guarantors = Guarantor::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.editloanguarantor',
            compact(
                'data',
                'loans',
                'guarantors'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateloanguarantor(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'guarantor_id' => 'required|exists:guarantors,id',

        'guarantee_amount' => 'required|numeric|min:0',

        'relationship_type' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $loanGuarantor = LoanGuarantor::findOrFail($id);

        $loan = Loan::findOrFail(
            $request->loan_id
        );

        $loanGuarantor->update([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'guarantor_id' =>
                $request->guarantor_id,

            'guarantee_amount' =>
                $request->guarantee_amount,

            'relationship_type' =>
                $request->relationship_type,

            'remarks' =>
                $request->remarks

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Loan Guarantor Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewloanguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanGuarantor::with([
            'loan',
            'client',
            'guarantor'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanguarantor',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function destroyloanguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanGuarantor::findOrFail($id);

        $data->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Loan Guarantor Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function loanpenalties()
{
    try {

        $data = LoanPenalty::with([
            'loan',
            'client',
            'penaltyCategory'
        ])
        ->where('Status', 'Active')
        ->latest()
        ->get();

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $penalties = LoanPenaltyCategory::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.loanpenalties',
            compact(
                'data',
                'loans',
                'penalties'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function storeloanpenalty(Request $request)
{
    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'penalty_id' => 'required|exists:loan_penalties_categories,id',

        'penalty_date' => 'required|date',

        'overdue_days' => 'required|integer|min:0',

        'penalty_rate' => 'required|numeric|min:0',

        'remarks' => 'nullable|string',

    ]);

    try {

        $loan = Loan::findOrFail(
            $request->loan_id
        );

        $penaltyAmount =
            ($loan->client_payable_frequency *
            $request->penalty_rate / 100)
            *
            $request->overdue_days;

        LoanPenalty::create([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'penalty_id' =>
                $request->penalty_id,

            'penalty_date' =>
                $request->penalty_date,

            'overdue_days' =>
                $request->overdue_days,

            'penalty_rate' =>
                $request->penalty_rate,

            'penalty_amount' =>
                $penaltyAmount,

            'payment_status' =>
                'NOT PAID',

            'remarks' =>
                $request->remarks,

            'User_id' =>
                Auth::id(),

            'Status' =>
                'Active',

            'AuditingStatus' =>
                'Pending',

            'ReportStatus' =>
                'Pending'

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Penalty Registered Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function editloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenalty::findOrFail($id);

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $penalties = LoanPenaltyCategory::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.editloanpenalty',
            compact(
                'data',
                'loans',
                'penalties'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateloanpenalty(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'penalty_id' => 'required|exists:loan_penalties_categories,id',

        'penalty_date' => 'required|date',

        'overdue_days' => 'required|integer|min:0',

        'penalty_rate' => 'required|numeric|min:0',

        'remarks' => 'nullable|string',

    ]);

    try {

        $penalty = LoanPenalty::findOrFail($id);

        $loan = Loan::findOrFail(
            $request->loan_id
        );

        $penaltyAmount =
            ($loan->client_payable_frequency *
            $request->penalty_rate / 100)
            *
            $request->overdue_days;

        $penalty->update([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'penalty_id' =>
                $request->penalty_id,

            'penalty_date' =>
                $request->penalty_date,

            'overdue_days' =>
                $request->overdue_days,

            'penalty_rate' =>
                $request->penalty_rate,

            'penalty_amount' =>
                $penaltyAmount,

            'remarks' =>
                $request->remarks

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Penalty Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenalty::with([
            'loan',
            'client',
            'penaltyCategory'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanpenalty',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function destroyloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $penalty = LoanPenalty::findOrFail($id);

        $penalty->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Penalty Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}
public function payloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $penalty = LoanPenalty::findOrFail($id);

        if ($penalty->payment_status == 'PAID') {

            Alert::warning(
                'Warning ' . Auth()->user()->name,
                'Penalty already marked as paid'
            );

            return back();
        }

        $amountPaid = $penalty->penalty_amount;

        $loan = Loan::findOrFail(
            $penalty->loan_id
        );

        $loan->increment(
            'penalty_fee_paid',
            $amountPaid
        );

        $penalty->update([

            'payment_status' => 'PAID',

            'paid_at' => now()

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Penalty Marked As Paid Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

    // LOAN APPROVAL
    public function approveloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('ApprovalStatus', 'Pending')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.approve.approveloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }
    public function rejectedloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('ApprovalStatus', 'Rejected')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.approve.rejectedloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function approveloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $loan = Loan::findOrFail($id);
        $category = LoanCategory::findOrFail($loan->loan_category_id);
        $loanNumber = $loan->loan_number;
        try {
            if ($loan->amount_paid < 2 * $loan->principal_due) {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Approved because the client has not started paying.'
                );
                return back();
            }

            if ($loan->ApprovalStatus !== 'Pending') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Approved because the Loan is either Refunded or Closed or Already Approved.'
                );
                return back();
            }

            $membershipFee = $loan->membership_fee;

            // Step 1: Approve the loan
            $loan->update([
                'amount_disbursed'       => $category->amount_disbursed ?? $loan->amount_requested,
                'interest_rate'          => $category->interest_rate ?? 0,
                'interest_amount'        => $category->interest_amount ?? 0,
                'ApprovalStatus'         => 'Approved',
                'approved_by'            => Auth::id(),
                'disbursement_date'      => now(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'approved and repayment schedule generated successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function rejectloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = Loan::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {

            if ($loan->ApprovalStatus !== 'Pending') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Rejected because the Loan is either Refunded or Closed.'
                );
                return back();
            }

            $loan->update([
                'ApprovalStatus' => 'Rejected',
                'closure_reason' => $request->input('reason', 'Rejected due to untolelatable reasons'),
                'approved_by' => Auth::id(),
                'closed_at' => now(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'have been REJECTED successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function refundloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = Loan::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {

            if ($loan->RefundStatus !== 'Not Refunded') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Refunded because the Loan is either Refunded or Closed.'
                );
                return back();
            }

            $loan->update([
                'RefundStatus' => 'Refunded',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'have been Refunded  successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function unrefundloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = Loan::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {

            if ($loan->RefundStatus !== 'Refunded') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Resset to Refund because the Loan is either Refunded or Closed.'
                );
                return back();
            }

            $loan->update([
                'RefundStatus' => 'Not Refunded',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'have been Reseted for the Refund successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }


    public function refundedloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('RefundStatus', 'Refunded')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.refundedloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }
    public function closedloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('CloseStatus', 'Closed')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.closedloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }


}