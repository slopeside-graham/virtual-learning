<?php

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_lessons_scripts()
{
    wp_enqueue_script('wp-api-manage-lessons');
    wp_enqueue_style('manage-lessons-style');
}


function vlp_view_manage_lessons()
{

    enqueue_lessons_scripts();

    $output = '';
    $output .= '<div id="lesson-grid"><div id="grid"></div>';
    return $output;
}
