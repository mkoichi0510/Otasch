<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserUpdateController extends Controller
{
    //ユーザーデータの更新
    public function updateUser(UpdateUserRequest $request, User $user)
    {
        $user = Auth::user();
        $input = $request->all();
        $user->fill($input)->save();
        return response($user, 200);
    }
}
