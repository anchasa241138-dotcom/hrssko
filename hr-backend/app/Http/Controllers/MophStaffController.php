<?php
namespace App\Http\Controllers;

use App\Models\MophStaff;
use Illuminate\Http\Request;

class MophStaffController extends Controller
{
    public function index()
    {
        return MophStaff::orderBy('id', 'asc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data[0]) && is_array($data[0])) {
            $created = [];
            foreach ($data as $item) {
                $created[] = MophStaff::create($item);
            }
            return response()->json($created, 201);
        }

        $staff = MophStaff::create($data);
        return response()->json($staff, 201);
    }

    public function show(MophStaff $mophStaff)
    {
        return $mophStaff;
    }

    public function update(Request $request, $id)
    {
        $staff = MophStaff::findOrFail($id);
        $staff->update($request->all());
        return response()->json($staff, 200);
    }

    public function destroy($id)
    {
        MophStaff::destroy($id);
        return response()->json(null, 204);
    }
}
