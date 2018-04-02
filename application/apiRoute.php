<?php
/**
 * Api路由
 */
use think\Route;

Route::miss('api/Index/index');
$afterBehavior = ['\app\api\behavior\ApiAuth', '\app\api\behavior\ApiPermission', '\app\api\behavior\RequestFilter'];
Route::rule('api/5ac235ee9a88a','api/Article/lists', 'GET', ['after_behavior' => $afterBehavior]);