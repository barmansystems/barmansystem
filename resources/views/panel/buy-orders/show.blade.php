@extends('panel.layouts.master')
@section('title', 'مشاهده سفارش خرید')
@section('styles')
    <style>
        table tbody tr td input {
            text-align: center;
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
                        <h4 class="page-title">مشاهده سفارش خرید</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end mb-4">
                                <div>
                                    @can('ceo')
                                        @if($buyOrder->status == 'bought')
                                            <form action="{{ route('buy-orders.changeStatus', $buyOrder->id) }}" method="post">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-success">{{ \App\Models\BuyOrder::STATUS['bought'] }}</button>
                                            </form>
                                        @else
                                            <form action="{{ route('buy-orders.changeStatus', $buyOrder->id) }}" method="post">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-warning">{{ \App\Models\BuyOrder::STATUS['order'] }}</button>
                                            </form>
                                        @endif
                                    @else
                                        @if($buyOrder->status == 'bought')
                                            <span class="badge badge-success">{{ \App\Models\BuyOrder::STATUS['bought'] }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ \App\Models\BuyOrder::STATUS['order'] }}</span>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                            <label class="form-label" for="customer_id">مشتری</label>
                                            <select name="customer_id" id="customer_id" class="form-control" data-toggle="select2" disabled>
                                                <option value="{{ $buyOrder->customer->id }}" selected>{{ $buyOrder->customer->code.' - '.$buyOrder->customer->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead class="table-primary">
                                        <tr>
                                            <th>عنوان کالا</th>
                                            <th>تعداد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(json_decode($buyOrder->items) as $item)
                                            <tr>
                                                <td>{{ $item->product }}</td>
                                                <td>{{ number_format($item->count) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr></tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label" for="description">توضیحات</label>
                                    <textarea name="description" id="description" class="form-control" rows="5"
                                              disabled>{{ $buyOrder->description }}</textarea>
                                </div>
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
            // add item
            $(document).on('click', '#btn_add', function () {
                $('table tbody').append(`
                    <tr>
                        <td><input type="text" class="form-control" name="products[]" required></td>
                        <td><input type="number" class="form-control" name="counts[]" min="1" value="1" required></td>
                        <td><button type="button" class="btn btn-danger btn-floating btn_remove"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `)
            })

            // remove item
            $(document).on('click', '.btn_remove', function () {
                $(this).parent().parent().remove()
            })
        })
    </script>
@endsection
