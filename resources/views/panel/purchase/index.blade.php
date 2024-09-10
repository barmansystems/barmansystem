@extends('panel.layouts.master')
@section('title', 'مهندسی خرید')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مهندسی خرید</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>عنوان</th>
                                        <th>انباردار</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ثبت</th>
                                        <th>تعیین وضعیت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($purchases as $key => $purchase)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $purchase->inventory->title  }}</td>
                                            <td>{{ $purchase->user->name .' '. $purchase->user->family }}</td>
                                            <td><span
                                                    class=" badge {{$purchase->status =='pending_purchase'?'bg-warning':'bg-success'}}">{{$purchase->status =='pending_purchase'?'در انتظار خرید':'خریداری شده'}}</span>
                                            </td>

                                            <td>{{ verta($purchase->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                {{--                                                <a href="{{url('/purchases/status/'.$purchase->id)}}"--}}
                                                {{--                                                   class="btn btn-warning">{{$purchase->status =='pending_purchase'?'در انتظار خرید':'خریداری شده'}}</a>--}}
                                                @if($purchase->status =='pending_purchase')
                                                    <a class="btn btn-warning btn-floating"
                                                       href="{{ route('purchase.status', $purchase->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-primary btn-floating {{$purchase->desc??'disabled'}}" data-desc="{{$purchase->desc}}"
                                                            href="#description-modal" data-bs-toggle="modal"><i class="fa fa-comment"></i></button>
                                                @endif

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
                                class="d-flex justify-content-center">{{ $purchases->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="description-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="description-modal">توضیحات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea name="desc" id="desc-status" class="form-control disabled"
                              placeholder="توضیحات (اختیاری)" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-primary[data-bs-toggle="modal"]').on('click', function () {
                var desc = $(this).data('desc');
                console.log(desc);
                $('#desc-status').val(desc)
            });
        });
    </script>
@endsection
