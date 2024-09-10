@extends('panel.layouts.master')
@section('title', 'مشاهده پیام ارسال شده')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مشاهده پیام ارسال شده</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <h5>شماره موبایل: {{ $smsHistory->phone }} </h5>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <h5>فرستنده: {{ $smsHistory->user->fullName() }} </h5>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        وضعیت:
                                        @if($smsHistory->status == 'sent')
                                            <span class="badge bg-success">ارسال شده</span>
                                        @else
                                            <span class="badge bg-warning">ناموفق</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                        <span class="d-block"><strong>متن پیام:</strong></span>
                                        <p>
                                            {!! str_replace("\n",'<br>',$smsHistory->text) !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
