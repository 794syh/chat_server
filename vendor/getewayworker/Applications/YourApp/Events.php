<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        //申请绑定用户ID
        Gateway::sendToClient($client_id, json_encode([
            "Type" => "init",
            "Id" => $client_id,
        ]));
    }

    /**
     * 当客户端发来消息时触发
     *
     * @param int   $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        // JSON转数组
        $message_data = json_decode($message, true);

        // 判断数据是否为空
        if (empty($message_data)) {
            return;
        }

        switch ($message_data['type']) {
            case "say": //对用户发送文本消息
                $from_id = intval($message_data["from_id"]); //发送用户Id
                $to_id = intval($message_data["to_id"]); //接收用户Id
                $text = trim(htmlspecialchars($message_data["data"])); //消息内容

                //输出给客户端的数据
                $data = [
                    "Data" => $text,
                    "FromId" => $from_id,
                    "ToId" => $to_id,
                    "Time" => date('H:i'),
                    "StrToTime" => time(),
                ];

                //判断用户是否在线
                if (Gateway::isUidOnline($to_id)) {
                    //数据为文本类型
                    $data['Type'] = "text";

                    //发送消息至客户端
                    Gateway::sendToUid($to_id, json_encode($data));
                    $data['IsRead'] = 1;
                } else {
                    $data['IsRead'] = 0;
                }

                //数据持久化类型
                $data['Type'] = "save";

                //发送消息至客户端
                Gateway::sendToUid($from_id, json_encode($data));

                break;
            case "bind": //将$client_id和用户Id进行绑定
                // 绑定Id
                $from_id = $message_data["from_id"];
                Gateway::bindUid($client_id, $from_id);

                break;
            case "online": //判断接收用户是否在线
                $from_id = intval($message_data["from_id"]); //发送用户Id
                $to_id = intval($message_data["to_id"]); //接收用户Id

                //判断接收用户是否在线
                $result = Gateway::isUidOnline($to_id);

                //输出给客户端的数据
                $data = [
                    "Type" => "online",
                    "Status" => $result //1:在线 0:不在线
                ];

                //发送消息至客户端
                Gateway::sendToUid($from_id, json_encode($data));

                break;
            case "img": //对用户发送图片消息
                $from_id = intval($message_data["from_id"]); //发送用户Id
                $to_id = intval($message_data["to_id"]); //接收用户Id
                $img = trim($message_data["data"]); //图片路径

                //输出给客户端的数据
                $data = [
                    "Type" => "img",
                    "FromId" => $from_id,
                    "ToId" => $to_id,
                    "ImgPath" => $img,
                    "Time" => date('H:i'),
                ];

                //发送消息至客户端
                Gateway::sendToUid($to_id, json_encode($data));

                break;
            default:
                break;
        }
    }

    /**
     * 当用户断开连接时触发
     *
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        // 向所有人发送
//        GateWay::sendToAll("$client_id logout\r\n");
    }
}
