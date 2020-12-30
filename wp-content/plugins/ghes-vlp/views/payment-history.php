<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_vlp_payment_history_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-vlp-payment-history');
    wp_enqueue_style('print-vlp-style');
}

function vlp_parent_payment_history($atts, $content = null)
{
    enqueue_vlp_payment_history_scripts();

    $output = "";
    $output = "";
    $output .= "<h2>Virtual Learning Payment History</h2>\n";
    $output .= "<div id='vlp-payments' class='ghes-grid'>\n";
        $output .= "<div id='vlp-payments-grid'></div>\n";
    $output .= "</div>\n";

    return $output;
}

