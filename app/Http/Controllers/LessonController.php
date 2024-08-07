<?php
namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Log;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        // Retrieve lessons for the specific course
        $lessons = $course->lessons;

        return view('courses.show', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        

        return view('lessons.create', compact('course'));
    }



    public function store(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'video_url' => 'nullable|url',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $course->lessons()->create($validatedData);

        return redirect()->route('courses.lessons.index', $course)->with('success', 'Lesson created successfully.');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $courses = Course::all(); // Retrieve all courses
        return view('lessons.edit', compact('lesson', 'course', 'courses'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $validatedData = $request->validate([
            'video_url' => 'nullable|url',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $lesson->update($validatedData);

        return redirect()->route('courses.lessons.index', $course)->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('courses.lessons.index', $course)->with('success', 'Lesson deleted successfully.');
    }

    public function show(Course $course, Lesson $lesson)
    {
        return view('lessons.show', compact('lesson', 'course'));
    }
}
