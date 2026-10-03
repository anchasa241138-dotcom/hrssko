<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'username' => $request->username,
            'prefix' => $request->prefix,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'employment_type' => $request->employment_type,
            'agency' => $request->agency,
            'district' => $request->district,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return response()->json(['success' => true]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => true, 'user' => Auth::user()]);
        }

        return response()->json(['success' => false, 'message' => 'ชื่อผู้ใช้งาน หรือ รหัสผ่าน ไม่ถูกต้อง!'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }
}
