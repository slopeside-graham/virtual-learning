$ = jQuery;

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