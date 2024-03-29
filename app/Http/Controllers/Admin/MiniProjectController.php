<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiniProject;
use App\Models\MiniProjectImage;
use App\Models\MiniProjectTag;
use App\Models\MiniProjectTechStack;
use App\Models\TechStack;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MiniProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mini_projects = MiniProject::paginate(5);

        return view('admin.mini_project.index', [
            'mini_projects' => $mini_projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tag = MiniProjectTag::all();
        $tech_stacks = TechStack::all();

        return view('admin.mini_project.create', [
            'tags' => $tag,
            'tech_stacks' => $tech_stacks
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
            'project_name' => 'required|max:100',
            'slug' => 'required|unique:mini_projects',
            'url' => 'required|url',
            'development_date' => 'required|before:today',
            'overview' => 'required',
            'images' => 'required',
            'thumbnail' => 'required|image|max:2048',
            'tag' => 'required',
            'tech_stacks' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $validatedData['mini_project_tag_id'] = $validatedData['tag'];
            $validatedData['development_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['development_date'])->format("Y-m-d");

            // Thumbnail
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/mini-project/thumbnail';
                $time = date('YmdHis');
                $file_name = 'mini-project-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            $new_mini_project = MiniProject::create($validatedData);

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $file) {
                    $original_name = $file->getClientOriginalName();
                    $destination_path = '/uploaded_files/mini-project/images';
                    $time = date('YmdHis');
                    $file_name = 'mini-project-' . $time . '-' . $original_name;
                    $file->move(public_path() . $destination_path, $file_name);
                    $image = $destination_path . '/' . $file_name;

                    MiniProjectImage::create([
                        'mini_project_id' => $new_mini_project->id,
                        'image' => $image,
                        'image_alt' => $new_mini_project->project_name  . "-image-" . $key
                    ]);
                }
            }

            // Process Tech Stack
            foreach ($validatedData['tech_stacks'] as $tech_stack_id) {
                MiniProjectTechStack::create([
                    'mini_project_id' => $new_mini_project->id,
                    'tech_stack_id' => $tech_stack_id
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mini-projects.index')->with('success', 'Mini Project has been Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MiniProject  $miniProject
     * @return \Illuminate\Http\Response
     */
    public function show(MiniProject $miniProject)
    {
        return view('admin.mini_project.show', ['mini_project' => $miniProject]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MiniProject  $miniProject
     * @return \Illuminate\Http\Response
     */
    public function edit(MiniProject $miniProject)
    {
        $tags = MiniProjectTag::all();
        $tech_stacks = TechStack::all();

        return view('admin.mini_project.edit', [
            'mini_project' => $miniProject,
            'tags' => $tags,
            'tech_stacks' => $tech_stacks
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MiniProject  $miniProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MiniProject $miniProject)
    {
        $rules = [
            'url' => 'required|url',
            'development_date' => 'required|before:today',
            'overview' => 'required',
        ];

        if ($request->project_name != $miniProject->project_name) {
            $rules['project_name'] = 'required';
        }

        if ($request->slug != $miniProject->slug) {
            $rules['slug'] = 'required|unique:mini_projects';
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

            $validatedData['development_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['development_date'])->format('Y-m-d');

            // Process Thumbnail
            if ($request->hasFile('thumbnail')) {
                if ($old_thumbnail_path = $miniProject->thumbnail) {
                    $file_path = public_path($old_thumbnail_path);
                    if (File::exists($file_path)) File::delete($file_path);
                }

                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/mini-project/thumbnail';
                $time = date('YmdHis');
                $file_name = 'mini-project-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            // Update Mini Project
            unset($validatedData['images']);
            $miniProject->update($validatedData);

            // Process Images
            if ($request->hasFile('images')) {
                // Delete All old images from Mini Project Image
                foreach ($miniProject->images as $image) {
                    $the_image = MiniProjectImage::where('id', $image->id)->first();
                    $file_path = public_path($the_image->image);
                    if (File::exists($file_path)) File::delete($file_path);
                    $the_image->delete();
                }

                // Add new images
                foreach ($request->file('images') as $key => $file) {
                    $original_name = $file->getClientOriginalName();
                    $destination_path = '/uploaded_files/mini-project/images';
                    $time = date('YmdHis');
                    $file_name = 'mini-project-' . $time . '-' . $original_name;
                    $file->move(public_path() . $destination_path, $file_name);
                    $image = $destination_path . '/' . $file_name;

                    MiniProjectImage::create([
                        'mini_project_id' => $miniProject->id,
                        'image' => $image,
                        'image_alt' => $miniProject->project_name  . "-image-" . $key
                    ]);
                }
            }

            // Process Tech Stack
            if ($request->tech_stacks) {
                foreach ($miniProject->tech_stacks as $tech_stack) {
                    MiniProjectTechStack::where("mini_project_id", $miniProject->id)->where('tech_stack_id', $tech_stack->id)->delete();
                }

                foreach ($request->tech_stacks as $new_tech_stack) {
                    MiniProjectTechStack::create([
                        'mini_project_id' => $miniProject->id,
                        'tech_stack_id' => $new_tech_stack
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect(route('mini-projects.show', $miniProject->id))->with('success', "Mini Project has been Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MiniProject  $miniProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(MiniProject $miniProject)
    {
        DB::beginTransaction();
        try {
            // delete thumbnail from storage
            if ($old_thumbnail_path = $miniProject->thumbnail) {
                $file_path = public_path($old_thumbnail_path);
                if (File::exists($file_path)) File::delete($file_path);
            }

            // delete all image from storage and database
            foreach ($miniProject->images as $image) {
                $the_image = MiniProjectImage::where('id', $image->id)->first();
                $file_path = public_path($the_image->image);
                if (File::exists($file_path)) File::delete($file_path);
                $the_image->delete();
            }

            // delete all tech stack
            foreach ($miniProject->tech_stacks as $tech_stack) {
                MiniProjectTechStack::where("mini_project_id", $miniProject->id)->where('tech_stack_id', $tech_stack->id)->delete();
            }

            $miniProject->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mini-projects.index')->with('success', "Mini Project has been deleted!");
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(MiniProject::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
