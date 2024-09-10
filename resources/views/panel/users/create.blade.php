@extends('panel.layouts.master')
@section('title', 'ایجاد کاربر')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد کاربر</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('users.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="name" class="form-label">نام <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="family" class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="family" id="family" value="{{ old('family') }}">
                                        @error('family')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="phone" class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="password" class="form-label">رمز عبور <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}">
                                        @error('password')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="role">نقش <span class="text-danger">*</span></label>
                                        <select class="form-control" data-toggle="select2" name="role" id="role">
                                            @foreach(\App\Models\Role::all() as $role)
                                                @can('superuser')
                                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                                                @else
                                                    @if($role->name != 'admin')
                                                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                                                    @endif
                                                @endcan
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="role">جنسیت <span class="text-danger">*</span></label>
                                        <select class="form-control" data-toggle="select2" name="gender" id="gender">
                                            <option selected disabled>انتخاب کنید...</option>
                                            <option value="male">مرد</option>
                                            <option value="female">زن</option>
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
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



