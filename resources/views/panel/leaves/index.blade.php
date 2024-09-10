@extends('panel.layouts.master')
@section('title', 'درخواست مرخصی')
@section('content')
    {{--  leave Modal  --}}
    <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveModalLabel">وضعیت درخواست</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <h5>درخواست شما</h5>
                    <strong>عنوان</strong>
                    <p id="leave_title"></p>
                    <strong>نوع</strong>
                    <p id="leave_type"></p>
                    <strong>تاریخ مرخصی</strong>
                    <p id="leave_date"></p>
                    <strong>توضیحات</strong>
                    <p id="leave_desc"></p>
                    <div id="answer_sec">
                        <hr>
                        <h5>پاسخ درخواست</h5>
                        <p id="leave_answer"></p>
                        <strong>زمان پاسخ</strong>
                        <p id="leave_answer_time"></p>
                        <strong>توضیحات</strong>
                        <p id="leave_answer_text"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end leave Modal  --}}

    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">درخواست مرخصی</h4>
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
                                    @can('leaves-create')
                                        <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus mr-2"></i>
                                            درخواست مرخصی
                                        </a>
                                    @endcan
                                @endcannot
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان</th>
                                            <th>نوع</th>
                                            @can('ceo')
                                                <th>درخواست دهنده</th>
                                            @endcan
                                            <th>تاریخ مرخصی</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ ایجاد</th>
                                            @cannot('ceo')
                                                <th>مشاهده</th>
                                            @endcannot
                                            @can('ceo')
                                                <th>تعیین وضعیت</th>
                                            @endcan
                                            @cannot('ceo')
                                                @can('leaves-delete')
                                                    <th>حذف</th>
                                                @endcan
                                            @endcannot
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($leaves as $key => $leave)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $leave->title }}</td>
                                            <td>{{ \App\Models\Leave::TYPE[$leave->type] }}</td>
                                            @can('ceo')
                                                <td>{{ $leave->user->fullName() }}</td>
                                            @endcan
                                            <td>{{ verta($leave->from_date)->format('Y/m/d') }}</td>
                                            <td>
                                                @if($leave->status == 'accept')
                                                    <span class="badge bg-success">{{ \App\Models\Leave::STATUS[$leave->status] }}</span>
                                                @elseif($leave->status == 'reject')
                                                    <span class="badge bg-danger">{{ \App\Models\Leave::STATUS[$leave->status] }}</span>
                                                @else
                                                    <span class="badge bg-warning">{{ \App\Models\Leave::STATUS[$leave->status] }}</span>
                                                @endif
                                            </td>
                                            <td>{{ verta($leave->created_at)->format('H:i - Y/m/d') }}</td>
                                            @cannot('ceo')
                                                <td>
                                                    <button class="btn btn-info btn-floating btn_show" data-bs-toggle="modal"
                                                            data-bs-target="#leaveModal" data-id="{{ $leave->id }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </td>
                                            @endcannot
                                            @can('ceo')
                                                <td>
                                                    <a href="{{ route('leaves.edit', $leave->id) }}"
                                                       class="btn btn-primary btn-floating">
                                                        <i class="fa fa-pen"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @cannot('ceo')
                                                @can('leaves-delete')
                                                    <td>
                                                        <button class="btn btn-danger btn-floating trashRow"
                                                                data-url="{{ route('leaves.destroy',$leave->id) }}"
                                                                data-id="{{ $leave->id }}" {{ $leave->status != 'pending' ? 'disabled' : '' }}>
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                @endcan
                                            @endcannot
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">{{ $leaves->appends(request()->all())->links() }}</div>
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
            $(document).on('click', '.btn_show', function () {
                let leave_id = $(this).data('id')

                $.ajax({
                    url: '/panel/get-leave-info',
                    type: 'post',
                    data: {
                        leave_id
                    },
                    success: function (res) {
                        $('#leave_title').text(res.data.title)
                        $('#leave_desc').text(res.data.desc)
                        $('#leave_type').text(res.data.typeText)

                        if (res.data.type == 'hourly') {
                            $('#leave_date').text(res.data.to + ' تا ' + res.data.from + ' - ' + res.data.date)
                        } else {
                            $('#leave_date').text(res.data.date)
                        }

                        if (res.data.status != 'pending') {
                            $('#answer_sec').removeClass('d-none')
                            $('#leave_answer').text(`درخواست شما توسط "${res.data.acceptor}" به وضعیت "${res.data.statusText}" تغییر یافت`)
                            $('#leave_answer_text').text(res.data.answer)
                            $('#leave_answer_time').text(res.data.answer_time)
                        } else {
                            $('#answer_sec').addClass('d-none')
                        }
                    }
                })
            })
        })
    </script>
@endsection
