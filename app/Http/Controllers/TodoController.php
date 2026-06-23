<?php

namespace App\Http\Controllers;

use App\Mail\TodoCreatedMail;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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
            'todos' => Todo::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('todos/create', [
            'todos' => Todo::all(),
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('todos/edit', [
            'todo' => Todo::findOrFail($id),
        ]);
    }

    public function store(Request $request)
    {
        $todo = Todo::create($request->all());

        try {
            Mail::to('test@example.com')->send(new TodoCreatedMail($todo));
        } catch (TransportExceptionInterface $exception) {
            Log::warning('Todo created, but the notification email could not be sent.', [
                'todo_id' => $todo->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return Inertia::render('todos/index', [
            'todos' => Todo::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update($request->all());

        return Inertia::render('todos/index', [
            'todos' => Todo::all(),
        ]);
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return Inertia::render('todos/index', [
            'todos' => Todo::all(),
        ]);
    }
}
