$ = jQuery;
$lessonId = '';

$(function () {



    $(document).ready(function () {

        var playicon = $('#play-icon').children('svg').get(0);
        var articon = $('#art-icon').children('svg').get(0);
        var learnicon = $('#learn-icon').children('svg').get(0);
        var nurtureicon = $('#nurture-icon').children('svg').get(0);


        var ageGroupid = getCookie("VLPAgeGroupId");
        var VLPSelectedChild = getCookie("VLPSelectedChild");

        displayLoading("#lessons-listView");

        lessondataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    $.ajax({
                        url: wpApiSettings.root + "ghes-vlp/v1/lesson",
                        dataType: "json",
                        method: "GET",
                        data: { ageGroupid: ageGroupid },
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                        },
                        success: function (result) {
                            hideLoading("#lessons-listView");
                            options.success(result);
                        },
                        error: function (result) {
                            hideLoading("#lessons-listView");
                            options.error(result);
                        }
                    });
                },
            },
            sort: {
                field: "Title",
                dir: "asc"
            },
            schema: {
                model: {
                    id: "id",
                    fields: {
                        id: { editable: false, nullable: true },
                        Title: { validation: { required: true } },
                        Type: { validation: { required: true } },
                        MainContent: { validation: { required: false } },
                        VideoURL: { validation: { required: false } },
                        Image_id: { editable: true, validation: { required: false } },
                        Theme_id: { editable: true, validation: { required: true } },
                        ThemeTitle: { editable: true, validation: { required: true } },
                        ThemeAgeGroupName: { editable: true, validation: { required: true } },
                        ThemeStartDate: { editable: true, validation: { required: true }, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate },
                        ThemeEndDate: { editable: true, validation: { required: true }, type: "date", format: "{0:yyyy-MM-dd}", parse: parseDate }
                    }
                }
            }
        });

        function parseDate(data) {
            if (data.date) {
                return kendo.parseDate(data.date, "yyyy-MM-dd");
            } else {
                return data;
            }
        }

        $(function () {
            var dataSource = lessondataSource
            $("#lessons-listView").kendoListView({
                dataSource: dataSource,
                template: kendo.template($("#lesson-template").html()),
                dataBound: onDataBound,
            });
        });

        function onDataBound() {
            $('.Play-icon').html(playicon.outerHTML);
            $('.Art-icon').html(articon.outerHTML);
            $('.Learn-icon').html(learnicon.outerHTML);
            $('.Nurture-icon').html(nurtureicon.outerHTML);

            if (this.dataSource.data().length == 0) {
                $("#lessons-listView").append("<h2>No Lessons for this Age</h2>");
            }
            /*
            if($('.lesson-video iframe[src="null"]')) {
                $('.lesson-video').hide();
            }
            */
        }

    });
});

function openLessonPopup(clicked_item) {
    $lessonId = clicked_item.dataset.lessonId;
    console.log("Lesson Number: " + $lessonId);
    $("#lesson-" + $lessonId).show();

    getLessonResources($lessonId);

    var videoIframe = $('#lesson-' + $lessonId + ' .lesson-video iframe');
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

function closeLessonPopup(clicked_item) {
    $(".lesson-popup").hide();

    pauseVimeoVideo();
}


/* Close Lesson Popup */
$(document).keydown(function (e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        closeLessonPopup();
    }
});

function pauseVimeoVideo() {
    if ($lessonId != '') {
        var videoIframe = $('#lesson-' + $lessonId + ' .lesson-video iframe');
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

$(document).mouseup(function (e) {
    var container = $(".lesson-popup");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
        pauseVimeoVideo();
    }
});

function getLessonResources($lessonId) {

    displayLoading("#resources-listView-lesson-" + $lessonId);
    $("#resources-listView-lesson-" + $lessonId).empty();

    resourcedataSource = new kendo.data.DataSource({
        transport: {
            read: function (options) {
                $.ajax({
                    url: wpApiSettings.root + "ghes-vlp/v1/resource",
                    dataType: "json",
                    method: "GET",
                    data: { lesson_id: $lessonId },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
                    },
                    success: function (result) {
                        hideLoading("#resources-listView-lesson-" + $lessonId);
                        options.success(result);
                    },
                    error: function (result) {
                        hideLoading("#resources-listView-lesson-" + $lessonId);
                        options.error(result);
                    }
                });
            },
        },
        sort: {
            field: "Title",
            dir: "asc"
        },
        schema: {
            model: {
                id: "id",
                fields: {
                    id: { editable: false, nullable: true },
                    Title: { validation: { required: true } },
                    Media_id: { validation: { required: false } },
                    Link: { validation: { required: false } },
                    Lesson_id: { validation: { required: false } },
                }
            }
        }
    });

    var dataSource = resourcedataSource
    $("#resources-listView-lesson-" + $lessonId).kendoListView({
        dataSource: dataSource,
        template: kendo.template($("#lesson-resources-template").html()),
    });

}