$ = jQuery;


$(document).ready(function () {
    //Unset VLP Cookies
    setCookie("VLPAgeGroupId", '', 1);
    setCookie("VLPSelectedChild", '', 1);
    setCookie("VLPThemeId", '', 1);
});

function setAgeGroupId(clicked_item) {
    setCookie("VLPAgeGroupId", clicked_item, 0, '/');
}
