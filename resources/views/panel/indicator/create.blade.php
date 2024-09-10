@extends('panel.layouts.master')
@section('title', 'نامه نگاری')
@section('styles')
    <style>
        #exportPdf {
            transition: background-color 0.3s ease, color 0.3s ease, opacity 0.3s ease;
        }
    </style>
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
                        <h4 class="page-title">نامه نگاری</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('indicator.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">عنوان <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               value="{{ old('title') }}">
                                        @error('title')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback text-danger d-block" id="error-title"></div>

                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="to_date" class="form-label">تاریخ</label>
                                        <input type="text" name="date" class="form-control date-picker-shamsi-list"
                                               id="date" value="{{ old('date') }}">
                                        @error('date')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
{{--                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">--}}
{{--                                        <label for="number" class="form-label">شماره نامه</label>--}}
{{--                                        <input type="text" class="form-control" name="number" id="number"--}}
{{--                                               value="{{ old('number') }}">--}}
{{--                                        @error('number')--}}
{{--                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="attachment" class="form-label">پیوست</label>
                                        <input type="text" class="form-control" name="attachment" id="attachment"
                                               value="{{ old('attachment') }}">
                                        @error('attachment')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="attachment" class="form-label">سربرگ</label>
                                        <select name="header" class="form-control" id="header">
                                            <option value="info">سربرگ فارسی پرسو تجارت (Info)</option>
                                            <option value="sale">سربرگ فارسی پرسو تجارت (Sale)</option>
                                            <option value="english">سربرگ انگلیسی پرسو تجارت</option>
                                        </select>
                                        @error('header')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="attachment" class="form-label">ارسال به</label>
                                        <select name="receiver[]" class="form-control" id="receiver" data-toggle="select2" multiple>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name.' '.$user->family}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-xl-12 col-lg-12 col-md-12">
                                        <label for="code" class="form-label">متن نامه<span
                                                class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" name="text"
                                                  id="text">{{ old('text') }}</textarea>
                                        @error('text')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback text-danger d-block" id="error-text"></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">ثبت نامه</button>
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
    <script src="{{asset('/assets/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('/assets/js/ckeditor/adapters/jquery.js')}}"></script>
    <script>
        $(document).ready(function () {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#text').ckeditor({
                language: "fa",
                font_names:
                    'Vazir/Vazir;' +
                    'Nazanin/Nazanin'
                ,
                contentsCss: [
                    CKEDITOR.basePath + 'contents.css',
                    `{{asset("/assets/ckeditor-fonts/font.css")}}`,
                ],
                extraPlugins: 'lineheight',
                toolbar: [
                    {
                        name: 'clipboard',
                        items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                    },
                    {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']},
                    '/',
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language', '-', 'LineHeight']
                    },
                    {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                    {name: 'insert', items: ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']},
                    '/',
                    {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
                    {name: 'colors', items: ['TextColor']},
                    {name: 'tools', items: ['Maximize', 'ShowBlocks']}
                ],
            });






            $(document).on('change', '.cke_combo__font', function () {
                $innerHtml = $("#cke_16_text").text();
            });

            function toggleExportButton() {
                const text = CKEDITOR.instances.text.getData().trim();
                if (text !== '') {
                    $('#exportPdf').prop('disabled', false);
                } else {
                    $('#exportPdf').prop('disabled', true);
                }
            }

            CKEDITOR.instances.text.on('instanceReady', function () {
                toggleExportButton();
            });

            CKEDITOR.instances.text.on('change', function () {
                toggleExportButton();
            });
        });
    </script>
@endsection
