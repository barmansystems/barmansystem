@extends('panel.layouts.master')
@section('title', 'ویرایش بسته ارسالی')
@section('styles')
    <!-- Clockpicker -->
    <link rel="stylesheet" href="/vendors/clockpicker/bootstrap-clockpicker.min.css" type="text/css">
    <!-- Datepicker -->
    <link rel="stylesheet" href="/vendors/datepicker/daterangepicker.css">
    <link rel="stylesheet" href="/vendors/datepicker-jalali/bootstrap-datepicker.min.css">

    <style>
        .select2-dropdown.select2-dropdown--below {
            z-index: 1000000 !important;
        }
    </style>
@endsection
@section('content')
    {{--  Send SMS Modal  --}}
    <div class="modal fade" id="smsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smsModalLabel">ارسال پیامک</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="phone">شماره موبایل<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" maxlength="11" minlength="11">
                        <div class="invalid-feedback text-danger d-block" id="phone_error"></div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="bodyId">پیامک<span class="text-danger">*</span></label>
                        <select class="form-control" id="bodyId" data-toggle="select2">
                            <option value="221288">کد رهگیری مرسوله</option>
                            <option value="221292">عودت فاکتور</option>
                            <option value="221289">یادآوری پرداخت پیش فاکتور</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="text">متن پیامک<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="text" rows="5" readonly></textarea>
                        <div class="invalid-feedback text-danger d-block" id="text_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn_send_sms">
                        <i class="fa fa-paper-plane me-2"></i>
                        <span>ارسال</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--  End Send SMS Modal  --}}

    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش بسته ارسالی</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('packets.update', $packet->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="url" value="{{ $url }}">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="invoice">سفارش<span class="text-danger">*</span></label>
                                        <select class="form-control" name="invoice" id="invoice" data-toggle="select2">
                                            @if($invoices->count())
{{--                                                <option value="{{ $packet->invoice_id }}" selected> {{ $packet->invoice_id }}- {{ $packet->invoice->customer->name }}</option>--}}
                                                @foreach($invoices as $invoiceId => $customerName)
                                                    <option value="{{ $invoiceId }}" {{ $packet->invoice_id == $invoiceId ? 'selected' : '' }}> {{ $invoiceId }}- {{ $customerName }}</option>
                                                @endforeach
                                            @else
                                                <option value="{{ $packet->invoice_id }}" selected> {{ $packet->invoice_id }}
                                                    - {{ $packet->invoice->customer->name }}</option>
                                            @endif
                                        </select>
                                        @error('invoice')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="receiver">گیرنده <span class="text-danger">*</span></label>
                                        <input type="text" name="receiver" class="form-control" id="receiver"
                                               value="{{ $packet->receiver }}">
                                        @error('receiver')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="address">آدرس <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" id="address"
                                               value="{{ $packet->address }}">
                                        @error('address')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="sent_time">زمان ارسال <span class="text-danger">*</span></label>
                                        <input type="text" name="sent_time" class="form-control date-picker-shamsi-list" id="sent_time"
                                               value="{{ verta($packet->sent_time)->format('Y/m/d') }}">
                                        @error('sent_time')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="sent_type">نوع ارسال <span class="text-danger">*</span></label>
                                        <select class="form-control" name="sent_type" id="sent_type" data-toggle="select2">
                                            @foreach(\App\Models\Packet::SENT_TYPE as $key => $value)
                                                <option value="{{ $key }}" {{ $packet->sent_type == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('sent_type')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="send_tracking_code">کد رهگیری ارسالی</label>
                                        <input type="text" name="send_tracking_code" class="form-control" id="send_tracking_code" value="{{ $packet->send_tracking_code }}">
                                        @error('send_tracking_code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="receive_tracking_code">کد رهگیری دریافتی </label>
                                        <input type="text" name="receive_tracking_code" class="form-control" id="receive_tracking_code"
                                               value="{{ $packet->receive_tracking_code }}">
                                        @error('receive_tracking_code')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="invoice_link">لینک پیش فاکتور </label>
                                        <input type="text" name="invoice_link" class="form-control" id="invoice_link" value="{{ $packet->invoice_link }}">
                                        @error('invoice_link')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="packet_status">وضعیت بسته <span class="text-danger">*</span></label>
                                        <select class="form-control" name="packet_status" id="packet_status" data-toggle="select2">
                                            @foreach(\App\Models\Packet::PACKET_STATUS as $key => $value)
                                                <option value="{{ $key }}" {{ $packet->packet_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('packet_status')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="invoice_status">وضعیت فاکتور <span class="text-danger">*</span></label>
                                        <select class="form-control" name="invoice_status" id="invoice_status" data-toggle="select2">
                                            @foreach(\App\Models\Packet::INVOICE_STATUS as $key => $value)
                                                <option value="{{ $key }}" {{ $packet->invoice_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('invoice_status')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="description">توضیحات</label>
                                        <textarea name="description" id="description"
                                                  class="form-control">{{ $packet->description }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary" type="submit">ثبت فرم</button>
                                    <div>
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#smsModal" id="btn_sms">
                                            <i class="fa fa-sms me-2"></i>
                                            ارسال پیامک
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/vendors/datepicker-jalali/bootstrap-datepicker.min.js"></script>
    <script src="/vendors/datepicker-jalali/bootstrap-datepicker.fa.min.js"></script>
    <script src="/vendors/datepicker/daterangepicker.js"></script>
    <script src="/assets/js/examples/datepicker.js"></script>
    <script src="/vendors/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="/assets/js/examples/clockpicker.js"></script>

    <script>
        $(document).ready(function () {
            var code;
            var bodyId;
            var receiver;
            var args;
            var text_error;
            var file_link = '';

            $('#btn_sms').on('click', function () {
                code = $('#send_tracking_code').val().trim();
                receiver = $('#receiver').val();
                bodyId = $('#bodyId').val();
                file_link = $('#invoice_link').val();
                changeBody(bodyId);
            })

            $('#bodyId').on('change', function () {
                bodyId = $(this).val();
                changeBody(bodyId);
            })

            // btn send sms
            $('#btn_send_sms').on('click', function () {
                let bodyId = $('#bodyId').val();
                let phone_error = false;
                let phone = $('#phone').val().trim();
                let text = $('#text').val()

                if (phone === '') {
                    $('#phone_error').text('شماره موبایل را وارد نمایید')
                    phone_error = true;
                } else {
                    if (phone.length !== 11) {
                        $('#phone_error').text('شماره موبایل باید 11 رقم باشد')
                        phone_error = true;
                    } else {
                        $('#phone_error').text('')
                        phone_error = false;
                    }
                }

                if (!phone_error && !text_error) {
                    $('#btn_send_sms').attr('disabled', 'disabled')
                    $('#btn_send_sms span').text('درحال ارسال...')

                    $.ajax({
                        url: "{{ route('sendSMS') }}",
                        type: 'post',
                        data: {
                            bodyId,
                            phone,
                            text,
                            args
                        },
                        success: function (res) {
                            if (res.recId == undefined || res.recId == 11) {
                                Swal.fire({
                                    title: 'خطایی رخ داد',
                                    text: res.status,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    toast: true,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    position: 'top-start',
                                    customClass: {
                                        popup: 'my-toast',
                                        icon: 'icon-center',
                                        title: 'left-gap',
                                        content: 'left-gap',
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'با موفقیت ارسال شد',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    toast: true,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    position: 'top-start',
                                    customClass: {
                                        popup: 'my-toast',
                                        icon: 'icon-center',
                                        title: 'left-gap',
                                        content: 'left-gap',
                                    }
                                })

                                // $('#smsModal').modal('hide')
                            }
                            $('#btn_send_sms').removeAttr('disabled')
                            $('#btn_send_sms span').text('ارسال')
                        }
                    })
                }
            })

            // end btn send sms

            function changeBody(bodyId) {
                if (bodyId == 221288) {
                    if (code == '') {
                        $('#text_error').text('ابتدا فیلد کد رهگیری را وارد نمایید')
                        text_error = true;
                    } else {
                        $('#text_error').text('')
                        text_error = false;
                    }

                    args = [code];

                    $('#text').html(`کد پیگیری مرسوله شما: ${code} \n\n` +
                        `پرسو تجارت ایرانیان\n` +
                        `Parsotejarat.com\n`)
                } else if (bodyId == 221289) {
                    if (receiver == '') {
                        $('#text_error').text('ابتدا فیلد گیرنده را وارد نمایید')
                        text_error = true;
                    }else if (file_link == '') {
                        $('#text_error').text('فیلد لینک پیش فاکتور را وارد نمایید')
                        text_error = true;
                    } else {
                        text_error = false;
                        $('#text_error').text('')
                    }

                    $('#text').html(`مشتری گرامی ${receiver} \n` +
                        `لطفا جهت پرداخت پیش فاکتور خود تا پایان امروز اقدام نمایید. \n` +
                        `لینک دانلود: \n` +
                        `${file_link} \n` +
                        ` با تشکر\n` +
                        `پرسو تجارت ایرانیان \n` +
                        `Parsotejarat.com`)

                    args = [receiver];
                } else {
                    if (receiver == '') {
                        $('#text_error').text('ابتدا فیلد گیرنده را وارد نمایید')
                        text_error = true;
                    } else {
                        text_error = false;
                        $('#text_error').text('')
                    }

                    $('#text').html(`مشتری گرامی ${receiver} \n` +
                        `لطفا پس از دریافت مرسوله خود، دو نسخه از فاکتورها را مهر و امضا و به آدرس زیر ارسال کنید. با تشکر \n` +
                        `آدرس: تهران، خیابان کریمخان، خیابان ایرانشهر، پلاک 242، طبقه پنجم\n` +
                        `کد پستی: 1584745337 \n` +
                        `پرسو تجارت ایرانیان \n` +
                        `Parsotejarat.com`)

                    args = [receiver];
                }
            }
        })
    </script>
@endsection
