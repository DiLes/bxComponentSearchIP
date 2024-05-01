$(document).ready(
    function(e) {
    $("#search-ip").on("click", function (e) {
        e.preventDefault();
        var ip = $('input[name="input-ip"]').val();

        if (ip) {
            var data = {
                "ip" : ip
            };
        }
        console.log(data, 'data');
        BX.ajax.runComponentAction("main:search.ip", "search", {
            mode: "class",
            data: data
        }).then(function (response) {
            console.log(response);

            // обработка ответа
        });
    });
});

