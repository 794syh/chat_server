(function () {
    /** 字母导航定位 **/
    $(".index-screen li").on("click", function () {
        var screen = $(this).text();

        if (screen === "#") {
            screen = "XYZ";
        }

        $(window).scrollTop($("#hierarchy_" + screen).offset().top - ($(".index-head").height() + 30))
    });
    /** 字母导航定位 **/
})();