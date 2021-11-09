<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserUpdateController extends Controller
{
    
    //更新処理
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = Auth::user();
        $input = $request->all();
        Log::debug($input);
        $user->fill($input)->save();
        return response($user, 200);
    }
}
