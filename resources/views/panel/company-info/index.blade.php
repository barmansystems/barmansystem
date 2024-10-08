@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')

@section('styles')
    <style>
        #stats i.fa, i.fab {
            font-size: 30px;
        }

        .form-check-input {
            height: 1.2rem;
            width: 1.2rem;
        }
        .no-select {
            user-select: none; /* برای جلوگیری از انتخاب محتوا */
            pointer-events: none; /* غیر فعال کردن کلیک و تعامل */
            background-color: #f9f9f9; /* رنگ پس‌زمینه برای مشخص کردن سلول */
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
                        <h4 class="page-title">اطلاعات شرکت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                <div>
                                    {{--                                    <button class="btn btn-success" form="excel_form">--}}
                                    {{--                                        <i class="fa fa-file-excel mr-2"></i>--}}
                                    {{--                                        دریافت اکسل--}}
                                    {{--                                    </button>--}}
                                    <button class="btn btn-info" id="print" form="form_print" disabled>
                                        <i class="fa fa-print mr-2"></i>
                                        پرینت
                                    </button>
                                    {{--                                       @can('products-create')--}}
                                    <a href="{{ route('company-info.edit',$info->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pen mr-2"></i>
                                        ویرایش اطلاعات
                                    </a>
                                    {{--                                       @endcan--}}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <form id="form_print" action="{{route('company-info-print-data')}}" method="post">
                                    @csrf
                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                            <th>ردیف</th>
                                            <th>شرح</th>
                                            <th>اطلاعات</th>
                                            <th>کپی</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                                 @foreach ([
                                            ['key' => 'name', 'name' => 'نام شخص حقوقی', 'value' => $info->name],
                                            ['key' => 'economic_number', 'name' => 'شماره اقتصادی', 'value' => $info->economic_number],
                                            ['key' => 'national_number', 'name' => 'شماره ثبت / شماره ملی', 'value' => $info->national_number],
                                            ['key' => 'national_id', 'name' => 'شناسه ملی', 'value' => $info->national_id],
                                            ['key' => 'address', 'name' => 'نشانی', 'value' => $info->address],
                                            ['key' => 'zip_code', 'name' => 'کد پستی', 'value' => $info->zip_code],
                                            ['key' => 'phone_number', 'name' => 'شماره تلفن', 'value' => $info->phone_number],
                                            ['key' => 'fax_number', 'name' => 'شماره فکس', 'value' => $info->fax_number],
                                            ['key' => 'mobile_number', 'name' => 'شماره موبایل', 'value' => $info->mobile_number],
                                            ['key' => 'bank_account_number', 'name' => 'شماره حساب', 'value' => $info->bank_account_number],
                                            ['key' => 'shaba_number', 'name' => 'شماره شبا', 'value' => $info->shaba_number],
                                            ['key' => 'account_user_name', 'name' => 'نام کاربری حساب', 'value' => $info->account_user_name],
                                            ['key' => 'account_user_password', 'name' => 'رمز عبور حساب', 'value' => $info->account_user_password],
                                        ] as $key => $field)
                                            <tr>
                                                <td><input type="checkbox" name="data[{{ $field['key'] }}]" value="{{ $field['value'] }}" class="form-check-input checkData"></td>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $field['name'] }}</td>
                                                <td class="no-select">{{ $field['value'] }}</td>
                                                <td>
                                                    <button type="button" data-copy="{{ $field['value'] }}" class="btn btn-info copyData">
                                                        <i class="fa fa-copy mr-2"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </form>
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
            $(document).on('change', '#checkAll', function () {
                if ($('#checkAll').is(':checked')) {

                    $('.checkData').prop('checked', true);
                    $('#print').prop('disabled', false);
                } else {
                    $('.checkData').prop('checked', false);
                    $('#print').prop('disabled', true);
                }
            });

            $(document).on('change', '.checkData', function () {
                if ($('.checkData:checked').length > 0) {
                    $('#print').prop('disabled', false);
                } else {

                    $('#print').prop('disabled', true);
                }
            });



            $(document).on('click', '.copyData', function () {
                var btn = $(this);
                btn.prop('disabled', true);

                btn.text('صبرکنید ...');
                var textToCopy = btn.data('copy');
                $.ajax({
                    url: '/panel/company-info-copy',
                    method: 'GET',
                    data: { copy_data: textToCopy },
                    success: function (response) {
                        if (response === 'success') {
                            console.log(textToCopy);

                            if (textToCopy) {
                                var tempInput = document.createElement("input");
                                tempInput.value = textToCopy;
                                document.body.appendChild(tempInput);
                                tempInput.select();
                                document.execCommand("copy");
                                document.body.removeChild(tempInput);

                                btn.text('کپی شد!');

                                setTimeout(function() {
                                    btn.html('<i class="fa fa-copy mr-2"></i>');
                                    btn.prop('disabled', false);
                                }, 2000);
                            } else {
                                btn.text('خطا');
                                setTimeout(function() {
                                    btn.html('<i class="fa fa-copy mr-2"></i>');
                                    btn.prop('disabled', false);
                                }, 2000);
                            }
                        } else {
                            btn.text('خطا');
                            setTimeout(function() {
                                btn.html('<i class="fa fa-copy mr-2"></i>');
                                btn.prop('disabled', false);
                            }, 2000);
                        }
                    },
                    error: function () {
                        btn.text('خطا');
                        setTimeout(function() {
                            btn.html('<i class="fa fa-copy mr-2"></i>');
                            btn.prop('disabled', false);
                        }, 2000);
                    }
                });
            });




        });
    </script>
@endsection
