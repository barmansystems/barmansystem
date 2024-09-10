@extends('panel.layouts.master')
@section('title', 'ایجاد محصول')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد محصول</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('off-site-products.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="website" value="{{ request()->website }}">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 mb-3">
                                        <label class="form-label" for="title">عنوان<span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
                                        @error('title')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if(request()->website == 'torob' || request()->website == 'emalls')
                                        <div class="col-xl-4 col-lg-4 col-md-4 mb-3">
                                            <label class="form-label" for="url">لینک صفحه (URL)<span class="text-danger">*</span></label>
                                            <input type="url" name="url" class="form-control" id="url" value="{{ old('url') }}">
                                            @error('url')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                            @error('error')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @elseif(request()->website == 'digikala')
                                        <div class="col-xl-4 col-lg-4 col-md-4 mb-3">
                                            <label class="form-label" for="code">کد کالا<span class="text-danger">*</span></label>
                                            <input type="text" name="code" class="form-control" id="code" value="{{ old('code') }}">
                                            @error('code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                            <div>
                                                <img src="{{ asset('/assets/images/digikala-code.png') }}" class="w-100 mt-2"
                                                     data-toggle="tooltip" data-placement="bottom" title=""
                                                     data-original-title="مثال: کد کالا">
                                            </div>
                                        </div>
                                    @endif
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
