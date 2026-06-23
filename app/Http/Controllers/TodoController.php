<?php

namespace App\Http\Controllers;

use App\Mail\TodoCreatedMail;
use App\Mail\TodoStatusChangedMail;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class TodoController extends Controller
{
    public function index()
    {
        return Inertia::render('todos/index', [
            'todos' => Auth::user()->todos,
        ]);
    }

    public function create()
    {
        return Inertia::render('todos/create');
    }

    public function edit($id)
    {
        $todo = Auth::user()->todos()->findOrFail($id);

        return Inertia::render('todos/edit', [
            'todo' => $todo,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $todo = Todo::create($data);

        try {
            Mail::to(Auth::user()->email)->send(new TodoCreatedMail($todo));
        } catch (TransportExceptionInterface $exception) {
            Log::warning('Todo created, but the notification email could not be sent.', [
                'todo_id' => $todo->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('todos.index');
    }

    public function update(Request $request, $id)
    {
        $todo = Auth::user()->todos()->findOrFail($id);

        $originalCompleted = $todo->is_completed;

        $todo->update($request->all());

        if (!$originalCompleted && $todo->is_completed) {
            try {
                Mail::to(Auth::user()->email)->send(new TodoStatusChangedMail($todo, 'completed'));
            } catch (TransportExceptionInterface $exception) {
                Log::warning('Todo status changed, but email could not be sent.', [
                    'todo_id' => $todo->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return redirect()->route('todos.index');
    }

    public function destroy($id)
    {
        $todo = Auth::user()->todos()->findOrFail($id);
        $todo->delete();

        return redirect()->route('todos.index');
    }
}
