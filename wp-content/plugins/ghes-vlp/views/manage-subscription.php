<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_manage_scripts()
{
    wp_enqueue_script('wp-api-manage');
}

function vlp_manage_subscription($atts, $content = null)
{
    enqueue_manage_scripts();

    $output = '';
    $output .= '<h1>Test</h1>';

    return $output;
}