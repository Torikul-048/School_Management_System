<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect to appropriate dashboard based on role
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return view('dashboards.admin');
        } elseif ($user->hasRole('Teacher')) {
            return view('dashboards.teacher');
        } elseif ($user->hasRole('Student')) {
            return view('dashboards.student');
        } elseif ($user->hasRole('Parent')) {
            return view('dashboards.parent');
        } elseif ($user->hasRole('Accountant')) {
            return view('dashboards.accountant');
        } elseif ($user->hasRole('Librarian')) {
            return view('dashboards.librarian');
        }
        
        // Default fallback
        return view('dashboard');
    }

    public function adminDashboard()
    {
        return view('dashboards.admin');
    }

    public function teacherDashboard()
    {
        return view('dashboards.teacher');
    }

    public function studentDashboard()
    {
        return view('dashboards.student');
    }

    public function parentDashboard()
    {
        return view('dashboards.parent');
    }

    public function accountantDashboard()
    {
        return view('dashboards.accountant');
    }

    public function librarianDashboard()
    {
        return view('dashboards.librarian');
    }
}
