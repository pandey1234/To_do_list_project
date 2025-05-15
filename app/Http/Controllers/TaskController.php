<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Display all tasks (for view)
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    // Get all tasks (JSON)
    public function getAllTasks()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    // Get active tasks (JSON)
    public function getActiveTasks()
    {
        $tasks = Task::where('completed', false)->get();
        return response()->json($tasks);
    }

    // Get completed tasks (JSON)
    public function getCompletedTasks()
    {
        $tasks = Task::where('completed', true)->get();
        return response()->json($tasks);
    }

    // Store a new task
    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255|unique:tasks,task',
        ]);

        $task = Task::create([
            'task' => $request->task,
            'completed' => false,
        ]);

        return response()->json($task, 201);
    }

    // Update a task (mark as completed/not completed)
    // public function update(Request $request, $id)
    // {
    //     $task = Task::findOrFail($id);
    //     $task->completed = $request->completed;
    //     $task->save();

    //     return response()->json([
    //         'success' => true,
    //         'task' => $task
    //     ]);
    // }
    public function update(Request $request, $id)
    {
        // Find the task by ID
        $task = Task::findOrFail($id);

        // Update the completion status
        $task->completed = $request->completed;
        $task->save();  // Save the updated task

        // Return the updated task list (or just return the task if needed)
        return response()->json(Task::all());  // This returns the updated task list
    }

    // Delete a task
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
