<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found'], 404);
        }
        return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateTask($request);
        return Task::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if ($task) {
            return $task;
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validateTask($request);
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->update($request->all());
        return response()->json(['message' => 'Task updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    /**
     * Validate the input data for task creation or update.
     */
    protected function validateTask(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => ['required', Rule::in(['New', 'In Progress', 'Completed'])],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'due_date' => 'required|date_format:Y-m-d H:i:s',
            'creator_id' => 'required',
            'assigned_user_id' => 'required'
        ]);
    }
}
