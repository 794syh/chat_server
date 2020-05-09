/** 加载QQFace **/
$(function () {
    $('#emotion').qqFace({
        assign: 'content_input',
        path: '/static/arclist/'	//表情存放的路径
    });
});
/** 加载QQFace **/

(function () {
    /** 连接Sockect服务器 **/
    var ws = new WebSocket("ws://127.0.0.1:8282");
    /** 连接Sockect服务器 **/

    /** 接收Sockect服务器发送的消息 **/
    ws.onmessage = function (e) {
        var message = JSON.parse(e.data);//JSON转数组

        switch (message.Type) {
            case "text": //接收为文本消息
                /** 在聊天列表写入接收的文本消息 **/
                if (to_id == message.FromId) { //只接收当前聊天用户的消息
                    $(".index-ul").append('<div class="index-li-left display-block">\n' +
                        '                <div class="index-img left">\n' +
                        '                    <img src="' + to_avatar + '"/>\n' +
                        '                </div>\n' +
                        '                <div class="index-right left">\n' +
                        '                    <div class="index-top">\n' +
                        '                        <div class="index-name left">' + to_name + '</div>\n' +
                        '                        <div class="index-time right">' + message.Time + '</div>\n' +
                        '                    </div>\n' +
                        '                    <div class="index-content">\n' +
                        replace_qqface(message.Data) +
                        '                    </div>\n' +
                        '                </div>\n' +
                        '            </div>');

                    content_status();
                }
                /** 在聊天列表写入接收的文本消息 **/

                /** 聊天区域滚动到底部 **/
                scrollTo_end();
                /** 聊天区域滚动到底部 **/

                break;
            case "init": //接收申请用户绑定消息
                /** 组装需要发送的消息 **/
                var bind = '{"type":"bind", "from_id":"' + from_id + '"}';
                /** 组装需要发送的消息 **/

                /** 发送消息给GetWorker服务端 **/
                ws.send(bind);
                /** 发送消息给GetWorker服务端 **/

                /** 获取当前接收用户是否在线 **/
                var online = '{"type":"online", "to_id":"' + to_id + '", "from_id":"' + from_id + '"}';
                ws.send(online);
                /** 获取当前接收用户是否在线 **/

                /** 聊天区域滚动到底部 **/
                scrollTo_end();
                /** 聊天区域滚动到底部 **/

                break;
            case "save": //数据持久化请求
                save_message(message);

                /** 判断接收用户是否在线 **/
                if (message.IsRead == 1) {
                    online = 1;
                    $(".index-words span").text("在线");
                } else {
                    online = 0;
                    $(".index-words span").text("不在线");
                }
                /** 判断接收用户是否在线 **/

                break;
            case "online": //判断接收用户是否在线
                /** 判断接收用户是否在线 **/
                if (message.Status == 1) {
                    online = 1;
                    $(".index-words span").text("在线");
                } else {
                    online = 0;
                    $(".index-words span").text("不在线");
                }
                /** 判断接收用户是否在线 **/

                break;
            case "img": //接收为图片消息
                /** 在聊天列表写入接收的图片消息 **/
                if (to_id == message.FromId) { //只接收当前聊天用户的消息
                    $(".index-ul").append('<div class="index-li-left display-block">\n' +
                        '                <div class="index-img left">\n' +
                        '                    <img src="' + to_avatar + '"/>\n' +
                        '                </div>\n' +
                        '                <div class="index-right left">\n' +
                        '                    <div class="index-top">\n' +
                        '                        <div class="index-name left">' + to_name + '</div>\n' +
                        '                        <div class="index-time right">' + message.Time + '</div>\n' +
                        '                    </div>\n' +
                        '                    <div class="index-content">\n' +
                        '                       <img src="' + message.ImgPath + '">\n' +
                        '                    </div>\n' +
                        '                </div>\n' +
                        '            </div>');

                    content_status();
                }
                /** 在聊天列表写入接收的图片消息 **/

                /** 聊天区域滚动到底部 **/
                scrollTo_end();
                /** 聊天区域滚动到底部 **/

                break;
            default:
                break;
        }
    };
    /** 接收Sockect服务器发送的消息 **/

    /** 客户端给GetWorker服务端发送消息 **/
    $('#content_input').on("keydown", function (e) {
        if (e.keyCode == 13) {
            /** 获取输入框的内容 **/
            var text = $(this).val();
            /** 获取输入框的内容 **/

            if (text.length) { //判断内容是否为空
                /** 组装需要发送的消息 **/
                var message = '{"data":"' + text + '", "type":"say", "from_id":"' + from_id + '", "to_id":"' + to_id + '"}';
                /** 组装需要发送的消息 **/

                /** 获取当前时间时分 **/
                var date = new Date(),
                    time = date.getHours() + ':' + append_zero(date.getMinutes());
                /** 获取当前时间时分 **/

                /** 发送消息后 在聊天列表写入发送消息 **/
                $(".index-ul").append('<div class="index-li-right display-block">\n' +
                    '                <div class="index-img right">\n' +
                    '                    <img src="' + from_avatar + '"/>\n' +
                    '                </div>\n' +
                    '                <div class="index-right right">\n' +
                    '                    <div class="index-top">\n' +
                    '                        <div class="index-time left">' + time + '</div>\n' +
                    '                        <div class="index-name right">' + from_name + '</div>\n' +
                    '                    </div>\n' +
                    '                    <div class="index-content">\n' +
                    replace_qqface(text) +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>');
                /** 发送消息后 在聊天列表写入发送消息 **/

                /** 聊天区域滚动到底部 **/
                scrollTo_end();
                /** 聊天区域滚动到底部 **/

                /** 发送消息给GetWorker服务端 **/
                ws.send(message);
                /** 发送消息给GetWorker服务端 **/

                /** 清空输入框 **/
                $(this).val("");
                /** 清空输入框 **/
            }
        }
    });
    /** 客户端给GetWorker服务端发送消息 **/

    /** 用户发送图片文件消息 **/
    $("#index-file").on("change", function () {
        var form_data = new FormData();
        form_data.append("form_id", from_id);
        form_data.append("to_id", to_id);
        form_data.append("online", online);
        form_data.append("file", $(this)[0].files[0]);

        $.ajax({
            url: "/ajax/img_file/",
            type: "POST",
            cache: false, //关闭缓存
            data: form_data,
            dataType: "JSON",
            processData: false, //关闭内容转换成对象
            contentType: false, //关闭url_encode编码
            success: function (data) {
                if (data.code == 1) {
                    /** 发送图片消息后 在聊天列表写入发送图片消息 **/
                    $(".index-ul").append('<div class="index-li-right display-block">\n' +
                        '                <div class="index-img right">\n' +
                        '                    <img src="' + from_avatar + '"/>\n' +
                        '                </div>\n' +
                        '                <div class="index-right right">\n' +
                        '                    <div class="index-top">\n' +
                        '                        <div class="index-time left">' + data.data.Time + '</div>\n' +
                        '                        <div class="index-name right">' + from_name + '</div>\n' +
                        '                    </div>\n' +
                        '                    <div class="index-content">\n' +
                        '                      <img src="' + data.data.ImgPath + '" />\n' +
                        '                    </div>\n' +
                        '                </div>\n' +
                        '            </div>');
                    /** 发送图片消息后 在聊天列表写入发送图片消息 **/

                    /** 组装需要发送的消息 **/
                    var message = '{"data":"' + data.data.ImgPath + '", "type":"img", "from_id":"' + from_id + '", "to_id":"' + to_id + '"}';
                    /** 组装需要发送的消息 **/

                    /** 聊天区域滚动到底部 **/
                    scrollTo_end();
                    /** 聊天区域滚动到底部 **/

                    /** 发送消息给GetWorker服务端 **/
                    ws.send(message);
                    /** 发送消息给GetWorker服务端 **/
                }
            }
        })
    });

    /** 用户发送图片文件消息 **/

    /** 修改聊天内容状态 **/
    function content_status() {
        $.post("/ajax/status/", {"from_id": from_id, "to_id": to_id}, function () {
        }, "json");
    }

    /** 修改聊天内容状态 **/

    /** 数据持久化请求 **/
    function save_message(message) {
        $.post("/ajax/save_message/", message, function () {
        }, "json");
    }

    /** 数据持久化请求 **/

    /** QQFace表情图片替换 **/
    function replace_qqface(str) {
        str = str.replace(/\</g, '&lt;');
        str = str.replace(/\>/g, '&gt;');
        str = str.replace(/\n/g, '<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g, '<img src="/static/arclist/$1.gif"/>');
        return str;
    }

    /** QQFace表情图片替换 **/

    /** 时间不足2位时补0 **/
    function append_zero(e) {
        if (e < 10) {
            return "0" + "" + e;
        } else {
            return e;
        }
    }

    /** 时间不足2位时补0 **/

    /** 聊天区域滚动到底部 **/
    function scrollTo_end() {
        setTimeout(function () {
            var h = ($(document).height() - $(window).height());
            $(window).scrollTop(($(".index-ul").prop("scrollHeight") + 1000));
        }, 10);
    }

    /** 聊天区域滚动到底部 **/
})();