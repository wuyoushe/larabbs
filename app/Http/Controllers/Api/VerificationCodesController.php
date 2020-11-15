<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use Illuminate\Http\Request;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        //生成四位随机数，左侧补0
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

        //测试环境不必每次都发短信
        if(!app()->environment('production')) {
            $code = '1234';
        }else{
            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ]
                ]);
            } catch(\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        //做接口的思路与我们做网页应用不同，网站处理验证码，通常是存入session，注册的时候验证用户输入的验证码与session中的验证码是否
        //相同，但是接口是无状态的，相互独立，处理这种相互关联，有先后调用顺序的接口时，常常是第一个接口返回一个随机的key,利用这个key去调用
        //第二个接口
        $key = 'verificationCode_' . Str::random(15);
        //设置过期时间
        $expiredAt = now()->addMinutes(5);
        //缓存验证码 5分钟过期
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->setStatusCode(201)->success(
            [
                'key' => $key,
                'code' => $code,
                'expired_at' => $expiredAt->toDateTimeString(),
            ],
            '返回成功'
        );
    }
}
