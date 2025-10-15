<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessLog;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user level.
     */
    public function index()
    {
        \App\Services\DataService::initializeData();
        
        $user = auth()->user();

        // Log the dashboard access
        AccessLog::create([
            'user_id' => $user->id,
            'action' => 'dashboard_access',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'description' => 'Acesso ao dashboard: ' . $user->level,
        ]);

        // Redirect to level-specific dashboard
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isProfessor()) {
            return $this->professorDashboard();
        } elseif ($user->isEstudante()) {
            return $this->estudanteDashboard();
        }

        // Fallback
        return view('dashboard');
    }

    /**
     * Show admin dashboard.
     */
    protected function adminDashboard()
    {
        $stats = [
            'teachers' => count(session('teachers', [])),
            'students' => count(session('students', [])),
            'disciplines' => count(session('disciplines', [])),
            'classrooms' => count(session('classrooms', [])),
        ];

        // Get recent access logs
        try {
            $recentAccess = AccessLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $recentAccess = collect();
        }

        return view('dashboards.admin', compact('stats', 'recentAccess'));
    }

    /**
     * Show professor dashboard.
     */
    protected function professorDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'disciplines' => count(session('disciplines', [])),
            'students' => count(session('students', [])),
        ];

        return view('dashboards.professor', compact('stats'));
    }

    /**
     * Show student dashboard.
     */
    protected function estudanteDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'disciplines' => count(session('disciplines', [])),
        ];

        return view('dashboards.estudante', compact('stats'));
    }
}
