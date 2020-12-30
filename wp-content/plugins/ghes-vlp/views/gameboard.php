<?php

use GHES\VLP;
use GHES\VLP\Lesson;
use GHES\VLP\Theme;
use GHES\VLP\AgeGroup;
use GHES\VLP\Resource;
use GHES\Children;

function enqueue_gameboard_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-gameboard');
}

function vlp_gameboard($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInVLPParent();
    enqueue_gameboard_scripts();

    // Get All Cookies
    if (isset($_COOKIE['VLPAgeGroupId'])) {
        $VLPAgeGroupId = $_COOKIE['VLPAgeGroupId'];
    }
    if (isset($_COOKIE['VLPSelectedChild'])) {
        $VLPSelectedChild = $_COOKIE['VLPSelectedChild'];
    }
    if (isset($_COOKIE['VLPThemeId'])) {
        $VLPThemeId = $_COOKIE['VLPThemeId'];
    }

    $completionIcon = plugin_dir_url(dirname(__FILE__)) . 'assets/Star.png';
    $nextWeekButton = plugin_dir_url(dirname(__FILE__)) . 'assets/Buttons/Next Week Button.png';
    $previousWeekButton = plugin_dir_url(dirname(__FILE__)) . 'assets/Buttons/Last Week Button.png';
    $viewAllWeeksButton = plugin_dir_url(dirname(__FILE__)) . 'assets/Buttons/View All Weeks Button.png';

    $browsethemeslink = get_permalink(get_option('vlp-themes'));

    $output = '';

    if (isset($_COOKIE['VLPSelectedChild'])) {
        $childid = $_COOKIE['VLPSelectedChild'];
        $child = Children::Get($childid);
        $todaysdate = new DateTime(date("Y-m-d"));
        $childdob = $child->DOB;
        $interval = $todaysdate->diff($childdob);
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $childagemonths = ($years * 12) + $months;

        $agegroup = AgeGroup::GetByAgeMonths($childagemonths);
        $agegroupid = $agegroup->id;
    } else if (isset($_COOKIE['VLPAgeGroupId'])) {
        $agegroupid = $_COOKIE['VLPAgeGroupId'];
    } else {
        $output .= 'No Chid or Age group Selected';
        return $output;
    }



    if (isset($_GET['theme-date'])) {
        $themedate = $_GET['theme-date'];
    } else if (isset($_COOKIE['VLPThemeId'])) {
        $theme = Theme::Get($_COOKIE['VLPThemeId']);
        $themedate = $theme->StartDate->format('Y-m-d');
    } else {
        $themedate = date("Y-m-d");
    };


    if (isset($themedate)) {
        $theme = Theme::GetbyDateandAgeGroup($themedate, $agegroupid); // TODO - this needs security when called directly.
    }

    $themeid = $theme->id;

    if ($themeid != null) {

        $themeDateObjectLastWeek = new DateTime($themedate);
        $themeDateObjectNextWeek = new DateTime($themedate);
        $oneWeek = new DateInterval('P7D');

        $themelastweekdate = $themeDateObjectLastWeek->sub($oneWeek);
        $themenextweekdate = $themeDateObjectNextWeek->add($oneWeek);
        $themestartdate = $theme->StartDate;
        $themeenddate = $theme->EndDate;
        $themecompleted = $theme->Completed;
        $themepercentcompleted = $theme->PercentComplete;
        $lastweekstheme = Theme::GetbyDateandAgeGroup($themelastweekdate, $agegroupid);
        $nextweekstheme = Theme::GetbyDateandAgeGroup($themenextweekdate, $agegroupid);

        if ($themecompleted) {
            $themeprogress = "completed";
        } else {
            $themeprogress = $themepercentcompleted;
        }
        $output .= '<script>var currentThemeId = ' . $themeid . '</script>';
        $output .= '<div class="vlp-intro">Take the journey and see what you can learn with our P.L.A.N.!</div>';
        $output .= '<div class="gameboard-theme-header">';

        $output .= '<div class="navigation-button last-week">';

        if (!is_null($lastweekstheme)) {
            $output .= '<a href="?theme-date=' . $themelastweekdate->format('Y-m-d') . '&age-group=' . $agegroupid . '"><img class="nav-btn-img" src="' . $previousWeekButton . '" /></a>';
        }
        $output .= '</div>';

        $output .= '<div id="theme-title" class="' . $themeprogress . '"><strong>' . $theme->Title . '</strong><span class="theme-completion-icon"><img src="' . $completionIcon . '" /></span></div>';

        $output .= '<div class="navigation-button next-week">';
        if (!is_null($nextweekstheme)) {
            $output .= '<a href="?theme-date=' . $themenextweekdate->format('Y-m-d') . '&age-group=' . $agegroupid . '"><img class="nav-btn-img" src="' . $nextWeekButton . '" /></a>';
        }
        $output .= '</div>';

        $output .= '</div>';

        if (isset($agegroupid)) {

            $lessons = Lesson::GetAllbyThemeId($themeid);

            if ($lessons->jsonSerialize()) {

                $lessonnumber = 1; // This just sets up an order for the lessons, should be 1 - 16.
                $lessonPopupnumber = 1; // This just sets up an order for the lessons, should be 1 - 16.

                $output .= ' <div id="gameboard">';
                $output .= '<div id="lesson-icons">';

                foreach ($lessons->jsonSerialize() as $k => $lesson) {
                    $lessonid = $lesson->id;
                    $lessonNumber = $lessonnumber++;
                    $completed = $lesson->Completed;
                    $percentcomplete = $lesson->PercentComplete;

                    if ($completed) {
                        $lessonprogress = "completed";
                    } else {
                        $lessonprogress = $percentcomplete;
                    }

                    if ($lesson->Type == 'Play') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/play.svg');
                    } else if ($lesson->Type == 'Art') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/art.svg');
                    } else if ($lesson->Type == 'Learn') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/learn.svg');
                    } else if ($lesson->Type == 'Nurture') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/nurture.svg');
                    } else {
                        $lessonicon = "No Icon Found";
                    }

                    $output .= ' <span id="lesson-icon-' . $lessonNumber . '" class="lesson-icon-area L-' . $lessonNumber . '-position icon-' . $lesson->Type . ' ' . $lessonprogress . '" onclick="openLessonPopup(this)" data-lesson-number="' . $lessonNumber . '" data-lesson-id="' . $lessonid . '">' . $lessonicon . '<span class="lesson-completion-icon"><img src="' . $completionIcon . '" /></span><span class="lesson-icon-title">' . $lesson->Title . '</span></span>';
                }
                $output .= '</div>';
                $output .= '<div id="lesson-popups">';

                foreach ($lessons->jsonSerialize() as $k => $lesson) {
                    $lessonid = $lesson->id;
                    $lessonPopupNumber = $lessonPopupnumber++;
                    $completed = $lesson->Completed;
                    $percentcomplete = $lesson->PercentComplete;

                    if ($completed) {
                        $lessonprogress = "completed";
                    } else {
                        $lessonprogress = $percentcomplete;
                    }

                    if ($lesson->Type == 'Play') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/play.svg');
                    } else if ($lesson->Type == 'Art') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/art.svg');
                    } else if ($lesson->Type == 'Learn') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/learn.svg');
                    } else if ($lesson->Type == 'Nurture') {
                        $lessonicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/nurture.svg');
                    } else {
                        $lessonicon = "No Icon Found";
                    }

                    $output .= '<div class="lesson-popup type-' . $lesson->Type . '" id="lesson-' . $lessonPopupNumber . '">';
                    $output .= '<span class="close-button">&times;</span>';
                    $output .= '<span class="corner-icon icon-' . $lesson->Type . '">' . $lessonicon . '</span>';
                    $output .= '<div class="lesson-content">';
                    $output .= '<div class="first-column">';
                    $output .= '<span class="type-heading">' . $lesson->Type . ' Activity</span><br/>';
                    $output .= '<span class="lesson-title">' . $lesson->Title . '</span><br/>';
                    $output .= '<p class="lesson-main-content">' . $lesson->MainContent . '</p>';
                    $output .= '</div>';
                    $output .= '<div class="second-column">';

                    $videocompleted = $lesson->VideoCompleted;
                    $videopercentcompleted = $lesson->VideoPercentCompleted;

                    if ($videocompleted) {
                        $videocompleted = "completed";
                    } else {
                        $videocompleted = $videopercentcompleted;
                    }
                    $output .= '<span class="lesson-video ' . $videocompleted . '">';
                    if ($lesson->VideoURL) {
                        $output .= '<iframe src="' . $lesson->VideoURL . '"frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe><span class="completion-icon video-completion-icon"><img src="' . $completionIcon . '" /></span>';
                    }
                    $output .= '</span>';
                    //$output .= '<p>Image ID: ' . $lesson->Image_id . '</p>';

                    $resources = Resource::GetAllbyLessonId($lessonid);

                    if ($resources->jsonSerialize()) {
                        $output .= '<div class="resources"><span class="related-materials-title">Related Materials</span><br/>';
                        $output .= '<ul class="related-materials-list">';

                        foreach ($resources->jsonSerialize() as $k => $resource) {
                            if ($resource->Media_id != "") {
                                $resourcelink = wp_get_attachment_url($resource->Media_id);
                            } else if ($resource->Link != "") {
                                $resourcelink = $resource->Link;
                            }
                            $resourceid = $resource->id;
                            $percentcomplete = $resource->PercentComplete;
                            $completed = $resource->Completed;

                            if ($completed) {
                                $resourceprogress = "completed";
                            } else {
                                $resourceprogress = $percentcomplete;
                            }
                            $output .= '<li class="' . $resourceprogress . '"><a href="' . $resourcelink . '" target="_blank" onclick="completeResource(this)" class="resource-item" data-resource-Id="' . $resourceid . '">' . $resource->Title . '</a><span class="completion-icon"><img src="' . $completionIcon . '" /></span></li>';
                        }
                        $output .= '</ul></div>';
                    }

                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
                $output .= '</div>';
            } else {
                $output .= '<h2>Uh-oh, no lessons found for this age!</h2>';
            }
        } else {
            $output .= '<h2>Uh-oh, no lessons found for this age!</h2>';
        };

        // Get All Lesson Categories - Lessons_id, Categories_id

        //Get All Resources - id, Media_id, Lesson_id

        $output .= ' </div> <!-- End of Gameboard -->';
        $output .= '<a href="' . $browsethemeslink . '" class="view-all-weeks-btn"><img class="nav-btn-img" src="' . $viewAllWeeksButton . '" /></a>';
    } else {
        $output .= '<h2>Sorry, no theme found for that week.</h2>';
    }
    return $output;
}