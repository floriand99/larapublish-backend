<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function update(Request $request, User $user){
        $user->fill($request->only([
            'name', 'image'
        ]));
        if($request->password)
            $user->password = \Hash::make($request->password);
        if(strlen($request->bio) < 300)
            $user->bio = $request->bio;
        $user->save();
        return $user;
    }

    public function show(User $user){
        return $user;
    }
}
