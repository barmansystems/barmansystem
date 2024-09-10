
<div class="card mt-2 mb-1 shadow-none border text-start" style="width: fit-content">
    <div class="p-2">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar-sm">
                <span class="avatar-title bg-primary rounded">
                    {{ $file->type }}
                </span>
                </div>
            </div>
            <div class="col ps-0" dir="ltr">
                <a href="javascript:void(0);" class="text-muted fw-medium">{{ $file->name }}</a>
                <p class="mb-0">{{ formatBytes($file->size) }}</p>
            </div>
            <div class="col-auto">
                <!-- Button -->
                <a href="{{ $file->path }}" download="{{ $file->path }}"
                   class="btn btn-link btn-lg text-muted">
                    <i class="ri-download-fill"></i>
                </a>
            </div>
        </div>
    </div>
</div>

