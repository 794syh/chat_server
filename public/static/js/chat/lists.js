(function () {
    /** 连接Sockect服务器 **/
    var ws = new WebSocket("ws://127.0.0.1:8282");
    /** 连接Sockect服务器 **/

    /** 接收Sockect服务器发送的消息 **/
    ws.onmessage = function (e) {
        var message = JSON.parse(e.data);//JSON转数组

        switch (message.Type) {
            case "init": //接收申请用户绑定消息
                /** 组装需要发送的消息 **/
                var bind = '{"type":"bind", "from_id":"' + from_id + '"}';
                /** 组装需要发送的消息 **/

                /** 发送消息给GetWorker服务端 **/
                ws.send(bind);
                /** 发送消息给GetWorker服务端 **/

                break;
            case "text": //接收为文本消息
                /** 获取最新的聊天列表 **/
                getChatLists();
                /** 获取最新的聊天列表 **/

                break;
            case "img": //接收为图片消息
                /** 获取最新的聊天列表 **/
                getChatLists();
                /** 获取最新的聊天列表 **/

                break;
        }
    };

    /** 接收Sockect服务器发送的消息 **/

    /** 获取最新的聊天列表 **/
    function getChatLists() {
        $.get("/ajax/lists/", {"from_id": from_id}, function (e) {
            var html = '';
            $.each(e.data, function (idx, obj) {
                var chat_num = '';
                if (obj.ChatNum > 0) {
                    chat_num = '<div class="lists-chatnum">' + obj.ChatNum + '</div>'
                }

                html += '<a class="lists-li display-block" href="/content/' + obj.ChatId + '/">\n' +
                    '                    <div class="lists-left left">\n' +
                    '                        <div class="lists-img">\n' +
                    '                            <img src="' + obj.Avatar + '">\n' +
                    '                        </div>\n' +
                    chat_num +
                    '                    </div>\n' +
                    '                    <div class="lists-right right">\n' +
                    '                        <div class="lists-top">\n' +
                    '                            <div class="lists-name left">' + obj.UserName + '</div>\n' +
                    '                            <div class="lists-time right">' + obj.CreateTime + '</div>\n' +
                    '                        </div>\n' +
                    '                        <div class="lists-content">\n' +
                    obj.Content +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </a>\n';
            });

            $(".lists-ul").html(html);
        }, "json");
    }

    /** 获取最新的聊天列表 **/

})();