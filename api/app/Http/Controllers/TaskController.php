<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();  

        $tasks = Task::where('user_id', $user->id)->get();
        return response()->json($tasks);
    }

    public function allTasks(Request $request)
    {
        if ($request->user()->typeAccount !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }
        $tasks = Task::with(['user:id,name,email'])->get(); 
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        if ($request->user()->typeAccount !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'expiryDate' => 'required|date_format:Y-m-d H:i',
            'completionDate' => 'nullable|date_format:Y-m-d H:i',
            'user_id' => 'required|exists:users,id', 
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'expiryDate' => $validated['expiryDate'],
            'completionDate' => $validated['completionDate'] ?? null, 
            'user_id' => $validated['user_id'], 
        ]);

        return response()->json($task, 201);
    }

    public function show(Request $request, string $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json($task);
    }

    public function toggleCompletion(Request $request, string $id)
    {
        if ($request->user()->typeAccount === 'admin') {
            $task = Task::findOrFail($id);
        } else {
            $task = Task::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
        }
        $task->completionDate = $task->completionDate ? null : now(); 
        $task->save();

        return response()->json($task);
    }

    public function update(Request $request, string $id)
    {
        if ($request->user()->typeAccount !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'expiryDate' => 'required|date_format:Y-m-d H:i',
            'completionDate' => 'nullable|date_format:Y-m-d H:i',
        ]);

        $task = Task::findOrFail($id); 

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Request $request, string $id)
    {
        if ($request->user()->typeAccount !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
