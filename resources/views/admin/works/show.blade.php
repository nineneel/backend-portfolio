@extends('layout.admin.main')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/datepicker.min.js"></script>

    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
@endsection

@section('content')
    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
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
                            <a href="{{ route('works.index') }}"
                                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">Works</a>
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
                                Details Work
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="items-center justify-between block sm:flex">
                <div class="flex items-center mb-4 sm:mb-0">
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Details Work</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('works.edit', $work->id) }}" id="updateProductButton"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                            </path>
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Edit Work
                    </a>

                    <button data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                        type="button">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Delete Work
                    </button>

                    <div id="popup-modal" tabindex="-1"
                        class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-md md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button"
                                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                                    data-modal-hide="popup-modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-6 text-center">
                                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you
                                        want to delete this Work?</h3>
                                    <form action="{{ route('works.destroy', $work->id) }}" method="POST"
                                        class="inline-flex">
                                        @method('delete')
                                        @csrf
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                            Yes, I'm sure
                                        </button>
                                    </form>
                                    <button data-modal-hide="popup-modal" type="button"
                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
                                        cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Content -->
        <div class="col-span-full">
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="grid grid-cols-6 gap-3">
                    {{-- Project ID --}}
                    <div class="col-span-6">
                        <label for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Work
                            ID</label>
                        <input type="text" id="disabled-input-2 id" value="{{ $work->id }}" name="id"
                            aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>
                    </div>
                    {{-- Project Name --}}
                    <div class="col-span-6">
                        <label for="project_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project Name</label>
                        <input type="text" id="disabled-input-2 project_name" value="{{ $work->project_name }}"
                            name="project_name" aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>
                    </div>
                    {{-- Slug --}}
                    <div class="col-span-6">
                        <label for="slug"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug</label>
                        <input type="text" id="disabled-input-2 slug" value="{{ $work->slug }}" name="slug"
                            aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>
                    </div>
                    {{-- Agency --}}
                    <div class="col-span-6">
                        <label for="agency"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agency</label>
                        <input type="text" id="disabled-input-2 agency" value="{{ $work->agency }}" name="agency"
                            aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>
                    </div>
                    {{-- Url --}}
                    <div class="col-span-6">
                        <div class="mb-2 flex justify-between items-end space-x-3">
                            <label for="url"
                                class="blocktext-sm font-medium text-gray-900 dark:text-white">URL</label>
                            <a href="{{ $work->url }}" target="_blank"
                                class="inline-flex items-center font-medium text-sm text-blue-600 dark:text-blue-500 hover:underline">
                                Visit Website
                                <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                        <input type="text" id="disabled-input-2 url" value="{{ $work->url }}" name="url"
                            aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>

                    </div>
                    {{-- Development Date --}}
                    <div class="col-span-6">
                        <label for="development_date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Development
                            Date</label>
                        <input type="text" id="disabled-input-2 development_date"
                            value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $work->development_date)->format('d F Y') }}"
                            name="development_date" aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>
                    </div>
                    {{-- Service --}}
                    <div class="col-span-6">
                        <label for="service"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Service</label>
                        <input type="text" id="disabled-input-2 service" value="{{ $work->service->name }}"
                            name="service" aria-label="disabled input 2"
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            disabled readonly>
                    </div>
                    {{-- Tech Stack --}}
                    <div class="col-span-6">
                        <label for="tech_stacks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tech
                            Stack</label>
                        <ul
                            class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach ($work->tech_stacks as $tech_stack)
                                <li
                                    class="w-full @if (!$loop->last) border-b @endif border-gray-200 rounded-t-lg dark:border-gray-600">
                                    <div class="flex items-center pl-3">
                                        <input id="vue-checkbox" type="checkbox" value="" disabled checked
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="vue-checkbox"
                                            class="flex items-center w-full py-3 ml-2 text-sm font-normal text-gray-900 dark:text-gray-300">
                                            <img src="{{ asset($tech_stack->thumbnail) }}"
                                                alt="{{ $tech_stack->thumbnail_alt }}" class="ml-4 w-8 h-8">
                                            <span class="ml-2">{{ $tech_stack->name }}</span>
                                        </label>

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- Overview --}}
                    <div class="col-span-6 sm:col-span-full">
                        <label for="overview"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overview</label>
                        <textarea id="overview" name="overview" rows="8" disabled
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Write your thoughts here...">{{ $work->overview }}</textarea>
                    </div>
                </div>
            </div>
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                {{-- <h3 class="mb-4 text-xl font-semibold dark:text-white">Image</h3> --}}
                <div class="grid grid-cols-6 gap-6">
                    {{-- Thumbnail --}}
                    <div class="col-span-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="image-container">Thumbnail</label>
                        <div class="flex flex-col items-center justify-center w-full">
                            <div id="image-preview"
                                class="flex p-4 flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 overflow-hidden dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div id="image-preview-container"
                                    class="py-1 flex flex-wrap justify-center w-full h-full gap-4 xl:gap-8">
                                    <img src="{{ asset($work->thumbnail) }}" alt="{{ $work->project_name }} thumbnail"
                                        class="h-56">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="col-span-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="image-container">Images</label>
                        <div class="flex flex-col items-center justify-center w-full">
                            <div id="image-preview"
                                class="flex p-4 flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 overflow-hidden dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div id="image-preview-container"
                                    class="py-1 flex flex-wrap justify-center w-full h-full gap-4 xl:gap-8">
                                    @foreach ($work->images as $image)
                                        <img src="{{ asset($image->image) }}" alt="{{ $image->image_alt }}"
                                            class="h-56">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
