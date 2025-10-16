<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * get all tasks with filters
     */
    public function index(Request $request): JsonResponse
    {
        $query = Task::with(['assigner', 'assignee']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by assignee
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by assigner
        if ($request->has('assigned_by')) {
            $query->where('assigned_by', $request->assigned_by);
        }

        // Filter by due date range
        if ($request->has('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from);
        }

        if ($request->has('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->per_page ?? 10;
        $tasks = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return apisuccess($tasks, 'Tasks retrieved successfully');
    }

    /**
     * store or update task
     */
    public function storeOrUpdate(Request $request, $id = null): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_by' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'required|date',
            'reminders' => 'nullable|array',
            'reminders.*' => 'integer|min:1', // each reminder must be integer minutes
            'notes' => 'nullable|string'
        ]);

        $data = $request->all();
        $dueDate = \Carbon\Carbon::parse($data['due_date']);
        $reminderDates = [];
        if (!empty($data['reminders'])) {
            foreach ($data['reminders'] as $minutes) {
                $reminderDates[] = $dueDate->copy()->subMinutes($minutes)->toDateTimeString();
            }
        }
        $data['reminders'] = $reminderDates;

        if ($id) {
            // Update existing task
            $task = Task::findOrFail($id);
            $task->update($data);
            $message = 'Task updated successfully';
            $statusCode = 200;
        } else {
            // Create new task
            $task = Task::create($data);
            $message = 'Task created successfully';
            $statusCode = 201;
        }

        $task->load(['assigner', 'assignee']);

        return apiSuccess($task, $message, $statusCode);
    }

    /**
     * get single task
     */
    public function show($id): JsonResponse
    {
        $task = Task::with(['assigner', 'assignee'])->findOrFail($id);
        return apiSuccess($task, 'Task retrieved successfully');
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return apiSuccess(null, 'Task deleted successfully');
    }

    /**
     * update task status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Cancelled'
        ]);

        $task = Task::findOrFail($id);

        $updateData = ['status' => $request->status];
        if ($request->status === 'Completed') {
            $updateData['completed_at'] = now();
        } else {
            $updateData['completed_at'] = null;
        }

        $task->update($updateData);
        $task->load(['assigner', 'assignee']);
        return apiSuccess($task, 'Task status updated successfully');
    }
     /**
     * Assign Task to user
     */
    public function assignTask(Request $request, $id): JsonResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $task = Task::findOrFail($id);
        $task->update(['assigned_to' => $request->assigned_to]);
        $task->load('assignee');
        return apiSuccess($task, 'Task assigned to user successfully');
    }
    /**
     * add reminder
     */
    public function addReminder(Request $request, $id): JsonResponse
    {
        $request->validate([
            'reminder_date' => 'required|date'
        ]);

        $task = Task::findOrFail($id);
        $reminders = $task->reminders ?? [];

        $reminders[] = $request->reminder_date;
        $task->update(['reminders' => $reminders]);
        return apiSuccess($task, 'Reminder added successfully');
    }

    /**
     * remove reminder
     */
    public function removeReminder(Request $request, $id): JsonResponse
    {
        $request->validate([
            'reminder_index' => 'required|integer'
        ]);

        $task = Task::findOrFail($id);
        $reminders = $task->reminders ?? [];

        if (isset($reminders[$request->reminder_index])) {
            unset($reminders[$request->reminder_index]);
            $reminders = array_values($reminders); // Reindex array
            $task->update(['reminders' => $reminders]);
        }
        return apiSuccess($task, 'Reminder removed successfully');
    }

    /**
     * add note
     */
    public function addNote(Request $request, $id): JsonResponse
    {
        $request->validate([
            'note' => 'required|string'
        ]);

        $task = Task::findOrFail($id);
        $notes = $task->notes ? $task->notes . "\n\n" . now()->format('Y-m-d H:i') . ": " . $request->note
            : now()->format('Y-m-d H:i') . ": " . $request->note;

        $task->update(['notes' => $notes]);
        return apiSuccess($task, 'Note added successfully');
    }

    /**
     * get user tasks with filters
     */
    public function getUserTasks(Request $request): JsonResponse
    {
        $query = Task::with(['assigner', 'assignee'])
            ->where('assigned_to', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter overdue tasks
        if ($request->has('overdue')) {
            $query->where('due_date', '<', now())->where('status', '!=', 'Completed');
        }

        $perPage = $request->per_page ?? 10;
        $tasks = $query->orderBy('due_date', 'asc')->paginate($perPage);
        return apiSuccess($tasks, 'Tasks retrieved successfully');
    }
}
