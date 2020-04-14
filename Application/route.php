<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::rule([
    "/" => "Index/Index/index", //通讯录
    "/login" => "Index/Login/index", //登录页
    "/register" => "Index/Login/register", //注册页
    "/lists" => "Index/Lists/index", //消息列表页
    "/content/:id$" => "Index/Content/index", //聊天内容页
    "/user/" => "Index/User/index", //我的
]);

/** Ajax请求 **/
Route::group("ajax", [
    "/user_info/" => "Index/Ajax/getUserInfo", //获取用户信息
    "/lists/" => "Index/Ajax/getChatLists", //获取聊天消息列表
    "/login/" => "Index/Ajax/setLogin", //登录
    "/register/" => "Index/Ajax/setRegister", //注册
    "/logout/" => "Index/Ajax/setLogout", //退出登录
    "/save_message/" => "Index/Ajax/setSaveMessage", //文本消息进行数据持久化
    "/img_file/" => "Index/Ajax/setChatImg", //上传聊天图片
    "/status/" => "Index/Ajax/setChatStatus", //设置聊天内容状态
]);
/** Ajax请求 **/