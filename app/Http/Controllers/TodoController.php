<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();

        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $groupedTodos = $todos->groupBy('day');

        $sortedGroupedTodos = collect();

        foreach ($dayOrder as $day) {
            if ($groupedTodos->has($day)) {
                $sortedGroupedTodos->put($day, $groupedTodos->get($day));
            }
        }

        if ($groupedTodos->has(null)) {
            $sortedGroupedTodos->put('Belum Terjadwal', $groupedTodos->get(null));
        }
        return view('todos.index', ['groupedTodos' => $sortedGroupedTodos]);
    }

    public function create()
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        return view('todos.create', compact('days'));
    }

    public function store(Request $request)
    {
        $request->validate(['task' => 'required|string', 'day' => 'nullable|string|in:Senin,Selasa,Rabu,Kamis,Jumat']);
        Todo::create(['task' => $request->task, 'day' => $request->day]);
        return redirect()->route('todos.index')->with('success', 'Task added successfully!');
    }

    public function show(Todo  $todo)
    {
        return view('todos.show', compact('todo'));
    }

    public function edit(Todo $todo)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        return view('todos.edit', compact('todo', 'days'));
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate(['task' => 'required|string', 'day' => 'nullable|string|in:Senin,Selasa,Rabu,Kamis,Jumat']);
        $todo->update(['task' => $request->task, 'day' => $request->day]);
        return redirect()->route('todos.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', 'Task deleted successfully!');

    }

    public function toggleStatus($id)
    {
        $todo = Todo::findOrFail($id);

        // Membalik status dan menyimpannya
        $todo->completed = !$todo->completed;
        $todo->save();

        // Mengembalikan respons yang dibutuhkan oleh JavaScript
        return response()->json(['success' => true, 'is_completed' => $todo->completed]);
    }
}
