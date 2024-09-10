<div class="card">
    <div class="card-body">
        <div class="card-title">{{ $title }}</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>IP</th>
                    <th>کاربر</th>
                    <th>فعالیت</th>
                    <th>تاریخ</th>
                </tr>
                </thead>
                <tbody>
                @foreach($activities as $key => $activity)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $activity->ip }}</td>
                        <td>{{ $activity->user->fullName() }}</td>
                        <td>{{ \App\Models\ActivityLog::ACTIVITY_NAMES[$activity->activity_name] }}</td>
                        <td>{{ verta($activity->created_at)->format('H:i - Y/m/d') }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-center">{{ $activities->appends(request()->all())->links() }}</div>
    </div>
</div>
