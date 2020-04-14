<?php
/**
 * 我的
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Controller;

use think\Db;

class User extends Action
{
    public function index()
    {
        $from_id = self::isLogin(true); //发送用户Id

        $result = Db::name("User")->field("UserName, Avatar")->where("Id", $from_id)->find();

        if (empty($result)) {
            echo '<script type="text/javascript">location="/login/";</script>';
            exit;
        }

        $data = [
            "UserName" => trim($result["UserName"]),
            "Avatar" => !empty($result["Avatar"]) ? trim($result["Avatar"]) : "/upload/avatar/avatar.jpg",
        ];

        $this->assign("data", $data);
        return $this->fetch();
    }
}