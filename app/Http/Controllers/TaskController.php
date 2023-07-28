<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
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
        $taskData = $request->all();
        $taskData['creator_id'] = auth()->id();
        return Task::create($taskData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return $task;


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

        if($task->creator->id !== auth()->id() && $task->assignedTo->id !== auth()->id()) {
            return response()->json(['message' => 'Only creator and assigned user can change task!'], 403);
        }

        $taskData = $request->except('creator_id');

        if($task->assignedTo->id === auth()->id()){
            unset($taskData['assigned_user_id']);
        }


        $task->update($taskData);
        return response()->json(['message' => 'Task updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $task = Task::findOrFail($id);
            if($task->creator->id === auth()->id() || auth()->user()->role === 'Admin'){
                $task->delete();
                return response()->json(['message' => 'Task deleted successfully']);
            } else return response()->json(['message' => 'Only task creator and admin can delete task!'], 403);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    /**
     * Search for task by title
     */
    public function search($title)
    {
        $tasks = Task::where('title', 'like', '%'.$title.'%')->get();
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No task found'], 404);
        }
        return $tasks;
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
            'creator_id' => ['nullable', Rule::exists('users', 'id')],
            'assigned_user_id' => ['required', Rule::exists('users', 'id')],
        ]);
    }
}
