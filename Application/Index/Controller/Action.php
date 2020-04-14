<?php
/**
 * 公共类
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Controller;

use app\Index\Library\Passport\LoginMethod;
use think\Controller;
use think\Db;

class Action extends Controller
{
    public function _initialize()
    {
        self::_initUserState();
    }

    /**
     * 登录判断和检查
     *
     * @param bool/string $redirect         转跳模式.
     *                                             auto: ajax请求返回uid, 反之自动转跳到登录页.
     *                                             true: 强制转跳到登录页
     *                                             false: 始终不转跳, 默认.
     * @return bool/integer                 未登录返回false, 登录返回安全登录UID
     */
    public static function isLogin($redirect = false)
    {
        $uid = (int)session("UserId") > 0 ? session("UserId") : 0;

        if ($uid < 1) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $redirect === 'auto') {
                $redirect = false;
            } else {
                if ($redirect === 'auto') {
                    $redirect = true;
                }
            }

            if ($redirect === true) {
                // 暂时到登录页
                echo '<script type="text/javascript">location="/login/";</script>';
                exit;
            } else {
                if ($redirect === false) {
                    return false;
                }
            }
        }

        return $uid;
    }

    /**
     * 时间转换
     *
     * @param integer $time 时间戳
     * @param integer $cate 时间显示类型 1:年月日"中文"显示并连接时分秒 2:年月日"/"显示不连接时分秒
     * @return false|string
     */
    public static function getTime($time, $cate = 1)
    {
        //获取时间距今天的天数
        $ago_day = floor((strtotime(date('Y-m-d 23:59:59')) - $time) / 86400);

        //判断时间是上午还是下午
        $hour = date("H", $time);
        if ($hour <= 4) {
            $type = "凌晨";
        } elseif ($hour > 4 && $hour < 12) {
            $type = "上午";
        } else {
            $type = "下午";
        }

        if ($ago_day == 0) { //当天
            $date = $type . date("h:i", $time);
        } elseif ($ago_day == 1) { //昨天
            if ($cate == 1) {
                $date = "昨天 " . $type . date("h:i", $time);
            } else {
                $date = "昨天";
            }
        } elseif ($ago_day > 1 && $ago_day < 7) { //一周内
            $weekarray = ['日', '一', '二', '三', '四', '五', '六'];

            if ($cate == 1) {
                $date = "星期" . $weekarray[date("w", $time)] . " " . $type . date("h:i", $time);
            } else {
                $date = "星期" . $weekarray[date("w", $time)];
            }
        } else { //一周之前
            if ($cate == 1) {
                $date = date("Y年m月d日", $time) . " " . $type . date("h:i", $time);
            } else {
                $date = date("Y/m/d", $time);
            }
        }

        return $date;
    }

    /**
     * 替换Emoji表情
     *
     * @param string $content 内容
     * @return string|string[]|null
     */
    public static function getContent($content)
    {
        return preg_replace("/\[em_([0-9]*)\]/i", "<img src=\"/static/arclist/$1.gif\"/>",
            str_replace(["<", ">", "\n"], ["&lt;", "&gt;", "<br/>"], $content));
    }

    /**
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private static function _initUserState()
    {
        $user_id = self::isLogin(false);
        if (!empty($user_id)) {
            return false;
        } else {
            if (null !== cookie("_auth")) {
                $user_id = LoginMethod::cookie_decode(cookie("_auth"));

                if (is_numeric($user_id) && $user_id > 0) {
                    $result = Db::name("User")->field("Id, UserName")->where("Id", $user_id)->find();

                    if (!is_array($result) || empty($result)) {
                        return false;
                    }

                    $data = [
                        "UserId" => $result["Id"],
                        "UserName" => $result["UserName"]
                    ];

                    //验证串
                    LoginMethod::setSessionCookie($data, true);
                } else {
                    LoginMethod::logout();
                }
            } else {
                return false;
            }
        }
    }
}