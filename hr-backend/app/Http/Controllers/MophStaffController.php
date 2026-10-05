<?php
namespace App\Http\Controllers;

use App\Models\MophStaff;
use Illuminate\Http\Request;

class MophStaffController extends Controller
{
    public function index()
    {
        return MophStaff::all();
    }

    public function store(Request $request)
    {
        $staff = MophStaff::create($request->all());
        return response()->json($staff, 201);
    }

    public function show(MophStaff $mophStaff)
    {
        return $mophStaff;
    }

    public function update(Request $request, MophStaff $mophStaff)
    {
        $mophStaff->update($request->all());
        return response()->json($mophStaff, 200);
    }

    public function destroy($id)
    {
        MophStaff::destroy($id);
        return response()->json(null, 204);
    }
}
