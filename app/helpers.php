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