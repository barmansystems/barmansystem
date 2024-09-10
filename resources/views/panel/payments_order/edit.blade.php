@extends('panel.layouts.master')
@section('title', 'دستور پرداخت / دریافت')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title"> دستور {{$type =='payments'?'پرداخت':'دریافت'}} </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('payments_order.update',$order_payment->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="type" value="{{$type}}">
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="to_date" class="form-label">مبلغ (ریال)<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="amount" class="form-control" id="amount"
                                               value="{{ old('amount',$order_payment->amount) }}">

                                        @error('amount')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="to_date" class="form-label">مبلغ به حروف (ریال)</label>
                                        <input type="text" name="amount_words" class="form-control" id="amount_words"
                                               value="{{ old('amount_words',$order_payment->amount_words) }}" readonly>

                                        @error('amount_words')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="attachment" class="form-label">شماره فاکتور</label>
                                        <input type="text" class="form-control" name="invoice_number"
                                               id="invoice_number"
                                               value="{{ old('invoice_number', $order_payment->invoice_number) }}">

                                        @error('invoice_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="to_date" class="form-label">بابت<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="for" id="for"
                                               value="{{ old('for',$order_payment->for) }}">

                                        @error('for')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror

                                    </div>


                                </div>
                                <div class="row">

                                    @if($type == 'payments')
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3" id="to">
                                            <label for="from" class="form-label">به شرکت/خانم/آقا<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="to"
                                                   value="{{ old('to',$order_payment->to) }}">

                                            @error('to')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    @else
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3" id="from">
                                            <label for="from" class="form-label">از شرکت/خانم/آقا<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="from"
                                                   value="{{ old('from',$order_payment->from) }}">
                                            @error('from')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    @endif
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="from" class="form-label">نام بانک</label>
                                        <input type="text" class="form-control" name="bank_name"
                                               id="bank_name"
                                               value="{{ old('bank_name',$order_payment->bank_name) }}">
                                        @error('bank_name')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="from" class="form-label">شماره کارت / حساب / شبا</label>
                                        <input type="text" class="form-control" name="bank_number"
                                               id="bank_number"
                                               value="{{ old('bank_number',$order_payment->bank_number) }}">
                                        @error('bank_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3" id="site_name" style="display: none">
                                        <label for="from" class="form-label">نام سایت</label>
                                        <input type="text" class="form-control" name="site_name"
                                               value="{{ old('site_name',$order_payment->site_name) }}">
                                        @error('site_name')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if($type == 'payments')
                                    <div class="row">
                                        <label for="from" class="form-label" id="is_online_payment_section">
                                            <input type="checkbox" class="form-check-input" id="is_online_payment"
                                                   name="is_online_payment"
                                                   value="true" {{ old('is_online_payment',$order_payment->is_online_payment) ? 'checked' : '' }}>
                                            پرداخت آنلاین
                                        </label>
                                    </div>
                                @endif

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
    <script src="{{asset('/assets/js/input_mask.js')}}"></script>
    <script src="{{asset('assets/js/number2word.js')}}"></script>
    <script>
        $(document).ready(function () {


            function toggleInputs() {
                var selectedType = $('#type').val();
                if (selectedType === 'payment') {
                    $('#to').show();
                    $('#is_online_payment_section').show();
                    $('#from').hide();

                } else if (selectedType === 'receive') {
                    $('#to').hide();
                    $('#is_online_payment_section').hide();
                    $('#site_name').hide();
                    $('#from').show();


                }
            }

            if ($('#is_online_payment').is(':checked')) {
                $('#site_name').show();
            } else {
                $('#site_name').hide();
            }

            // کنترل تغییر وضعیت چک‌باکس
            $('#is_online_payment').change(function () {
                if ($(this).is(':checked')) {
                    $('#site_name').show();
                } else {
                    $('#site_name').hide();
                }
            });


            $('#type').change(function () {
                toggleInputs();
            });

            toggleInputs();

            $('#amount').inputmask({
                alias: 'numeric',
                groupSeparator: ',',
                autoGroup: true,
                digits: 0,
                removeMaskOnSubmit: true
            });


            // Update amount_words field when amount field changes
            var number2Word = new Number2Word();
            $('#amount').on('input', function () {
                var amountValue = $(this).val().replace(/,/g, ''); // Remove commas from amount
                var amountWords = number2Word.numberToWords(amountValue)
                $('#amount_words').val(amountWords);


            });


        });
    </script>
@endsection
