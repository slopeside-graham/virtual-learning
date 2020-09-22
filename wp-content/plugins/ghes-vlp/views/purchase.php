<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_purchase_scripts()
{
    wp_enqueue_script('wp-api-purchase');
}

function vlp_purchase($atts, $content = null)
{
    enqueue_purchase_scripts();

    $output = '';

    $purchasebodyfilepath = plugin_dir_path(__FILE__) . '/templates/purchase.html';
    $purchasebody = file_get_contents($purchasebodyfilepath);
    $billinginfofilepath = plugin_dir_path(__FILE__) . '/templates/purchase-billing.html';
    $billinginfo = file_get_contents($billinginfofilepath);

    $output .= '<div id="validation-summary">';
    $output .= '</div>';
    $output .= '<form id="purchase-vll">';
    $output .= '<h3>Select Subscription Level:</h3>';
    $subscriptiondefinitions = SubscriptionDefinition::GetAll();

    foreach ($subscriptiondefinitions->jsonSerialize() as $k => $subscriptiondefinition) {
        $output .= '<input type="radio" id="subscription-' . $subscriptiondefinition->id . '" name="subscription-select" value="' . $subscriptiondefinition->id . '" data-monthly-price="' . $subscriptiondefinition->MonthlyAmount . '" data-yearly-price="' . $subscriptiondefinition->YearlyAmount . '" required>';
        $output .= '<label for="subscription-' . $subscriptiondefinition->id . '">&nbsp;' . $subscriptiondefinition->Name . '</label><br/>';
    }
    $output .= '<span class="k-invalid-msg" data-for="subscription-select"></span><br/>';
    $output .= '<h3>Select Payment Frequency:</h3>';

    $output .= '<input type="radio" id="monthly" name="payment-frequency" value="monthly" data-price="0" required validationMessage="Please Select a Payment Frequency">';
    $output .= '<label for="monthly">&nbsp;Monthly</label><br/>';
    $output .= '<input type="radio" id="yearly" name="payment-frequency" value="yearly" data-price="0" required validationMessage="Please Select a Payment Frequency">';
    $output .= '<label for="yearly">&nbsp;Yearly</label><br/>';
    $output .= '<span class="k-invalid-msg" data-for="payment-frequency"></span><br/>';
    $output .= '<h3 id="user-id">User ID: ' . get_current_user_id() . '</h3>';
    $output .= '<h3>Parent ID: <span id="parent-id">' . GHES\Parents::GetByUserID(get_current_user_id())->id . '</span></h3>';
    $today = date("m/d/Y");
    $oneyear = date("m/d/Y", strtotime('+1 years, -1 days'));
    $output .= '<h3>Subscription Start Date: <span id="sub-start-date">' . $today . '</span><h3>';
    $output .= '<h3>Subscription End Date: <span id="sub-end-date">' . $oneyear . '</span><h3>';
    $output .= '<h3 id="subscription-total-area">Total: </h3>';

    $output .= '<button name="purchase" id="purchase-vlp" class="ghes-save" type="submit">Purchase</button>';
    $output .= '</form>';
    // $output .= $billinginfo;
    return $output;
}
