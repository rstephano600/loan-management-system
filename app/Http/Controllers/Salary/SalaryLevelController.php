<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\SalaryLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryLevelController extends Controller
{
    // Display list with search & pagination
    public function index(Request $request)
    {
        $query = SalaryLevel::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $salaryLevels = $query->orderBy('id', 'desc')->paginate(10);

        return view('in.salaries.salary_levels.index', compact('salaryLevels'));
    }

    // Show create form
    public function create()
    {
        return view('in.salaries.salary_levels.create');
    }

    // Store new Salary Level
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:salary_levels,name',
            'description' => 'nullable|string',
            'default_salary' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['created_by'] = Auth::id();

        SalaryLevel::create($validated);

        return redirect()->route('salary_levels.index')->with('success', 'Salary Level created successfully.');
    }

    // Show single Salary Level
    public function show(SalaryLevel $salaryLevel)
    {
        return view('in.salaries.salary_levels.show', compact('salaryLevel'));
    }

    // Show edit form
    public function edit(SalaryLevel $salaryLevel)
    {
        return view('in.salaries.salary_levels.edit', compact('salaryLevel'));
    }

    // Update Salary Level
    public function update(Request $request, SalaryLevel $salaryLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:salary_levels,name,'.$salaryLevel->id,
            'description' => 'nullable|string',
            'default_salary' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['updated_by'] = Auth::id();

        $salaryLevel->update($validated);

        return redirect()->route('salary_levels.index')->with('success', 'Salary Level updated successfully.');
    }

    // Delete Salary Level
    public function destroy(SalaryLevel $salaryLevel)
    {
        $salaryLevel->delete();
        return redirect()->route('salary_levels.index')->with('success', 'Salary Level deleted successfully.');
    }
}
