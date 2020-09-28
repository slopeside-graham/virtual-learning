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
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    // Get All Subscriptions by PArent, not necessary as we are going to get them in a more specific way.
    /*
    $subscriptions = GHES\VLP\Subscription::GetAllByParentId($parentid);
    if ($subscriptions->jsonSerialize()) {
        $output .= '<h3>My Active Subscriptions</h3>';
        $output .= '<ul>';
        foreach ($subscriptions->jsonSerialize() as $k => $subscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($subscription->SubscriptionDefinition_id);
            $output .= '<li>' . $subscriptionDefinition->Name . ' - ' . $subscription->Status . '</li>';
        }
        $output .= '</ul>';
    }
    */

    $paidSubscriptions = GHES\VLP\Subscription::GetAllPaidByParentId($parentid);
    if ($paidSubscriptions->jsonSerialize()) {
        $output .= '<h3>My Paid Subscriptions</h3>';
        $output .= '<ul>';
        foreach ($paidSubscriptions->jsonSerialize() as $k => $subscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($subscription->SubscriptionDefinition_id);
            $output .= '<li>' . $subscriptionDefinition->Name . ' - ' . $subscription->Status . '</li>';
        }
        $output .= '</ul>';
    }


    $unpaidSubscriptions = GHES\VLP\Subscription::GetAllUnpaidByParentId($parentid);
    if ($unpaidSubscriptions->jsonSerialize()) {

        $output .= '<h2>Current Due Payment</h2>';
        $output .= '<ul>';
        foreach ($unpaidSubscriptions->jsonSerialize() as $k => $currentUnpaidSubscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($currentUnpaidSubscription->SubscriptionDefinition_id);
            $subscriptionPayments = \GHES\VLP\SubscriptionPayment::GetCurrentDueBySubscriptionId($currentUnpaidSubscription->id);
            if ($currentUnpaidSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($currentUnpaidSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>' . $paymentFrequency . ' Subscription: ' . $subscriptionDefinition->Name . ' - ' . $currentUnpaidSubscription->Status . '</li>';
            $output .= '<ul class="checkbox-list">';
            foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                $output .= '<li><label><input class="current-due subscription-payment" type="checkbox" checked value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
            }
            $output .= '</ul>';
        }
        $output .= '</ul>';

        $output .= '<h3 id="current-due">Current Due: $0.00</h3>';
        $output .= '<h3 id="total-due">Total Due: $0.00</h3><br/>';

        $output .= '<h4>My Upcoming Payments</h4>';
        $output .= '<ul>';
        foreach ($unpaidSubscriptions->jsonSerialize() as $k => $futureUnpaidSubscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($futureUnpaidSubscription->SubscriptionDefinition_id);
            $subscriptionPayments = \GHES\VLP\SubscriptionPayment::GetUpcomingBySubscriptionId($futureUnpaidSubscription->id);
            if ($futureUnpaidSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($futureUnpaidSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>' . $paymentFrequency . ' Subscription: ' . $subscriptionDefinition->Name . ' - ' . $futureUnpaidSubscription->Status . '</li>';
            $output .= '<ul class="checkbox-list">';
            if ($subscriptionPayments->jsonSerialize()) {
                foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                    $output .= '<li><label><input type="checkbox" class="future-due subscription-payment" value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                }
            } else {
                $output .= '<li><label>All Payments are Currently due.</label></li>';
            }
            $output .= '</ul>';
        }
        $output .= '</ul>';
    }


    return $output;
}
