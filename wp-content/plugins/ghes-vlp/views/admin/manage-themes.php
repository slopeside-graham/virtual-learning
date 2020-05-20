<?php

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_themes_scripts()
{
    wp_enqueue_script('wp-api-manage-themes');
    wp_enqueue_style('manage-themes-style');
}


function vlp_view_manage_themes()
{

    enqueue_themes_scripts();

    $output = '';
    $output .= 'Test of the manage theme view';
    return $output;
}
