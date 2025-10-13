<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\Employee;
use App\Models\SalaryLevel;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeSalaryController extends Controller
{
    public function index()
    {
        $salaries = EmployeeSalary::with(['employee', 'salaryLevel'])
            ->latest()
            ->paginate(10);

        return view('in.salaries.salaries.index', compact('salaries'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        $salaryLevels = SalaryLevel::where('status', 'active')->get();

        return view('in.salaries.salaries.create', compact('employees', 'salaryLevels'));
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'salary_level_id' => 'nullable|exists:salary_levels,id',
        'bonus' => 'nullable|numeric|min:0',
        'effective_from' => 'required|date',
        'effective_to' => 'nullable|date|after_or_equal:effective_from',
        'payments.*.amount_paid' => 'nullable|numeric|min:0',
        'payments.*.payment_date' => 'nullable|date',
        'confirm_multiple' => 'nullable|boolean'
    ]);

    // Check if the employee already has one or more active salaries
    $existingSalaries = EmployeeSalary::where('employee_id', $validated['employee_id'])
        ->where('status', 'active')
        ->count();

    if ($existingSalaries > 0 && !$request->boolean('confirm_multiple')) {
        return back()
            ->withInput()
            ->with('warning', 'This employee already has an active salary. Please confirm to continue.');
    }

    $salaryLevel = SalaryLevel::findOrFail($validated['salary_level_id']);
    $baseSalary = $salaryLevel->default_salary;

    $salary = EmployeeSalary::create([
        'employee_id' => $validated['employee_id'],
        'salary_level_id' => $validated['salary_level_id'],
        'base_salary' => $baseSalary,
        'bonus' => $validated['bonus'] ?? 0,
        'currency' => $salaryLevel->currency,
        'effective_from' => $validated['effective_from'],
        'effective_to' => $validated['effective_to'] ?? null,
        'created_by' => Auth::id(),
        'status' => 'active',
    ]);

    // Save inline payments if any
    if ($request->has('payments')) {
        foreach ($request->payments as $payment) {
            if (!empty($payment['amount_paid']) && !empty($payment['payment_date'])) {
                $salary->payments()->create([
                    'amount_paid' => $payment['amount_paid'],
                    'payment_date' => $payment['payment_date'],
                    'payment_method' => $payment['payment_method'] ?? null,
                    'currency' => $salary->currency,
                    'status' => 'paid',
                    'created_by' => Auth::id(),
                ]);
            }
        }
    }

    return redirect()->route('employee_salaries.index')
        ->with('success', 'Employee salary created successfully!');
}




    public function show(EmployeeSalary $employeeSalary)
    {
        $employeeSalary->load(['employee', 'salaryLevel', 'payments']);
        return view('in.salaries.salaries.show', compact('employeeSalary'));
    }

    public function edit(EmployeeSalary $employeeSalary)
    {
        $employees = Employee::where('is_active', true)->get();
        $salaryLevels = SalaryLevel::where('status', 'active')->get();
        $employeeSalary->load('payments');

        return view('in.salaries.salaries.edit', compact('employeeSalary', 'employees', 'salaryLevels'));
    }

    public function update(Request $request, EmployeeSalary $employeeSalary)
    {
        $validated = $request->validate([
            'salary_level_id' => 'nullable|exists:salary_levels,id',
            'bonus' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
        ]);

    $salaryLevel = SalaryLevel::findOrFail($validated['salary_level_id']);
    $baseSalary = $salaryLevel->default_salary;

        $employeeSalary->update([
            ...$validated,
        'base_salary' => $baseSalary,
            'updated_by' => Auth::id(),
        ]);

        // Handle Payments Update
        EmployeeSalaryPayment::where('employee_salary_id', $employeeSalary->id)->delete();

        if ($request->has('payments')) {
            foreach ($request->payments as $payment) {
                EmployeeSalaryPayment::create([
                    'employee_salary_id' => $employeeSalary->id,
                    'amount_paid' => $payment['amount_paid'],
                    'payment_date' => $payment['payment_date'],
                    'payment_method' => $payment['payment_method'] ?? 'cash',
                    'currency' => $employeeSalary->currency,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('employee_salaries.index')
            ->with('success', 'Employee salary updated successfully.');
    }

    public function destroy(EmployeeSalary $employeeSalary)
    {
        $employeeSalary->delete();
        return redirect()->route('employee_salaries.index')->with('success', 'Employee salary deleted successfully.');
    }
}
