<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;
use GHES\VLP\Subscription;
use GHES\VLP\Payment;
use GHES\VLP\SubscriptionPayment;

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_cancel_confirmation_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-cancel-confirmation');
}

function vlp_cancel_confirmation($atts, $content = null)
{

    GHES\VLP\Utils::CheckSubscriptionStatus();
    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_cancel_confirmation_scripts();
    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    $output .= '<h2>Your Subscription was cancelled successfully</h2>';
    $output .= '<h3>See cancellation details below</h3>';

    $subscriptioncancelid = $_COOKIE['subscriptioncancelid'];
    $totalPayment = 0;
    $payments = Payment::GetAllCancelledBySubscriptionId($subscriptioncancelid);
    foreach ($payments->jsonSerialize() as $k => $payment) {
        $totalPayment += $payment->Amount;
    }

    $output .= '<h3>Refund Amount: ' . $formatter->formatCurrency($totalPayment, 'USD') . '</h3>';

    $cancelledSubscription = Subscription::Get($subscriptioncancelid);
    $subscriptionDefinition = SubscriptionDefinition::Get($cancelledSubscription->SubscriptionDefinition_id);
    $output .= 'Cancelled Subscription: ' . $subscriptionDefinition->Name . ' - ' . date('m/d/Y', strtotime($cancelledSubscription->StartDate)) . ' - ' . date('m/d/Y', strtotime($cancelledSubscription->EndDate));



    $output .= '<ul>';
    foreach ($payments->jsonSerialize() as $k => $payment) {
        $output .= '<li>Payment Type: ' . $payment->Type . '</li>';
        $output .= '<ul>';
        $output .= '<li>Paid: ' . date('m/d/Y', strtotime($payment->DateCreated)) . ' - ' . $formatter->formatCurrency($payment->Amount, 'USD') . '</li>';
        $output .= '<li>' . $payment->accountType . ': ' . $payment->accountNumber . '</li>';
        $output .= '</ul>';
    }
    $output .= '</ul>';

    $subscriptionPayments = SubscriptionPayment::GetAllBySubscriptionId($subscriptioncancelid);

    $output .= '<ul>';
    foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
        $output .= '<li>' . $subscriptionPayment->Status . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</li>';
    }
    $output .= '</ul>';

    $output .= '<hr>';
    $output .= '<div class="vll-links">';

    $managesubscriptionlink = get_permalink(esc_attr(get_option('vlp-manage')));
    $launchgameboardlink = get_permalink(esc_attr(get_option('vlp-agetree'))) . '?destination=Gameboard';
    $myprofilelink = get_permalink(esc_attr(get_option('registration_welcome_url')));
    $output .= '<a href="' . $managesubscriptionlink . '">Manage Subscription</a>';
    $output .= '<a href="' . $launchgameboardlink . '">Launch Gameboard</a>';
    $output .= '<a href="' . $myprofilelink . '">My Profile</a>';

    $output .= '</div>';



    return $output;
}
