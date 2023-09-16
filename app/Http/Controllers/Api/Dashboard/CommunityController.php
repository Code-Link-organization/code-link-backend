<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community; // Make sure to import the Community model

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::all();
        return response()->json(['data' => $communities], 200);
    }

    public function store(Request $request)
    {
        $community = Community::create($request->all());

        return response()->json(['data' => $community], 201);
    }

    public function show($id)
    {
        $community = Community::find($id);

        if (!$community) {
            return response()->json(['message' => 'Community not found'], 404);
        }

        return response()->json(['data' => $community], 200);
    }

    public function update(Request $request, $id)
    {
        $community = Community::find($id);

        if (!$community) {
            return response()->json(['message' => 'Community not found'], 404);
        }

        $community->update($request->all());

        return response()->json(['data' => $community], 200);
    }

    public function destroy($id)
    {
        $community = Community::find($id);

        if (!$community) {
            return response()->json(['message' => 'Community not found'], 404);
        }

        $community->delete();

        return response()->json(['message' => 'Community deleted successfully'], 200);
    }
}
