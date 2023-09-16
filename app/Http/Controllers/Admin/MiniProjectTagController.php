<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiniProject;
use App\Models\MiniProjectTag;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MiniProjectTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = MiniProjectTag::paginate(5);

        return view('admin.mini_project_tags.index', [
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.mini_project_tags.create');
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
            'name' => "required",
            "slug" => "required|unique:mini_project_tags",
            "description" => "required"
        ]);

        DB::beginTransaction();
        try {
            MiniProjectTag::create($validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mini-project-tags.index')->with('success', "Tag has been Created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MiniProjectTag $mini_project_tag)
    {
        $mini_projects = MiniProject::all();

        return view('admin.mini_project_tags.show', [
            'tag' => $mini_project_tag,
            'mini_projects' => $mini_projects
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MiniProjectTag $mini_project_tag)
    {
        return view('admin.mini_project_tags.edit', [
            'tag' => $mini_project_tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MiniProjectTag $mini_project_tag)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];

        if ($request->slug != $mini_project_tag->slug) {
            $rules['slug'] = 'required|unique:mini_project_tags';
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            $mini_project_tag->update($validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mini-project-tags.show', $mini_project_tag->id)->with('success', 'Tag has been Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MiniProjectTag $mini_project_tag)
    {
        DB::beginTransaction();
        try {
            $mini_project_tag->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mini-project-tags.index')->with('success', 'Tag has been Deleted!');
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(MiniProjectTag::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}
