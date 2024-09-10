@extends('panel.layouts.master')
@section('title', 'ویرایش کاربر')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش کاربر</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('users.update', $user->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="name" class="form-label">نام <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name"
                                               value="{{ $user->name }}">
                                        @error('name')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="family" class="form-label">نام خانوادگی <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="family" id="family"
                                               value="{{ $user->family }}">
                                        @error('family')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="phone" class="form-label">شماره موبایل <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                               value="{{ $user->phone }}">
                                        @error('phone')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="password" class="form-label">رمز عبور <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password"
                                               value="{{ old('password') }}">
                                        @error('password')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if(auth()->id() != $user->id)
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                            <label for="role">نقش <span class="text-danger">*</span></label>
                                            <select class="form-control" data-toggle="select2" name="role" id="role">
                                                @foreach(\App\Models\Role::all() as $role)
                                                    @can('superuser')
                                                        <option
                                                            value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                                                    @else
                                                        @if($role->name != 'admin')
                                                            <option
                                                                value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                                                        @endif
                                                    @endcan

                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="role">جنسیت <span class="text-danger">*</span></label>
                                        <select class="form-control" data-toggle="select2" name="gender" id="gender">
                                            <option selected disabled>انتخاب کنید...</option>
                                            <option value="male" {{$user->gender == 'male'?'selected':''}}>مرد</option>
                                            <option value="female" {{$user->gender == 'female'?'selected':''}}>زن
                                            </option>
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @can('admin')
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                            <label for="sign_image">تصویر امضاء (PNG)</label>
                                            <input type="file" class="form-control" name="sign_image" id="sign_image"
                                                   accept="image/png">
                                            @if($user->sign_image)
                                                <a href="{{ $user->sign_image }}" class="btn btn-link" target="_blank">مشاهده
                                                    امضاء</a>
                                            @endif
                                            @error('sign_image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



