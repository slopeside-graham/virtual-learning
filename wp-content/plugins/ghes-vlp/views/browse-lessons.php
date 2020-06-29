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

    if (isset($_COOKIE['VLPThemeId'])) {
        $themeid = $_COOKIE['VLPThemeId'];
    } else {
        $output .= 'No Theme Selected';
        return $output;
    }

    $playicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/play.svg');
    $articon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/art.svg');
    $learnicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/learn.svg');
    $nurtureicon = file_get_contents(plugin_dir_url(dirname(__FILE__)) . '/assets/icons/nurture.svg');

    $gobutton = plugin_dir_url(dirname(__FILE__)) . '/assets/Buttons/Go Button.png';


    $output .= '<div class="hiden-lesson-icons">';
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
    $output .= '    <span class="browse-lesson-title">#: Title#</span>';
    $output .= '    <span class="browse-lesson-go-btn">Lets Go!</span>';
    $output .= '    <span class="browse-lesson-spacer">&nbsp;</span>';
    $output .= '    <span class="browse-lesson-go-icon"><img src="' . $gobutton . '"></span>'; //TODO what does this link to?
    $output .= ' </div>';
    $output .= '</script>';

    return $output;
}
