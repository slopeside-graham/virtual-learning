$ = jQuery;
var currentLessonId;
var currentLessonNumber;
var selectedChild = getCookie("VLPSelectedChild");

$(function () {
    $(document).ready(function () {


    })
})

function openLessonPopup(clicked_item) {
    $lessonNumber = clicked_item.dataset.lessonNumber;
    console.log("Lesson Number: " + clicked_item.dataset.lessonNumber);
    $("#lesson-" + $lessonNumber).show();
    currentLessonId = clicked_item.dataset.lessonid;
    currentLessonNumber = clicked_item.dataset.lessonNumber;
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
    var resourceId = clicked_item.dataset.resourceId;
    console.log(selectedChild);

    $.ajax({
        url: wpApiSettings.root + "ghes-vlp/v1/childresourcestatus",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        data: {
            Resource_id: resourceId,
            Child_id: selectedChild,
            Completed: 1
        },
        success: function (result) {
            // notify the data source that the request succeeded
            console.log("Resource ID: " + clicked_item.dataset.resourceId + " completed.");
            console.log("Done:" + result);
            $(clicked_item).parent().addClass("completed");
            $(clicked_item).siblings(".completion-icon").children("svg").show();
            updateLessonStatus();
        },
        error: function (result) {
            if (typeof result.responseJSON !== "undefined") {
                alert(result.responseJSON.message);
            }
            console.log(result.responseText);
            // notify the data source that the request failed
        }
    });
}

function updateLessonStatus() {

    var totalResources = $("#lesson-" + currentLessonNumber + " .resources .related-materials-list li").length;
    var completedResources = $("#lesson-" + currentLessonNumber + " .resources .related-materials-list .completed").length;
    console.log("Total Resources: " + totalResources);
    console.log("Completed Resources: " + completedResources);

    var percentCompleted = (completedResources / totalResources) * 100;
    console.log("Percent Completed: " + percentCompleted);
    
    if (percentCompleted = 100) {
        $("#lesson-icon-" + currentLessonNumber).addClass("completed");
    }

}