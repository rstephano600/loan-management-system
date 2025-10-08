<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\LogActivity;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        switch ($user->role) {
            case User::ROLE_ADMIN:
                return redirect()->route('admin.dashboard');

            case User::ROLE_DIRECTOR:
                return redirect()->route('director.dashboard');

            case User::ROLE_CEO:
                return redirect()->route('ceo.dashboard');

            case User::ROLE_SHAREHOLDERS:
                return redirect()->route('shareholders.dashboard');

            case User::ROLE_MANAGER:
                return redirect()->route('manager.dashboard');

            case User::ROLE_MARKETING_OFFICER:
                return redirect()->route('marketingofficer.dashboard');

            case User::ROLE_HR:
                return redirect()->route('hr.dashboard');

            case User::ROLE_ACCOUNTANT:
                return redirect()->route('accountant.dashboard');

            case User::ROLE_SECRETARY:
                return redirect()->route('secretary.dashboard');

            case User::ROLE_LOAN_OFFICER:
                return redirect()->route('loanofficer.dashboard');

            case User::ROLE_CLIENT:
                return redirect()->route('client.dashboard');

            case User::ROLE_USER:
            default:
                return redirect()->route('user.dashboard');
        }
    }
}
