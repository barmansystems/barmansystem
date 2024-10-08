@extends('panel.layouts.master')
@section('title', 'ویرایش اطلاعات شرکت')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش اطلاعات شرکت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('company-info.update',$info->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="name" class="form-label">نام شخص حقوقی<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name',$info->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره اقتصادی<span class="text-danger">*</span></label>
                                        <input type="text" name="economic_number" class="form-control" id="economic_number" value="{{ old('economic_number',$info->economic_number) }}">
                                        @error('economic_number')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره ثبت /شماره ملی<span class="text-danger">*</span></label>
                                        <input type="text" name="national_number" class="form-control" id="national_number" value="{{ old('national_number',$info->national_number) }}">
                                        @error('national_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شناسه ملی<span class="text-danger">*</span></label>
                                        <input type="text" name="national_id" class="form-control" id="national_id" value="{{ old('national_id',$info->national_id) }}">
                                        @error('national_id')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">کد پستی<span class="text-danger">*</span></label>
                                        <input type="text" name="zip_code" class="form-control" id="zip_code" value="{{ old('zip_code', $info->zip_code) }}">
                                        @error('zip_code')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره تلفن<span class="text-danger">*</span></label>
                                        <input type="text" name="phone_number" class="form-control" id="phone_number" value="{{ old('zip_code', $info->phone_number) }}">
                                        @error('phone_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره فکس<span class="text-danger">*</span></label>
                                        <input type="text" name="fax_number" class="form-control" id="fax_number" value="{{ old('fax_number', $info->fax_number) }}">
                                        @error('fax_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره فکس<span class="text-danger">*</span></label>
                                        <input type="text" name="mobile_number" class="form-control" id="mobile_number" value="{{ old('mobile_number',  $info->mobile_number) }}">
                                        @error('mobile_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره حساب<span class="text-danger">*</span></label>
                                        <input type="text" name="bank_account_number" class="form-control" id="mobile_number" value="{{ old('bank_account_number',  $info->bank_account_number) }}">
                                        @error('bank_account_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">شماره شبا<span class="text-danger">*</span></label>
                                        <input type="text" name="shaba_number" class="form-control" id="shaba_number" value="{{ old('shaba_number',  $info->shaba_number) }}">
                                        @error('shaba_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">نام کاربری حساب<span class="text-danger">*</span></label>
                                        <input type="text" name="account_user_name" class="form-control" id="account_user_name" value="{{ old('account_user_name',  $info->account_user_name) }}">
                                        @error('account_user_name')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">رمز عبور حساب<span class="text-danger">*</span></label>
                                        <input type="text" name="account_user_password" class="form-control" id="account_user_password" value="{{ old('account_user_password',  $info->account_user_password) }}">
                                        @error('account_user_password')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                        <label for="slug" class="form-label">آدرس<span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" id="address" value="{{ old('address',  $info->address) }}">
                                        @error('address')
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

