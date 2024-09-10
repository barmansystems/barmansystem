@extends('panel.layouts.master')
@section('title', 'بسته های ارسالی')
@section('content')
    {{--  Post Status Modal  --}}
    <div class="modal fade" id="postStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postStatusModalLabel">وضعیت مرسوله</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    {{--  End Post Status Modal  --}}

    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">بسته های ارسالی</h4>
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
                                    <form action="{{ route('packets.excel') }}" method="post" id="excel_form">
                                        @csrf
                                    </form>

                                    <button class="btn btn-success" form="excel_form">
                                        <i class="fa fa-file-excel me-2"></i>
                                        دریافت اکسل
                                    </button>

                                    @can('packets-create')
                                        <a href="{{ route('packets.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i>
                                            ایجاد بسته ارسالی
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            <form action="{{ route('packets.search') }}" method="get" id="search_form"></form>
                            <div class="row mb-3">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="invoice_id" form="search_form" class="form-control"
                                            data-toggle="select2">
                                        <option value="all">سفارش (همه)</option>
                                        @foreach($invoices as $invoice)
                                            <option
                                                value="{{ $invoice->id }}" {{ request()->invoice_id == $invoice->id ? 'selected' : '' }}>{{ $invoice->id.' - '.$invoice->customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="packet_status" form="search_form" class="form-control"
                                            data-toggle="select2">
                                        <option value="all">وضعیت بسته (همه)</option>
                                        @foreach(\App\Models\Packet::PACKET_STATUS as $key => $value)
                                            <option
                                                value="{{ $key }}" {{ request()->packet_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="invoice_status" form="search_form" class="form-control"
                                            data-toggle="select2">
                                        <option value="all">وضعیت فاکتور (همه)</option>
                                        @foreach(\App\Models\Packet::INVOICE_STATUS as $key => $value)
                                            <option
                                                value="{{ $key }}" {{ request()->invoice_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>گیرنده</th>
                                        <th>آدرس</th>
                                        <th>شماره سفارش</th>
                                        <th>نوع ارسال</th>
                                        <th>وضعیت بسته</th>
                                        <th>وضعیت فاکتور</th>
                                        <th>زمان ارسال</th>
                                        <th>تاریخ ایجاد</th>
                                        <th>تایید تحویل</th>
                                        <th>چاپ مشخصات پستی</th>
                                        <th>وضعیت مرسوله</th>
                                        @can('packets-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('packets-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($packets as $key => $packet)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $packet->receiver }}</td>
                                            <td>{{ $packet->address }}</td>
                                            <td>
                                                <strong><u><a
                                                            href="{{ route('invoices.show', [$packet->invoice_id, 'type' => 'pishfactor']) }}"
                                                            class="text-primary"
                                                            target="_blank">{{ $packet->invoice_id }}</a></u></strong>
                                            </td>
                                            <td>{{ \App\Models\Packet::SENT_TYPE[$packet->sent_type] }}</td>
                                            <td>
                                                @if($packet->packet_status == 'delivered')
                                                    <span
                                                        class="badge bg-success">{{ \App\Models\Packet::PACKET_STATUS[$packet->packet_status] }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-warning">{{ \App\Models\Packet::PACKET_STATUS[$packet->packet_status] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($packet->invoice_status == 'delivered')
                                                    <span
                                                        class="badge bg-success">{{ \App\Models\Packet::INVOICE_STATUS[$packet->invoice_status] }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-warning">{{ \App\Models\Packet::INVOICE_STATUS[$packet->invoice_status] }}</span>
                                                @endif
                                            </td>
                                            <td>{{ verta($packet->sent_time)->format('Y/m/d') }}</td>
                                            <td>{{ verta($packet->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <span
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="{{ !is_null($packet->delivery_at) ?verta($packet->delivery_at)->format('H:i - Y/m/d'):'' }}">
                                                <button
                                                    class="btn {{!is_null($packet->delivery_at) ? 'btn-success':'btn-warning'}} btn-floating modal-data-send"
                                                    href="#delivery-modal"
                                                    data-bs-toggle="modal" data-packet_id="{{ $packet->id }}"
                                                {{!is_null($packet->delivery_at) ? 'disabled':''}}  >
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                    </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('packet.download', $packet) }}"
                                                   class="btn btn-info btn-floating" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-floating btn_post_status"
                                                        type="button"
                                                        data-bs-toggle="modal" data-bs-target="#postStatusModal"
                                                        data-code="{{ $packet->send_tracking_code }}"{{ (!is_null($packet->delivery_at) || $packet->send_tracking_code == null || $packet->sent_type != 'post') ? 'disabled' : '' }}
                                                >
                                                    <i class="fa fa-truck"></i>
                                                </button>
                                            </td>

                                            @can('packets-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating {{!is_null($packet->delivery_at) ? 'disabled':''}} "
                                                       href="{{ route('packets.edit', ['packet' => $packet->id, 'url' => request()->getRequestUri()]) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('packets-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow "
                                                            data-url="{{ route('packets.destroy',$packet->id) }}" {{!is_null($packet->delivery_at) ? 'disabled':''}}>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div
                                class="d-flex justify-content-center">{{ $packets->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delivery-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="description-modal">کد تحویل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt2">
                        <label for="delivery-code">کد</label>
                        <input type="number" id="delivery_code" class="form-control">
                        <span class="text-danger" id="delivery-code-error"></span>
                        <span class="text-success" id="delivery-success"></span>
                    </div>
                    <button class="btn btn-primary mt-2" id="send-delivery-code">ارسال</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // btn post status
        $('.btn_post_status').on('click', function () {
            var code = $(this).data('code');
            $('#postStatusModal .modal-body').html(`<div class="spinner-grow text-primary"></div>`)

            $.ajax({
                url: "{{ route('get-post-status') }}",
                type: 'post',
                data: {
                    code
                },
                success: function (res) {
                    $('#postStatusModal .modal-body').html('')

                    $.each(res.data, function (i, item) {
                        if (item.is_header) {
                            $('#postStatusModal .modal-body').append(`
                                    <table class="table table-bordered table-striped text-center">
                                        <thead class="table-primary">
                                            <tr>
                                                <th colspan="2">${item.title}</th>
                                                <th>موقعیت</th>
                                                <th>ساعت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                `)
                        } else {
                            $('#postStatusModal .modal-body table:last tbody').append(`
                                        <tr>
                                            <td>${item.row}</td>
                                            <td>${item.last_status}</td>
                                            <td>${item.location}</td>
                                            <td>${item.time}</td>
                                        </tr>
                                `)
                        }
                    })
                },
                error: function (err) {
                    $('#postStatusModal .modal-body').html(`<h5 class="text-danger text-center">خطایی رخ داد! از اتصال اینترنت خود و صحت کد رهگیری ثبت شده اطمینان حاصل فرمایید</h5>`)
                }
            })
        })
        // end btn post status
    </script>

    <script>
        $(document).ready(function () {
            var id;
            $('.modal-data-send').click(function () {
                id = $(this).data('packet_id');
            });
            $('#send-delivery-code').click(function () {
                var $button = $(this);
                $button.prop('disabled', true).html('درحال پردازش...');
                var code = $('#delivery_code').val();
                $.ajax({
                    url: '{{route('check.delivery.code')}}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        code: code,
                        id: id,
                    },
                    success: function (response) {
                        $('#delivery-code-error').html('');
                        $('#delivery-success').html(response);
                        setTimeout(function () {
                            location.reload();
                        }, 1300)
                    },
                    error: function (xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        if (errors) {
                            $.each(errors, function (key, value) {
                                errorMessage += value.join('<br>');
                            });
                        } else {
                            errorMessage = xhr.responseJSON;
                        }
                        $('#delivery-code-error').html(errorMessage);
                    },
                    complete: function () {
                        $button.prop('disabled', false).html('ارسال');
                    }
                });
            });
        });
    </script>
@endsection
