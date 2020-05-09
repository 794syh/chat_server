(function () {
    $(".user-logout").on("click", function () {
        $.post("/ajax/logout/", "", function () {
            location.href = "/login/";
        }, "json");
    })
})();