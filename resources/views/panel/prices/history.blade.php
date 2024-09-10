@extends('panel.layouts.master')
@section('title', 'تاریخچه قیمت ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">تاریخچه قیمت ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" id="search_form">
                                @csrf
                            </form>
                            <div class="row mb-3">
                                <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12">
                                    <input type="text" name="title" class="form-control" placeholder="عنوان محصول"
                                           value="{{ request()->title ?? null }}" form="search_form">
                                </div>
                                <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>عنوان محصول</th>
                                        <th>فیلد قیمت</th>
                                        <th>قیمت قبلی</th>
                                        <th>قیمت تغییر داده شده</th>
                                        <th>تاریخ ویرایش</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pricesHistory as $key => $item)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $item->product->title }}</td>
                                            <td>{{ \App\Models\PriceHistory::FIELDS[$item->price_field] }}</td>
                                            <td>{{ number_format($item->price_amount_from / 10) }} تومان</td>
                                            <td>{{ number_format($item->price_amount_to / 10) }} تومان</td>
                                            <td>{{ verta($item->created_at)->format('Y/m/d') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">{{ $pricesHistory->appends(request()->all())->links() }}</div>
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
            $('.btn_show').on('click', function () {
                $('#itemsModal .modal-body ol').html('')

                let id = $(this).data('id')
                $.ajax({
                    url: '/panel/get-report-items/' + id,
                    type: 'get',
                    success: function (res) {
                        $.each(res.data, function (i, item) {
                            $('#itemsModal .modal-body ol').append(`<li>${item}</li>`);
                        })
                    }
                })
            })
        })
    </script>
@endsection

