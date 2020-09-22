<?php

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_agegroups_scripts()
{
    wp_enqueue_script('wp-api-utils');
    wp_enqueue_script('wp-api-manage-agegroups');
    wp_enqueue_style('manage-agegroups-style');
}


function vlp_view_manage_agegroups()
{

    enqueue_agegroups_scripts();

    $output = '';
    $output .= '<div id="agegroups-grid"><div id="grid"></div>';
    return $output;
}
