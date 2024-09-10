@extends('panel.layouts.master')
@section('title', 'نقش ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">نقش ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('roles-create')
                                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد نقش
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نقش</th>
                                        <th>تاریخ ایجاد</th>
                                        @can('roles-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('roles-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $key => $role)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $role->label }}</td>
                                            <td>{{ verta($role->created_at)->format('H:i - Y/m/d') }}</td>
                                            @can('roles-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating {{ $role->name == 'admin' ? 'disabled' : '' }}"
                                                       href="{{ route('roles.edit', $role->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('roles-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('roles.destroy',$role->id) }}"
                                                            data-id="{{ $role->id }}" {{ $role->name == 'admin' ? 'disabled' : '' }}>
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
                            <div class="d-flex justify-content-center">{{ $roles->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



