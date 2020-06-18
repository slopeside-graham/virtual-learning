$ = jQuery;


function themeExpandCollapse(clickedtheme) {

    $themeid = clickedtheme.id;
    $clickedThemeLessons = $("[data-theme-id=" + $themeid + "]");
    var minusSymbol = "";



    if ($clickedThemeLessons.css('display') == "none") {
        $(".vlp-lesson-items").hide();
        $(".Theme-Title .expand-collapse").text($.parseHTML("&#43; ")[0].data);
        $(clickedtheme).find(".expand-collapse").text($.parseHTML("&#8722; ")[0].data);
        $clickedThemeLessons.show();
    } else if ($clickedThemeLessons.css('display') == "block") {
        $(clickedtheme).find(".expand-collapse").text($.parseHTML("&#43; ")[0].data);
        $clickedThemeLessons.hide()
    }
}

function lessonExpandCollapse(clickedlesson) {

    $lessonid = clickedlesson.id;
    $clickedLessonContent = $("[data-lesson-id=" + $lessonid + "]");

    if ($clickedLessonContent.css('display') == "none") {
        $(".archive-lesson-content").hide();
        $(".archive-lesson-title .expand-collapse").text($.parseHTML("&#43; ")[0].data);
        $(clickedlesson).find(".expand-collapse").text($.parseHTML("&#8722; ")[0].data);
        $clickedLessonContent.show();
    } else if ($clickedLessonContent.css('display') == "block") {
        $(clickedlesson).find(".expand-collapse").text($.parseHTML("&#43; ")[0].data);
        $clickedLessonContent.hide()
    }
}

$(function () {
    $(document).ready(function () {


    })
})

