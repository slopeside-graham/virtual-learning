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
                    $output .= '<li>' . $paidPayment->Status . ' - $' . $paidPayment->Amount . ' - ' . date('m/d/Y', strtotime($paidPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($paidPayment->EndDate)) . '<br/>';
                    $output .= 'Payment Date: ' .  date('m/d/Y', strtotime($paidPayment->PaymentDate)) . '</li>';
                }
            } else {
                $output .= '<li>There are no past payments for this subscription.</li>';
            }
            $output .= '</ul>';
        }

        $output .= '</ul>';
        $output .= '</div>';
    }
    return $output;
}
