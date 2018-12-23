$(document).ready(function() {


    $("#read").click(function (e) {
        var id =$(this).attr("value");
        $.ajax({
            url: web_url + "api/user/"+id,
            type: "GET",
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            success: function (result) {
                console.log(result);
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    });


    $("#save").click(function (e) {
        var id =$(this).attr("value");
        $.ajax({
            url: web_url + "api/vacation/",
            type: "POST",
            dataType: 'json',
            data : JSON.stringify({
                user_id : id,
                date_from : '2018-12-21',
                date_to   : '2018-12-24'
            }),
            contentType: "application/json; charset=utf-8",
            success: function (result) {
                console.log(result);
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    });


    $("#approve").click(function (e) {
        var id =$(this).attr("value");
        $.ajax({
            url: web_url + "api/vacation/",
            type: "PUT",
            dataType: 'json',
            data : JSON.stringify({
                id : id,
                status : 'approved'
            }),
            contentType: "application/json; charset=utf-8",
            success: function (result) {
                console.log(result);
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    });

    $("#decline").click(function (e) {
        var id =$(this).attr("value");
        $.ajax({
            url: web_url + "api/vacation/",
            type: "PUT",
            dataType: 'json',
            data : JSON.stringify({
                id : id,
                status : 'declined'
            }),
            contentType: "application/json; charset=utf-8",
            success: function (result) {
                console.log(result);
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    });


});

