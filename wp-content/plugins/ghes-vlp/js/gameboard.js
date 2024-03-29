// TODO: On load run a check status function.

$ = jQuery;
$lessonNumber = '';
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

    var videoIframe = $('#lesson-' + $lessonNumber + ' .lesson-video iframe');
    var player = new Vimeo.Player(videoIframe);

    player.on('play', function () {
        console.log('played the video!');
    });
    player.on('timeupdate', function (data) {
        if (data.percent == 1) {
            completeVideo(player);
        };
    })
}


/* Close Lesson Popup */
$(document).keydown(function (e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        $(".lesson-popup").hide();

        pauseVimeoVideo();
    }
});

function pauseVimeoVideo() {
    if ($lessonNumber != '') {
        var videoIframe = $('#lesson-' + $lessonNumber + ' .lesson-video iframe');
        var player = new Vimeo.Player(videoIframe);

        player.pause().then(function () {
            // the video was paused
        }).catch(function (error) {
            switch (error.name) {
                case 'PasswordError':
                    // the video is password-protected and the viewer needs to enter the
                    // password first
                    break;

                case 'PrivacyError':
                    // the video is private
                    break;

                default:
                    // some other error occurred
                    break;
            }
        });
    }
}
$(".lesson-popup .close-button").click(function () {
    $(".lesson-popup").hide();
    pauseVimeoVideo();
});
$(document).mouseup(function (e) {
    var container = $(".lesson-popup");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
        pauseVimeoVideo();
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
                $(clicked_item).siblings(".completion-icon").children("img").show();
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

function completeVideo(player) {
    console.log('The Video was watched');
    console.log('Video for: Lesson Number ' + $lessonNumber)

    if (selectedChild != "false") {

        $.ajax({
            url: wpApiSettings.root + "ghes-vlp/v1/childlessonstatus",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            },
            data: {
                Lesson_id: currentLessonId,
                Child_id: selectedChild,
                VideoCompleted: 1,
                VideoPercentComplete: 100
            },
            success: function (result) {
                // notify the data source that the request succeeded
                $('#lesson-' + $lessonNumber + ' .completion-icon img').show();
                $('#lesson-' + $lessonNumber + ' .lesson-video').addClass('completed');
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
                VideoCompleted: 1,
                VideoPercentComplete: 100,
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
        var totalVideo = $('#lesson-' + $lessonNumber + ' .lesson-video iframe').length;
        var totalItems = totalResources + totalVideo
        var completedVideo = $('#lesson-' + $lessonNumber + ' .lesson-video.completed').length;
        var completedResources = $("#lesson-" + currentLessonNumber + " .resources .related-materials-list .completed").length;
        var totalCompletedItems = completedVideo + completedResources;

        var percentCompleted = (totalCompletedItems / totalItems) * 100;
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