<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('users-list');

        $users = User::latest()->paginate(10);
        return view('panel.users.index', compact(['users']));
    }

    public function create()
    {
        $this->authorize('users-create');

        return view('panel.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('users-create');

        $selectedRole = \App\Models\Role::find($request->role);
        if (auth()->user()->cannot('superuser')) {
            if ($selectedRole->name == 'admin') {
                return redirect()->back()->withErrors(['role' => 'شما مجاز به انتخاب این نقش نیستید.']);
            }
        }
        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'role_id' => $request->role,
            'password' => bcrypt($request->password),
        ]);


        $userData = [
            'company_user_id' => $user->id,
            'name' => $user->name,
            'family' => $user->family,
            'company' => env('COMPANY_NAME'),
            'role_name' => $user->role->label,
            'phone' => $user->phone,
        ];
        $this->createLeaveInfo($user);
        $this->addUserToMoshrefiApp($userData);

        // log
        activity_log('create-user', __METHOD__, $request->all());

        alert()->success('کاربر مورد نظر با موفقیت ایجاد شد', 'ایجاد کاربر');
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            if (!Gate::allows('edit-profile', $user->id)) {
                abort(403);
            }
        }

        if (!auth()->user()->isSuperuser() && ($user->role->name == 'admin' && $user->id != auth()->id())) {
            abort(403);
        }

        return view('panel.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('users-edit');

        if (!auth()->user()->isSuperuser() && ($user->role->name == 'admin' && $user->id != auth()->id())) {
            alert()->error('شما مجاز به انتخاب این نقش نیستید.', 'عدم دسترسی');
            return redirect()->back();
        }

        // log
        activity_log('edit-user', __METHOD__, [$request->all(), $user]);

        if (auth()->user()->isAdmin()) {
            if ($request->sign_image) {
                if ($user->sign_image) {
                    unlink(public_path($user->sign_image));
                    $sign_image = upload_file($request->file('sign_image'), 'Signs');
                } else {
                    $sign_image = upload_file($request->file('sign_image'), 'Signs');
                }
            } else {
                $sign_image = $user->sign_image;
            }
        } else {
            $sign_image = $user->sign_image;
        }

        $user->update([
            'name' => $request->name,
            'family' => $request->family,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'role_id' => $request->role ?? $user->role_id,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'sign_image' => $sign_image,
        ]);

        $user->refresh();

        $userData = [
            'company_user_id' => $user->id,
            'name' => $user->name,
            'family' => $user->family,
            'company' => env('COMPANY_NAME'),
            'role_name' => $user->role->label,
            'phone' => $user->phone,
        ];
        $test = $this->editUserToMoshrefiApp($userData);


        if (Gate::allows('edit-profile', $user->id)) {
            alert()->success('پروفایل شما با موفقیت ویرایش شد', 'ویرایش پروفایل');
            return redirect()->back();
        } else {
            alert()->success('کاربر مورد نظر با موفقیت ویرایش شد', 'ویرایش کاربر');
            return redirect()->route('users.index');
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('users-delete');

        if ($user->role->name == 'admin' && !auth()->user()->isSuperuser()) {
            return response('شما مجاز به حذف ادمین نیستید', 500);
        }

        activity_log('delete-user', __METHOD__, $user);
        $this->deleteUserToMoshrefiApp($user->id);
        $user->delete();
        return back();
    }

    private function createLeaveInfo(User $user)
    {
        DB::table('leave_info')->insert([
            'user_id' => $user->id,
            'count' => 2,
            'month_updated' => verta()->month,
        ]);
    }

    private function addUserToMoshrefiApp($data)
    {
        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'add-user', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function editUserToMoshrefiApp($data)
    {
        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'edit-user', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

    private function deleteUserToMoshrefiApp($data)
    {
        $data = ['user_id' => $data, 'company' => env('COMPANY_NAME')];
        try {
            $response = Http::timeout(30)->post(env('API_BASE_URL') . 'delete-user', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }

}
