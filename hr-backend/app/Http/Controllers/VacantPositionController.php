<?php
namespace App\Http\Controllers;

use App\Models\VacantPosition;
use Illuminate\Http\Request;

class VacantPositionController extends Controller
{
    public function index()
    {
        return VacantPosition::orderBy('id', 'asc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data[0]) && is_array($data[0])) {
            $created = [];
            foreach ($data as $item) {
                $created[] = VacantPosition::create($item);
            }
            return response()->json($created, 201);
        }

        $position = VacantPosition::create($data);
        return response()->json($position, 201);
    }

    public function show(VacantPosition $vacantPosition)
    {
        return $vacantPosition;
    }

    public function update(Request $request, $id)
    {
        $position = VacantPosition::findOrFail($id);
        $position->update($request->all());
        return response()->json($position, 200);
    }

    public function destroy($id)
    {
        VacantPosition::destroy($id);
        return response()->json(null, 204);
    }
}
