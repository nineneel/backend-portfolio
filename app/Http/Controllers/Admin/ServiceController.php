<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::paginate(5);
        return view('admin.services.index', [
            'services' => $services
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.create');
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
            'name' => "required|max:255",
            'slug' => "required|unique:services",
            'description' => 'required',
            'thumbnail' => 'required|image|max:1024'
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/service/thumbnail';
                $time = date('YmdHis');
                $file_name = 'service-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            Service::create($validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect(route('services.index'))->with('success', "Services has been Created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return view('admin.services.show', [
            'service' => $service
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', [
            'service' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $rules = [
            'name' => "required|max:255",
            'description' => 'required',
        ];

        if ($request->slug != $service->slug) {
            $rules['slug'] = "required|unique:services";
        }

        if ($request->hasFile('thumbnail')) {
            $rules['thumbnail'] = 'required|image|max:1024';
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                if ($old_thumbnail_path = $service->thumbnail) {
                    $file_path = public_path($old_thumbnail_path);
                    if (File::exists($file_path)) File::delete($file_path);
                }

                $file = $request->file('thumbnail');
                $original_name = $file->getClientOriginalName();
                $destination_path = '/uploaded_files/service/thumbnail';
                $time = date('YmdHis');
                $file_name = 'service-' . $time . '-' . $original_name;
                $file->move(public_path() . $destination_path, $file_name);

                $validatedData['thumbnail'] = $destination_path . '/' . $file_name;
            }

            $service->update($validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect(route('services.show', $service->id))->with('success', "Services has been Created!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        if (count($service->works) > 0) {
            return redirect()->back()->with('error', 'Can\'t delete this service, because this service is attached with some work!');
        }

        DB::beginTransaction();
        try {
            if ($old_thumbnail_path = $service->thumbnail) {
                $file_path = public_path($old_thumbnail_path);
                if (File::exists($file_path)) File::delete($file_path);
            }
            $service->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect(route('services.index'))->with('success', "Services has been Deleted!");
    }

    public function create_slug(Request $request)
    {
        $slug = SlugService::createSlug(Service::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
