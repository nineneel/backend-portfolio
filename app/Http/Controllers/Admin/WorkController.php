<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\TechStack;
use App\Models\TempFile;
use App\Models\Work;
use App\Models\WorkImage;
use App\Models\WorkTechStack;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $works = Work::paginate(5);

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
        $tech_stack = TechStack::all();
        $services = Service::all();

        return view('admin.works.create', [
            'tech_stacks' => $tech_stack,
            'services' => $services,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_name' => 'required|unique:works|max:100',
            'slug' => 'required|unique:works',
            'agency' => 'required|max:255',
            'url' => 'required|url',
            'development_date' => 'required|before:today',
            'overview' => 'required',
            'images' => 'required',
            'thumbnail' => 'required|image|max:2048',
            'service' => 'required',
            'tech_stacks' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $validatedData['service_id'] = $validatedData['service'];
            $validatedData['development_date'] = Carbon::parse($validatedData['development_date'])->toDate();

            // Process Thumbnail
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/work/thumbnail';
                $time = date('YmdHis');
                $file_name = 'work-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            // Create Work
            $work = Work::create($validatedData);

            // Process Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $file) {
                    $original_name = $file->getClientOriginalName();
                    $destination_path = '/uploaded_files/work/images';
                    $time = date('YmdHis');
                    $file_name = 'work-' . $time . '-' . $original_name;
                    $file->move(public_path() . $destination_path, $file_name);
                    $image = $destination_path . '/' . $file_name;

                    WorkImage::create([
                        'work_id' => $work->id,
                        'image' => $image,
                        'image_alt' => $work->project_name  . "-image-" . $key
                    ]);
                }
            }

            // Process Tech Stack
            foreach ($validatedData['tech_stacks'] as $tech_stack_id) {
                WorkTechStack::create([
                    'work_id' => $work->id,
                    'tech_stack_id' => $tech_stack_id
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

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
        return view('admin.works.show', [
            'work' => $work,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        $tech_stack = TechStack::all();
        $services = Service::all();

        return view('admin.works.edit', [
            'work' => $work,
            'tech_stacks' => $tech_stack,
            'services' => $services,
        ]);
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
        $rules = [
            'agency' => 'required|max:255',
            'url' => 'required|url',
            'development_date' => 'required|before:today',
            'overview' => 'required',
        ];

        if ($request->project_name != $work->project_name) {
            $rules['project_name'] = 'required|unique:works';
        }

        if ($request->slug != $work->slug) {
            $rules['slug'] = 'required|unique:works';
        }

        if ($request->hasFile('thumbnail')) {
            $rules['thumbnail'] = 'required|image|max:2048';
        }

        if ($request->hasFile('images')) {
            $rules['images'] = 'required';
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($request->service) {
                $validatedData['service_id'] = $request->service;
            }
            $validatedData['development_date'] = Carbon::parse($validatedData['development_date'])->toDate();

            // Process Thumbnail
            if ($request->hasFile('thumbnail')) {
                if ($old_thumbnail_path = $work->thumbnail) {
                    $file_path = public_path($old_thumbnail_path);
                    if (File::exists($file_path)) File::delete($file_path);
                }

                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/work/thumbnail';
                $time = date('YmdHis');
                $file_name = 'work-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            // Update Work
            unset($validatedData['images']);
            Work::where('id', $work->id)->update($validatedData);

            // Process Images
            if ($request->hasFile('images')) {
                // Delete All old images from Work Image
                foreach ($work->images as $image) {
                    $work_image = WorkImage::where('id', $image->id)->first();
                    $file_path = public_path($work_image->image);
                    if (File::exists($file_path)) File::delete($file_path);
                    $work_image->delete();
                }

                // Add new images
                foreach ($request->file('images') as $key => $file) {
                    $original_name = $file->getClientOriginalName();
                    $destination_path = '/uploaded_files/work/images';
                    $time = date('YmdHis');
                    $file_name = 'work-' . $time . '-' . $original_name;
                    $file->move(public_path() . $destination_path, $file_name);
                    $image = $destination_path . '/' . $file_name;

                    WorkImage::create([
                        'work_id' => $work->id,
                        'image' => $image,
                        'image_alt' => $work->project_name  . "-image-" . $key
                    ]);
                }
            }

            // Process Tech Stack
            if ($request->tech_stacks) {
                foreach ($work->tech_stacks as $tech_stack) {
                    WorkTechStack::where("work_id", $work->id)->where('tech_stack_id', $tech_stack->id)->delete();
                }

                foreach ($request->tech_stacks as $new_tech_stack) {
                    WorkTechStack::create([
                        'work_id' => $work->id,
                        'tech_stack_id' => $new_tech_stack
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect(route('works.show', $work->id))->with('success', "Work has been Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        DB::beginTransaction();
        try {
            // delete thumbnail from storage
            if ($old_thumbnail_path = $work->thumbnail) {
                $file_path = public_path($old_thumbnail_path);
                if (File::exists($file_path)) File::delete($file_path);
            }

            // delete all image from storage and database
            foreach ($work->images as $image) {
                $work_image = WorkImage::where('id', $image->id)->first();
                $file_path = public_path($work_image->image);
                if (File::exists($file_path)) File::delete($file_path);
                $work_image->delete();
            }

            // delete all tech stack
            foreach ($work->tech_stacks as $tech_stack) {
                WorkTechStack::where("work_id", $work->id)->where('tech_stack_id', $tech_stack->id)->delete();
            }

            $work->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('works.index')->with('success', "Work has been deleted");
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(Work::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
