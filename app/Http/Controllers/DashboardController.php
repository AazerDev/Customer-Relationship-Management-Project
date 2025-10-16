<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        return view('supper-admin.dashboard');
    }


    /**
     * Get User Dashboard Stats
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $user = Auth::user();

            // Quick Stats
            $assignedLeads = Lead::where('assigned_to', $user->id)
                ->whereIn('status', ['new', 'contacted', 'qualified'])
                ->count();

            // My Tasks (due today and upcoming)
            $myTasks = Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'completed')
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get();

            // Upcoming Meetings (next 7 days)
            $upcomingMeetings = Meeting::whereHas('lead', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
                ->whereBetween('date', [now(), now()->addDays(7)])
                ->orderBy('date', 'asc')
                ->take(5)
                ->get();

            $res = [
                'quick_stats' => [
                    'assigned_leads' => $assignedLeads,
                    'pending_tasks' => $myTasks->count(),
                    'upcoming_meetings' => $upcomingMeetings->count()
                ],
                // 'activity_timeline' => $recentActivities,
                'my_tasks' => $myTasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'due_date' => $task->due_date ? $task->due_date->format('M j, Y') : 'No due date',
                        'due_date_raw' => $task->due_date,
                        'priority' => $task->priority,
                        'status' => $task->status,
                        'is_today' => $task->due_date ? $task->due_date->isToday() : false,
                        'is_tomorrow' => $task->due_date ? $task->due_date->isTomorrow() : false
                    ];
                }),
                'upcoming_meetings' => $upcomingMeetings->map(function ($meeting) {
                    return [
                        'id' => $meeting->id,
                        'title' => $meeting->title,
                        'meeting_date' => $meeting->date,
                        'meeting_time' => $meeting->time,
                        'meeting_date_raw' => $meeting->date,
                        'client_name' => $meeting->client->name ?? 'N/A',
                        'location' => $meeting->location,
                        'is_today' => $meeting->date->isToday()
                    ];
                }),
                'user_info' => [
                    'name' => $user->name,
                    'role' => $user->getRoleNames()->first() ?? 'User',
                    'welcome_message' => $this->getWelcomeMessage() . ', ' . $user->name . '!'
                ]
            ];
            return apiSuccess($res, 'Dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return apiError('Failed to load dashboard data', 500, $e->getMessage());
        }
    }

    /**
     * Get Team Dashboard Stats
     */
    public function getTeamDashboard(): JsonResponse
    {
        try {
            $user = Auth::user();

            // Only managers and admins can access team dashboard
            if (!$user->hasAnyRole(['Admin', 'Manager'])) {
                return apiError('Unauthorized access to team dashboard', 403);
            }

            // Team-wide stats
            $totalLeads = Lead::count();
            $activeLeads = Lead::whereIn('status', ['new', 'contacted', 'qualified'])->count();
            $convertedLeads = Lead::where('status', 'converted')->count();

            // Team tasks overview
            $teamTasks = Task::with('assignee')
                ->where('status', '!=', 'completed')
                ->orderBy('due_date', 'asc')
                ->take(10)
                ->get();

            // Team meetings overview
            $teamMeetings = Meeting::with(['assignedUser', 'client'])
                ->where('date', '>=', now())
                ->where('date', '<=', now()->addDays(7))
                ->orderBy('date', 'asc')
                ->take(10)
                ->get();

            $res = [
                'team_stats' => [
                    'total_leads' => $totalLeads,
                    'active_leads' => $activeLeads,
                    'converted_leads' => $convertedLeads,
                    'conversion_rate' => $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0
                ],
                'team_tasks' => $teamTasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'due_date' => $task->due_date ? $task->due_date->format('M j, Y') : 'No due date',
                        'assignee' => $task->assignee->name,
                        'priority' => $task->priority
                    ];
                }),
                'team_meetings' => $teamMeetings->map(function ($meeting) {
                    return [
                        'id' => $meeting->id,
                        'title' => $meeting->title,
                        'meeting_date' => $meeting->date->format('D, M j g:i A'),
                        'assigned_to' => $meeting->assignedUser->name,
                        'client' => $meeting->client->name ?? 'N/A'
                    ];
                }),
            ];
            return apisuccess($res, 'Team dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return apiError('Failed to load team dashboard data', 500, $e->getMessage());
        }
    }

    /**
     * Get Quick Actions
     */
    public function getQuickActions(): JsonResponse
    {
        try {
            $actions = [
                [
                    'id' => 'new_lead',
                    'label' => 'New Lead',
                    'icon' => null,
                    'route' => '/leads/create'
                ],
                [
                    'id' => 'new_task',
                    'label' => 'New Task',
                    'icon' => null,
                    'route' => '/tasks/create'
                ],
                [
                    'id' => 'new_note',
                    'label' => 'New Note',
                    'icon' => null,
                    'route' => '/activities/create'
                ],
                [
                    'id' => 'schedule_meeting',
                    'label' => 'Schedule Meeting',
                    'icon' => null,
                    'route' => '/meetings/create'
                ]
            ];
            return apisuccess($actions, 'Quick actions retrieved successfully');
        } catch (\Exception $e) {
            return apierror('Failed to load quick actions', 500, $e->getMessage());
        }
    }

    private function getWelcomeMessage(): string
    {
        $hour = now()->hour;
        if ($hour < 12) {
            return 'Good morning';
        } elseif ($hour < 17) {
            return 'Good afternoon';
        } else {
            return 'Good evening';
        }
    }
}
