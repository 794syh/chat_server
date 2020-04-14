<?php
/**
 * 消息列表页
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Controller;

use think\Db;

class Lists extends Action
{
    public function index()
    {
        $from_id = self::isLogin(true); //发送用户Id

        /** 获取与当前用户相关的聊天信息 **/
        $chat_record = Db::name("chat_record")->field("FromId, ToId, Content, CreateTime, Type")->where("Toid = {$from_id} || FromId = {$from_id}")->order("CreateTime DESC")->select();
        /** 获取与当前用户相关的聊天信息 **/

        /** 组装聊天列表 **/
        $data = [];
        $user_id = [];
        $chat_id = 0;
        if (!empty($chat_record)) {
            foreach ($chat_record as $val) {
                $chat_num = 0;

                /** 判断获取聊天的接收用户 **/
                if ($val["FromId"] != $from_id) {
                    $user_id[$val["FromId"]] = $val["FromId"];
                    $chat_id = $val["FromId"];
                    $chat_num = self::getChatNoreadNum($val["FromId"], $val["ToId"]);
                } else if ($val["ToId"] != $from_id) {
                    $user_id[$val["ToId"]] = $val["ToId"];
                    $chat_id = $val["ToId"];
                }
                /** 判断获取聊天的接收用户 **/

                if (empty($data[$chat_id]) && $chat_id > 0) {
                    if ($val["Type"] == 1) {
                        $val["Content"] = self::getContent($val["Content"]);
                    } else {
                        $val["Content"] = "[图片]";
                    }

                    $data[$chat_id] = [
                        "ChatId" => $chat_id,
                        "ChatNum" => 0,
                        "Content" => $val["Content"],
                        "CreateTime" => self::getTime($val["CreateTime"], 2)
                    ];
                }

                if ($chat_num > 0) {
                    $data[$chat_id]["ChatNum"] = $chat_num;
                }
            }
        }
        /** 组装聊天列表 **/

        /** 获取用户信息 **/
        if (!empty($user_id)) {
            $user_info = Db::name("user")->where(["id" => ["in", implode(",", $user_id)]])->column("UserName, Avatar", "Id");

            if (!empty($user_info)) {
                foreach ($user_info as $val) {
                    $data[$val["Id"]]["UserName"] = trim($val["UserName"]);
                    $data[$val["Id"]]["Avatar"] = !empty($val["Avatar"]) ? trim($val["Avatar"]) : "/upload/avatar/avatar.jpg";
                }
            }
        }
        /** 获取用户信息 **/

        $this->assign("from_id", $from_id);
        $this->assign("data", $data);
        return $this->fetch();
    }

    private static function getChatNoreadNum($from_id, $to_id)
    {
        return Db::name("chat_record")->where(["FromID" => $from_id, "ToId" => $to_id, "Status" => 0])->count("Id");
    }
}