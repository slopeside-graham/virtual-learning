<?php

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_lessons_scripts()
{
    wp_enqueue_script('wp-api-utils');
    wp_enqueue_script('wp-api-manage-lessons');
    wp_enqueue_style('manage-lessons-style');
}


function vlp_view_manage_lessons()
{

    enqueue_lessons_scripts();

    $lessonTemplate = file_get_contents(plugin_dir_path(__FILE__) . 'templates/lesson-editor.html');
    $relatedItemsTemplate = file_get_contents(plugin_dir_path(__FILE__) . 'templates/related-items-editor.html');

    $output = '';

    $output .= '<script type="text/x-kendo-template" id="RelatedMaterialstemplate">';
    $output .= $relatedItemsTemplate;
    $output .= '</script>';
    $output .= '<script id="lesson-editor" type="text/x-kendo-template">';
    $output .= $lessonTemplate;
    $output .= '</script>';
    $output .= '<div id="lesson-grid"><div id="grid">';
    $output .= '<div class="lesson-loading-window"></div>';
    $output .= '</div>';
    return $output;
}
