<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track; 
class TrackController extends Controller
{
    public function index()
    {
        $tracks = Track::all();
        return response()->json(['data' => $tracks], 200);
    }

    public function store(Request $request)
    {
        $track = Track::create($request->all());

        return response()->json(['data' => $track], 201);
    }

    public function show($id)
    {
        $track = Track::find($id);

        if (!$track) {
            return response()->json(['message' => 'Track not found'], 404);
        }

        return response()->json(['data' => $track], 200);
    }

    public function update(Request $request, $id)
    {
        $track = Track::find($id);

        if (!$track) {
            return response()->json(['message' => 'Track not found'], 404);
        }

        $track->update($request->all());

        return response()->json(['data' => $track], 200);
    }

    public function destroy($id)
    {
        $track = Track::find($id);

        if (!$track) {
            return response()->json(['message' => 'Track not found'], 404);
        }

        $track->delete();

        return response()->json(['message' => 'Track deleted successfully'], 200);
    }
}
