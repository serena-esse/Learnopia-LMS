<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5 bg-fef7ed rounded-lg shadow-lg p-6">
        @if (Auth::user()->isAdmin() || Auth::user()->isTeacher())
        <div class="mb-4">
            <a href="{{ route('courses.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-1 px-3 rounded-lg shadow-lg inline-flex items-center justify-center text-sm">
                Create Course
            </a>
        </div>
        @endif

        @if ($message = Session::get('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-3" role="alert">
            {{ $message }}
        </div>
        @endif

        <div class="flex justify-center mb-4">
            <form method="GET" action="{{ route('courses.index') }}" class="flex items-center w-full">
                <input type="text" name="search" placeholder="Search courses..." class="form-input rounded-md shadow-sm mt-1 block w-full h-full">
                <button type="submit" class="ml-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg inline-flex items-center justify-center text-xs h-full">Search</button>
            </form>
        </div>

        @if ($courses->isEmpty())
        <p class="text-center text-gray-600">There are no courses to display.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($courses as $course)
            <div class="overflow-hidden bg-gray-200 rounded-lg shadow-md flex flex-col">
                @if($course->image)
                <img src="{{ asset('images/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-56 object-cover rounded-t-lg">
                @endif
                <div class="p-4 bg-white rounded-b-lg flex-grow flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $course->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs text-gray-500">Start Date: {{ $course->start_date }}</p>
                        <p class="text-xs text-gray-500">End Date: {{ $course->end_date }}</p>
                    </div>
                    <div class="mt-auto flex justify-center space-x-2">
                        <button type="button" onclick="window.location.href='{{ route('courses.show', $course->id) }}'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded-lg shadow-lg inline-flex items-center justify-center text-xs">
                            View
                        </button>
                        @if (Auth::user()->isAdmin() || Auth::user()->isTeacher())
                        <button type="button" onclick="window.location.href='{{ route('courses.edit', $course->id) }}'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded-lg shadow-lg inline-flex items-center justify-center text-xs">
                            Edit
                        </button>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-lg shadow-lg inline-flex items-center justify-center text-xs">
                                Delete
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline-flex">
                            @csrf
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-lg shadow-lg inline-flex items-center justify-center text-xs">
                                Enroll
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <!-- Pagination -->
    <div class="mt-6">
        {{ $courses->links() }}
    </div>
</x-app-layout>
