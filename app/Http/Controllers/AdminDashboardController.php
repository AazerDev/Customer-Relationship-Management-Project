<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Meeting;
use App\Models\Notify;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class AdminDashboardController extends Controller
{
     /**
     * Get comprehensive dashboard data for admin
     */
    public function adminDashboard(Request $request): JsonResponse
    {
        try {
            $data = [
                'summary' => $this->getSummaryStats(),
                'recent_activities' => $this->getRecentActivities(),
                'performance_metrics' => $this->getPerformanceMetrics(),
                'notifications' => $this->getNotifications(),
                'upcoming_tasks' => $this->getUpcomingTasks(),
                'user_management' => $this->getUserManagementData(),
                'lead_stage_analytics' => $this->getLeadStageAnalytics(),
                'charts' => [
                    'lead_conversion' => $this->getLeadConversionChart(),
                    'user_performance' => $this->getUserPerformanceChart(),
                    'monthly_trends' => $this->getMonthlyTrendsChart(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics
     */
    private function getSummaryStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'qualified')->count();
        
        $totalClients = Client::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        
        $totalMeetings = Meeting::count();
        $upcomingMeetings = Meeting::where('date', '>=', now())->count();

        return [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                // 'growth' => $this->calculateGrowthPercentage(User::class, 'users')
            ],
            'leads' => [
                'total' => $totalLeads,
                'converted' => $convertedLeads,
                // 'growth' => $this->calculateGrowthPercentage(Lead::class, 'leads')
            ],
            'clients' => [
                'total' => $totalClients,
                // 'growth' => $this->calculateGrowthPercentage(Client::class, 'clients')
            ],
            'tasks' => [
                'total' => $totalTasks,
                'completed' => $completedTasks,
                'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0
            ],
            'meetings' => [
                'total' => $totalMeetings,
                'upcoming' => $upcomingMeetings
            ]
        ];
    }

    /**
     * Calculate growth percentage for various metrics
     */
    // private function calculateGrowthPercentage($model, $metric): float
    // {
    //     $currentPeriod = $model::where('created_at', '>=', now()->subDays(30))->count();
    //     $previousPeriod = $model::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        
    //     if ($previousPeriod === 0) {
    //         return $currentPeriod > 0 ? 100.0 : 0.0;
    //     }
        
    //     return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 2);
    // }

    /**
     * Get recent activities across different models
     */
    private function getRecentActivities(): array
    {
        $recentLeads = Lead::with('assignedUser')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($lead) {
                return [
                    'type' => 'lead',
                    'action' => 'New lead created',
                    'details' => $lead->name,
                    'assigned_to' => $lead->assignedUser->name ?? 'Unassigned',
                    'time' => $lead->created_at->diffForHumans()
                ];
            });

        $recentTasks = Task::with('assignee')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($task) {
                return [
                    'type' => 'task',
                    'action' => 'New task assigned',
                    'details' => $task->title,
                    'assigned_to' => $task->assignee->name ?? 'Unassigned',
                    'time' => $task->created_at->diffForHumans()
                ];
            });

        $recentMeetings = Meeting::with('lead')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($meeting) {
                return [
                    'type' => 'meeting',
                    'action' => 'Meeting scheduled',
                    'details' => $meeting->title,
                    'related_to' => $meeting->lead->name ?? 'N/A',
                    'time' => $meeting->created_at->diffForHumans()
                ];
            });

        $activities = collect([...$recentLeads, ...$recentTasks, ...$recentMeetings])
            ->sortByDesc('time')
            ->take(10)
            ->values();

        return $activities->toArray();
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(): array
    {
        // Lead conversion rate
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'qualified')->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

        // Task completion rate
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        // User engagement (users with activity in last 7 days)
        $activeUsers = User::whereHas('assignedLeads', function ($query) {
            $query->where('last_contacted', '>=', now()->subDays(7));
        })->orWhereHas('settings', function ($query) {
            $query->where('updated_at', '>=', now()->subDays(7));
        })->count();

        $totalUsers = User::count();
        $engagementRate = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;

        return [
            'lead_conversion_rate' => $conversionRate,
            'task_completion_rate' => $taskCompletionRate,
            'user_engagement_rate' => $engagementRate,
            'average_response_time' => '1h 12m', // This would need additional tracking
            'resolution_rate' => 92.1
        ];
    }

    /**
     * Get notifications
     */
    private function getNotifications(): array
    {
        return Notify::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time' => $notification->created_at->diffForHumans()
                ];
            })
            ->toArray();
    }

    /**
     * Get upcoming tasks and deadlines
     */
    private function getUpcomingTasks(): array
    {
        return Task::with(['assignee', 'assigner'])
            ->where('due_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'due_date' => $task->due_date->format('Y-m-d H:i:s'),
                    'priority' => $task->priority,
                    'assignee' => $task->assignee->name ?? 'Unassigned',
                    'assigner' => $task->assigner->name ?? 'System'
                ];
            })
            ->toArray();
    }

    /**
     * Get user management data
     */
    private function getUserManagementData(): array
    {
        $usersByType = User::select('user_type', DB::raw('count(*) as count'))
            ->groupBy('user_type')
            ->get()
            ->pluck('count', 'user_type');

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'email', 'user_type', 'created_at']);

        return [
            'users_by_type' => $usersByType,
            'recent_users' => $recentUsers,
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count()
        ];
    }

    /**
     * Get lead stage analytics
     */
    private function getLeadStageAnalytics(): array
    {
        $stages = Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return [
            'stages' => $stages,
            'total' => array_sum($stages->toArray())
        ];
    }

    /**
     * Get lead conversion chart data
     */
    private function getLeadConversionChart(): array
    {
        $data = Lead::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total'),
            DB::raw('sum(case when status = "qualified" then 1 else 0 end) as converted')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'labels' => $data->pluck('date'),
            'datasets' => [
                [
                    'label' => 'Total Leads',
                    'data' => $data->pluck('total')
                ],
                [
                    'label' => 'Converted Leads',
                    'data' => $data->pluck('converted')
                ]
            ]
        ];
    }

    /**
     * Get user performance chart data
     */
    private function getUserPerformanceChart(): array
    {
        $users = User::withCount(['assignedLeads', 'assignedLeads as converted_leads_count' => function ($query) {
            $query->where('status', 'qualified');
        }])
        ->having('assigned_leads_count', '>', 0)
        ->orderBy('converted_leads_count', 'desc')
        ->limit(10)
        ->get();

        return [
            'labels' => $users->pluck('name'),
            'datasets' => [
                [
                    'label' => 'Total Leads',
                    'data' => $users->pluck('assigned_leads_count')
                ],
                [
                    'label' => 'Converted Leads',
                    'data' => $users->pluck('converted_leads_count')
                ]
            ]
        ];
    }

    /**
     * Get monthly trends chart data
     */
    private function getMonthlyTrendsChart(): array
    {
        $months = [];
        $leadsData = [];
        $tasksData = [];
        $usersData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $months[] = $monthName;

            // Leads created in month
            $leadsData[] = Lead::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            // Tasks created in month
            $tasksData[] = Task::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            // Users created in month
            $usersData[] = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => $leadsData
                ],
                [
                    'label' => 'Tasks',
                    'data' => $tasksData
                ],
                [
                    'label' => 'Users',
                    'data' => $usersData
                ]
            ]
        ];
    }
}
