<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    /**
     * Store a newly created todo in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
        ];

        // Admin only rules
        if ($user->isAdmin()) {
            $rules['assigned_to'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Assigning logic based on roles
        if ($user->isAdmin()) {
            $assignedTo = $validated['assigned_to'];
        } else {
            // Supervisor can only assign to themselves (Personal Todo)
            $assignedTo = $user->id;
        }

        // Calculate sort order
        $maxSortOrder = Todo::where('assigned_to', $assignedTo)->max('sort_order') ?? 0;

        $todo = Todo::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => 'pending',
            'due_date' => $validated['due_date'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'created_by' => $user->id,
            'assigned_to' => $assignedTo,
            'sort_order' => $maxSortOrder + 1,
        ]);

        // Firebase notification hook: Notify supervisor if Admin assigned it
        if ($user->isAdmin() && $todo->assigned_to !== $user->id) {
            $assignee = User::find($todo->assigned_to);
            if ($assignee) {
                try {
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationService->sendToUser(
                        $assignee,
                        "New Task Assigned",
                        "Admin has assigned you: " . $todo->title,
                        'todo_assigned',
                        $todo->department_id,
                        ['click_action' => route('supervisor.dashboard')]
                    );
                } catch (\Exception $e) {
                    Log::error("Failed to send todo Firebase notification: " . $e->getMessage());
                }
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully!',
                'todo' => $todo
            ]);
        }

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update the specified todo in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $user = auth()->user();

        // Authorization checks:
        // Admin: Full access.
        // Supervisor: Edit only own Personal Todo (created by them, assigned to them).
        if (!$user->isAdmin()) {
            if ($todo->created_by !== $user->id || $todo->assigned_to !== $user->id) {
                return response()->json(['message' => 'Unauthorized to edit this task.'], 403);
            }
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
        ];

        if ($user->isAdmin()) {
            $rules['assigned_to'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
        ];

        if ($user->isAdmin()) {
            $updateData['assigned_to'] = $validated['assigned_to'];
        }

        $todo->update($updateData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully!',
                'todo' => $todo
            ]);
        }

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    /**
     * Toggle status between completed and pending.
     */
    public function toggle(Request $request, Todo $todo)
    {
        $user = auth()->user();

        // Authorization:
        // Admin: Any task
        // Supervisor: Only tasks assigned to them
        if (!$user->isAdmin() && $todo->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized to complete this task.'], 403);
        }

        if ($todo->status === 'completed') {
            $todo->update([
                'status' => 'pending',
                'completed_at' => null
            ]);
            $message = 'Task marked as pending!';
        } else {
            $todo->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
            $message = 'Task completed!';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'todo' => $todo
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update status directly for drag and drop task lanes.
     */
    public function status(Request $request, Todo $todo)
    {
        $user = auth()->user();

        // Admin: Any task
        // Supervisor: Only tasks assigned to them
        if (!$user->isAdmin() && $todo->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this task.'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $status = $validated['status'];
        $todo->update([
            'status' => $status,
            'completed_at' => $status === 'completed' ? ($todo->completed_at ?? now()) : null,
        ]);

        $message = match ($status) {
            'completed' => 'Task completed!',
            'in_progress' => 'Task moved to in progress!',
            default => 'Task marked as pending!',
        };

        return response()->json([
            'success' => true,
            'message' => $message,
            'todo' => $todo,
        ]);
    }

    /**
     * Delete the specified todo from database.
     */
    public function destroy(Request $request, Todo $todo)
    {
        $user = auth()->user();

        // Authorization:
        // Admin: Any task
        // Supervisor: Only own Personal Todo
        if (!$user->isAdmin()) {
            if ($todo->created_by !== $user->id || $todo->assigned_to !== $user->id) {
                return response()->json(['message' => 'Unauthorized to delete this task.'], 403);
            }
        }

        $todo->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    /**
     * Reorder the todo item up or down.
     */
    public function reorder(Request $request, Todo $todo)
    {
        $direction = $request->input('direction');
        $user = auth()->user();

        // Authorization:
        // Admin: Any task
        // Supervisor: Only own tasks
        if (!$user->isAdmin() && $todo->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized to reorder this task.'], 403);
        }

        $query = Todo::query();
        if (!$user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        } else {
            // Admin list grouping by assignee to maintain correct relative sorting
            $query->where('assigned_to', $todo->assigned_to);
        }

        // Get sorted list of all tasks for this query
        $todos = $query->orderBy('sort_order', 'asc')->get();

        $currentIndex = $todos->pluck('id')->search($todo->id);
        if ($currentIndex === false) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        $targetIndex = null;
        if ($direction === 'up' && $currentIndex > 0) {
            $targetIndex = $currentIndex - 1;
        } elseif ($direction === 'down' && $currentIndex < $todos->count() - 1) {
            $targetIndex = $currentIndex + 1;
        }

        if ($targetIndex !== null) {
            $targetTodo = $todos[$targetIndex];
            
            // Swap sort_order values
            $temp = $todo->sort_order;
            $todo->sort_order = $targetTodo->sort_order;
            $targetTodo->sort_order = $temp;

            $todo->save();
            $targetTodo->save();

            return response()->json([
                'success' => true,
                'message' => 'Tasks reordered successfully!'
            ]);
        }

        return response()->json(['message' => 'Task is already at the limit.'], 400);
    }
}
