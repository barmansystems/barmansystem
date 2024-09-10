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
                            <form action="{{ route('products.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">عنوان محصول <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                                        @error('title')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="sku" class="form-label">کد محصول (sku)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sku" id="sku" value="{{ old('sku') }}">
                                        @error('sku')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="code" class="form-label">کد حسابداری <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}">
                                        @error('code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="category" class="form-label">دسته بندی <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category" id="category" data-toggle="select2">
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="single_price" class="form-label">قیمت تک فروشی (ریال) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="single_price" id="single_price" value="{{ old('single_price') }}">
                                        <small id="single_price_words" class="text-primary"></small>
                                        @error('single_price')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="system_price" class="form-label">قیمت سامانه (ریال) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="system_price" id="system_price" value="{{ old('system_price') }}">
                                        <small id="system_price_words" class="text-primary"></small>
                                        @error('system_price')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="partner_price_tehran" class="form-label">قیمت همکار - تهران (ریال) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="partner_price_tehran" id="partner_price_tehran" value="{{ old('partner_price_tehran') }}">
                                        <small id="partner_price_tehran_words" class="text-primary"></small>
                                        @error('partner_price_tehran')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="partner_price_other" class="form-label">قیمت همکار - شهرستان (ریال) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="partner_price_other" id="partner_price_other" value="{{ old('partner_price_other') }}">
                                        <small id="partner_price_other_words" class="text-primary"></small>
                                        @error('partner_price_other')
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
@section('scripts')
    <script src="{{ asset('/assets/js/number2word.js') }}" type="text/javascript"></script>
    <script>
        var number2Word = new Number2Word();

        $(document).ready(function () {
            // Number To Words

            // when document was ready
            let system_price = number2Word.numberToWords($('#system_price').val()) + ' ریال '
            $('#system_price_words').text(system_price)

            let partner_price_tehran = number2Word.numberToWords($('#partner_price_tehran').val()) + ' ریال '
            $('#partner_price_tehran_words').text(partner_price_tehran)

            let partner_price_other = number2Word.numberToWords($('#partner_price_other').val()) + ' ریال '
            $('#partner_price_other_words').text(partner_price_other)

            let single_price = number2Word.numberToWords($('#single_price').val()) + ' ریال '
            $('#single_price_words').text(single_price)

            // when change the inputs
            $(document).on('keyup', '#system_price', function () {
                let price = number2Word.numberToWords(this.value) + ' ریال '
                $('#system_price_words').text(price)
            })

            $(document).on('keyup', '#partner_price_tehran', function () {
                let price = number2Word.numberToWords(this.value) + ' ریال '
                $('#partner_price_tehran_words').text(price)
            })

            $(document).on('keyup', '#partner_price_other', function () {
                let price = number2Word.numberToWords(this.value) + ' ریال '
                $('#partner_price_other_words').text(price)
            })

            $(document).on('keyup', '#single_price', function () {
                let price = number2Word.numberToWords(this.value) + ' ریال '
                $('#single_price_words').text(price)
            })
            // end Number To Words
        })
    </script>
@endsection
