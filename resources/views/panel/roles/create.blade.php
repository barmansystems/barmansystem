@extends('panel.layouts.master')
@section('title', 'ایجاد نقش')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد نقش</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('roles.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="label" class="form-label">نام فارسی <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="label" id="label" value="{{ old('label') }}" placeholder="مدیر فروش">
                                        @error('label')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="name" class="form-label">نام انگلیسی <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" placeholder="sales-manager">
                                        @error('name')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="permissions" class="mb-1">دسترسی ها <span class="text-danger">*</span></label>
                                        <select class="form-control" data-toggle="select2" name="permissions[]" id="permissions" multiple>
                                            @foreach(\App\Models\Permission::all() as $permission)
                                                <option value="{{ $permission->id }}" {{ old('permissions') ? (in_array($permission->id, old('permissions')) ? 'selected' : '') : '' }}>{{ $permission->label }}</option>
                                            @endforeach
                                        </select>
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



