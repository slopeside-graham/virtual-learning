// TODO: On load run a check status function.

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
    currentLessonId = clicked_item.dataset.lessonId;
    currentLessonNumber = clicked_item.dataset.lessonNumber;
}

/* Close Lesson Popup */
$(document).keydown(function (e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        $(".lesson-popup").hide();
    }
});
$(".lesson-popup .close-button").click(function () {
    $(".lesson-popup").hide();
});
$(document).mouseup(function (e) {
    var container = $(".lesson-popup");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
    }
});

function completeResource(clicked_item) {
    if (selectedChild != "false") {
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
}

function completeLesson() {
    if (selectedChild != "false") {
        console.log(selectedChild);

        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/childlessonstatus",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Lesson_id: currentLessonId,
                Child_id: selectedChild,
                Completed: 1,
                PercentComplete: 100
            },
            success: function (result) {
                // notify the data source that the request succeeded
                console.log("Lesson ID: " + currentLessonId + " completed.");
                console.log("Done:" + result);
                updateThemeStatus();
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
}

function inProgressLesson(percentCompleted) {
    if (selectedChild != "false") {
        console.log(selectedChild);

        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/childlessonstatus",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Lesson_id: currentLessonId,
                Child_id: selectedChild,
                PercentComplete: percentCompleted
            },
            success: function (result) {
                // notify the data source that the request succeeded
                console.log("Lesson ID: " + currentLessonId + " " + percentCompleted + "% complete.");
                console.log("Done:" + result);
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
}

function updateLessonStatus() {
    if (selectedChild != "false") {
        currentLessonId;
        var totalResources = $("#lesson-" + currentLessonNumber + " .resources .related-materials-list li").length;
        var completedResources = $("#lesson-" + currentLessonNumber + " .resources .related-materials-list .completed").length;
        console.log("Total Resources: " + totalResources);
        console.log("Completed Resources: " + completedResources);

        var percentCompleted = (completedResources / totalResources) * 100;
        console.log("Percent Completed: " + percentCompleted);

        if (percentCompleted == 100) {
            $("#lesson-icon-" + currentLessonNumber).addClass("completed");
            completeLesson();
        } else {
            $("#lesson-icon-" + currentLessonNumber).addClass(percentCompleted + "%-completed");
            inProgressLesson(percentCompleted);
        }
    }
}

function updateThemeStatus() {
    if (selectedChild != "false") {

        var totalLessons = $(".lesson-icon-area").length;
        var completedLessons = $(".lesson-icon-area.completed").length;
        console.log("Total Lessons: " + totalLessons);
        console.log("Completed Lessons: " + completedLessons);

        var percentCompleted = (completedLessons / totalLessons) * 100;
        console.log("Percent Completed: " + percentCompleted);

        if (percentCompleted == 100) {
            $("#theme-title").addClass("completed");
            completeTheme();
        } else {
            $("#theme-title").addClass(percentCompleted + "%-completed");
            inProgressTheme(percentCompleted);
        }
    }
}

function completeTheme() {
    if (selectedChild != "false") {
        console.log(selectedChild);

        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/childthemestatus",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Theme_id: currentThemeId,
                Child_id: selectedChild,
                Completed: 1,
                PercentComplete: 100
            },
            success: function (result) {
                // notify the data source that the request succeeded
                console.log("Theme ID: " + currentThemeId + " completed.");
                console.log("Done:" + result);
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
}

function inProgressTheme(percentCompleted) {
    if (selectedChild != "false") {
        console.log(selectedChild);

        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/childthemestatus",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Theme_id: currentThemeId,
                Child_id: selectedChild,
                PercentComplete: percentCompleted
            },
            success: function (result) {
                // notify the data source that the request succeeded
                console.log("Theme ID: " + currentThemeId + " " + percentCompleted + "% complete.");
                console.log("Done:" + result);
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
}