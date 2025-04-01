<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class TaskAssignedController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = User::with('tasks.user')->where('id', auth()->id())->first()->tasks;
            return Datatables::of($tasks)
                ->addColumn('actions', function (Task $task) {
                    $action = "";
                    if ($task->pivot->status == 'pending') {
                        $action = '<form method="POST" action="' . route('tasks.assigned.status', ['id' => $task->id]) . '">'
                            . csrf_field()
                            . method_field('PATCH')
                            . '<input name="status" type="hidden" value="in-progress">
                                <button type="submit" class="btn btn-warning">start</button>
                            </form>';
                    } elseif ($task->pivot->status == 'in-progress') {
                        $action = '<form method="POST" action="' . route('tasks.assigned.status', ['id' => $task->id]) . '">'
                            . csrf_field()
                            . method_field('PATCH')
                            . '<input name="status" type="hidden" value="complete">
                                <button type="submit" class="btn btn-primary">Complete</button>
                            </form>';
                    } else {
                        $action = '<span class="badge bg-success">completed</span>';
                    }
                    return $action;

                })
                ->rawColumns(['actions'])
                ->make();

        }
        return view('tasks.assigned.index');


    }

    public function show(string $taskId)
    {
        Task::findOrFail($taskId)->users()->wherePivot('user_id', auth()->id());
    }

    public function status(string $taskId, Request $request)
    {

        $user = auth()->user();
        $task = Task::findOrFail($taskId);
        $user->tasks()->updateExistingPivot($taskId, ['status' => $request->status]);
        return redirect()->route('tasks.assigned.index');
    }

}
