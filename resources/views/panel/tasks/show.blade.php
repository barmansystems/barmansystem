@extends('panel.layouts.master')
@section('title', 'مشاهده وظیفه')
@section('content')
    {{--    $task['company_auth_id']--}}
    {{--$task['auth_user']['id'])--}}
    @php
        $isCreator = $task['auth_user']['id']==$task['creator']['id'];
        if (!$isCreator){

            $task_done = $task['task_user']['status'] == 'done' ? true : false;

        }
    @endphp
    {{--  description Modal  --}}
    <div class="modal fade" id="descriptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">توضیحات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end description Modal  --}}


    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مشاهده وظیفه "{{ $task['task']['title'] }}"</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between align-items-center">
                                @if(!$isCreator)
                                    <div class="form-check {{ $isCreator ? 'd-none' : '' }}">
                                        <input class="form-check-input" type="checkbox"
                                               id="btn_task" {{ $task_done ? 'checked' : '' }}>
                                        <label class="form-check-label" for="btn_task" id="btn_task_lbl">
                                            {{ $task_done ? 'انجام شده' : 'انجام نشده' }}
                                        </label>
                                    </div>
                                @endif
                            </div>
                            @if(!$isCreator)
                                <div class="row">
                                    <div class="col">
                                        <strong>توضیحات</strong>
                                        <div>{!! nl2br(e($task['task']['description'])) !!}</div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <textarea class="form-control"
                                                      placeholder="درصورت نیاز توضیحات را وارد کنید..."
                                                      id="description">{{ $task['task_user']['description'] }}</textarea>
                                        </div>
                                        <button class="btn btn-primary mt-2" id="btn_add_desc">ثبت</button>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table class="table table-striped text-center table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>نام و نام خانوادگی</th>
                                                    <th>وضعیت</th>
                                                    <th>زمان انجام</th>
                                                    <th>توضیحات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($task['task']['users'] as $user)
                                                    <tr>

                                                        <td>{{ $user['name'].' '.$user['family'] }}</td>
                                                        <td>
                                                            {{--                                                            @dd($user['pivot']['status'])--}}
                                                            @if($user['pivot']['status'] == 'done')
                                                                <span
                                                                    class="badge bg-success">{{ \App\Models\Task::STATUS[$user['pivot']['status'] ] }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-warning">{{ \App\Models\Task::STATUS[$user['pivot']['status']] }}</span>
                                                            @endif
                                                        </td>

                                                        <td>{{ $user['pivot']['done_at'] ? verta($user['pivot']['done_at'])->format('H:i - Y/m/d') : '---' }}</td>
                                                        <td>
                                                            <button class="btn btn-primary btn-floating btn_show_desc"
                                                                    data-id="{{ $user['pivot']['id'] }}" {{ $user['pivot']['description'] ? '' : 'disabled' }}>
                                                                <i class="fa fa-comment">
                                                                    {{--                                                                    <span class="badge bg-info">{{ $user->pivot->description ? 1 : '' }}</span>--}}
                                                                </i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var task_id = "{{ $task['task']['id'] }}";

        $(document).ready(function () {
            // btn task status
            $(document).on('change', '#btn_task', function () {
                $(this).attr('disabled', 'disabled')

                $.ajax({
                    url: `/panel/task/change-status`,
                    type: 'post',
                    data: {
                        task_id
                    },
                    success: function (res) {
                        $('#btn_task_lbl').text(JSON.parse(res).message)
                        $('#btn_task').removeAttr('disabled')
                    }
                })
            })
            // end btn task status

            // btn add desc
            $(document).on('click', '#btn_add_desc', function () {
                $(this).attr('disabled', 'disabled')
                let description = $('#description').val();
                $.ajax({
                    url: `/panel/task/add-desc`,
                    type: 'post',
                    data: {
                        task_id,
                        description
                    },
                    success: function (res) {
                        $('#btn_add_desc').removeAttr('disabled')
                        Swal.fire({
                            title: 'توضیحات شما ثبت شد',
                            icon: 'success',
                            showConfirmButton: false,
                            toast: true,
                            timer: 2000,
                            timerProgressBar: true,
                            position: 'top-start',
                            customClass: {
                                popup: 'my-toast',
                                icon: 'icon-center',
                                title: 'left-gap',
                                content: 'left-gap',
                            }
                        })
                    }
                })
            })
            // end btn add desc

            // btn get task
            $(document).on('click', '.btn_show_desc', function () {
                let pivot_id = $(this).data('id');

                $.ajax({
                    url: `/panel/task/get-desc`,
                    type: 'post',
                    data: {
                        pivot_id
                    },
                    success: function (res) {
                        $('#descriptionModal').modal('show')
                        $('#descriptionModal .modal-body p').text(JSON.parse(res).data)
                    }
                })
            })
            // end btn get task
        })
    </script>
@endsection
