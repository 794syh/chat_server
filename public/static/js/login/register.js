(function () {
    /** 注册 **/
    $("#register_submit").on("click", function () {
        var user_name = $("#user_name").val(), //用户名
            mobile = $("#mobile").val(), //手机号
            password = $("#password").val(), //密码
            email = $("#email").val(), //邮箱
            code = $("#code").val(); //验证码

        if (user_name === '') {
            alert("请输入用户名");
            return;
        }

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

        if (email === '') {
            alert("请输入邮箱");
            return;
        }

        /** 密码加密 **/
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(pubkey);
        password = encrypt.encrypt(register_time + '|' + password);
        /** 密码加密 **/

        $.post("/ajax/register/", {
            "user_name": encodeURIComponent(user_name),
            "mobile": mobile,
            "password": password,
            "code": code,
            "email": email
        }, function (e) {
            if (e.data.status === 200) {
                location.href = "/";
            } else {
                $("#code").val("");
                alert(e.msg);
                refreshCode();
            }
        }, "json");
    });

    /** 注册 **/

    /** 刷新验证码 **/
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