<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Auth\AuthenticationException;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyDate = \Cache::get($request->verification_key);

        if(!$verifyDate) {
            abort(403, "验证码已失效");
        }

        //比较验证码是否一致，使用了hash_equals方法（无论字符串是否相等，函数的消耗时间都是恒定的），hash_equals是可防止时序共计的字符串比较，
        if(!hash_equals($verifyDate['code'], $request->verification_code)) {
            //返回401
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone'=> $verifyDate['phone'],
            'password' => $request->password,
        ]);

        //清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->success(new UserResource($user), '注册成功');

        // return new UserResource($user);
    }
}
