@extends('panel.layouts.master')
@section('title', 'مدیریت فایل')
@section('styles')
    <style>
        .form-check-input {
            height: 1.5rem;
            width: 1.5rem;
        }

        .first_td {
            width: 50px;
        }

        .mdi {
            font-size: large;
        }
    </style>
@endsection
@section('content')
    {{--  Uploading Modal  --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="UploadingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">آپلود فایل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    {{--  end Uploading Modal  --}}

    {{--  Warning Modal  --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="WarningModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جایگزین فایل های همنام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body text-center text-danger">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn_action" data-action="skip">
                        <i class="fa fa-times"></i>
                        رد کردن
                    </button>
                    <button class="btn btn-primary btn_action" data-action="override">
                        <i class="fa fa-check"></i>
                        جایگزین کن
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--  end Warning Modal  --}}

    {{--  Create Folder Modal  --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="CreateFolderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جایگزین فایل های همنام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body text-center text-danger">
                    <input type="text" name="folder_name" id="folder_name" class="form-control"
                           placeholder="نام پوشه جدید">
                    <span class="text-center text-danger" id="error_message"></span>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn_create_folder">ایجاد</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end Create Folder Modal  --}}

    {{--  Delete Modal  --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="DeleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تایید حذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center text-danger">آیا از حذف فایل یا پوشه های انتخابی اطمینان دارید؟</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    <button class="btn btn-danger" id="btn_delete">حذف</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end Delete Modal  --}}

    {{--  Edit Name Modal  --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="EditNameModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش عنوان</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body text-center text-danger">
                    <input type="text" name="file_name" id="file_name" class="form-control" placeholder="عنوان جدید">
                    <input type="hidden" name="file_type" id="file_type">
                    <span class="text-center text-danger" id="file_edit_error"></span>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn_edit_name">ویرایش</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end Edit Name Modal  --}}

    {{--  Move Modal  --}}
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="MoveModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">انتقال فایل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body text-center text-danger">
                    {{--                    <input type="text" name="file_name" id="file_name" class="form-control" placeholder="عنوان جدید">--}}
                    {{--                    <input type="hidden" name="file_type" id="file_type">--}}
                    {{--                    <span class="text-center text-danger" id="file_edit_error"></span>--}}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn_move">انتقال</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end Move Modal  --}}

    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مدیریت فایل</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            @php $moving = session()->get('moving') @endphp
                            <div
                                class="btn btn-primary waves-effect waves-light default_sec {{ $moving ? 'd-none' : '' }} mb-3"
                                data-bs-toggle="modal" data-bs-target="#CreateFolderModal">
                                <span><i class="mdi mdi-folder-plus me-1"></i> ایجاد پوشه</span>
                            </div>
                            <label for="new_files">
                                <div
                                    class="btn btn-primary waves-effect waves-light default_sec {{ $moving ? 'd-none' : '' }} mb-3"
                                    id="btn_upload">
                                    <span><i class="mdi mdi-cloud-upload me-1"></i> بارگذاری فایل</span>
                                    <input type="file" name="new_files[]" style="display: none" id="new_files" multiple>
                                </div>
                            </label>
                            <div
                                class="btn btn-primary waves-effect waves-light default_sec {{ $moving ? 'd-none' : '' }} disabled mb-3"
                                id="btn_move_show">
                                <span><i class="mdi mdi-cursor-move me-1" style="pointer-events: none"></i>انتقال</span>
                            </div>
                            <div
                                class="btn btn-primary waves-effect waves-light {{ $moving ? '' : 'd-none' }} moving_sec mb-3"
                                id="btn_move">
                                <span><i class="mdi mdi-cursor-move me-1"></i>انتقال به اینجا</span>
                            </div>
                            <div
                                class="btn btn-danger waves-effect waves-light {{ $moving ? '' : 'd-none' }} moving_sec mb-3"
                                id="btn_cancel_move">
                                <span><i class="mdi mdi-cursor-move me-1"></i>لغو انتقال</span>
                            </div>
                            <div
                                class="btn btn-primary waves-effect waves-light default_sec {{ $moving ? 'd-none' : '' }} disabled mb-3"
                                id="btn_edit_show">
                                <span><i class="mdi mdi-pencil me-1"></i> ویرایش عنوان</span>
                            </div>
                            <div
                                class="btn btn-primary waves-effect waves-light default_sec {{ $moving ? 'd-none' : '' }} disabled mb-3"
                                data-bs-toggle="modal" data-bs-target="#DeleteModal" id="btn_delete_show">
                                <span><i class="mdi mdi-delete me-1"></i> حذف</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-centered text-center table-nowrap mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">
                                            <div class="form-check form-checkbox-primary">
                                                <input id="checkAll" type="checkbox" class="form-check-input">
                                            </div>
                                        </th>
                                        <th scope="col" style="text-align: right">عنوان</th>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isCEO() ||auth()->user()->isItManager())
                                            <th scope="col">توسط</th>
                                        @endif
                                        <th scope="col">نوع</th>
                                        <th scope="col">حجم</th>
                                        <th scope="col">تاریخ ویرایش</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($sub_folder_id)
                                        <tr>
                                            <td></td>
                                            <td style="text-align: right">
                                                @php $sub_folder = \App\Models\File::whereId($sub_folder_id)->first() @endphp
                                                <img src="/assets/images/file-icons/folder.svg" height="30" alt="icon"
                                                     class="me-2">
                                                <a href="{{ $sub_folder->parent_id ? url()->current().'?sub_folder_id='.$sub_folder->parent_id : url()->current() }}"
                                                   class="text-dark">
                                                    <i class="mdi mdi-subdirectory-arrow-right" style="font-size: large"
                                                       title="برگشت"></i>
                                                </a>
                                            </td>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isCEO() ||auth()->user()->isItManager())
                                                <td>{{ $sub_folder->user->fullName() }}</td>
                                            @endif
                                            <td>--</td>
                                            <td>--</td>
                                            <td>--</td>
                                        </tr>
                                    @endif
                                    @foreach($files as $key => $file)
                                        @if($file->is_folder)
                                            <tr data-is_folder="true">
                                                <td>
                                                    <div class="form-check form-checkbox-primary first_td">
                                                        {{ ++$key }}
                                                        <input type="checkbox" name="files[]"
                                                               class="form-check-input checkFile"
                                                               value="{{ $file->id }}">
                                                    </div>
                                                </td>
                                                <td style="text-align: right">
                                                    <a href="{{ route('file-manager.index', ['sub_folder_id' => $file->id]) }}"
                                                       class="text-dark">
                                                        <img src="/assets/images/file-icons/folder.svg" height="30"
                                                             alt="icon" class="me-2">
                                                        {{ $file->name }}</a>
                                                </td>
                                                @if(auth()->user()->isAdmin() || auth()->user()->isCEO() ||auth()->user()->isItManager())
                                                    <td>{{ $file->user->fullName() }}</td>
                                                @endif
                                                <td>--</td>
                                                <td>--</td>
                                                <td class="font-13"
                                                    dir="ltr">{{ verta($file->updated_at)->format('Y/m/d - H:i') }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <div class="form-check form-checkbox-primary first_td">
                                                        {{ ++$key }}
                                                        <input type="checkbox" name="files[]"
                                                               class="form-check-input checkFile"
                                                               value="{{ $file->id }}">
                                                    </div>
                                                </td>
                                                <td style="text-align: right">
                                                    <a href="{{ asset($file->path) }}" class="text-dark"
                                                       target="_blank">
                                                        <img src="/assets/images/file-icons/{{ $file->type }}.svg"
                                                             height="30" alt="icon" class="me-2">
                                                        {{ $file->name }}</a>
                                                </td>
                                                @if(auth()->user()->isAdmin() || auth()->user()->isCEO() ||auth()->user()->isItManager())
                                                    <td>{{  $file->user->fullName() }}</td>
                                                @endif
                                                <td>{{ strtoupper($file->type) }}</td>
                                                <td dir="ltr">{{ formatBytes($file->size) }}</td>
                                                <td class="font-13"
                                                    dir="ltr">{{ verta($file->updated_at)->format('Y/m/d - H:i') }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
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
        var sub_folder_id = "{{ $sub_folder_id }}";

        $(document).ready(function () {
            $(document).on('change', '#checkAll', function () {
                $('.checkFile').prop('checked', this.checked);
                toggleDeleteBtn();
                toggleEditBtn();
                toggleMoveBtn();
            })

            var fileData = new FormData();
            var duplicated_files_names = [];
            var moving = "{{ session()->get('moving') }}";

            $(document).on('change', '#new_files', function () {
                var files = this.files;
                let error = false;
                let message;
                let warning = false;
                let warning_message;
                let duplicated_files_count = 0;

                var files_name = [];

                $.each($('table tbody td:nth-child(2)'), function (i, item) {
                    if (!$(item).parent().data('is_folder')) {
                        files_name.push(item.innerText.trim())
                    }
                })

                $.each(files, function (i, file) {
                    if (jQuery.inArray(file.name, files_name) !== -1) {
                        duplicated_files_count++;
                        duplicated_files_names.push(file.name);
                        warning = true;
                        fileData.append(i, files[i]);
                    } else if (file.size > 5000000000000000) {
                        error = true;
                        message = `فایل ${file.name} حجمش بیشتر از 5 MB است`;
                        return false;
                    } else {
                        fileData.append(i, files[i]);
                        error = false;
                        message = '';
                    }
                })

                fileData.append('sub_folder_id', sub_folder_id);

                if (error) {
                    $('#UploadingModal').modal('show');
                    $('#UploadingModal .modal-body').html(`<p class="text-center text-danger">${message}</p>`)
                } else {
                    if (warning) {
                        warning_message = `تعداد (${duplicated_files_count}) فایل تکراری وجود دارد آیا می خواهید جایگزین شوند؟`;
                        $('#WarningModal').modal('show');
                        $('#WarningModal .modal-body').text(warning_message)
                    } else {
                        $('#UploadingModal').modal('show');

                        $.ajax({
                            xhr: function () {
                                var xhr = new window.XMLHttpRequest();

                                xhr.upload.addEventListener("progress", function (evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        percentComplete = parseInt(percentComplete * 100);
                                        $('#UploadingModal .modal-body').html(`
                                            <div class="progress" role="progressbar" aria-label="Default striped example" aria-valuenow="${percentComplete}" aria-valuemin="0" aria-valuemax="100">
                                              <div class="progress-bar progress-bar-striped" style="width: ${percentComplete}%">${percentComplete}%</div>
                                            </div>
                                        `)
                                        // if (percentComplete === 100) {
                                        //
                                        // } else {
                                        //
                                        // }
                                    }
                                }, false);

                                return xhr;
                            },
                            url: '/panel/upload-file',
                            type: "POST",
                            data: fileData,
                            contentType: false, // Not to set any content header
                            processData: false, // Not to process data
                            success: function (res) {
                                // console.log(res);
                                if (sub_folder_id) {
                                    window.location.replace(`{{ url()->current() }}?sub_folder_id=${sub_folder_id}`)
                                } else {
                                    window.location.replace(`{{ url()->current() }}`)
                                }
                            }
                        });
                    }
                }

            })

            $(document).on('click', '.btn_action', function () {
                $('#WarningModal').modal('hide');
                $('.modal-backdrop').remove();
                $('#UploadingModal').modal('show');

                let action = $(this).data('action');

                fileData.append('duplicated_files_action', action);
                fileData.append('duplicated_files_names', duplicated_files_names);
                fileData.append('sub_folder_id', sub_folder_id);

                $.ajax({
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $('#UploadingModal .modal-body').html(`
                                            <div class="progress" role="progressbar" aria-label="Default striped example" aria-valuenow="${percentComplete}" aria-valuemin="0" aria-valuemax="100">
                                              <div class="progress-bar progress-bar-striped" style="width: ${percentComplete}%">${percentComplete}%</div>
                                            </div>
                                        `)
                                // if (percentComplete === 100) {
                                //
                                // } else {
                                //
                                // }
                            }
                        }, false);

                        return xhr;
                    },
                    url: '/panel/upload-file',
                    type: "POST",
                    data: fileData,
                    contentType: false, // Not to set any content header
                    processData: false, // Not to process data
                    success: function (res) {
                        duplicated_files_names = [];
                        if (sub_folder_id) {
                            window.location.replace(`{{ url()->current() }}?sub_folder_id=${sub_folder_id}`)
                        } else {
                            window.location.replace(`{{ url()->current() }}`)
                        }
                    }
                });
            })

            $(document).on('click', '#btn_create_folder', function () {
                $(this).attr('disabled', 'disabled').text('درحال ایجاد...');

                if ($('#folder_name').val().trim() === '') {
                    $('#error_message').text('فیلد نام پوشه الزامی است');
                    return false;
                }

                let folder_name = $('#folder_name').val();

                $.ajax({
                    url: '/panel/create-folder',
                    type: "POST",
                    data: {
                        sub_folder_id,
                        folder_name
                    },
                    success: function (res) {
                        if (res.error) {
                            $('#error_message').text(res.message);
                        } else {
                            $('.card-body').html($(res).find('.card-body').html());
                            $('#CreateFolderModal').modal('hide');
                            $('.modal-backdrop').remove();
                        }
                        $('#btn_create_folder').removeAttr('disabled').text('ایجاد');
                    }
                });
            })

            $(document).on('click', '#btn_delete', function () {
                let checked_files = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).get();

                $.ajax({
                    url: '/panel/file-manager-delete',
                    type: "POST",
                    data: {
                        checked_files
                    },
                    success: function (res) {
                        $('.card-body').html($(res).find('.card-body').html());
                        $('#DeleteModal').modal('hide');
                        $('.modal-backdrop').remove();
                    }
                });
            })

            $(document).on('click', '#btn_edit_show', function () {
                let file_id = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).first()[0];

                $.ajax({
                    url: '/panel/get-file-name',
                    type: "GET",
                    data: {
                        file_id
                    },
                    success: function (res) {
                        $('#EditNameModal').modal('show');
                        $('#EditNameModal .modal-body #file_name').val(res.name)
                        $('#EditNameModal .modal-body #file_type').val(res.type)
                    }
                });
            })

            $(document).on('click', '#btn_edit_name', function () {
                let file_id = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).first()[0];

                let new_name = $('#file_name').val();
                let file_type = $('#file_type').val();

                let self = $(this);

                self.addClass('disabled').text('در حال ویرایش');

                $.ajax({
                    url: '/panel/edit-file-name',
                    type: "POST",
                    data: {
                        file_id,
                        new_name,
                        file_type,
                        sub_folder_id
                    },
                    success: function (res) {
                        if (!res.error) {
                            $('.card-body').html($(res).find('.card-body').html());
                            $('#EditNameModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('#file_edit_error').text('');
                        } else {
                            $('#file_edit_error').text(res.message);
                        }

                        self.removeClass('disabled').text('ویرایش');
                    }
                });
            })

            $(document).on('click', '#btn_move_show', function () {
                $(this).addClass('disabled', 'disabled');

                let checked_files = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).get();

                $.ajax({
                    url: '/panel/moving',
                    type: "POST",
                    data: {
                        checked_files
                    },
                    success: function (res) {
                        toggleSections();
                        $(this).removeClass('disabled');
                    }
                });
            })

            $(document).on('click', '#btn_cancel_move', function () {
                $.ajax({
                    url: '/panel/cancel-moving',
                    type: "POST",
                    success: function (res) {
                        // console.log(res)
                        toggleSections();
                    }
                });
            })

            $(document).on('click', '#btn_move', function () {
                $.ajax({
                    url: '/panel/move-files',
                    type: "POST",
                    data: {
                        sub_folder_id
                    },
                    success: function (res) {
                        $('.card-body').html($(res).find('.card-body').html());
                        toggleSections();
                    }
                });
            })

            $(document).on('change', 'input[name="files[]"]', function () {
                toggleDeleteBtn();
                toggleEditBtn();
                toggleMoveBtn();
            })

            function toggleDeleteBtn() {
                let checked_files = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).get();

                if (checked_files.length != 0) {
                    $('#btn_delete_show').removeClass('disabled');
                } else {
                    $('#btn_delete_show').addClass('disabled');
                }
            }

            function toggleEditBtn() {
                let checked_files = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).get();

                if (checked_files.length == 0 || checked_files.length > 1) {
                    $('#btn_edit_show').addClass('disabled');
                } else {
                    $('#btn_edit_show').removeClass('disabled');
                }
            }

            function toggleMoveBtn() {
                let checked_files = $('input[name="files[]"]:checked').map(function () {
                    return $(this).val();
                }).get();

                if (checked_files.length > 0) {
                    $('#btn_move_show').removeClass('disabled');
                } else {
                    $('#btn_move_show').addClass('disabled');
                }
            }

            function toggleSections() {
                if (moving == false) {
                    moving = true;
                    $('.default_sec').addClass('d-none');
                    $('.moving_sec').removeClass('d-none');
                } else {
                    moving = false;
                    $('.moving_sec').addClass('d-none');
                    $('.default_sec').removeClass('d-none');
                }
            }
        })
    </script>
@endsection
