@extends('panel.layouts.master')
@section('title', 'ویرایش کد تخفیف')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش کد تخفیف</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('coupons.update', $coupon->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title" value="{{ $coupon->title }}">
                                        @error('title')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="code" class="form-label">کد <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" id="code" value="{{ $coupon->code }}">
                                        @error('code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label>درصد تخفیف<span class="text-danger">*</span></label>
                                        <div class="input-group mt-1">
                                            <span class="input-group-text" id="basic-addon1">%</span>
                                            <input type="text" name="amount_pc" class="form-control" min="0" value="{{ $coupon->amount_pc }}">
                                        </div>
                                        @error('amount_pc')
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
