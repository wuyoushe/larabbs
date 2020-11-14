<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/10/29
 * Time: 11:37
 */

namespace App\Observers;

use App\Models\Link;
use Cache;


class LinkObserver
{
    //在保存时清空cache_key对应的缓存
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }

}
