@extends('panel.layouts.master')
@section('title', 'مهندسی خرید')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مهندسی خرید</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('purchase.status.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="attachment" class="form-label">تعداد<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="count" id="count"
                                               value="{{ old('count') }}">
                                        @error('count')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="attachment" class="form-label">وضعیت<span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control" data-toggle="select2">

                                            @foreach (App\Models\Purchase::STATUS as $key => $value)
                                                <option value="{{ $key }}" {{ old('status', 'pending_purchase') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mb-2 col-xl-6 col-lg-6 col-md-6">
                                        <label for="code" class="form-label">توضیحات</label>
                                        <textarea type="desc" class="form-control" name="desc"
                                                  id="desc" placeholder="اختیاری">{{ old('desc') }}</textarea>
                                        @error('desc')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback text-danger d-block" id="error-text"></div>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary mt-3">ثبت فرم</button>
                                </div>


                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

