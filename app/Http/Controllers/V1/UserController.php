<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'nickname' => $request['nickname'],
            'password' => $request['password']
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel8Auth')->accessToken;
            $user = auth()->user();
            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function index()
    {
        return User::all();
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = validator($request->only('name', 'email', 'nickname', 'password'), [
            'name' => 'required|string',
            'email' => 'email|unique:users',
            'nickname' => 'required|string',
            'password' => 'required|string|min:5'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors()->all(), 400);
        }

        $data = $request->only('name', 'email', 'nickname', 'password');
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nickname' => $data['nickname'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('Laravel8Auth')->accessToken;
        $user->assignRole('user');

        return response()->json(['token' => $token], 201);
    }

    public function show($id)
    {
        return User::where('id', $id)->get();
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validate = validator($request->only('name', 'email', 'nickname', 'password'), [
            'name' => 'string',
            'email' => "email|unique:users,email,$id,id",
            'nickname' => 'string'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors()->all(), 400);
        }

        $user = User::findOrFail($id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->nickname = $request['nickname'];
        $user->save();

        return response()->json($user, 201);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'The user has been deleted'], 200);
    }
}
