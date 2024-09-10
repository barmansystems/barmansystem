@extends('panel.layouts.master')
@section('title', 'افزودن کالا')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">افزودن کالا</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('inventory.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="warehouse_id" value="{{ $warehouse_id }}">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="title">عنوان کالا <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" id="title"
                                               value="{{ old('title') }}">
                                        @error('title')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="code">کد کالا <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" id="code"
                                               value="{{ old('code') }}">
                                        @error('code')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="type">نوع <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="category_id" id="category_id"
                                                data-toggle="select2">
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option
                                                    value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="count">مقدار اولیه <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="count" class="form-control" id="count"
                                               value="{{ old('count') }}"
                                               min="0">
                                        @error('count')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
