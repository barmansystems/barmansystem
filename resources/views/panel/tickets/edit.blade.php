@extends('panel.layouts.master')
@section('title', 'مشاهده تیکت')
@section('styles')
    <!-- lightbox -->
    <link rel="stylesheet" href="/vendors/lightbox/magnific-popup.css" type="text/css">

    <style>
        .fa-check-double, .fa-check {
            color: green !important;
        }

        .fe-paperclip {
            font-size: large;
        }

        .fe-paperclip span {
            font-size: xx-small;
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
                        <h4 class="page-title">مشاهده تیکت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <!-- chat area -->
                <div class="col">
                    <div class="card">
                        <div class="card-body py-2 px-3 border-bottom border-light">
                            <div class="d-flex py-1">
                                <img src="/assets/images/users/avatar.png" class="me-2 rounded-circle" height="36"
                                     alt="Brandon Smith">
                                <div class="flex-1">
                                    <h5 class="mt-0 mb-0 font-15">
                                        <a href="javascript:void(0)" class="text-reset">
                                            @if(auth()->id() == $ticket['sender']['company_user_id'])
                                                {{ $ticket['receiver']['name'].' '.$ticket['receiver']['family'] }}


                                            @else
                                                {{ $ticket['sender']['name'].' '.$ticket['sender']['family']}}
                                                {{--                                                @dd("test")--}}
                                            @endif
                                        </a>
                                    </h5>
                                </div>
                                <div id="tooltip-container">
                                    <div>
                                        @if($ticket['status'] == 'closed')
                                            <span class="badge bg-success me-2">بسته شده</span>
                                        @else
                                            <span class="badge bg-warning me-2">درحال بررسی</span>
                                        @endif
                                        <button type="button" data-bs-toggle="dropdown"
                                                class="btn btn-sm btn-primary btn-floating" aria-expanded="true">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <div class="dropdown">
                                            <ul class="dropdown-menu">
                                                <li>
                                                    @if($ticket['status'] == 'closed')
                                                        <a class="dropdown-item"
                                                           href="{{ route('ticket.changeStatus', $ticket['id']) }}">درحال
                                                            بررسی</a>
                                                    @else
                                                        <a class="dropdown-item"
                                                           href="{{ route('ticket.changeStatus', $ticket['id']) }}">بسته
                                                            شده</a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="conversation-list chat-app-conversation" data-simplebar
                                style="max-height: 460px">

                                @foreach($ticket['messages'] as $message)
                                    @php
                                        $file = json_decode($message['file']);
                                    @endphp
                                    {{--                                    @dd(auth()->id(),$message['user']['company_user_id'])--}}
                                    @if(auth()->id() == $message['user']['company_user_id'])
                                        <li class="clearfix" @if($loop->last) id="last_message" @endif>
                                            <div class="chat-avatar">
                                                @if($message['read_at'])
                                                    <i class="fa fa-check-double"></i>
                                                @else
                                                    <i class="fa fa-check"></i>
                                                @endif
                                            </div>
                                            <div class="conversation-text">
                                                <div class="ctext-wrap">
                                                    <i>{{ $message['user']['name'].' '. $message['user']['family'] }}</i>
                                                    <p>{!! nl2br(e($message['text'])) !!}</p>
                                                    <hr class="my-0 mt-2">
                                                    <i class="text-muted">{{ verta($message['created_at'])->timezone('Asia/Tehran')->format('H:i - Y/m/d') }}</i>
                                                </div>
                                            </div>
                                        </li>
                                        {{--                                    @dd($file)--}}
                                        @if($file)

                                            <li class="clearfix">
                                                <div class="card mt-2 mb-1 shadow-none border text-start">
                                                    <div class="p-2">
                                                        <a href="{{ env('API_PATH_URL').$file->path }}"
                                                           download="{{env('API_PATH_URL'). $file->path }}"
                                                           target="_blank">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <div class="avatar-sm">
                                                                    <span
                                                                            class="avatar-title bg-primary rounded">{{ $file->type }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col ps-0" dir="ltr">
                                                                    <a href="javascript:void(0);"
                                                                       class="text-muted fw-medium">{{ $file->name }}</a>
                                                                    <p class="mb-0">{{ formatBytes($file->size) }}</p>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <a href="{{ env('API_PATH_URL').$file->path }}"
                                                                       download="{{env('API_PATH_URL'). $file->path }}"
                                                                       class="btn btn-link btn-lg text-muted">
                                                                        <i class="ri-download-fill"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>

                                                </div>

                                            </li>

                                        @endif
                                    @else
                                        <li class="clearfix odd" @if($loop->last) id="last_message" @endif>
                                            <div class="conversation-text">
                                                <div class="ctext-wrap">
                                                    <i>{{ $message['user']['name'].' '. $message['user']['family'] }}</i>
                                                    <p>{!! nl2br(e($message['text'])) !!}</p>
                                                    <hr class="my-0 mt-2">
                                                    <i class="text-muted">{{ verta($message['created_at'])->format('H:i - Y/m/d') }}</i>
                                                </div>
                                            </div>
                                        </li>
                                        @if($file)
                                            <li class="clearfix odd">
                                                <div class="card mt-2 mb-1 shadow-none border text-start"
                                                     style="background: #f1f5f7">
                                                    <div class="p-2">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <div class="avatar-sm">
                                                                    <span
                                                                            class="avatar-title bg-primary rounded">{{ $file->type }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col ps-0" dir="ltr">
                                                                <a href="javascript:void(0);"
                                                                   class="text-muted fw-medium">{{ $file->name }}</a>
                                                                <p class="mb-0">{{ formatBytes($file->size) }}</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="{{env('API_PATH_URL'). $file->path }}"
                                                                   download="{{env('API_PATH_URL'). $file->path }}"
                                                                   class="btn btn-link btn-lg text-muted">
                                                                    <i class="ri-download-fill"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col">
                                    <div class="mt-2 bg-light p-3 rounded">
                                        <form action="{{ route('tickets.update', $ticket['id']) }}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="file" name="file" class="d-none" id="file">
                                            <div class="row">
                                                <div class="col mb-2 mb-sm-0">
                                                    <input type="text" name="text" class="form-control border-0"
                                                           placeholder="پیام خود را وارد کنید" required>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <div class="btn-group">
                                                        <label class="btn btn-light" for="file" id="file_lbl" dir="ltr">
                                                            <i class="fe-paperclip"></i>
                                                        </label>
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-success chat-send">
                                                                <i class='fe-send'></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endsection
        @section('scripts')
            <script>
                $(document).ready(function () {
                    $('*').animate({
                        scrollTop: $("#last_message").offset().top
                    }, 0);
                    $('#file').on('change', function () {
                        $('#file_lbl').attr('title', this.files[0].name).html(`<i class="fe-paperclip"><span class="badge bg-danger">1</span></i>`)
                        $('input[name="text"]').removeAttr('required')
                    });
                });
            </script>
@endsection
