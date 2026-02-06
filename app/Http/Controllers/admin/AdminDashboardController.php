<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_today' => User::whereDate('last_login_at', today())->count(),
            'total_resumes' => Resume::count(),
            'premium_users' => User::where('is_premium', true)->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }

    public function users()
    {
        return view('admin.users.index');
    }
}