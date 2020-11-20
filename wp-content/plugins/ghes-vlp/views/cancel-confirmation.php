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
    wp_enqueue_script('wp-api-cancel-confirmation');
}

function vlp_cancel_confirmation($atts, $content = null)
{
    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_cancel_confirmation_scripts();
    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    $output .= '<h2>Your Subscription was cancelled successfully</h2>';
    $output .= '<h3>See cancellation details below</h3>';

    $subscriptioncancelid = $_COOKIE['subscriptioncancelid'];
    $cancelledSubscription = Subscription::Get($subscriptioncancelid);
    $subscriptionDefinition = SubscriptionDefinition::Get($cancelledSubscription->SubscriptionDefinition_id);
    $output .= 'Cancelled Subscription: ' . $subscriptionDefinition->Name . ' - ' . date('m/d/Y', strtotime($cancelledSubscription->StartDate)) . ' - ' . date('m/d/Y', strtotime($cancelledSubscription->EndDate));

    $payments = Payment::GetAllCancelledBySubscriptionId($subscriptioncancelid);

    $output .= '<ul>';
    foreach ($payments->jsonSerialize() as $k => $payment) {
        $output .= '<li>Payment Type: ' . $payment->Type . '</li>';
        $output .= '<ul>';
        $output .= '<li>' . $formatter->formatCurrency($payment->Amount, 'USD') . '</li>';
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

    



    return $output;
}
