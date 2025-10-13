<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeSalaryPaymentController extends Controller
{
    /**
     * Display list of salaries ready for payment.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $salaries = EmployeeSalary::with(['employee', 'salaryLevel'])
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->where('status', 'active')
            ->latest()
            ->paginate(10);

        $dueSalaries = EmployeeSalary::with(['employee', 'lastPayment'])
            ->where('status', 'active')
            ->get()
            ->filter(function ($salary) {
                $last = $salary->lastPayment ? Carbon::parse($salary->lastPayment->payment_date) : null;
                return !$last || $last->diffInDays(now()) >= 30;
            });

        return view('in.salaries.employee_payments.index', compact('salaries', 'search' , 'dueSalaries'));
    }

    /**
     * Show the payment form for a specific salary.
     */
    public function create($id)
    {
        $salary = EmployeeSalary::with(['employee', 'salaryLevel', 'payments'])
            ->findOrFail($id);

        return view('in.salaries.employee_payments.create', compact('salary'));
    }

    /**
     * Store a new payment record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_salary_id' => 'required|exists:employee_salaries,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $payment = new EmployeeSalaryPayment();
        $payment->employee_salary_id = $validated['employee_salary_id'];
        $payment->amount_paid = $validated['amount_paid'];
        $payment->payment_date = $validated['payment_date'];
        $payment->payment_method = $validated['payment_method'] ?? 'Cash';
        $payment->currency = EmployeeSalary::find($validated['employee_salary_id'])->currency ?? 'TZS';
        $payment->status = 'paid';
        $payment->created_by = Auth::id();

        if ($request->hasFile('attachment')) {
            $payment->attachment = $request->file('attachment')->store('salary_payments', 'public');
        }

        $payment->save();
        

        return redirect()->route('employee_payments.index')
            ->with('success', 'Salary payment recorded successfully.');
    }

    /**
     * Show all payment records.
     */
    public function show($id)
    {
        $salary = EmployeeSalary::with(['employee', 'salaryLevel', 'payments'])
            ->findOrFail($id);

        return view('in.salaries.employee_payments.show', compact('salary'));
    }

    /**
     * Delete payment record (optional).
     */
    public function destroy($id)
    {
        $payment = EmployeeSalaryPayment::findOrFail($id);
        $payment->delete();

        return back()->with('success', 'Payment deleted successfully.');
    }
}
