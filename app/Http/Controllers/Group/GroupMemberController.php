<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use App\Models\Group;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;

class GroupMemberController extends Controller
{
    public function create($groupId)
    {
        $group = Group::findOrFail($groupId);

        // Only active employees can be added
        $employees = Employee::where('is_active', true)->get();

        return view('in.groups.group_members.create', compact('group', 'employees'));
    }

    public function store(Request $request, $groupId)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'role_in_group' => 'nullable|string|max:255',
        ]);

        $group = Group::findOrFail($groupId);

        // Generate unique member code
        $groupCodePart = strtoupper(substr($group->group_name, 0, 3));
        $employeePart = $request->employee_id;
        $randomPart = strtoupper(Str::random(3));
        $memberCode = "{$groupCodePart}-{$employeePart}-{$randomPart}";

        while (GroupMember::where('member_code', $memberCode)->exists()) {
            $randomPart = strtoupper(Str::random(3));
            $memberCode = "{$groupCodePart}-{$employeePart}-{$randomPart}";
        }

        GroupMember::create([
            'group_id' => $group->id,
            'employee_id' => $validated['employee_id'],
            'role_in_group' => $validated['role_in_group'] ?? null,
            'member_code' => $memberCode,
            'created_by' => auth()->id() ?? 1,
        ]);

        return redirect()->route('groups.show', $groupId)
            ->with('success', 'Group member added successfully!');
    }

    public function destroy(GroupMember $member)
    {
        $member->delete();

        return back()->with('success', 'Member removed successfully!');
    }
}

