$(document).ready(
    function(e) {
    $("#search-ip").on("click", function (e) {
        e.preventDefault();
        var ip = $(input[name="input-ip"]).val();

        var data = {
            "ip" : ip
        };
        console.log(ip, 'ip');
        console.log(data, 'data');
        BX.ajax.runComponentAction("diles:search.ip", "send", {
            mode: "class",
            data: {
                "email": "vasya@email.tld",
                "username": "Василий",
                "message": "Где мой заказ? Жду уже целый час!"
            }
        }).then(function (response) {
            console.log(response);

            // обработка ответа
        });
    });
});

