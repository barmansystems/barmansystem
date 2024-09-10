@extends('panel.layouts.master')
@section('title', 'نامه نگاری')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">نامه ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('indicator')
                                    <a href="{{ route('indicator.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد نامه جدید
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>عنوان</th>
                                        <th>شماره نامه</th>
                                        <th>تاریخ</th>
                                        {{--                                        @can('coupons-edit')--}}
                                        <th>دانلود</th>
                                        <th>ویرایش</th>
{{--                                        <th>حذف</th>--}}
                                        {{--                                        @endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($indicators as $key => $indicator)
                                        <tr>
                                            <td>{{ ++$key }}</td>

                                            <td>{{ $indicator->title }}</td>
                                            <td>{{ $indicator->number?? '---' }}</td>
                                            <td>{{ verta($indicator->created_at)->format('H:i - Y/m/d') }}</td>
                                            {{--                                            @can('coupons-edit')--}}
                                            <td><a class="btn btn-info btn-floating"
                                                   href="{{ route('indicator.download', $indicator->id) }}">
                                                    <i class="fa fa-download"></i>
                                                </a></td>
                                            <td>
                                                <a class="btn btn-warning btn-floating"
                                                   href="{{ route('indicator.edit', $indicator->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            {{--                                            @endcan--}}
                                            {{--                                            @can('coupons-delete')--}}

{{--                                            <td>--}}
{{--                                                <button class="btn btn-danger btn-floating trashRow"--}}
{{--                                                        data-url="{{ route('indicator.destroy',$indicator->id) }}"--}}
{{--                                                        data-id="{{ $indicator->id }}">--}}
{{--                                                    <i class="fa fa-trash"></i>--}}
{{--                                                </button>--}}
{{--                                            </td>--}}
                                            {{--                                            @endcan--}}
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
                                class="d-flex justify-content-center">{{ $indicators->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



