<?php

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

    $gameboard = file_get_contents('wp-content/plugins/ghes-vlp/views/templates/gameboard-1.html');

    $output = '';
    $output .= $gameboard;
    return $output;
}
