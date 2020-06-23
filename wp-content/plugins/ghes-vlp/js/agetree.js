$ = jQuery;


function SetAgeGroup(clicked_item) {

    if (getCookie("VLPSelectedChild") == "false") {
        setCookie("VLPAgeGroupId", clicked_item.dataset.agegroupid);
    }

}


$(document).ready(function () {

});
