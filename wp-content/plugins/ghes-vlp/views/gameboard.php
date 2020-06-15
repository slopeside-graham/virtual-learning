<?php

use GHES\VLP;
use GHES\VLP\Lesson;
use GHES\VLP\Theme;
use GHES\VLP\AgeGroup;
use GHES\VLP\Resource;
use GHES\Children;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_gameboard_scripts()
{
    wp_enqueue_script('wp-api-gameboard');
}

function vlp_gameboard($atts, $content = null)
{
    enqueue_gameboard_scripts();

    $completionIcon = file_get_contents("wp-content/plugins/ghes-vlp/assets/check-solid.svg");

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
    $themedate = $_GET['theme-date'];

    $output = '';

    //Get all Theme items - id, Title, StartDate, EndDate, Gameboard_id

    if (isset($_GET['theme-date'])) {
        $themedate = $_GET['theme-date'];
    } else {
        $themedate = date("Y-m-d");
    };


    //$todaysdate = "2020-06-30";

    if (isset($themedate)) {
        $theme = Theme::GetbyDate($themedate);
    }

    $themeid = $theme->id;

    if ($themeid != null) {

        $themelastweekdate = date('Y-m-d', strtotime('-7 day', strtotime($themedate)));
        $themenextweekdate = date('Y-m-d', strtotime('+7 day', strtotime($themedate)));
        $themestartdate = $theme->StartDate;
        $themeenddate = $theme->EndDate;
        $themecompleted = $theme->Completed;
        $themepercentcompleted = $theme->PercentComplete;

        if ($themecompleted) {
            $themeprogress = "completed";
        } else {
            $themeprogress = $themepercentcompleted;
        }
        $output .= '<script>var currentThemeId = '. $themeid . '</script>';
        $output .= '<div class="vlp-intro">Take the journey and see what you can learn with our P.L.A.N.!</div>';
        $output .= '<div class="gameboard-theme-header">';

        $output .= '<div class="navigation-button last-week">';
        if (is_null(Theme::GetbyDate($themelastweekdate))) {
            $output .= '<a href="?theme-date=' . $themelastweekdate . '&age-group=' . $agegroupid . '">Previous Week</a>';
        }
        $output .= '</div>';

        $output .= '<div id="theme-title" class="'. $themeprogress . '%-completed">This weeks theme: <strong>' . $theme->Title . '</strong><span class="theme-completion-icon">' . $completionIcon . '</span></div>';
        
        $output .= '<div class="navigation-button next-week">';
        if (is_null(Theme::GetbyDate($themenextweekdate))) {
            $output .= '<a href="?theme-date=' . $themenextweekdate . '&age-group=' . $agegroupid . '">Next Week</a>';
        }
        $output .= '</div>';

        $output .= '</div>';

        if (isset($agegroupid)) {

            $lessons = Lesson::GetAllbyThemeIdAndAgeGroup($themeid, $agegroupid);

            if ($lessons->jsonSerialize()) {

                $lessonnumber = 1; // This just sets up an order for the lessons, should be 1 - 16.

                $output .= ' <div id="gameboard">';

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
                        $lessonicon = file_get_contents("wp-content/plugins/ghes-vlp/assets/icons/play.svg");
                    } else if ($lesson->Type == 'Art') {
                        $lessonicon = file_get_contents("wp-content/plugins/ghes-vlp/assets/icons/art.svg");
                    } else if ($lesson->Type == 'Learn') {
                        $lessonicon = file_get_contents("wp-content/plugins/ghes-vlp/assets/icons/learn.svg");
                    } else if ($lesson->Type == 'Nurture') {
                        $lessonicon = file_get_contents("wp-content/plugins/ghes-vlp/assets/icons/nurture.svg");
                    } else {
                        $lessonicon = "No Icon Found";
                    }

                    $output .= ' <span id="lesson-icon-'. $lessonNumber .'" class="lesson-icon-area L-' . $lessonNumber . '-position icon-' . $lesson->Type . ' ' . $lessonprogress . '" onclick="openLessonPopup(this)" data-lesson-number="' . $lessonNumber . '" data-lesson-id="' . $lessonid . '">' . $lessonicon . '<span class="lesson-completion-icon">' . $completionIcon . '</span><span class="lesson-icon-title">' . $lesson->Title . '</span></span>';
                    $output .= '<div class="lesson-popup type-' . $lesson->Type . '" id="lesson-' . $lessonNumber . '">';
                    $output .= '<span class="close-button">&times;</span>';
                    $output .= '<span class="corner-icon icon-' . $lesson->Type . '">' . $lessonicon . '</span>';
                    $output .= '<div class="lesson-content">';
                    $output .= '<div class="first-column">';
                    $output .= '<span class="type-heading">' . $lesson->Type . ' Activity</span><br/>';
                    $output .= '<span class="lesson-title">' . $lesson->Title . '</span><br/>';
                    $output .= '<p class="lesson-main-content">' . $lesson->MainContent . '</p>';
                    $output .= '</div>';
                    $output .= '<div class="second-column">';
                    $output .= '<span class="lesson-video">' . wp_oembed_get($lesson->VideoURL, array( 'height' => 180)) . '</span>';
                    //$output .= '<p>Image ID: ' . $lesson->Image_id . '</p>';

                    $resources = Resource::GetAllbyLessonId($lessonid);

                    if ($resources->jsonSerialize()) {
                        $output .= '<div class="resources"><span class="related-materials-title">Related Materials</span><br/>';
                        $output .= '<ul class="related-materials-list">';

                        foreach ($resources->jsonSerialize() as $k => $resource) {
                            $resourcelink = wp_get_attachment_url($resource->Media_id);
                            $resourceid = $resource->id;
                            $percentcomplete = $resource->PercentComplete;
                            $completed = $resource->Completed;
                            
                            if ($completed) {
                                $resourceprogress = "completed";
                            } else {
                                $resourceprogress = $percentcomplete;
                            }
                            $output .= '<li class="' . $resourceprogress . '"><a href="' . $resourcelink . '" target="_blank" onclick="completeResource(this)" class="resource-item" data-resource-Id="' . $resourceid . '">' . $resource->Title . '</a><span class="completion-icon">' . $completionIcon . '</span></li>';
                        }
                        $output .= '</ul></div>';
                    }
                    
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
            } else {
                $output .= '<h2>Uh-oh, no lessons found for this age!</h2>';
            }
        } else {
            $output .= '<h2>Uh-oh, no lessons found for this age!</h2>';
        };

        // Get All Lesson Categories - Lessons_id, Categories_id

        //Get All Resources - id, Media_id, Lesson_id

        $output .= ' </div>';
    } else {
        $output .= '<h2>Sorry, no theme found for that week.</h2>';
    }
    return $output;
}
