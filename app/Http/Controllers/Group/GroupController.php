<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;

class GroupController extends Controller
{
    /**
     * Display a listing of the groups.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $groups = Group::when($search, function ($query, $search) {
            $query->where('group_name', 'like', "%{$search}%")
                  ->orWhere('group_code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

        return view('in.groups.index', compact('groups', 'search'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        $creditOfficers = Employee::where('position', 'Loan Officer')->get();
        return view('in.groups.create', compact('creditOfficers'));
    }

    /**
     * Store a newly created group.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'group_name' => 'required|string|max:255',
        'group_type' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'credit_officer_id' => 'nullable|exists:employees,id',
        'registration_date' => 'nullable|date',
    ]);

    // Auto-generate unique group code based on name and date
    $namePart = strtoupper(substr(preg_replace('/\s+/', '', $request->group_name), 0, 3)); // first 3 letters (no spaces)
    $datePart = now()->format('Ymd'); // current date (e.g. 20251006)
    $randomPart = strtoupper(Str::random(3)); // random 3 letters

    $groupCode = "{$namePart}-{$datePart}-{$randomPart}";

    // Ensure code is unique (retry if not)
    while (\App\Models\Group::where('group_code', $groupCode)->exists()) {
        $randomPart = strtoupper(Str::random(3));
        $groupCode = "{$namePart}-{$datePart}-{$randomPart}";
    }

    $validated['group_code'] = $groupCode;
    $validated['created_by'] = auth()->id() ?? 1;

    Group::create($validated);

    return redirect()->route('groups.index')->with('success', 'Group created successfully!');
}


    /**
     * Display the specified group.
     */
    public function show(Group $group)
    {
        return view('in.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        $creditOfficers = Employee::where('position', 'Loan Officer')->get();
        return view('in.groups.edit', compact('group', 'creditOfficers'));
    }

    /**
     * Update the specified group.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'group_code' => 'required|unique:groups,group_code,' . $group->id,
            'group_name' => 'required|string|max:255',
            'group_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'credit_officer_id' => 'nullable|exists:employees,id',
            'registration_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id() ?? 1;

        $group->update($validated);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully!');
    }

    /**
     * Remove the specified group.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully!');
    }
}
