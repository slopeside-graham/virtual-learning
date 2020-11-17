<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_cancel_confirmation_scripts()
{
    wp_enqueue_script('wp-api-cancel-confirmation');
}

function vlp_cancel_confirmation($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_cancel_confirmation_scripts();
    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;


    return $output;
}
