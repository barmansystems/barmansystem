@extends('panel.layouts.master')
@section('title', 'ثبت گزارش')
@section('styles')
    <!-- Clockpicker -->
    <link rel="stylesheet" href="/vendors/clockpicker/bootstrap-clockpicker.min.css" type="text/css">
    <!-- Datepicker -->
    <link rel="stylesheet" href="/vendors/datepicker/daterangepicker.css">
    <link rel="stylesheet" href="/vendors/datepicker-jalali/bootstrap-datepicker.min.css">

    <style>
        .btn_remove {
            cursor: pointer;
        }

        #btn_add{
            margin-top: 30px
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
                        <h4 class="page-title">ثبت گزارش</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="date" class="form-label">تاریخ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control date-picker-shamsi-list" name="date" id="date" form="form" value="{{ old('date') ?? verta()->format('Y/m/d') }}" required>
                                        @error('date')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="item" class="form-label">گزارش <span class="text-muted">(موردی)</span> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="item" id="item" required>
                                        @error('item')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <button class="btn btn-success" id="btn_add">
                                            <i class="fa fa-plus mr-2"></i>
                                            افزودن
                                        </button>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <ol id="items">
                                        </ol>
                                    </div>
                                </div>
                            <form action="{{ route('reports.store') }}" method="post" id="form">
                                @csrf
                                <input type="hidden" name="items" id="items_input">
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
    <script src="/vendors/datepicker-jalali/bootstrap-datepicker.min.js"></script>
    <script src="/vendors/datepicker-jalali/bootstrap-datepicker.fa.min.js"></script>
    <script src="/vendors/datepicker/daterangepicker.js"></script>
    <script src="/assets/js/examples/datepicker.js"></script>
    <script src="/vendors/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="/assets/js/examples/clockpicker.js"></script>
    <script>
        var items = [];
        @error('date')
        @foreach(explode(',',session('items')) as $item)
        add_item("{{ $item }}")
        @endforeach
        @enderror
        $(document).ready(function () {
            $('#btn_add').on('click', function () {
                var item = $('#item').val()
                add_item(item)
            })

            $('#item').keypress(function (e) {
                var item = $('#item').val()
                var key = e.which;
                if (key == 13)  // the enter key code
                {
                    add_item(item)
                }
            });

            // remove item
            $(document).on('click', '.btn_remove', function () {
                var text = $(this).siblings()[0].innerText;
                const index = items.indexOf(text);
                items.splice(index, 1);
                $(this).parent().remove()

                $('#items_input').val(items)
            })
        })

        // add item
        function add_item(item) {
            if (item !== '' && items.includes(item) !== true) {
                $('#items').append(`<li>
                        <span>${item}</span>
                        <i class="fa fa-times text-danger ml-2 btn_remove" title="حذف"></i>
                    </li>`)

                $('#item').val('')

                items.push(item)

                $('#items_input').val(items)
            }
        }
    </script>
@endsection




