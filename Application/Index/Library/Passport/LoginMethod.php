<?php
/**
 * 登录注册
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Library\Passport;

use app\Index\Library\Encrypt\Blowfish;
use think\Db;

class LoginMethod
{
    /**
     * 登录
     *
     * @param string $mobile 手机号
     * @param string $password 密码
     * @param string $code 验证码
     * @param bool $auto_login 是否保持登录 true:保持 false:不保持
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setLogin($mobile, $password, $code, $auto_login = true)
    {
        if (empty($mobile)) {
            return ["status" => 220, "msg" => "手机号不能为空"];
        }

        if (empty($password)) {
            return ["status" => 211, "msg" => "密码不能为空"];
        }

        if (empty($code)) {
            return ["status" => 222, "msg" => "验证码不能为空"];
        }

        if (!preg_match("@^1[0-9]{10}$@", $mobile)) {
            return ['status' => 223, 'msg' => '手机号码错误'];
        }

        if (!captcha_check($code)) { //验证失败
            return ["status" => 224, "msg" => "验证码错误"];
        }

        $result = self::decodePassword($password);
        if ($result['status'] != 200) {
            return ['status' => $result['status'], 'msg' => $result['msg']];
        } else {
            $password = $result['password'];
        }

        $user = self::checkMobileExists($mobile);

        if (empty($user["UserId"]) || empty($user["Password"])) {
            return ['status' => 225, 'msg' => '用户名或者密码不正确'];
        }

        if (!password_verify($password, $user['Password'])) {
            return ['status' => 241, 'msg' => '用户名或者密码不正确'];
        }

        self::setSessionCookie($user, $auto_login);
        self::updateUserLoginInfo($user["UserId"]);

        return ['status' => 200];
    }

    /**
     * 注册
     *
     * @param string $username 用户名
     * @param string $mobile 手机号
     * @param string $password 密码
     * @param string $code 验证码
     * @param string $email 邮箱
     * @param bool $login 注册成功是否登录 true:登录 false:不登录
     * @param bool $auto_login 是否保持登录 true:保持 false:不保持
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setRegister($username, $mobile, $password, $code, $email, $login = false, $auto_login = true)
    {
        if (empty($mobile)) {
            return ["status" => 220, "msg" => "手机号不能为空"];
        }

        if (empty($password)) {
            return ["status" => 211, "msg" => "密码不能为空"];
        }

        if (empty($username)) {
            return ["status" => 212, "msg" => "用户名不能为空"];
        }

        if (empty($code)) {
            return ["status" => 223, "msg" => "验证码不能为空"];
        }

        if (empty($email)) {
            return ["status" => 224, "msg" => "邮箱不能为空"];
        }

        if (!preg_match("@^1[0-9]{10}$@", $mobile)) {
            return ['status' => 225, 'msg' => '手机号码错误'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 226, 'msg' => '邮箱格式错误'];
        }

        $res = self::decodePassword($password);
        if ($res['status'] != 200) {
            return ['status' => $res['status'], 'msg' => $res['msg']];
        } else {
            $password = $res['password'];
        }

        if (!captcha_check($code)) { //验证失败
            return ["status" => 227, "msg" => "验证码错误"];
        }

        if (Db::name("User")->where("Mobile", $mobile)->find()) {
            return ["status" => 228, "msg" => "手机号已注册"];
        }

        $data = [
            "UserName" => $username,
            "Mobile" => $mobile,
            "Password" => password_hash($password, PASSWORD_DEFAULT),
            "Email" => $email,
            "LoginIP" => $_SERVER['REMOTE_ADDR'],
            "LoginTime" => time(),
            "CreateTime" => time(),
        ];

        $result = Db::name("User")->insertGetId($data);

        if ($result) {
            if ($login) {
                self::setSessionCookie(["UserId" => $result, "UserName" => $data["UserName"]], $auto_login);
            }

            return ["status" => 200];
        } else {
            return ["status" => 229, "msg" => "注册失败"];
        }
    }

    /**
     * 退出登录
     */
    public static function logout()
    {
        session('UserId',null);
        session('UserName',null);
        session('[destroy]');

        cookie("_auth", null);
        cookie("_uid", null);
    }

    private static function updateUserLoginInfo($user_id)
    {
        $data = [
            "LoginIP" => $_SERVER['REMOTE_ADDR'],
            "LoginTime" => time()
        ];

        Db::name("User")->where("Id", $user_id)->update($data);
    }

    /**
     * 解密密码
     *
     * @param string $pwd 加密密码[RSA加密]
     *
     * @return array    返回，200:成功，201:失败 ['status' => 200|201, 'msg' => '', 'pwd' => '']
     */
    private static function decodePassword($pwd)
    {
        if (empty($pwd)) {
            return ['status' => 230, 'msg' => '密码不能为空'];
        }
        $decode = private_decode($pwd);
        if (empty($decode)) {
            return ['status' => 231, 'msg' => '密码解析失败'];
        }

        list($time, $pwd) = explode('|', $decode, 2);
        $temp = time() - intval($time);
        if ($temp >= 600 || $temp <= -600) { // 允许10分钟的误差范围
            return ['status' => 232, 'msg' => '请检查您的时间设置是否正确'];
        } else {
            $min_pwd_length = 6;
            if (strlen($pwd) < $min_pwd_length) {
                return ['status' => 233, 'msg' => "密码长度不少于6位"];
            } else {
                return ['status' => 200, 'password' => $pwd];
            }
        }
    }

    /**
     * 根据手机号检查用户是否存在
     *
     * @param string $mobile 手机号
     *
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private static function checkMobileExists($mobile)
    {
        $user = Db::name("user")->where("Mobile", $mobile)->field("Id, Password, UserName")->find();

        return ["UserId" => $user["Id"], "UserName" => $user["UserName"], 'Password' => $user["Password"]];
    }

    /**
     * 设置登陆相关的信息
     *
     * @param $user_info
     * @param $auto_login
     */
    public static function setSessionCookie($user_info, $auto_login)
    {
        self::initSession($user_info);
        self::initCookie($user_info['UserId'], $auto_login);
    }

    /**
     * 初始化session
     *
     * @param array $user_info 用户信息
     * @return void
     */
    private static function initSession($user_info)
    {
        session('UserId', $user_info['UserId']);
        session('UserName', $user_info['UserName']);
    }

    /**
     * 初始化cookie
     *
     * @param int  $uid 用户ID
     * @param bool $auto_login 是否保持登录
     */
    private static function initCookie($uid, $auto_login)
    {
        $expire = 0;
        if ($auto_login) {
            $expire = time() + 31536000;
        }

        cookie("_auth", self::cookie_encode($uid), $expire);
        cookie("_uid", $uid, $expire);
    }

    /**
     * 加密 Cookie
     *
     * @param mixed $data 待加密的数据
     * @return string            返回一个包含初始向量(IV)、加密数据的字符串，均以十六进制表示，前 16 位为IV
     */
    private static function cookie_encode($data)
    {
        $checksum = sprintf("%u", crc32($_SERVER['HTTP_USER_AGENT']));
        $serialized = $checksum . '|' . json_encode($data);

        return Blowfish::encode($serialized);
    }

    /**
     * 解密 Cookie
     *
     * @param string $encoded_hex 已加密数据
     * @return mixed                    原始数据
     */
    public static function cookie_decode($encoded_hex)
    {
        $encoded_hex = trim($encoded_hex);
        $serialized = Blowfish::decode($encoded_hex);
        if ($serialized === false) {
            return false;
        }
        $arr = explode('|', $serialized);
        if (count($arr) != 2) {
            return false;
        }
        $checksum = sprintf("%u", crc32($_SERVER['HTTP_USER_AGENT']));
        if ($checksum != $arr[0]) {
            return false;
        }

        return json_decode($arr[1], true);
    }
}