<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course; 

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json(['data' => $courses], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'courseUrl' => 'required|string',
            'description' => 'required|string',
            'track_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $course = Course::create($request->all());

        return response()->json(['data' => $course], 201);
    }

    public function show($id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json(['data' => $course], 200);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $request->validate([
            'courseUrl' => 'string',
            'description' => 'string',
            'track_id' => 'integer',
            'user_id' => 'integer',
        ]);

        $course->update($request->all());

        return response()->json(['data' => $course], 200);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}
