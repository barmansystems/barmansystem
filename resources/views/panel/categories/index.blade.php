@extends('panel.layouts.master')
@section('title', 'دسته بندی ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">دسته بندی ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end align-items-center">
                                @can('categories-create')
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد دسته بندی
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>دسته بندی</th>
                                        <th>اسلاگ</th>
                                        <th>تاریخ ایجاد</th>
                                        @can('categories-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('categories-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $key => $category)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ verta($category->created_at)->format('H:i - Y/m/d') }}</td>
                                            @can('categories-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating" href="{{ route('categories.edit', $category->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('categories-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('categories.destroy', $category->id) }}"
                                                            data-id="{{ $category->id }}">
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
                            <div class="d-flex justify-content-center">{{ $categories->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


