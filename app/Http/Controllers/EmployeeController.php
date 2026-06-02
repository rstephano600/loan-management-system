<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Employee;
use App\Models\NextOfKin;
use App\Models\Referee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    public function employeeinfo()
    {
        try{
        $data = Employee::where('Status', 'Active')->get();
        $roles = User::getRoles();
        return view('in.employee.employeeinfo', compact('data', 'roles'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storenewemployeeinfo(Request $request)
    {
        $validated = $request->validate([
            // User Information
            'FirstName' => 'required|string|max:50',
            'MiddleName' => 'nullable|string|max:50',
            'LastName' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'Role' => ['required', Rule::in(User::getRoles())],
            'gender' => 'required|in:Male,Female',
            'Dob' => 'required|date|before:today',
            
            // Employee Information
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'nida' => 'nullable|string|unique:employees,nida|max:50',
            'tribe' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'date_of_hire' => 'required|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            'EmployeeID' => 'nullable|string|unique:employees,EmployeeID',
            'basic_salary' => 'nullable|string',
            
            // Next of Kin
            'nok_first_name' => 'nullable|string|max:255',
            'nok_last_name' => 'nullable|string|max:255',
            'nok_relationship' => 'nullable|string|max:255',
            'nok_gender' => 'nullable|in:Male,Female',
            'nok_email' => 'nullable|email|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_address' => 'nullable|string|max:500',
            'nok_other_informations' => 'nullable|string',
            
            // Referee
            'ref1_first_name' => 'nullable|string|max:255',
            'ref1_last_name' => 'nullable|string|max:255',
            'ref1_gender' => 'nullable|in:Male,Female',
            'ref1_email' => 'nullable|email|max:255',
            'ref1_phone' => 'nullable|string|max:20',
            'ref1_address' => 'nullable|string|max:500',
            'ref1_other_informations' => 'nullable|string',
            'ref1_occupation' => 'nullable|string',
            
        ]);

        // if ($validator->fails()) {
        //         dd($validator->errors()->all());
        //     }

        DB::beginTransaction();

        $userCount = User::count();
        $no = $userCount + 1;
        $month     = date('Y');
        $name = $validated['LastName'] . ',' . ' ' . $validated['FirstName'] . ' ' .  $validated['MiddleName'];
        $FName = strtoupper(substr($validated['FirstName'], 0,1));
        $MName = !empty($validated['MiddleName']) 
                ? strtoupper(substr($validated['MiddleName'], 0, 1)) 
                : '';
        $LName = strtoupper(substr($validated['LastName'], 0,1));
        $Name = $FName.$MName.$LName;
        $username = 'ArBif/'. $Name . '/' . $month. '/00' . $no;
        $profilePicturePath = null;
        $cvPath = null;
        try {
            $user = User::create([
                'username' => $username,
                'name' => $name,
                'FirstName' => $validated['FirstName'],
                'MiddleName' => $validated['MiddleName'],
                'LastName' => $validated['LastName'],
                'gender' => $validated['gender'],
                'Dob' => $validated['Dob'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make('AiBif123456'),
                'Role' => $validated['Role'],
                'User_id' => auth()->id(),
            ]);

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')
                    ->store('employees/profile_pictures', 'public');
            }

            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')
                    ->store('employees/cvs', 'public');
            }
            // Create Employee
            $employee = Employee::create([
                'Employee_id' => $user->id,
                'EmployeeID' => $validated['EmployeeID'],
                'user_id' => auth()->id(),
                'marital_status' => $validated['marital_status'],
                'nida' => $validated['nida'],
                'tribe' => $validated['tribe'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'education_level' => $validated['education_level'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'date_of_hire' => $validated['date_of_hire'],
                'is_active' => true,
                'profile_picture' => $profilePicturePath,
                'cv' => $cvPath,
                'other_information' => $validated['other_information'],
                'created_by' => auth()->id(),
                'User_id' => auth()->id(),
            ]);

            // Create Next of Kin if provided
            if ($request->filled('nok_first_name')) {
                NextOfKin::create([
                    'employee_id' => $employee->id,
                    'first_name' => $validated['nok_first_name'],
                    'last_name' => $validated['nok_last_name'],
                    'gender' => $validated['nok_gender'],
                    'email' => $validated['nok_email'],
                    'phone' => $validated['nok_phone'],
                    'address' => $validated['nok_address'],
                    'other_informations' => $validated['nok_other_informations'],
                    'relationship' => $validated['nok_relationship'],
                    'User_id' => auth()->id(),
                ]);
            }

            // Create Referee 1 if provided
            if ($request->filled('ref1_first_name')) {
                Referee::create([
                    'employee_id' => $employee->id,
                    'first_name' => $validated['ref1_first_name'],
                    'last_name' => $validated['ref1_last_name'],
                    'gender' => $validated['ref1_gender'],
                    'email' => $validated['ref1_email'],
                    'phone' => $validated['ref1_phone'],
                    'address' => $validated['ref1_address'],
                    'other_informations' => $validated['ref1_other_informations'],
                    'occupation' => $validated['ref1_occupation'],
                    'User_id' => auth()->id(),
                ]);
            }
            DB::commit();
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve Registered added Employee' . $username . 'Successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();

            dd($th->getMessage());
            if ($profilePicturePath) {
                Storage::disk('public')->delete($profilePicturePath);
            }
            if ($cvPath) {
                Storage::disk('public')->delete($cvPath);
            }
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790' . $th->getMessage());
            return back();
        }
    }


    public function editemployeeinfo(Employee $employee)
    {
       try{
        $employee->load(['user', 'nextOfKin', 'referees']);
        $roles = User::getRoles();
        return view('in.employees.employeeinfo', compact('employee', 'roles'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function updateemployeeinfo(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            // User Information
            'email' => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'FirstName' => 'required|string|max:50',
            'MiddleName' => 'nullable|string|max:50',
            'LastName' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'Role' => ['required', Rule::in(User::getRoles())],
            'gender' => 'required|in:male,female',
            'Dob' => 'required|date|before:today',
            
            // Employee Information
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nida' => 'nullable|string|max:50|unique:employees,nida,' . $employee->id,
            'tribe' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date_of_hire' => 'required|date',
            'is_active' => 'required|boolean',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            
            // Next of Kin
            'nok_first_name' => 'nullable|string|max:255',
            'nok_last_name' => 'nullable|string|max:255',
            'nok_gender' => 'nullable|in:male,female,other',
            'nok_email' => 'nullable|email|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_address' => 'nullable|string|max:500',
            'nok_other_informations' => 'nullable|string',

            // Referee
            'ref1_first_name' => 'nullable|string|max:255',
            'ref1_last_name' => 'nullable|string|max:255',
            'ref1_gender' => 'nullable|in:male,female',
            'ref1_email' => 'nullable|email|max:255',
            'ref1_phone' => 'nullable|string|max:20',
            'ref1_address' => 'nullable|string|max:500',
            'ref1_other_informations' => 'nullable|string',
        ]);

        DB::beginTransaction();
        $name = $validated['LastName'] . ',' . ' ' . $validated['FirstName'] . ' ' .  $validated['MiddleName'];
        try {
            // Update User
            $userData = [
                'FirstName' => $validated['FirstName'],
                'MiddleName' => $validated['MiddleName'],
                'LastName' => $validated['LastName'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'Role' => $validated['Role'],
                'gender' => $validated['gender'],
                'Dob' => $validated['Dob'],
                'name' => $name,
            ];

            $employee->user->update($userData);

            // Handle file uploads
            if ($request->hasFile('profile_picture')) {
                // Delete old file
                if ($employee->profile_picture) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }
                $validated['profile_picture'] = $request->file('profile_picture')
                    ->store('employees/profile_pictures', 'public');
            }

            if ($request->hasFile('cv')) {
                // Delete old file
                if ($employee->cv) {
                    Storage::disk('public')->delete($employee->cv);
                }
                $validated['cv'] = $request->file('cv')
                    ->store('employees/cvs', 'public');
            }

            // Update Employee
            $employee->update([
                'marital_status' => $validated['marital_status'],
                'nida' => $validated['nida'],
                'tribe' => $validated['tribe'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'education_level' => $validated['education_level'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'date_of_hire' => $validated['date_of_hire'],
                'is_active' => $validated['is_active'],
                'profile_picture' => $validated['profile_picture'] ?? $employee->profile_picture,
                'cv' => $validated['cv'] ?? $employee->cv,
                'other_information' => $validated['other_information'],
                'updated_by' => auth()->id(),
            ]);

            // Update or Create Next of Kin
            if ($request->filled('ref1_first_name')) {
                $employee->nextOfKin()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'first_name' => $validated['ref1_first_name'],
                        'last_name' => $validated['nok_last_name'],
                        'gender' => $validated['nok_gender'],
                        'email' => $validated['nok_email'],
                        'phone' => $validated['nok_phone'],
                        'address' => $validated['nok_address'],
                        'other_informations' => $validated['nok_other_informations'],
                    ]
                );
            }

            // Update or Create Next of Kin
            if ($request->filled('ref1_first_name')) {
                $employee->referees()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'first_name' => $validated['ref1_first_name'],
                        'last_name' => $validated['ref1_last_name'],
                        'gender' => $validated['ref1_gender'],
                        'email' => $validated['ref1_email'],
                        'phone' => $validated['ref1_phone'],
                        'address' => $validated['ref1_address'],
                        'other_informations' => $validated['ref1_other_informations'],
                    ]
                );
            }

            DB::commit();
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve  Updated Employee' . $name . 'Successfully');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function destroyemployeeinfo($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = Employee::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  Empployee successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
}
