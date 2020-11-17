<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_payment_confirmation_scripts()
{
    wp_enqueue_script('wp-api-past-payments');
}

function vlp_payment_confirmation($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_payment_confirmation_scripts();

    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;


    return $output;
}