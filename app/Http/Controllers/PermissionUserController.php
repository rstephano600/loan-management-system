<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Models\PermissionUser;
use Illuminate\Http\Request;

class PermissionUserController extends Controller
{
    public function usersRole()
    {
        $users = User::where('Status', 'Active')->get();
        return view('in.permissions.usersRole', compact('users'));
    }

    public function assignRole($id)
    {
        $user = User::findOrFail($id);

        $permissions = Permission::where('Status', 'Active')->get();

        // already assigned permissions
        $assigned = PermissionUser::where('User_id', $id)
            ->pluck('permission_id')
            ->toArray();

        return view('in.permissions.assignRole', compact('user', 'permissions', 'assigned'));
    }

    // 🔹 3. Store assigned permissions
    public function permissionsstore(Request $request)
    {
        $request->validate([
            'User_id' => 'required|exists:users,id',
            'permissions' => 'required|array'
        ]);

        $userId = $request->User_id;
        $creatorId = auth()->id();

        // ❗ Remove old permissions (optional but recommended)
        PermissionUser::where('User_id', $userId)->delete();

        foreach ($request->permissions as $permissionId) {
            PermissionUser::create([
                'User_id' => $userId,
                'permission_id' => $permissionId,
                'Creater_id' => $creatorId,
                'Status' => 'Active',
                'duration' => 'Permanent'
            ]);
        }

        return redirect()->back()->with('success', 'Permissions assigned successfully');
    }
}