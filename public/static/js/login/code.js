(function () {
    /** 刷新验证码 **/
    $(".reloadverify").click(function(){
        var codeimg = $("#code_img");
        var verifyimg = codeimg.attr("src");
        if( verifyimg.indexOf('?')>0){
            codeimg.attr("src", verifyimg+'&random='+Math.random());
        }else{
            codeimg.attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
        }
    });
    /** 刷新验证码 **/
})();