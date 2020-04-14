<?php
/**
 * 聊天内容页
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Controller;

use think\Db;

class Content extends Action
{
    public function index()
    {
        $from_id = self::isLogin(true); //发送用户Id
        $to_id = input("id"); //接收用户Id

        /** 获取用户信息 **/
        $user = Db::name("user")->where(["id" => ["in", "$from_id, $to_id"]])->column("UserName, Avatar", "Id");
        /** 获取用户信息 **/

        /** 判断组装用户信息 **/
        $from_info = [];
        $to_info = [];
        if (!empty($user)) {
            if (!empty($user[$from_id])) {
                $from_info = [
                    "Id" => $from_id,
                    "UserName" => $user[$from_id]["UserName"],
                    "Avatar" => $user[$from_id]["Avatar"]
                ];
            } else {
                $this->error("信息错误", "/404.html");
            }

            if (!empty($user[$to_id])) {
                $to_info = [
                    "Id" => $to_id,
                    "UserName" => $user[$to_id]["UserName"],
                    "Avatar" => $user[$to_id]["Avatar"]
                ];
            } else {
                $this->error("信息错误", "/404.html");
            }
        } else {
            $this->error("信息错误", "/404.html");
        }
        /** 判断组装用户信息 **/

        /** 获取聊天记录 **/
        $count = Db::name("chat_record")
            ->where("(FromId = :from_id AND ToId = :to_id) || (FromId = :to_id1 AND ToId = :from_id1)",
                ["from_id" => $from_id, "to_id" => $to_id, "from_id1" => $from_id, "to_id1" => $to_id])
            ->count();

        if ($count > 10) {
            $record = Db::name("chat_record")
                ->where("(FromId = :from_id AND ToId = :to_id) || (FromId = :to_id1 AND ToId = :from_id1)",
                    ["from_id" => $from_id, "to_id" => $to_id, "from_id1" => $from_id, "to_id1" => $to_id])
                ->limit($count - 10, 10)
                ->order("id")
                ->select();
        } else {
            $record = Db::name("chat_record")
                ->where("(FromId = :from_id AND ToId = :to_id) || (FromId = :to_id1 AND ToId = :from_id1)",
                    ["from_id" => $from_id, "to_id" => $to_id, "from_id1" => $from_id, "to_id1" => $to_id])
                ->order("id")
                ->select();
        }
        /** 获取聊天记录 **/

        /** 组装聊天内容 **/
        $chat_record = [];
        if (is_array($record) && count($record) > 0) {
            foreach ($record as $val) {
                if ($from_id == $val["FromId"]) {
                    $cate = 1;
                    $name = $from_info["UserName"];
                    $avatar = !empty($from_info["Avatar"]) ? $from_info["Avatar"] : "/upload/avatar/avatar.jpg";
                } else {
                    $cate = 2;
                    $name = $to_info["UserName"];
                    $avatar = !empty($to_info["Avatar"]) ? $to_info["Avatar"] : "/upload/avatar/avatar.jpg";
                }

                if ($val["Type"] == 1) {
                    $val["Content"] = self::getContent($val["Content"]);
                }

                $chat_record[] = [
                    "Cate" => $cate,
                    "Type" => $val["Type"],
                    "UserName" => $name,
                    "Avatar" => $avatar,
                    "Content" => $val["Content"],
                    "Time" => self::getTime($val["CreateTime"])
                ];
            }
        }
        /** 组装聊天内容 **/

        /** 更新当前用户全部聊天内容为已读状态 **/
        Db::name("chat_record")->where(["FromId" => $to_id, "ToId" => $from_id, "Status" => 0])->update(["Status" => 1]);
        /** 更新当前用户全部聊天内容为已读状态 **/

        $this->assign("from_info", $from_info);
        $this->assign("to_info", $to_info);
        $this->assign("chat_record", $chat_record);

        return $this->fetch();
    }
}
