@extends('panel.layouts.master')
@section('title', 'ایجاد وظیفه')
@section('styles')
    <link rel="stylesheet" href="{{asset('/vendors/clockpicker/bootstrap-clockpicker.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/vendors/datepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendors/datepicker-jalali/bootstrap-datepicker.min.css')}}">

@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد وظیفه</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('tasks.store') }}" method="post">
                                @csrf
                                <div class="row">

                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <div class="mb-2">
                                            <label for="title" class="form-label">عنوان <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title" id="title"
                                                   value="{{ old('title') }}">
                                            @error('title')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-8"></div>
                                        <div class="mb-2">
                                            <label for="description" class="form-label">توضیحات <span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" class="form-control" name="description"
                                                      id="description"
                                                      rows="5">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-8"></div>
                                        <div class="mb-2">
                                            <label for="start_at" class="form-label">تاریخ شروع <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="start_at"
                                                   class="form-control date-picker-shamsi-list"
                                                   id="start_at" value="{{ old('start_at') }}" autocomplete="off">
                                            @error('start_at')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="expire_at" class="form-label">تاریخ انقضا <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="expire_at"
                                                   class="form-control date-picker-shamsi-list"
                                                   id="expire_at" value="{{ old('expire_at') }}" autocomplete="off">
                                            @error('expire_at')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-8"></div>
                                        <div class="mb-2">
                                            <label for="users" class="mb-1">تخصیص به </label>
                                            <select class="form-control" data-toggle="select2" name="users[]"
                                                    id="user_select"
                                                    multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9">
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
    <script src="{{asset('/vendors/datepicker-jalali/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/vendors/datepicker-jalali/bootstrap-datepicker.fa.min.js')}}"></script>
    <script src="{{asset('/vendors/datepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/assets/js/examples/datepicker.js')}}"></script>
    <script src="{{asset('/vendors/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
    <script src="{{asset('/assets/js/examples/clockpicker.js')}}"></script>
    <script>
        $(document).ready(function () {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            function fetchUsers() {

                $.ajax({
                    url: '{{ env('API_BASE_URL').'get-all-users' }}',
                    type: 'POST',
                    headers: {
                        'API_KEY': "{{env('API_KEY_TOKEN_FOR_TICKET')}}"
                    },
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#user_select').empty();
                        $('#user_select').append('<option value="">در حال بارگذاری...</option>');
                    },
                    success: function (response) {
                        console.log(response)
                        $('#user_select').empty();
                        $('#user_select').append('<option disabled>انتخاب کنید...</option>');
                        $.each(response, function (key, user) {
                            $('#user_select').append('<option value="' + user.id + '">' + user.name + ' ' + user.family +' - '+company(user.company_name) +'</option>');
                        });
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                    }
                });

            }

            fetchUsers();

        });


        function company(company) {
            if(company == 'parso'){
                return 'پرسو تجارت';
            }
            if(company == 'adaktejarat'){
                return 'آداک تجارت';
            }
            if(company == 'barman'){
                return 'بارمان سیستم';
            }
        }

    </script>
@endsection