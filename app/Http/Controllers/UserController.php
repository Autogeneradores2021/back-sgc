<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $data = request(['email', 'password', 'name']);

        if (!$data) {
            return response()->json(['error' => 'Incomplete data'], 400);
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return response()->json([
            'message' => 'successfuly',
            'user' => $user
        ], 200);
    }
}
