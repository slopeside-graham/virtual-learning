<?php

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_themes_scripts()
{
    wp_enqueue_script('wp-api-themes');
}


function vlp_themes($atts, $content = null)
{

    enqueue_themes_scripts();

    $output = '';
    $output .= 'Test';
    return $output;
}
