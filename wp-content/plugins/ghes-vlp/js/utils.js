$ = jQuery;
var validator 
$(document).ready(function () {
    validator = $("form").kendoValidator({
        rules: {
            radio: function (input) {
                if (input.is(':radio')) {
                    if (input.filter("[type=radio]") && input.attr("required")) {
                        return $("form").find("[type=radio][name=" + input.attr("name") + "]").is(":checked");
                    }
                    return true;
                }
                return true;
            }
        },
        messages: {
            radio: "This is a required field"
        }
    }).data("kendoValidator");

    // Masked Text box rules
    $(".phone-number").kendoMaskedTextBox({
        mask: "(999) 000-0000"
    });

    $(".creditcard-number").kendoMaskedTextBox({
        mask: "0000 0000 0000 0000"
    });
});

function displayLoading(target) {
    var element = $(target);
    kendo.ui.progress(element, true);
}
function hideLoading(target) {
    var element = $(target);
    kendo.ui.progress(element, false);
}
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "false";
}

function setCookie(cookieName, cookieValue, expiresDays) {
    if (cookieValue == "") {
        // No value, Delete the cookie
        document.cookie = cookieName + "=;Thu, 01 Jan 1970 00:00:00 UTC;path=/";
        return;
    }
    if (expiresDays) {
        var d = new Date();
        d.setTime(d.getTime() + expiresDays * 24 * 60 * 60 * 1000);
        var expires = "expires=" + d.toUTCString();
        document.cookie =
            cookieName + "=" + cookieValue + ";" + expires + ";path=/";
    } else {
        document.cookie = cookieName + "=" + cookieValue + ";path=/";
    }
}
