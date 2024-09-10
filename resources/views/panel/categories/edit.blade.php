@extends('panel.layouts.master')
@section('title', 'ویرایش دسته بندی')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش دسته بندی</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('categories.update', $category->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="name" class="form-label">نام دسته بندی<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}"
                                               placeholder="نویسنده">
                                        @error('name')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">اسلاگ<span class="text-danger">*</span></label>
                                        <input type="text" name="slug" class="form-control" id="slug" value="{{ $category->slug }}"
                                               placeholder="writer">
                                        @error('slug')
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

