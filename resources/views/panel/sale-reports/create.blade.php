@extends('panel.layouts.master')
@section('title', 'ایجاد گزارش فروش')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد گزارش فروش</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('sale-reports.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="person_name">نام شخص<span class="text-danger">*</span></label>
                                        <input type="text" name="person_name" class="form-control" id="person_name" value="{{ old('person_name') }}">
                                        @error('person_name')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="organ_name">نام سازمان</label>
                                        <input type="text" name="organ_name" class="form-control" id="organ_name" value="{{ old('organ_name') }}">
                                        @error('organ_name')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="national_code">کد/شناسه ملی</label>
                                        <input type="text" name="national_code" class="form-control" id="national_code" value="{{ old('national_code') }}">
                                        @error('national_code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="invoice">سفارش</label>
                                        <select class="form-control" name="invoice" id="invoice" data-toggle="select2">
                                            @foreach($invoices as $invoiceId => $customerName)
                                                <option value="{{ $invoiceId }}" {{ old('invoice') == $invoiceId ? 'selected' : '' }}> {{ $invoiceId }}- {{ $customerName }}</option>
                                            @endforeach
                                        </select>
                                        @error('invoice')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="payment_type">نوع پرداخت</label>
                                        <input type="text" name="payment_type" class="form-control" id="payment_type"
                                               value="{{ old('payment_type') }}">
                                        @error('payment_type')
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
