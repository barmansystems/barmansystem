<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('tasks-list');
        $url = $request->query('url');
        $tasks = $this->getTasks($url);
//        dd($tasks);

        return view('panel.tasks.index', compact(['tasks']));
    }

    public function create()
    {
        $this->authorize('tasks-create');

        return view('panel.tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
//        $request->dd();

        $this->authorize('tasks-create');

        $data = [
            'creator_id' => auth()->id(),
            'title' => $request->title,
            'company_name' => env('COMPANY_NAME'),
            'start_at' => verta()->parse($request->start_at)->formatGregorian('Y-m-d H:i:s'),
            'expire_at' => verta()->parse($request->expire_at)->formatGregorian('Y-m-d H:i:s'),
            'users' => $request->users,
            'description' => $request->description,
        ];
        $task = $this->createTask($data);
//        dd($task);
//        $this->assignTaskNotification(json_decode($task));
        // log
        activity_log('create-task', __METHOD__, [$request->all(), $task]);


        alert()->success('وظیفه مورد نظر با موفقیت ایجاد شد', 'ایجاد وظیفه');
        return redirect()->route('tasks.index');
    }

    public function show($id)
    {
        $task = $this->showTask($id);
//       dd($task);
        return view('panel.tasks.show', compact(['task']));
    }

    public function edit($id)
    {
        // access to tasks-edit permission
        $task = $this->showTask($id);
        $this->authorize('tasks-edit');
//        dd($task);

//        // edit own task
//        $this->authorize('edit-task', $task);

        return view('panel.tasks.edit', compact(['task']));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        // access to tasks-edit permission
//        $this->authorize('tasks-edit');
//
//        // edit own task
//        $this->authorize('edit-task', $task);

        // log


        $data = [
            'task_id' => $id,
            'title' => $request->title,
            'start_at' => verta()->parse($request->start_at)->formatGregorian('Y-m-d H:i:s'),
            'expire_at' => verta()->parse($request->expire_at)->formatGregorian('Y-m-d H:i:s'),
            'users' => $request->users,
            'description' => $request->description,
        ];
        $task = $this->updateTask($data);

        activity_log('edit-task', __METHOD__, [$request->all(), $task]);


        alert()->success('وظیفه مورد نظر با موفقیت ویرایش شد', 'ویرایش وظیفه');
        return redirect()->route('tasks.index');

    }

    public function destroy($id)
    {
        // access to tasks-delete permission
//        $this->authorize('tasks-delete');
//
//        // delete own task
//        $this->authorize('delete-task', $task);

        // log
        $task = $this->deleteTask($id);
        activity_log('delete-task', __METHOD__, $task);

//        $task->delete();
        return back();
    }

    public function changeStatus(Request $request)
    {
        $task = $this->changeTaskStatus($request->task_id);
        // log
        activity_log('task-change-status', __METHOD__, [$request->all(), $task]);
        return $task;

    }

    public function addDescription(Request $request)
    {
        $data = [
            'task_id' => $request->task_id,
            'user_id' => auth()->id(),
            'company_name' => env('COMPANY_NAME'),
            'description' => $request->description,
        ];


        $task = $this->changeTaskDescription($data);
        activity_log('task-add-desc', __METHOD__, [$request->all(), $task]);

        return $task;

    }

    public function getDescription(Request $request)
    {
        $task = $this->getTaskDescription($request->pivot_id);
        return $task;
    }

    private function getTasks($url)
    {
        $data = [
            'auth_id' => auth()->id(),
            'company_name' => env('COMPANY_NAME'),
        ];
        $apiUrl = $url ?? env('API_BASE_URL') . 'get-all-tasks';

//        dd($apiUrl);

        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post($apiUrl, $data);
//            dd($response->body());
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function createTask($data)
    {
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'create-task', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function updateTask($data)
    {
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'update-task', $data);
//            dd($response->body());
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function showTask($data)
    {
        $data = [
            'task_id' => $data,
            'auth_id' => auth()->id(),
            'company_name' => env('COMPANY_NAME'),
        ];
        try {
            $response = Http::timeout(30)
                ->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])
                ->post(env('API_BASE_URL') . 'show-task', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function changeTaskStatus($data)
    {
        $data = [
            'task_id' => $data,
            'user_id' => auth()->id(),
            'company_name' => env('COMPANY_NAME'),
        ];
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'change-task-status', $data);
//            dd($response->body());
            if ($response->successful()) {
                return $response->body();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function changeTaskDescription($data)
    {
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'add-task-desc', $data);
            if ($response->successful()) {
                return $response->body();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function getTaskDescription($data)
    {
        $data = [
            'pivot_id' => $data
        ];
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'get-task-desc', $data);
//            dd($response->body());
            if ($response->successful()) {
                return $response->body();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteTask($data)
    {
        $data = [
            'task_id' => $data
        ];
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'delete-task', $data);
//            dd($response->body());
            if ($response->successful()) {
                return $response->body();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

}
