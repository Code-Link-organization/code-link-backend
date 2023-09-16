<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mentor; 

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::all();
        return response()->json(['data' => $mentors], 200);
    }

    public function store(Request $request)
    {
        $mentor = Mentor::create($request->all());

        return response()->json(['data' => $mentor], 201);
    }

    public function show($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(['message' => 'Mentor not found'], 404);
        }

        return response()->json(['data' => $mentor], 200);
    }

    public function update(Request $request, $id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(['message' => 'Mentor not found'], 404);
        }

        $mentor->update($request->all());

        return response()->json(['data' => $mentor], 200);
    }

    public function destroy($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(['message' => 'Mentor not found'], 404);
        }

        $mentor->delete();

        return response()->json(['message' => 'Mentor deleted successfully'], 200);
    }
}
