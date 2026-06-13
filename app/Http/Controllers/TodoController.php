<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Inertia\Inertia;
use App\Mail\TodoCreatedMail;
use Illuminate\Support\Facades\Mail;

class TodoController extends Controller
{
    // menampilkan daftar todo list
    public function index()
    {
        // $todos = Todo::all();
        // if ($todos->isEmpty()) {
        //     return response()->json(['message' => 'No todos found'], 404);
        // }       
        // return response()->json($todos);
        return Inertia::render('todos/index', [
            'todos' => Todo::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('todos/create',[
            'todos'=>Todo::all()
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('todos/edit', [
            'todo' => Todo::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $todo = Todo::create($request->all());
        Mail::to('test@example.com')->send(new TodoCreatedMail($todo));
        return Inertia::render('todos/index', [
            'todos' => Todo::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update($request->all());
        return Inertia::render('todos/index', [
            'todos' => Todo::all()
        ]);
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return Inertia::render('todos/index', [
            'todos' => Todo::all()
        ]);
    }
}