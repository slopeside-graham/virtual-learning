<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_past_payments_scripts()
{
    wp_enqueue_script('wp-api-past-payments');
}

function vlp_past_payments($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_past_payments_scripts();

    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    $paidSubscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
    if ($paidSubscriptions->jsonSerialize()) {
        $output .= '<hr>';
        $output .= '<div class="paid-payments">';
        $output .= '<h3>My Past Payments</h3>';
        $output .= '<ul>';
        foreach ($paidSubscriptions->jsonSerialize() as $k => $paidSubscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($paidSubscription->SubscriptionDefinition_id);
            if ($paidSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($paidSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>' . $paidSubscription->Status . ' - ' .  $subscriptionDefinition->Name . ' - ' . $paidSubscription->StartDate . ' - ' . $paidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments.</li>';
            $output .= '<ul>';
            $paidPayments = GHES\VLP\SubscriptionPayment::GetAllExceptUnpaidandCancelledBySubscriptionId($paidSubscription->id);
            if ($paidPayments->jsonSerialize()) {
                foreach ($paidPayments->jsonSerialize() as $k => $paidPayment) {
                    $payment = GHES\VLP\Payment::Get($paidPayment->Payment_id);
                    $output .= '<li>' . $paidPayment->Status . ' - $' . $paidPayment->Amount . ' - ' . date('m/d/Y', strtotime($paidPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($paidPayment->EndDate)) . '<br/>';
                    $output .= 'Payment Date: ' .  date('m/d/Y', strtotime($paidPayment->PaymentDate)) . ', ';
                    $output .= $payment->accountType . ' - ' . $payment->accountNumber . '</li>';
                }
            } else {
                $output .= '<li>There are no past payments for this subscription.</li>';
            }
            $output .= '</ul>';
        }

        $output .= '</ul>';
        $output .= '</div>';
        $output .= '<hr>';
        $output .= '<div class="vll-links">';

        $managesubscriptionlink = get_permalink(esc_attr(get_option('vlp-manage')));
        $launchgameboardlink = get_permalink(esc_attr(get_option('vlp-agetree'))) . '?destination=Gameboard';
        $myprofilelink = get_permalink(esc_attr(get_option('registration_welcome_url')));
        $output .= '<a href="' . $managesubscriptionlink . '">Manage Subscription</a>';
        $output .= '<a href="' . $launchgameboardlink . '">Launch Gameboard</a>';
        $output .= '<a href="' . $myprofilelink . '">My Profile</a>';

        $output .= '</div>';
    }
    return $output;
}
