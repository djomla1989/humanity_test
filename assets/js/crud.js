$(document).ready(function() {


    $("#read").click(function (e) {
        var id =$(this).attr("value");
        $.ajax({
            url: web_url + "/api/api.php",
            type: "POST",
            dataType: 'json',
            data: {
                'action' : 'read',
                'id': id,
            },
            success: function (result) {
                console.log(result);
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    });


});

