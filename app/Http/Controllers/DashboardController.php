<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function redirectBasedOnRole()
    {
        if (auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');  // Redirect ke admin dashboard
        } else {
            return redirect()->route('user.index');  // Redirect ke user dashboard
        }
    }
}
