$ = jQuery;
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