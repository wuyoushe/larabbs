<?php

//use Illuminate\Support\Facades\Route;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/2
 * Time: 20:38
 */

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class(if_route('categories.show') && if_route_param('category', $category_id));
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    //return Str::limit($excerpt, $length);
    return \Illuminate\Support\Str::limit($excerpt, $length);
}


















