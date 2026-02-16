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

            'template_usage' => $this->getTemplateUsage(),
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
    
     private function getTemplateUsage()
    {
        // Get count of resumes grouped by template
        $templateStats = Resume::selectRaw('template, COUNT(*) as count')
            ->groupBy('template')
            ->orderBy('count', 'desc')
            ->get();

        // Transform the data for frontend pie chart
        return $templateStats->map(function ($stat) {
            return [
                'template' => $stat->template ?? 'Unknown', // Handle null templates
                'count' => $stat->count,
            ];
        })->toArray();
    }

}
