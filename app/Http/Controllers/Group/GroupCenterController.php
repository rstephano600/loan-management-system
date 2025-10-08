<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\GroupCenter;
use App\Models\Group;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;

class GroupCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    // Search and filter inputs
    $search = $request->input('search');
    $status = $request->input('status');
    $groupId = $request->input('group_id');

    // Query builder
    $query = GroupCenter::with(['group', 'collection_officer']);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('center_name', 'like', "%{$search}%")
              ->orWhere('center_code', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    if ($status) {
        $query->where('status', $status);
    }

    if ($groupId) {
        $query->where('group_id', $groupId);
    }

    $centers = $query->orderBy('id', 'desc')->paginate(10);

    // 👇 Add this line to fix the error
    $groups = Group::select('id', 'group_name')->orderBy('group_name')->get();

    // Pass both $centers and $groups to the view
    return view('in.groups.group_centers.index', compact('centers', 'groups'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::where('is_active', true)->get();
        $employees = Employee::where('is_active', true)->get();

        return view('in.groups.group_centers.create', compact('groups', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'center_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'collection_officer_id' => 'nullable|exists:employees,id',
            'established_date' => 'nullable|date',
        ]);

        // Generate a unique center code based on group and date
        $group = Group::find($validated['group_id']);
        $validated['center_code'] = strtoupper('CTR-' . Str::slug($group->group_name, '-') . '-' . now()->format('Ymd') . '-' . rand(100, 999));

        $validated['created_by'] = auth()->id() ?? 1;
        $validated['is_active'] = true;

        GroupCenter::create($validated);

        return redirect()->route('group_centers.index')->with('success', 'Group Center created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupCenter $groupCenter)
    {
        $groupCenter->load(['group', 'collectionOfficer']);
        return view('in.groups.group_centers.show', compact('groupCenter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupCenter $groupCenter)
    {
        $groups = Group::where('is_active', true)->get();
        $employees = Employee::where('is_active', true)->get();

        return view('in.groups.group_centers.edit', compact('groupCenter', 'groups', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupCenter $groupCenter)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'center_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'collection_officer_id' => 'nullable|exists:employees,id',
            'established_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id() ?? 1;

        $groupCenter->update($validated);

        return redirect()->route('group_centers.index')->with('success', 'Group Center updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupCenter $groupCenter)
    {
        $groupCenter->delete();

        return redirect()->route('group_centers.index')->with('success', 'Group Center deleted successfully!');
    }
}
