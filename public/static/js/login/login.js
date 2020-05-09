(function () {
    /** 登录 **/
    $("#login_submit").on("click", function () {
        var mobile = $("#mobile").val(), //手机号
            password = $("#password").val(), //密码
            code = $("#code").val(); //验证码

        if (mobile === '') {
            alert("请输入手机号");
            return;
        }

        if (password === '') {
            alert("请输入密码");
            return;
        }

        if (code === '') {
            alert("请输入验证码");
            return;
        }

        /** 密码加密 **/
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(pubkey);
        password = encrypt.encrypt(login_time + '|' + password);
        /** 密码加密 **/

        $.post("/ajax/login/", {"mobile": mobile, "password": password, "code": code}, function (e) {
            if (e.data.status === 200) {
                location.href = "/";
            } else {
                $("#code").val("");
                alert(e.msg);
                refreshCode();
            }
        }, "json");
    });
    /** 登录 **/

    /** 刷新验证码 **/
    $(".reloadverify").click(function () {
        refreshCode();
    });

    function refreshCode() {
        var codeimg = $("#code_img");
        var verifyimg = codeimg.attr("src");
        if (verifyimg.indexOf('?') > 0) {
            codeimg.attr("src", verifyimg + '&random=' + Math.random());
        } else {
            codeimg.attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
    }

    /** 刷新验证码 **/
})();