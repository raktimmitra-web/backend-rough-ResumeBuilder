<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
     public function index()
    {
        return [
            'total_users' => User::where('role', 'user')->count(),

            'active_today' => User::where('role', 'user')
                ->whereDate('last_login_at', today())
                ->count(),

            'total_resumes' => Resume::count(),

            'premium_users' => User::where('role', 'user')
                ->where('is_premium', true)
                ->count(),

            'suspended' => User::where('status', 'suspended')->count(),

            'activity_chart' => $this->getLoginActivity(),
        ];
    }
    
     private function getLoginActivity()
    {
        $days = 7; // last 7 days

        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $count = User::where('role', 'user')
                ->whereDate('last_login_at', $date)
                ->count();

            $data[] = [
                'date' => $date->format('d M'), // ex: 02 Feb
                'count' => $count,
            ];
        }

        return $data;
    }


}
