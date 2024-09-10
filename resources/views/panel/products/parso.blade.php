@extends('panel.layouts.master')
@section('title', 'محصولات سایت پرسو تجارت')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">محصولات سایت پرسو تجارت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('parso.index') }}" method="post" id="search_form">
                                @csrf
                            </form>
                            <div class="row mb-3">
                                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                                    <input type="text" name="sku" class="form-control" placeholder="کد محصول (sku)" value="{{ request()->sku ?? null }}" form="search_form">
                                </div>
                                <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12 mt-2">
                                    <input type="text" name="title" class="form-control" placeholder="عنوان محصول" value="{{ request()->title ?? null }}" form="search_form">
                                </div>
                                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            @if(isset($product) && !$errors->count())
                                @if($product)
                                    <form action="{{ route('parso.update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="product" value="{{ json_encode($product) }}">
                                        <div class="row">
                                            <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                                <label for="title" class="form-label">عنوان محصول </label>
                                                <input type="text" class="form-control" name="title" id="title" value="{{ $product->post_title }}" disabled>
                                                @error('title')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                                <label for="sku" class="form-label">کد محصول (sku)</label>
                                                <input type="text" class="form-control" name="sku" id="sku" value="{{ $product->sku }}" disabled>
                                                @error('sku')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                                <label for="price" class="form-label">قیمت (تومان) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="price" id="price" value="{{ (int)$product->min_price }}">
                                                @error('price')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">تغییر قیمت</button>
                                    </form>
                                @else
                                    <div class="text-center text-danger">محصولی با کد یا عنوان مشخص شده وجود ندارد.</div>
                                @endif
                            @endif
                            @if($errors->count())
                                <form action="{{ route('parso.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="product" value="{{ json_encode($product) }}">
                                    <div class="row">
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                            <label for="title" class="form-label">عنوان محصول </label>
                                            <input type="text" class="form-control" name="title" id="title" value="{{ $product->post_title }}" disabled>
                                        </div>
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                            <label for="sku" class="form-label">کد محصول (sku)</label>
                                            <input type="text" class="form-control" name="sku" id="sku" value="{{ $product->sku }}" disabled>
                                        </div>
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                            <label for="price" class="form-label">قیمت (تومان) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="price" id="price" value="{{ (int)$product->min_price }}">
                                            <div class="invalid-feedback text-danger d-block">{{ $errors->messages()['price'][0] }}</div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">تغییر قیمت</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



