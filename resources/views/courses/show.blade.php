<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Course Details
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="overflow-hidden shadow sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <h2 class="text-2xl font-semibold">{{ $course->title }}</h2>
                </div>
                @if ($course->image)
                    <div class="mb-4">
                        <img src="{{ asset('images/' . $course->image) }}" alt="{{ $course->title }}" class="rounded-lg">
                    </div>
                @endif

                <div class="mb-4">
                    <p>{{ $course->description }}</p>
                </div>
                <div class="mb-4">
                    <p><strong>Start Date:</strong> {{ $course->start_date }}</p>
                </div>
                <div class="mb-4">
                    <p><strong>End Date:</strong> {{ $course->end_date }}</p>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">Back to Courses</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="overflow-hidden shadow sm:rounded-lg">
            <div class="p-6  border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Lessons</h3>
                @if (Auth::user()->isAdmin() || Auth::user()->isTeacher())
                    <a href="{{ route('courses.lessons.create', $course) }}" class="btn btn-primary mb-3">Add Lesson</a>
                @endif
                @if($course->lessons->isEmpty())
                    <p>No lessons available.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($course->lessons as $lesson)
                            <div class="flex flex-col bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                                <div class="p-4 flex-1">
                                    <a href="{{ route('courses.lessons.show', [$course, $lesson]) }}" class=" font-semibold">{{ $lesson->title }}</a>
                                    <div class="mt-2">
                                        @if ($lesson->video_url)
                                            <div class="video-container">
                                                <iframe src="{{ $lesson->video_url }}" frameborder="0" allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <p>No video available</p>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        {!! nl2br(e($lesson->content)) !!}
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-100">
                                    @if (Auth::user()->isAdmin() || Auth::user()->isTeacher())
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('courses.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="overflow-hidden shadow sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Quizzes</h3>
                @if (Auth::user()->isAdmin() || Auth::user()->isTeacher())
                    <a href="{{ route('courses.quizzes.create', $course) }}" class="btn btn-primary mb-3">Add Quiz</a>
                @endif
                @if($course->quizzes->isEmpty())
                    <p>No quizzes available.</p>
                @else
                    <ul>
                        @foreach($course->quizzes as $quiz)
                            <li class="mb-2">
                                <a href="{{ route('courses.quizzes.show', [$course, $quiz]) }}" class="text-blue-500">{{ $quiz->title }}</a>
                                @if (Auth::user()->isAdmin() || Auth::user()->isTeacher())
                                    <div class="flex justify-end">
                                        <a href="{{ route('courses.quizzes.edit', ['course' => $course->id, 'quiz' => $quiz->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('courses.quizzes.destroy', [$course, $quiz]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</x-app-layout>
