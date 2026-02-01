<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
     public function index()
    {
        return [
            'total_users' => User::count(),
            'active_today' => User::whereDate('last_login_at', today())->count(),
            'total_resumes' => Resume::count(),
            'premium_users' => User::where('is_premium', true)->count(),
            'suspended' => User::where('status','suspended')->count(),
        ];
    }
}
