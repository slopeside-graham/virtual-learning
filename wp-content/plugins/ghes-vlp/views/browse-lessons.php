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

function enqueue_browse_lessons_scripts()
{
    wp_enqueue_script('wp-api-browse-lessons');
}

function vlp_browse_lessons($atts, $content = null)
{
    enqueue_browse_lessons_scripts();

    $output = '';

    $playicon = file_get_contents(plugin_dir_path(__FILE__) . '../assets/icons/play.svg');
    $articon = file_get_contents(plugin_dir_path(__FILE__) . '../assets/icons/art.svg');
    $learnicon = file_get_contents(plugin_dir_path(__FILE__) . '../assets/icons/learn.svg');
    $nurtureicon = file_get_contents(plugin_dir_path(__FILE__) . '../assets/icons/nurture.svg');

    $gobuttonURL = plugin_dir_url(dirname(__FILE__)) . '/assets/Buttons/Go Button.png';

    $completionIconURL = plugin_dir_url(dirname(__FILE__)) . 'assets/Star.png';

    $output .= '<div class="hidden-lesson-icons">';
    $output .= '<div hidden id="play-icon">' . $playicon . '</div>';
    $output .= '<div hidden id="art-icon">' . $articon . '</div>';
    $output .= '<div hidden id="learn-icon">' . $learnicon . '</div>';
    $output .= '<div hidden id="nurture-icon">' . $nurtureicon . '</div>';
    $output .= '</div>';


    $output .= '<div class="browse-lessons-header">What do you want to learn today?</div>';
    $output .= '<div class="k-content wide">';
    $output .= '<div id="lessons-listView"></div>';
    $output .= '</div>';

    $output .= '<script type="text/x-kendo-template" id="lesson-template">';
        $output .= '<div class="browse-lessons">';
        $output .= '    <span class="browse-lesson-icon #: Type#-icon">#: Type#</span>';
        $output .= '    <span class="browse-lesson-spacer-blue">&nbsp;</span>';
        $output .= '    <span class="browse-lesson-theme-title">#: ThemeTitle#</span>';
        $output .= '    <span class="browse-lesson-spacer">&nbsp;</span>';
        $output .= '    <span class="browse-lesson-title Completed-#: Completed#">#: Title#<span class="lesson-completion-icon"><img src="' . $completionIconURL . '" /></span></span>';
        $output .= '    <span class="browse-lesson-go-btn">Lets Go!</span>';
        $output .= '    <span class="browse-lesson-spacer">&nbsp;</span>';
        $output .= '    <span class="browse-lesson-go-icon" data-lesson-id="#: id#" onclick="openLessonPopup(this)"><img src="' . $gobuttonURL . '"></span>'; //TODO what does this link to?
        $output .= ' </div>';
        $output .= ' <div class="lesson-popup type-#: Type#" id="lesson-#: id#">';
            $output .= '<span class="close-button" onclick="closeLessonPopup(this)">&times;</span>';
            $output .= '<span class="corner-icon #: Type#-icon">#: Type#</span>';
            $output .= '<div class="lesson-content">';
                $output .= '<div class="first-column">';
                    $output .= '<span class="type-heading">#: Type# Activity</span><br/>';
                    $output .= '<span class="lesson-title">#: Title #</span><br/>';
                    $output .= '<p class="lesson-main-content">#= MainContent #</p>';
                $output .= '</div>';
                $output .= '<div class="second-column">';
                    $output .= '<div class="lesson-video">';
                        $output .= '<iframe src="#: VideoURL #"frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe><span class="completion-icon video-completion-icon"><img src="' . $completionIconURL . '" /></span>';
                    $output .= '</div>';
                    $output .= '<div class="resources"><span class="related-materials-title">Related Materials</span><br/>';
                        $output .= '<ul id="resources-listView-lesson-#: id#" class="related-materials-list"></ul>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= ' </div>';
        $output .= ' </div>';
    $output .= ' </script>';
    $output .= '<script type="text/x-kendo-template" id="lesson-resources-template">';
    $output .= '    <li class="resource-title"><a href="#: ResourceLink #" target="_blank">#: Title#</a></li>';
    $output .= '</script>';

    return $output;
}