<?php

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_themes_scripts()
{
    wp_enqueue_script('wp-api-utils');
    wp_enqueue_script('wp-api-manage-themes');
    wp_enqueue_style('manage-themes-style');
}


function vlp_view_manage_themes()
{

    enqueue_themes_scripts();

    $output = '';
    $output .= '<div id="theme-grid"><div id="grid"></div>';
    return $output;
}
