<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('file-manager');

        $sub_folder_id = $request->sub_folder_id;


        if (auth()->user()->isAdmin() || auth()->user()->isCEO() || auth()->user()->isItManager()) {
            if ($sub_folder_id) {
                $files = File::where('parent_id', $sub_folder_id)->orderBy('is_folder', 'desc')->latest()->paginate(30);
            } else {
                $files = File::whereNull('parent_id')->orderBy('is_folder', 'desc')->latest()->paginate(30);
            }
        } else {
            if ($sub_folder_id) {
                $files = auth()->user()->files()->where('parent_id', $sub_folder_id)->orderBy('is_folder', 'desc')->latest()->paginate(30);
            } else {
                $files = auth()->user()->files()->whereNull('parent_id')->orderBy('is_folder', 'desc')->latest()->paginate(30);
            }
        }


        return view('panel.file-manager.index', compact(['files', 'sub_folder_id']));
    }

    public function createFolder(Request $request)
    {
        $this->authorize('file-manager');

        if (auth()->user()->files()->where(['name' => $request->folder_name, 'parent_id' => $request->sub_folder_id, 'is_folder' => 1])->first()) {
            return response()->json([
                'error' => true,
                'message' => 'پوشه ای با همین نام موجود است'
            ]);
        }

        $file = File::create([
            'user_id' => auth()->id(),
            'name' => $request->folder_name,
            'parent_id' => $request->sub_folder_id,
            'is_folder' => 1,
        ]);

        // log
        activity_log('create-folder', __METHOD__, [$request->all(), $file]);

        return back();
    }

    public function uploadFile(Request $request)
    {
        $this->authorize('file-manager');

        if ($request->duplicated_files_action) {
            $duplicated_files_names = array_unique(explode(',', $request->duplicated_files_names));

            if ($request->duplicated_files_action == 'override') {
                if (count($duplicated_files_names)) {
                    $files_path = File::whereIn('name', $duplicated_files_names)->pluck('path')->toArray();
                    foreach ($files_path as $path) {
                        unlink(public_path($path));
                    }

                    File::whereIn('name', $duplicated_files_names)->delete();
                }

                foreach ($request->files as $file) {
                    $this->createFile($file, $request->sub_folder_id);
                }
            } else {
                foreach ($request->files as $file) {
                    if (!in_array($file->getClientOriginalName(), $duplicated_files_names)) {
                        $this->createFile($file, $request->sub_folder_id);
                    }
                }
            }
        } else {
            foreach ($request->files as $file) {
                $this->createFile($file, $request->sub_folder_id);
            }
        }
    }

    public function delete(Request $request)
    {
        $this->authorize('file-manager');

        if ($files = File::whereIn('id', $request->checked_files)->where('is_folder', 0)->get()) {
            foreach ($files as $file) {
                unlink(public_path($file->path));
            }
        }
        File::whereIn('id', $request->checked_files)->delete();

        // log
        activity_log('delete-file', __METHOD__, $request->all());

        return back();
    }

    public function getFileName(Request $request)
    {
        $this->authorize('file-manager');

        $file = File::where('id', $request->file_id)->first();

        if ($file->is_folder) {
            $file_name = $file->name;
            $file_type = null;
        } else {
            $file_name = pathinfo($file->name)['filename'];
            $file_type = pathinfo($file->name)['extension'];
        }

        return response()->json(['name' => $file_name, 'type' => $file_type]);
    }

    public function editFileName(Request $request)
    {
        $this->authorize('file-manager');

        $file = File::where('id', $request->file_id)->first();

        if ($file->is_folder) {
            if (File::where('id', '!=', $file->id)->where(['name' => $request->new_name, 'parent_id' => $request->sub_folder_id, 'is_folder' => 1])->first()) {
                return response()->json([
                    'error' => true,
                    'message' => 'نام انتخابی موجود است'
                ]);
            }

            File::where('id', $request->file_id)->update(['name' => $request->new_name]);
        } else {
            $new_name = $request->new_name . '.' . $request->file_type;

            if (File::where('id', '!=', $file->id)->where(['name' => $new_name, 'parent_id' => $request->sub_folder_id, 'type' => $request->file_type, 'is_folder' => 0])->first()) {
                return response()->json([
                    'error' => true,
                    'message' => 'نام انتخابی موجود است'
                ]);
            }

            File::where('id', $request->file_id)->update(['name' => $new_name]);
        }

        // log
        activity_log('edit-file-name', __METHOD__, $request->all());

        return back();
    }

    public function moving(Request $request)
    {
        $this->authorize('file-manager');

        $files_id = $request->checked_files;
        session()->put('moving', true);
        session()->put('files_id', $files_id);

        // log
        activity_log('moving-file', __METHOD__, $request->all());
    }

    public function cancelMoving()
    {
        $this->authorize('file-manager');

        session()->forget(['moving', 'files_id']);

        // log
        activity_log('cancel-move-file', __METHOD__);
    }

    public function moveFiles(Request $request)
    {
        $this->authorize('file-manager');

        $files_id = session()->get('files_id');

        File::whereIn('id', $files_id)->update(['parent_id' => $request->sub_folder_id]);

        session()->forget(['moving', 'files_id']);

        // log
        activity_log('move-file', __METHOD__, $request->all());

        return back();
    }

    private function createFile($file, $sub_folder_id)
    {
        $this->authorize('file-manager');

        $file_uploaded = File::create([
            'user_id' => auth()->id(),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'is_folder' => 0,
            'parent_id' => $sub_folder_id,
            'path' => upload_file($file, 'FileManager/' . auth()->id()),
        ]);

        // log
        activity_log('upload-file', __METHOD__, [$file, $file_uploaded, $sub_folder_id]);
    }

}
