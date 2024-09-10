@extends('panel.layouts.master')
@section('title', 'دستور پرداخت / دریافت')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">دستورات {{$type =='payments'?'پرداخت':'دریافت'}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @cannot('ceo')
                                    @can('order-payment-create')
                                        <a href="{{ route('payments_order.create',['type'=>$type]) }}"
                                           class="btn btn-primary">
                                            <i class="fa fa-plus mr-2"></i>
                                            دستور {{$type =='payments'?'پرداخت':'دریافت'}}
                                        </a>
                                    @endcan
                                @endcannot
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ایجاد کننده</th>
                                        <th>شماره</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
                                        <th>دانلود</th>
                                        <th>توضیحات</th>
                                        @cannot('ceo')
                                            <th>ویرایش</th>
                                            <th>حذف</th>
                                        @endcannot
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments_order as $key => $payment)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $payment->user->name }} {{ $payment->user->family }}</td>
                                            <td>{{ $payment->number}}</td>
                                            <td>
                                                @can('ceo')
                                                    @if($payment->status =='pending')
                                                        <a class="btn btn-warning" data-bs-toggle="modal"
                                                           href="#factorResetModal" data-id="{{$payment->id}}">تعیین
                                                            وضعیت</a>
                                                    @else
                                                        @if($payment->status == 'approved')
                                                            <button class="btn btn-success disabled">تایید شد</button>
                                                        @else
                                                            <button class="btn btn-danger disabled">رد شد</button>
                                                        @endif
                                                    @endif

                                                @else
                                                    @if($payment->status == 'approved')
                                                        <span class="badge bg-success">تایید شد</span>
                                                    @elseif($payment->status == 'pending')
                                                        <span class="badge bg-warning">در انتظار تایید</span>
                                                    @else
                                                        <span class="badge bg-danger">رد شد</span>
                                                    @endif
                                                @endcan
                                            </td>
                                            <td>{{ verta($payment->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('payments_order.download', $payment->id) }}">
                                                    <i class="fa fa-download"></i>
                                                </a></td>
                                            <td>
                                                @if(!is_null($payment->description) || !empty($payment->description))
                                                    <a class="btn btn-primary btn-floating description-modal"
                                                       data-status="{{$payment->status}}"
                                                       data-desc="{{$payment->description}}"
                                                       href="#description-modal" data-bs-toggle="modal">
                                                        <i class="fa fa-comment"></i>
                                                    </a>
                                                @else
                                                    <a class="btn btn-primary btn-floating disabled">
                                                        <i class="fa fa-comment"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            @cannot('ceo')
                                                <td>

                                                        <a class="btn btn-warning btn-floating {{($payment->status =='pending')&&(\Illuminate\Support\Facades\Gate::allows('order-payment-edit',$payment))?'':'disabled'}}"
                                                           href="{{ route('payments_order.edit', ['payments_order'=>$payment->id,'type'=>$type]) }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                </td>
                                                <td>
                                                    <button
                                                            class="btn btn-danger btn-floating trashRow {{($payment->status =='pending')&&(\Illuminate\Support\Facades\Gate::allows('order-payment-delete',$payment))?'':'disabled'}}"
                                                            data-url="{{ route('payments_order.destroy',['payments_order'=>$payment->id,'type'=>$type]) }}"
                                                            data-id="{{ $payment->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endcannot
                                                </td>
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
                                class="d-flex justify-content-center">{{ $payments_order->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="factorResetModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="factorResetModal">تعیین وضعیت</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('payments_order_status')}}" method="post"
                          id="deleteInvoiceAction">
                        @csrf
                        <label for="status" class="form-label">وضعیت</label>
                        <input type="hidden" name="payment_id" id="value-form" value="">
                        <select name="status" class="form-control mb-2">
                            <option value="approved">تایید دستور {{$type =='payments'?'پرداخت':'دریافت'}}</option>
                            <option value="failed">رد دستور {{$type =='payments'?'پرداخت':'دریافت'}}</option>
                        </select>
                        <label for="desc" class="form-label">توضیحات</label>
                        <textarea name="desc" id="desc" class="form-control" placeholder="توضیحات (اختیاری)"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="deleteInvoiceAction">ارسال</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="description-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="description-modal">تعیین وضعیت</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="status" class="form-label">وضعیت</label>
                    <span class="status-section"></span>
                    <textarea name="desc" id="desc-status" class="form-control disabled"
                              placeholder="توضیحات (اختیاری)"></textarea>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-warning[data-bs-toggle="modal"]').on('click', function () {
                var paymentId = $(this).data('id');
                $('#value-form').val(paymentId)
            });
            $('.description-modal').on('click', function (event) {
                var description = $(this).data('desc');
                $('#desc-status').val(description);
                var status = $(this).data('status');
                var statusText = status === 'approved' ? 'تایید' : 'رد';
                var statusSpan = $('.status-section');
                statusSpan.text(statusText);
                if (status === 'approved') {
                    statusSpan.removeClass('badge bg-danger').addClass('badge bg-success');
                } else {
                    statusSpan.removeClass('badge bg-success').addClass('badge bg-danger');
                }
            });
        });
    </script>
@endsection



