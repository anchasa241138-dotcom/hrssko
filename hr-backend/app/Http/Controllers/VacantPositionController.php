<?php
namespace App\Http\Controllers;

use App\Models\VacantPosition;
use Illuminate\Http\Request;

class VacantPositionController extends Controller
{
    public function index()
    {
        return VacantPosition::all();
    }

    public function store(Request $request)
    {
        $position = VacantPosition::create($request->all());
        return response()->json($position, 201);
    }

    public function show(VacantPosition $vacantPosition)
    {
        return $vacantPosition;
    }

    public function update(Request $request, VacantPosition $vacantPosition)
    {
        $vacantPosition->update($request->all());
        return response()->json($vacantPosition, 200);
    }

    public function destroy($id)
    {
        VacantPosition::destroy($id);
        return response()->json(null, 204);
    }
}
