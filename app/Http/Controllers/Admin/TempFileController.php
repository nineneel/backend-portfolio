<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TempFileController extends Controller
{
    public function temp_upload(Request $request)
    {
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $file_name = $image->getClientOriginalName();
            $folder = uniqid('temp', true);
            $image->storeAs('temps/temp/' . $folder, $file_name);

            TempFile::create([
                'filename' => $file_name,
                'folder' => $folder,
            ]);

            Session::push('folder', $folder);
            Session::push('filename', $file_name);

            return $folder;
        }

        return '';
    }

    public function temp_delete()
    {
        $temp_image = TempFile::where('folder', request()->getContent())->first();

        if ($temp_image) {
            Storage::deleteDirectory('temps/temp/' . $temp_image->folder);
            $temp_image->delete();
            return '';
        }
    }

    public function temp_restore(Request $request)
    {
        foreach ($request->file('files') as $file) {
            // process each file
            Storage::disk('local')->put($file->getClientOriginalName(), File::get($file));
        }

        return response()->json(['success' => true]);
    }
}
