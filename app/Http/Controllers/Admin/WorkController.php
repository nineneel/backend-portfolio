<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempFile;
use App\Models\Work;
use App\Models\WorkImage;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $works = Work::all();
        return view('admin.works.index', [
            'works' => $works,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Session::has('folder')) {
            Session::remove('folder');
            Session::remove('filename');
        }

        return view('admin.works.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'project_name' => 'required|unique:works|max:100',
            'slug' => 'required',
            'agency' => 'required|max:255',
            'url' => 'required',
            'development_date' => 'required|before:today',
            'overview' => 'required',
            'images' => 'required'
        ]);

        $validateData['service_id'] = 0;
        $validateData['development_date'] = Carbon::parse($validateData['development_date'])->toDate();

        $new_work = Work::create($validateData);

        $temporaryFolder = Session::get('folder');
        $namefile = Session::get('filename');

        for ($i = 0; $i < count($temporaryFolder); $i++) {
            $temp_image = TempFile::where('folder', $temporaryFolder[$i])->where('filename', $namefile[$i])->first();

            if ($temp_image) {
                Storage::copy('temps/temp/' . $temp_image->folder . "/" . $temp_image->filename, 'public/work-images/' . $temp_image->folder . '/' . $temp_image->filename);

                $image_path = $temp_image->folder . '/' . $temp_image->filename;
                WorkImage::create([
                    'work_id' => $new_work->id,
                    'image' => $image_path,
                    'image_alt' => $new_work->project_name . "images"
                ]);

                Storage::deleteDirectory('temps/temp/' . $temp_image->folder);

                $temp_image->delete();
            }
        }

        Session::remove('folder');
        Session::remove('filename');

        return redirect(route('works.index'))->with('success', "Work has been Created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        //
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(Work::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
