@extends('panel.layouts.master')
@section('title', 'وظایف')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">وظایف</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('tasks-create')
                                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد وظیفه
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
                                        @if(!auth()->user()->isCEO() || !auth()->user()->isItManager() )
                                            <th>وضعیت</th>
                                        @endif

                                        <th>ایجاد کننده</th>
                                        <th>زمان شروع</th>
                                        <th>زمان پایان</th>
                                        <th>مشاهده</th>
                                        @can('tasks-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('tasks-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--                                    @dd($tasks['data'])--}}
                                    @foreach($tasks['data'] as $key => $task)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $task['title'] }}</td>
                                            @if(!auth()->user()->isCEO() || !auth()->user()->isItManager() )
                                                <td>@if($task['status'] == 'doing')
                                                        <span class="badge bg-warning">در انتظار انجام</span>
                                                    @else
                                                        <span class="badge bg-success">انجام شد</span>
                                                    @endif</td>
                                            @endif
                                            <td> {{ $task['creator_id'] == auth()->id() ? 'شما' : $task['creator'] }}</td>
                                            <td>{{ verta($task['start_at'])->format('Y/m/d') }}</td>
                                            <td>{{verta( $task['expire_at'])->format('Y/m/d') }}</td>
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('tasks.show', $task['id']) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            @can('tasks-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating {{ $task['creator_id'] != auth()->id() ? 'disabled' : '' }}"
                                                       href="{{ route('tasks.edit', $task['id']) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('tasks-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('tasks.destroy',$task['id']) }}"
                                                            data-id="{{ $task['id'] }}" {{ $task['creator_id'] != auth()->id() ? 'disabled' : '' }}>
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
                            {{--                            <div class="d-flex justify-content-center">{{ $tasks->appends(request()->all())->links() }}</div>--}}

                            @if(count($tasks['data']) && count($tasks['data']) >= 10)
                                <div class="d-flex justify-content-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <li class="page-item {{ $tasks['pagination']['prev_page_url'] ? '' : 'disabled' }}">
                                                <a class="page-link"
                                                   href="{{ $tasks['pagination']['prev_page_url'] ? '/panel/tasks?url=' . $tasks['pagination']['prev_page_url'] : '#' }}">قبلی</a>
                                            </li>
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">صفحه {{ $tasks['pagination']['current_page'] }} از {{ $tasks['pagination']['last_page'] }}</span>
                                            </li>
                                            <li class="page-item {{ $tasks['pagination']['next_page_url'] ? '' : 'disabled' }}">
                                                <a class="page-link"
                                                   href="{{ $tasks['pagination']['next_page_url'] ? '/panel/tasks?url=' . $tasks['pagination']['next_page_url'] : '#' }}">بعدی</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



