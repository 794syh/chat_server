<?php
/**
 * 首页
 *
 * @author syh <794syh940@gmail.com>
 */

namespace app\Index\Controller;

use think\Db;

class Index extends Action
{
    public function index()
    {
        $from_id = self::isLogin(true); //发送用户Id

        $result = Db::name("user")->field("Id, UserName, Avatar")->where("Id", "NEQ",
            $from_id)->order("Id DESC")->select();

        $screen = [];
        $data = [];
        foreach ($result as $val) {
            $hierarchy = self::getFirstChar($val["UserName"]);

            $screen[$hierarchy["id"]] = trim($hierarchy["hierarchy"]);

            $data[$hierarchy["id"]]["Hierarchy"] = trim($hierarchy["hierarchy"]);
            $data[$hierarchy["id"]]["List"][] = [
                "Id" => intval($val["Id"]),
                "Name" => trim($val["UserName"]),
                "Avatar" => !empty($val["Avatar"]) ? trim($val["Avatar"]) : "/upload/avatar/avatar.jpg"
            ];
        }

        ksort($screen);
        ksort($data);

        $this->assign("screen", $screen);
        $this->assign("data", $data);

        return $this->fetch();
    }




    /**
     * @param $name
     * @return array|bool|string
     */
    public function getFirstChar($name)
    {
        $surname = mb_substr($name, 0, 3); //获取名字的姓
        $name = iconv('UTF-8', 'gb2312', $surname); //将UTF-8转换成GB2312编码

        if (ord($surname) > 128) { //汉字开头，汉字没有以U、V开头的
            $asc = ord($name{0}) * 256 + ord($name{1}) - 65536;
            if ($asc >= -20319 and $asc <= -20284) {
                return ["id" => 65, "hierarchy" => "A"];
            }
            if ($asc >= -20283 and $asc <= -19776) {
                return ["id" => 66, "hierarchy" => "B"];
            }
            if ($asc >= -19775 and $asc <= -19219) {
                return ["id" => 67, "hierarchy" => "C"];
            }
            if ($asc >= -19218 and $asc <= -18711) {
                return ["id" => 68, "hierarchy" => "D"];
            }
            if ($asc >= -18710 and $asc <= -18527) {
                return ["id" => 69, "hierarchy" => "E"];
            }
            if ($asc >= -18526 and $asc <= -18240) {
                return ["id" => 70, "hierarchy" => "F"];
            }
            if ($asc >= -18239 and $asc <= -17760) {
                return ["id" => 71, "hierarchy" => "G"];
            }
            if ($asc >= -17759 and $asc <= -17248) {
                return ["id" => 72, "hierarchy" => "H"];
            }
            if ($asc >= -17247 and $asc <= -17418) {
                return ["id" => 73, "hierarchy" => "I"];
            }
            if ($asc >= -17417 and $asc <= -16475) {
                return ["id" => 74, "hierarchy" => "J"];
            }
            if ($asc >= -16474 and $asc <= -16213) {
                return ["id" => 75, "hierarchy" => "K"];
            }
            if ($asc >= -16212 and $asc <= -15641) {
                return ["id" => 76, "hierarchy" => "L"];
            }
            if ($asc >= -15640 and $asc <= -15166) {
                return ["id" => 77, "hierarchy" => "M"];
            }
            if ($asc >= -15165 and $asc <= -14923) {
                return ["id" => 78, "hierarchy" => "N"];
            }
            if ($asc >= -14922 and $asc <= -14915) {
                return ["id" => 79, "hierarchy" => "O"];
            }
            if ($asc >= -14914 and $asc <= -14631) {
                return ["id" => 80, "hierarchy" => "P"];
            }
            if ($asc >= -14630 and $asc <= -14150) {
                return ["id" => 81, "hierarchy" => "Q"];
            }
            if ($asc >= -14149 and $asc <= -14091) {
                return ["id" => 82, "hierarchy" => "R"];
            }
            if ($asc >= -14090 and $asc <= -13319) {
                return ["id" => 83, "hierarchy" => "S"];
            }
            if ($asc >= -13318 and $asc <= -12839) {
                return ["id" => 84, "hierarchy" => "T"];
            }
            if ($asc >= -12838 and $asc <= -12557) {
                return ["id" => 87, "hierarchy" => "W"];
            }
            if ($asc >= -12556 and $asc <= -11848) {
                return ["id" => 88, "hierarchy" => "X"];
            }
            if ($asc >= -11847 and $asc <= -11056) {
                return ["id" => 89, "hierarchy" => "Y"];
            }
            if ($asc >= -11055 and $asc <= -10247) {
                return ["id" => 90, "hierarchy" => "Z"];
            }
        } else {
            if (ord($name) >= 48 and ord($name) <= 57) { //数字开头
                switch (iconv_substr($name, 0, 1, 'utf-8')) {
                    case 1:
                        return ["id" => 89, "hierarchy" => "Y"];
                    case 2:
                        return ["id" => 69, "hierarchy" => "E"];
                    case 3:
                        return ["id" => 83, "hierarchy" => "S"];
                    case 4:
                        return ["id" => 83, "hierarchy" => "S"];
                    case 5:
                        return ["id" => 87, "hierarchy" => "W"];
                    case 6:
                        return ["id" => 76, "hierarchy" => "L"];
                    case 7:
                        return ["id" => 81, "hierarchy" => "Q"];
                    case 8:
                        return ["id" => 66, "hierarchy" => "B"];
                    case 9:
                        return ["id" => 74, "hierarchy" => "J"];
                    case 0:
                        return ["id" => 76, "hierarchy" => "L"];
                }
            } else {
                if (ord($name) >= 65 and ord($name) <= 90) { //大写英文开头
                    return ["id" => ord($name), "hierarchy" => substr($name, 0, 1)];
                } else {
                    if (ord($name) >= 97 and ord($name) <= 122) { //小写英文开头
                        return ["id" => ord($name) - 32, "hierarchy" => strtoupper(substr($name, 0, 1))];
                    } else {
                        return ["id" => 100, "hierarchy" => "#"];
                    }
                }
            }
        }
    }
}