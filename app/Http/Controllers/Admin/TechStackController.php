<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechStack;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TechStackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tech_stacks = TechStack::paginate(10);
        return view('admin.tech_stacks.index', [
            'tech_stacks' => $tech_stacks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tech_stacks.create');
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
            'name' => 'required|max:255',
            'slug' => 'required|unique:tech_stacks',
            'description' => 'required',
            'thumbnail' => 'required|image|max:1024'
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/tech_stack/thumbnail';
                $time = date('YmdHis');
                $file_name = "tech-stack-" . $time . "-" . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
                $validatedData['thumbnail_alt'] = "tech stack thumbnail";
            }

            TechStack::create($validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('tech-stacks.index')->with('success', "Tech Stack has been created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechStack  $techStack
     * @return \Illuminate\Http\Response
     */
    public function show(TechStack $techStack)
    {
        $techStack = $techStack->with(['works'])->where('id', $techStack->id)->first();
        return view('admin.tech_stacks.show', [
            'tech_stack' => $techStack,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TechStack  $techStack
     * @return \Illuminate\Http\Response
     */
    public function edit(TechStack $techStack)
    {
        return view('admin.tech_stacks.edit', [
            'tech_stack' => $techStack
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechStack  $techStack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechStack $techStack)
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'required',
        ];

        if ($request->slug != $techStack->slug) {
            $rules['slug'] = 'required|unique:tech_stacks';
        }
        if ($request->hasFile('thumbnail')) {
            $rules['thumbnail'] = 'required|image|max:1024';
        }

        DB::beginTransaction();
        try {
            $validatedData = $request->validate($rules);

            if ($request->hasFile('thumbnail')) {
                if ($old_path = $techStack->exif_thumbnail) {
                    $file_path = public_path($old_path);
                    if (File::exists($file_path)) File::delete($file_path);
                }

                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/tech_stack/thumbnail';
                $time = date('YmdHis');
                $file_name = "tech-stack-" . $time . "-" . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            $techStack->update($validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('tech-stacks.show', $techStack->id)->with('success', "Tech Stack has been Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechStack  $techStack
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechStack $techStack)
    {
        if (count($techStack->works) > 0) {
            return redirect()->back()->with('error', 'Can\'t delete this tech stack, because this tech stack is attached with some work!');
        }

        DB::beginTransaction();
        try {
            if ($old_path = $techStack->thumbnail) {
                $file_path = public_path($old_path);
                if (File::exists($file_path)) File::delete($file_path);
            }
            $techStack->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('tech-stacks.index')->with('success', "Tech Stack has been Deleted!");
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(TechStack::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
