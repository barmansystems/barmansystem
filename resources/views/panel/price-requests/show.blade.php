@extends('panel.layouts.master')
@section('title', 'مشاهده قیمت')
@section('styles')
    <style>
        table tbody tr td input {
            text-align: center;
            width: fit-content !important;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مشاهده قیمت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead class="table-primary">
                                        <tr>
                                            <th>عنوان کالا</th>
                                            <th>تعداد</th>
                                            <th>قیمت (تومان)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(json_decode($priceRequest->items) as $item)
                                            <tr>
                                                <td>{{ $item->product }}</td>
                                                <td>{{ $item->count }}</td>
                                                <td>{{ isset($item->price) ? $item->price ? number_format($item->price) : '---' : '---' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



