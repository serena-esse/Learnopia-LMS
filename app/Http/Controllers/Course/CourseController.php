<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display a listing of the courses
    public function index()
    {
        // Fetch all courses
        $courses = Course::all();
        return view('courses.index', ['courses' => $courses]);
    }

    // Show the form for creating a new course
    public function create()
    {
        return view('courses.create');
    }

    // Store a newly created course in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $course = new Course();
            $course->title = $request['title'];
            $course->description = $request['description'];
            $course->start_date = $request['start_date'];
            $course->end_date = $request['end_date'];
            $course->users_id = Auth::user()->id;

            Log::info('Course data prepared for saving', [
                'title' => $course->title,
                'description' => $course->description,
                'start_date' => $course->start_date,
                'end_date' => $course->end_date,
                'users_id' => $course->users_id
            ]);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $course->image = $imageName;
                Log::info('Image uploaded successfully', ['image' => $course->image]);
            }

            $course->save();
            Log::info('Course saved successfully', ['course_id' => $course->id]);

            return redirect()->route('courses.index')->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating course: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to create course. Please try again.']);
        }
    }

    // Display the specified course
    public function show(Course $course)
    {
        $quizzes = $course->quizzes()->get();
        return view('courses.show', compact('course', 'quizzes'));
    }

    // Show the form for editing the specified course
    public function edit(Course $course)
    {
        return view('courses.edit', ['course' => $course]);
    }

    // Update the specified course in the database
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $course->title = $request['title'];
            $course->description = $request['description'];
            $course->start_date = $request['start_date'];
            $course->end_date = $request['end_date'];

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $course->image = $imageName;
            }

            $course->save();
            return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Remove the specified course from the database
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
    public function enroll(Course $course)
    {
        $user = auth()->user();
        if (!$course->users()->where('user_id', $user->id)->exists()) {
            $course->users()->attach($user->id);
            return redirect()->route('courses.show', $course)->with('success', 'You have been enrolled in the course.');
        }
        return redirect()->route('courses.show', $course)->with('error', 'You are already enrolled in this course.');
    }
}
