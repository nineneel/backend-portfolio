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
            $validatedData['development_date'] = Carbon::parse($validatedData['development_date'])->toDate();

            // Thumbnail
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/mini-project/thumbnail';
                $time = date('YmdHis');
                $file_name = 'work-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            $new_mini_project = MiniProject::create($validatedData);

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $file) {
                    $original_name = $file->getClientOriginalName();
                    $destination_path = '/uploaded_files/work/images';
                    $time = date('YmdHis');
                    $file_name = 'work-' . $time . '-' . $original_name;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MiniProject  $miniProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(MiniProject $miniProject)
    {
        //
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(MiniProject::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
