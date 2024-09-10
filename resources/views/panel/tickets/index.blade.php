@extends('panel.layouts.master')
@section('title', 'تیکت ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">تیکت ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('tickets-create')
                                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ثبت تیکت
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>فرستنده</th>
                                        <th>گیرنده</th>
                                        <th>شرکت</th>
                                        <th>عنوان تیکت</th>
                                        <th>شماره تیکت</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ایجاد</th>
                                        @can('tickets-create')
                                            <th>مشاهده</th>
                                        @endcan
                                        @can('tickets-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ticketsData['data'] as $key =>  $ticket)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $ticket['sender_name'] }}</td>
                                            <td>{{ $ticket['receiver_name'] }}</td>
                                            <td>{{ getCompany($ticket["company_sender"]) }}</td>
                                            <td>{{ $ticket['title']}}</td>
                                            <td>{{ $ticket['code'] }}</td>
                                            <td>
                                                @if($ticket['status'] == 'closed')
                                                    <span class="badge bg-success">بسته شده</span>
                                                @else
                                                    <span class="badge bg-warning">درحال بررسی</span>
                                                @endif
                                            </td>
                                            <td>{{ verta($ticket['created_at'])->format('H:i - Y/m/d') }}</td>
                                            @can('tickets-create')
                                                <td>
                                                    <a class="btn btn-info btn-floating"
                                                       href="{{ route('tickets.edit', $ticket['id']) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('tickets-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('tickets.destroy',$ticket['id']) }}"
                                                            data-id="{{ $ticket['id'] }}">
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
                            @if(count($ticketsData['data']) && count($ticketsData['data']) >= 10)
                                <div class="d-flex justify-content-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <li class="page-item {{ $ticketsData['pagination']['prev_page_url'] ? '' : 'disabled' }}">
                                                <a class="page-link"
                                                   href="{{ $ticketsData['pagination']['prev_page_url'] ? '/panel/tickets?url=' . $ticketsData['pagination']['prev_page_url'] : '#' }}">قبلی</a>
                                            </li>
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">صفحه {{ $ticketsData['pagination']['current_page'] }} از {{ $ticketsData['pagination']['last_page'] }}</span>
                                            </li>
                                            <li class="page-item {{ $ticketsData['pagination']['next_page_url'] ? '' : 'disabled' }}">
                                                <a class="page-link"
                                                   href="{{ $ticketsData['pagination']['next_page_url'] ? '/panel/tickets?url=' . $ticketsData['pagination']['next_page_url'] : '#' }}">بعدی</a>
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


