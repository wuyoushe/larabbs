<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(3);
//        return $this->success($users);
        return UserResource::collection($users);
    }

    /**
     * 返回一条信息
     */
    public function show(User $user)
    {
//        return $this->success($user);
        return $this->success(new UserResource($user));
    }

    /**
     * @param UserRequest $request
     * @return string
     * 用户注册
     */
    public function store(UserRequest $request)
    {
        User::create($request->all());
        return $this->setStatusCode(201)->success('用户注册成功');
    }

    public function login(Request $request)
    {
        $token = Auth::guard('api')->attempt(['name' => $request->name, 'password' => $request->password]);
        if($token) {
            return $this->setStatusCode(201)->success(['token' =>'bearer '. $token]);
        }
        return $this->failed('账号或密码错误', 401);
    }

    //用户退出
    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->success('退出成功...');
    }

    //返回当前登录用户信息
    public function info()
    {
        $user = Auth::guard('api')->user();
        return $this->success(new UserResource($user));
    }
}
