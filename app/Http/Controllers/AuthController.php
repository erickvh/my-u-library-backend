<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role_id);
        $user->save();

        return response()->json([
            'data' => $user,
            'message' => 'Successfully created user'
        ], 201);
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Bad credentials'
            ], 401);
        }
        $body = [
            'message' => 'success',
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ];

        return response()->json($body, 200);
    }


    public function logout(Request $request)
    {

        $cookie = Cookie::forget('jwt');

        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
