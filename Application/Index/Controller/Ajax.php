<?php
/**
 * Ajax数据请求
 *
 * @author 794syh940@gmail.com
 */

namespace app\Index\Controller;

use app\Index\Library\Passport\LoginMethod;
use think\Db;
use think\Request;

class Ajax extends Action
{
    /**
     * 获取用户信息
     *
     * <pre>
     * GET参数
     * from_id: 发送用户Id
     * to_id: 接收用户Id
     * </pre>
     *
     */
    public function getUserInfo()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $from_id = self::isLogin();
            $to_id = intval($_GET["to_id"]);

            $user = Db::name("user")->where(["id" => ["in", "$from_id, $to_id"]])->column("UserName, Avatar", "id");

            if (!empty($user)) {
                $data = [
                    "From" => [
                        "UserName" => $user[$from_id]["UserName"],
                        "Avatar" => !empty($user[$from_id]["Avatar"]) ? $user[$from_id]["Avatar"] : "/upload/avatar/avatar.jpg"
                    ],
                    "To" => [
                        "UserName" => $user[$to_id]["UserName"],
                        "Avatar" => !empty($user[$to_id]["Avatar"]) ? $user[$to_id]["Avatar"] : "/upload/avatar/avatar.jpg"
                    ],
                ];

                $this->success("成功", "", $data);
            }

