@extends('layout.admin.main')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/datepicker.min.js"></script>

    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
@endsection

@section('content')
    <div class="grid grid-cols-1 px-4 pt-6 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('mini-projects.index') }}"
                                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                Mini Projects
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('mini-projects.show', $mini_project->id) }}"
                                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                Detail Mini Project
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">
                                Update Mini Project
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Update Mini Project</h1>
        </div>

        <div class="col-span-2">
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <form action="{{ route('mini-projects.update', $mini_project->id) }}" method="post"
                    enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <input type="hidden" name="work_id" value="{{ $mini_project->id }}">
                    <div class="grid grid-cols-6 gap-6">
                        {{-- Project Name --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="project_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project Name</label>
                            <input type="text" name="project_name" id="project_name" placeholder="Project Name" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('project_name')  border-red-500 text-red-900 focus:border-red-500 dark:text-red-500 dark:border-red-500 @enderror"
                                value="{{ old('project_name', $mini_project->project_name) }}">
                            @error('project_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="col-span-6 sm:col-span-3">
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label for="slug"
                                        class="block text-sm font-medium text-gray-900 dark:text-white">Slug</label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input id="slug-toggle" type="checkbox" value="" class="sr-only peer">
                                        <div
                                            class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Edit
                                            Slug</span>
                                    </label>
                                </div>
                                <input type="text" name="slug" id="slug" placeholder="project-name" readonly
                                    value="{{ old('slug', $mini_project->slug) }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 cursor-not-allowed @error('slug')  border-red-500 text-red-900 focus:border-red-500 dark:text-red-500 dark:border-red-500 @enderror">
                                @error('slug')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- URL --}}
                        <div class="col-span-6 sm:col-span-2">
                            <label for="url"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL</label>
                            <input type="url" name="url" id="url" required placeholder="https://nineneel.com"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('url')  border-red-500 text-red-900 focus:border-red-500 dark:text-red-500 dark:border-red-500 @enderror"
                                value="{{ old('url', $mini_project->url) }}">
                            @error('url')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Development Date --}}
                        <div class="col-span-6 sm:col-span-2 ">
                            <label for="development_date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Development
                                Date</label>
                            <div class="relative ">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input datepicker type="text" datepicker-format="dd/mm/yyyy"
                                    value="{{ old('development_date', \Carbon\Carbon::createFromFormat('Y-m-d', $mini_project->development_date))->format('d/m/Y') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('development_date')  border-red-500 text-red-900 focus:border-red-500 dark:text-red-500 dark:border-red-500 @enderror"
                                    name="development_date" placeholder="Select date">
                            </div>
                            @error('development_date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tag --}}
                        <div class="col-span-6 sm:col-span-2 ">
                            <label for="tag"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tag</label>
                            <select id="tag" name="tag"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                {{-- <option selected disabled>Select tag </option> --}}
                                @foreach ($tags as $tag)
                                    @if ($tag->id == $mini_project->tag_id)
                                        <option selected value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @else
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('tag')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tech Stack --}}
                        <div class="col-span-6">
                            <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Tech Stack</h4>
                            <ul
                                class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach ($tech_stacks as $tech_stack)
                                    <li
                                        class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600  @if ($loop->iteration == count($tech_stacks)) border-b-0 sm:border-r-0 @endif)">
                                        <div class="flex justify-center items-center p-2 sm:flex-col">
                                            <input @if ($mini_project->tech_stacks->contains('id', $tech_stack->id)) checked @endif
                                                id="{{ $tech_stack->name }}" type="checkbox" name="tech_stacks[]"
                                                value="{{ $tech_stack->id }}"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="{{ $tech_stack->name }}"
                                                class="flex sm:justify-center w-full text-center pt-0 mx-1 text-sm font-medium text-gray-900 dark:text-gray-300 sm:pt-3 ">
                                                <img src="{{ asset($tech_stack->thumbnail) }}"
                                                    alt="{{ $tech_stack->thumbnail_alt }}" class="ml-3 w-7 h-7 sm:ml-0">
                                                <span class="ml-2 sm:hidden">{{ $tech_stack->name }}</span>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            @error('tech_stacks')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Overview --}}
                        <div class="col-span-6 sm:col-span-full">
                            <label for="overview"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overview</label>
                            <textarea id="overview" name="overview" rows="3"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('overview')  border-red-500 text-red-900 focus:border-red-500 dark:text-red-500 dark:border-red-500 @enderror"
                                placeholder="Write your thoughts here...">{{ old('overview', $mini_project->overview) }}</textarea>
                            @error('overview')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Thumbnail --}}
                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="thumbnail-container">Thumbnail</label>
                            <div class="flex flex-col items-center justify-center w-full">
                                <div id="thumbnail-preview"
                                    class="flex mb-2 p-4 flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 overflow-hidden dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div id="thumbnail-preview-container"
                                        class="py-1 flex flex-wrap justify-center w-full h-full gap-4 xl:gap-8">
                                        <img src="{{ asset($mini_project->thumbnail) }}"
                                            alt="{{ $mini_project->project_name }} thumbnail" class="h-56">
                                    </div>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Thumbnail Preview</span></p>
                                </div>
                                <label for="thumbnail"
                                    class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                class="font-semibold">Click to upload</span></p>
                                    </div>
                                    <input id="thumbnail" type="file" name="thumbnail" class="hidden"
                                        accept="image/png" />
                                </label>
                            </div>
                            @error('thumbnail')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="image-container">Images</label>

                            <div class="flex flex-col items-center justify-center w-full">
                                <div id="image-preview"
                                    class="flex mb-2 p-4 flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 overflow-hidden dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div id="image-preview-container"
                                        class="py-1 flex flex-wrap justify-center w-full h-full gap-4 xl:gap-8">
                                        @foreach ($mini_project->images as $image)
                                            <img src="{{ asset($image->image) }}" alt="{{ $image->image_alt }}"
                                                class="h-56">
                                        @endforeach
                                    </div>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Preview</span></p>
                                </div>
                                <label for="images"
                                    class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                class="font-semibold">Click to upload</span></p>
                                    </div>
                                    <input id="images" type="file" name="images[]" class="hidden"
                                        accept="image/png" multiple />
                                </label>
                            </div>
                            @error('images')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Save --}}
                        <div class="col-span-6">
                            <button
                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const project_name = document.querySelector('#project_name');
        const slug = document.querySelector('#slug');
        const slug_toggle = document.querySelector('#slug-toggle');

        project_name.addEventListener('change', function() {
            fetch(`/admin/mini-project/create-slug?title=${project_name.value}`)
                .then(response => response.json())
                .then(data => slug.value = data.slug);
        })

        slug_toggle.addEventListener('change', function() {
            slug_toggle.toggleAttribute("checked");
            slug.toggleAttribute('readonly')
            slug.classList.toggle('cursor-not-allowed')
        })

        // Image Preview
        const imagesPreview = (file, isSingle) => {
            const file_reader = new FileReader();

            const oFReader = new FileReader();
            oFReader.readAsDataURL(file);

            oFReader.onload = function(oFREvent) {
                const img = document.createElement("img");
                img.classList.add('h-64');
                img.src = oFREvent.target.result;
                img.alt = file.name;
                if (isSingle) {
                    document.querySelector('#thumbnail-preview-container').append(img);
                } else {
                    document.querySelector('#image-preview-container').append(img);
                }
            }
        }

        // Thumbnail
        const thumbnail_input = document.querySelector("#thumbnail");
        const thumbnail_input_wrapper = document.querySelector("#thumbnail-preview");

        thumbnail_input.addEventListener("change", (element) => {
            if (!element.target.files) return; // Do nothing.
            document.querySelector('#thumbnail-preview-container').innerHTML = '';
            [...element.target.files].forEach((file) => imagesPreview(file, true));
            thumbnail_input_wrapper.classList.remove('hidden');
            thumbnail_input_wrapper.classList.add('flex');
        });

        // Images
        const images_input = document.querySelector("#images");
        const images_input_wrapper = document.querySelector("#image-preview");

        images_input.addEventListener("change", (element) => {
            if (!element.target.files) return; // Do nothing.
            document.querySelector('#image-preview-container').innerHTML = '';
            [...element.target.files].forEach((file) => imagesPreview(file, false));
            images_input_wrapper.classList.remove('hidden');
            images_input_wrapper.classList.add('flex');

        });
    </script>
@endsection
