<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskPostRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class TaskCreatedController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $users = User::all(['id', 'name']);
        return view('tasks.created.create')->with('users', $users);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::where('user_id', auth()->id())->get(); // Returns a collection of tasks for the user

            return Datatables::of($tasks)
                ->addColumn('actions', function (Task $task) {
                    return '<a class="link-offset-2 link-underline link-underline-opacity-0"
                           href="' . route("tasks.show", ['task' => $task->id]) . '">
                            <i class="fa fa-eye text-dark"></i>
                        </a>' .
                        '<a class="m-2 link-offset-2 link-underline link-underline-opacity-0"
                           href="' . route('tasks.edit', ['task' => $task->id]) . '">
                            <i class="fa fa-edit text-warning"></i>
                        </a>' .
                        '<form method="POST" action="' . route('tasks.destroy', ['task' => $task->id]) . '"
                              class="d-inline">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type = "submit" class="btn btn-link p-0 border-0 align-baseline" >
                                <i class="fa fa-trash text-danger" ></i >
                            </button >
                        </form >';
                })
                ->editColumn('status', function (Task $task) {
                    if ($task->status == 'pending')
                        return "<span class='badge bg-warning'>" . $task->status . "</span>";
                    elseif ($task->status == 'in-progress')
                        return "<span class='badge bg-primary'>$task->status</span";
                    else
                        return "<span class=' badge bg-success'>$task->status</span";

                })
                ->rawColumns(['actions', 'status'])
                ->make();
        }

        return view('tasks.created.index');
    }

    /**
     * @param string $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

    public function show(string $id)
    {
        $task = Task::with('users')->findOrFail($id);
        return view('tasks.created.show')->with('task', $task);
    }

    /**
     * @param TaskPostRequest $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function store(TaskPostRequest $request)
    {
        DB::beginTransaction();
        try {
            $task = Task::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'priority' => $request->priority,
                'completion_percentage' => 0,
            ]);
            $task->users()->sync($request->users);
            DB::commit();
            return redirect()->route('tasks.index');
        } catch (\Exception $exception) {
            LOG::error('Task not created due to' . $exception);
            DB::rollBack();
          //  return response()->
            return 'Task creation fail due to some technically error';
        }

    }

    /**
     * @param string $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|string
     */
    public function edit(string $id)
    {
        try {
            $task = Task::with('users')->findOrFail($id);
            return view('tasks.created.edit')->with(['task' => $task, 'users' => User::all(['id', 'name'])]);
        } catch (\Exception $exception) {
            Log::error("In edit task not found" . $exception);
            return 'task not found';
        }
    }

    /**
     * @param TaskPostRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function update(TaskPostRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $task = Task::findOrFail($id);
            $task->update([
                'name' => $request->name,
                'description' => $request->description,
                'priority' => $request->priority,
            ]);
            $task->users()->sync($request->users);
            DB::commit();
            return redirect()->route('tasks.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Task not found' . $exception);
            return 'Task update fail due to some technically error';
        }
    }

    /**
     * @param string $taskId
     * @return \Illuminate\Http\RedirectResponse|string
     */

    public function destroy(string $taskId)
    {
        try {
            $taskDeleted = Task::findOrFail($taskId)->name;
            Task::findOrFail($taskId)->delete();
            return redirect()->route('tasks.index', ['deletedTask' => $taskDeleted]);
        } catch (\Exception $exception) {
            Log::error('Unable to delete' . $exception);
            return "Task not found";
        }
    }

}
