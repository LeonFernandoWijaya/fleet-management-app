<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login.index');
    }

    public function store(Request $request)
    {
        $request->only('email', 'password');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect('/vehicles');
        } else {
            return response()->json(['error' => 'Invalid credentials'], 422);
        }
    }

    public function destroy()
    {
        auth()->logout();
        return redirect('/login');
    }

    public function changePassword(Request $request)
    {
        $request->only('oldPassword', 'newPassword', 'newConfirmPassword');
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|string|min:8',
            'newConfirmPassword' => 'required|same:newPassword',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        if (auth()->attempt(['email' => auth()->user()->email, 'password' => $request->oldPassword])) {
            auth()->user()->update(['password' => bcrypt($request->newPassword)]);
            return response()->json(['message' => 'Password changed successfully'], 200);
        } else {
            return response()->json(['errors' => 'Invalid old password'], 422);
        }
    }
}
