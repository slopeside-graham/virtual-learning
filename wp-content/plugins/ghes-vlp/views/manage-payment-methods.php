<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_manage_payment_methods_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-manage-payment-methods');
}

function vlp_parent_payment_method($atts, $content = null)
{

    enqueue_manage_payment_methods_scripts();

    $output = '';

    $output .= "<h2>VLP Payment Methods</h2>";
    $output .= "<div id='vlpManagePaymentMethodsGrid'></div>";

    return $output;
}