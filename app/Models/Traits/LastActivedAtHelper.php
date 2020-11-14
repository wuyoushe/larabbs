<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->hash_prefix . $date;

        // 字段名称，如：user_1
        $field = $this->field_prefix . $this->id;

        // 当前时间，如：2017-10-21 08:35:15
        $now = Carbon::now()->toDateTimeString();

        // 数据写入 Redis ，字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        //获取昨天的日期，格式如 2017-10-24
        $yesterday_date = Carbon::yesterday()->toDateString();

        // $yesterday_date = Carbon::now()->toDateString();

        //Redis哈希表的命名
        $hash = $this->hash_prefix . $yesterday_date;

        //从Redis中获取所有哈希表里的数据
        $dates = Redis::hGetAll($hash);

        //遍历，并同步到数据库中
        foreach ($dates as $user_id => $actived_at) {
            //会将 `user_id` 转换为1
            $user_id = str_replace($this->field_prefix,  '', $user_id);

            //只有当用户存在时，才更新到数据库中
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        //以数据库为中心的存储，同步后就删除缓存
        Redis::del($hash);
    }
}