            $this->error("数据不存在");
        }

        $this->error("请求格式错误");
    }

    /**
     * 获取聊天消息列表
     *
     * <pre>
     * GET参数
     * from_id: 发送用户Id
     * </pre>
     *
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChatLists()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $from_id = self::isLogin();

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
                    } else {
                        if ($val["ToId"] != $from_id) {
                            $user_id[$val["ToId"]] = $val["ToId"];
                            $chat_id = $val["ToId"];
                        }
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
                $user_info = Db::name("user")->where([
                    "id" => [
                        "in",
                        implode(",", $user_id)
                    ]
                ])->column("UserName, Avatar",
                    "Id");

                if (!empty($user_info)) {
                    foreach ($user_info as $val) {
                        $data[$val["Id"]]["UserName"] = trim($val["UserName"]);
                        $data[$val["Id"]]["Avatar"] = !empty($val["Avatar"]) ? trim($val["Avatar"]) : "/upload/avatar/avatar.jpg";
                    }
                }
            }
            /** 获取用户信息 **/

            $this->success("成功", "", $data);
        }
    }

    /**
     * 文本消息进行数据持久化
     *
     * <pre>
     * POST参数
     * FromId: 发送用户Id
     * ToId: 接收用户Id
     * Data: 聊天内容
     * IsRead: 状态
     * StrToTime: 发送时间(时间戳)
     * </pre>
     *
     * <pre>
     * 成功
     * </pre>
     *
     * @return string|void 返回JSON数据
     */
    public function setSaveMessage()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $from_id = self::isLogin();
            $to_id = intval($_POST["ToId"]);
            $content = trim($_POST["Data"]);
            $create_time = intval($_POST["StrToTime"]);

            if (empty($from_id)) {
                $this->error("FromId不能为空");
            }

            if (empty($to_id)) {
                $this->error("ToId不能为空");
            }

            if (empty($content)) {
                $this->error("Content不能为空");
            }

            if (empty($create_time)) {
                $this->error("StrToTime不能为空");
            }

            $data = [
                "FromId" => $from_id,
                "ToId" => $to_id,
                "Content" => $content,
                "Type" => 1,
                "Status" => 0,
                "CreateTime" => $create_time,
            ];

            $result = Db::name("chat_record")->insert($data);

            if ($result) {
                $this->success("添加成功");
            } else {
                $this->error("添加失败");
            }
        }

        $this->error("请求格式错误");
    }

    /**
     * 上传聊天图片
     *
     * <pre>
     * FILES参数
     * file: 文件
     *
     * POST参数
     * from_id: 发送用户Id
     * to_id: 接收用户Id
     * </pre>
     */
    public function setChatImg()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $file = $_FILES['file'];
            $form_id = self::isLogin();
            $to_id = intval($_POST["to_id"]);

            $uniqid = uniqid();
            $num = mt_rand(1, 99);
            $hash1 = sprintf("%02x", $form_id % 256);
            $hash2 = substr(md5($to_id . "_" . $uniqid . $num), 0, 2);

            $file_directory = ROOT_PATH . "public";
            $file_path = "/upload/chat/content/" . date("Ym") . "/" . date("d") . "/" . $hash1 . "/" . $hash2 . "/";
            $file_name = $uniqid . $num . ".jpg";

            if (!is_dir($file_directory . $file_path)) {
                mkdir($file_directory . $file_path, 0777, true);
            }

            $res = move_uploaded_file($file["tmp_name"], $file_directory . $file_path . $file_name);

            if ($res) {
                $date = time();
                $data = [
                    "FromId" => $form_id,
                    "ToId" => $to_id,
                    "Content" => $file_path . $file_name,
                    "Type" => 2,
                    "Status" => 0,
                    "CreateTime" => $date,
                ];

                $result = Db::name("chat_record")->insertGetId($data);

                if ($result) {
                    $this->success("添加成功", "", ["ImgPath" => $file_path . $file_name, "Time" => date("H:i", $date)]);
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }

    /**
     * 设置聊天内容状态
     *
     * <pre>
     * POST参数
     * from_id: 发送用户Id
     * to_id: 接收用户Id
     * </pre>
     *
     * <pre>
     * 成功
     * </pre>
     *
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setChatStatus()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $from_id = self::isLogin();
            $to_id = intval($_POST["to_id"]);

            $result = Db::name("chat_record")->where([
                "FromId" => $to_id,
                "ToId" => $from_id,
                "Status" => 0
            ])->update(["Status" => 1]);

            if ($result) {
                $this->success("添加成功");
            } else {
                $this->error("添加失败");
            }
        }
    }

    /**
     * 设置登录信息
     */
    public function setLogin()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $mobile = trim($_POST["mobile"]);
            $password = trim($_POST["password"]);
            $code = trim($_POST["code"]);

            if (empty($mobile)) {
                $this->error("手机号不能为空", "", ["status" => 201]);
            }

            if (empty($password)) {
                $this->error("密码不能为空", "", ["status" => 202]);
            }

            if (empty($code)) {
                $this->error("验证码不能为空", "", ["status" => 203]);
            }

            $result = LoginMethod::setLogin($mobile, $password, $code, true);

            if ($result["status"] == 200) {
                $this->success("登录成功", "/", ["status" => 200]);
            } else {
                $this->error($result["msg"], "", ["status" => $result["status"]]);
            }
        }
    }

    public function setRegister()
    {
        //判断请求是不是Ajax请求
        if (Request::instance()->isAjax()) {
            $user_name = urldecode(trim($_POST["user_name"]));
            $mobile = trim($_POST["mobile"]);
            $password = trim($_POST["password"]);
            $code = trim($_POST["code"]);
            $email = trim($_POST["email"]);

            if (empty($user_name)) {
                $this->error("用户名不能为空", "", ["status" => 201]);
            }

            if (empty($mobile)) {
                $this->error("手机号不能为空", "", ["status" => 202]);
            }

            if (empty($password)) {
                $this->error("密码不能为空", "", ["status" => 203]);
            }

            if (empty($code)) {
                $this->error("验证码不能为空", "", ["status" => 204]);
            }

            if (empty($email)) {
                $this->error("邮箱不能为空", "", ["status" => 205]);
            }

            $result = LoginMethod::setRegister($user_name, $mobile, $password, $code, $email, true, true);

            if ($result["status"] == 200) {
                $this->success("注册成功", "/", ["status" => 200]);
            } else {
                $this->error($result["msg"], "", ["status" => $result["status"]]);
            }
        }
    }

    public function setLogout()
    {
        LoginMethod::logout();
//print_r(session());die;
        $this->success("退出成功", "/", ["status" => 200]);
    }

    /**
     * 获取聊天未读消息数
     *
     * @param integer $from_id 发送用户Id
     * @param integer $to_id 接收用户Id
     * @return int|string
     * @throws \think\Exception
     */
    private static function getChatNoreadNum($from_id, $to_id)
    {
        return Db::name("chat_record")->where(["FromID" => $from_id, "ToId" => $to_id, "Status" => 0])->count("Id");
    }
}