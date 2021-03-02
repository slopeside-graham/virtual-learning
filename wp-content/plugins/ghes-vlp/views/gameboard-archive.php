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

function enqueue_gameboard_archive_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-gameboard-archive');
}

function vlp_gameboard_archive($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInVLPParent();
    enqueue_gameboard_archive_scripts();

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
    } else if (isset($_GET['agegroup'])) {
        $agegroupid = $_GET['agegroup'];
    } else {
        $output .= 'No Child or Age Group Selected';
        return $output;
    }
    if (isset($agegroupid)) {
        $themes = Theme::GetAllbyAgeGroup($agegroupid);

        if ($themes->jsonSerialize()) {

            $output .= "<h2>All Themes</h2>";

            foreach ($themes->jsonSerialize() as $k => $theme) {
                $themeid = $theme->id;
                $themeTitle = $theme->Title;
                $themeStartDate = $theme->StartDate->format('l F j, Y');
                $themeEndDate = $theme->EndDate->format('l F j, Y');
                $themeGameboard_id = $theme->Gameboard_id;
                $themeAgeGrou_id = $theme->AgeGroup_id;
                $themecompleted = $theme->Completed;
                $themepercentcompleted = $theme->PercentComplete;

                $output .= '<div class="Single-Theme">';
                $output .= '<span id="theme-' . $themeid . '" class="Theme-Title" onclick="themeExpandCollapse(this)"><h3><span class="expand-collapse">&#43; </span>' . $themeTitle . '</h3>';
                $output .= '<span class="Theme-Date">' . $themeStartDate . ' - ' . $themeEndDate . '</span></span>';

                $lessons = Lesson::GetAllbyThemeId($themeid);
                if ($lessons->jsonSerialize()) {
                    $output .= '<ul class="vlp-lesson-items" data-theme-id="theme-' . $themeid . '">';

                    foreach ($lessons->jsonSerialize() as $k => $lesson) {
                        $lessonid = $lesson->id;
                        $lessonTitle = $lesson->Title;
                        $lessonType = $lesson->Type;
                        $lessonMainContent = $lesson->MainContent;
                        $lessonVideoURL = $lesson->VideoURL;
                        $lessonImage_id = $lesson->Image_id;
                        $lessonTheme_id = $lesson->Theme_id;
                        $lessonCompleted = $lesson->Completed;
                        $lessonPercentComplete = $lesson->PercentComplete;

                        $output .= '<li>';
                        $output .= '<div id="lesson-' . $lessonid . '" class="archive-lesson-title" onclick="lessonExpandCollapse(this)"><span class="expand-collapse">&#43; </span>' . $lessonTitle . '</div>';
                        $output .= '<div class="archive-lesson-content" data-lesson-id="lesson-' . $lessonid . '">';
                        $output .= '<div class="first-column">';
                        $output .= '<div class="lessonType">' . $lessonType . '</div>';
                        $output .= '<div class="lessonMainContent">' . $lessonMainContent . '</div>';
                        $output .= '</div>';
                        $output .= '<div class="second-column">';
                        $output .= '<div class="lessonVideo">' .  wp_oembed_get($lessonVideoURL, array('height' => 180)) . '</div>';

                        $resources = Resource::GetAllbyLessonId($lessonid);

                        if ($resources->jsonSerialize()) {
                            $output .= '<div class="resources-heading">Resources</div>';
                            $output .= '<ul class="vlp-lesson-item-resources" data-lesson-id="lesson-' . $lessonid . '">';

                            foreach ($resources->jsonSerialize() as $k => $resource) {
                                $resourceid = $resource->id;
                                $resourceTitle = $resource->Title;
                                $resourceMedia_id = $resource->Media_id;
                                $resourceURL = wp_get_attachment_url($resourceMedia_id);
                                $resourceLesson_id = $resource->Lesson_id;
                                $output .= '<li>';
                                $output .= '<a href="'. $resourceURL . '" target="_blank" id="resource-' . $resourceid . '" class="archive-lesson-resource-link">' . $resourceTitle . '</a>';
                                $output .= '</li>';
                            }
                            $output .= '</ul>';
                        }
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                }

                $output .= '</div><hr/>';
            }
        } else {
            $output .= 'Sorry, no themes found for that Age Group';
        }
    } else {
        $output .= 'No Age Group Set.';
    }
    return $output;
}
