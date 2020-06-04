$ = jQuery;

var selectedChild = getCookie("VLPSelectedChild");

$(function () {
    $(document).ready(function () {


    })
})

function openLessonPopup(clicked_item) {
    $lessonNumber = clicked_item.dataset.lessonNumber;
    console.log("Lesson Number: " + clicked_item.dataset.lessonNumber);
    $("#lesson-" + $lessonNumber).show();
}

$(document).keydown(function (e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        $(".lesson-popup").hide();
    }
});
$(".lesson-popup .close-button").click(function () {
    $(".lesson-popup").hide();
});

function completeResource(clicked_item) {
    $resourceId = clicked_item.dataset.resourceId;
    console.log("Resource ID: " + clicked_item.dataset.resourceId + " completed.");
    console.log(selectedChild);

    resourcedataSource = new kendo.data.DataSource({
        transport: {
            create: function (options) {
                $.ajax({
                    url: wpApiSettings.root + "ghes-vlp/v1/child-resource-status",
                    method: "POST",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                    },
                    data: {
                        Resource_id: $resourceId,
                        Child_id: $selectedchild,
                        Completed: 1
                    },
                    success: function (result) {
                        // notify the data source that the request succeeded
                        console.log("Done:" + result);
                        options.success(result);
                    },
                    error: function (result) {
                        if (typeof result.responseJSON !== "undefined") {
                            alert(result.responseJSON.message);
                        }
                        console.log(result.responseText);
                        // notify the data source that the request failed
                        options.error(result);
                    }
                });
            }
        }
    });
}