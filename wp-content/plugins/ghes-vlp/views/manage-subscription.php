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

    $activeSubscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
    if ($activeSubscriptions->jsonSerialize()) {
        $output .= '<h3>My Active Subscriptions</h3>';
        $output .= '<ul>';
        foreach ($activeSubscriptions->jsonSerialize() as $k => $activeSubscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($activeSubscription->SubscriptionDefinition_id);
            if ($activeSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($activeSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>' . $subscriptionDefinition->Name . ' - ' . $activeSubscription->StartDate . ' - ' . $activeSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments.</li>';
        }
        $output .= '</ul>';
    }


    $unpaidSubscriptions = GHES\VLP\Subscription::GetAllByParentId($parentid);
    if ($unpaidSubscriptions->jsonSerialize()) {

        $output .= '<h3>Current Due Payment</h3>';
        $output .= '<ul>';
        foreach ($unpaidSubscriptions->jsonSerialize() as $k => $currentUnpaidSubscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($currentUnpaidSubscription->SubscriptionDefinition_id);
            $subscriptionPayments = \GHES\VLP\SubscriptionPayment::GetCurrentDueBySubscriptionId($currentUnpaidSubscription->id);
            if ($currentUnpaidSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($currentUnpaidSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>' . $subscriptionDefinition->Name . ' - ' . $currentUnpaidSubscription->StartDate . ' - ' . $currentUnpaidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments.</li>';
            $output .= '<ul class="checkbox-list">';
            if ($subscriptionPayments->jsonSerialize()) {
                foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                    $output .= '<li><label><input class="current-due subscription-payment" data-id="' . $subscriptionPayment->id . '" type="checkbox" checked value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                }
            } else {
                $output .= '<li>You have no current payments.</li>';
            }
            $output .= '</ul>';
        }
        $output .= '</ul>';

        $output .= '<h3>My Upcoming Payments</h3>';
        $output .= '<ul>';
        foreach ($unpaidSubscriptions->jsonSerialize() as $k => $futureUnpaidSubscription) {
            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($futureUnpaidSubscription->SubscriptionDefinition_id);
            $subscriptionPayments = \GHES\VLP\SubscriptionPayment::GetUpcomingBySubscriptionId($futureUnpaidSubscription->id);
            if ($futureUnpaidSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($futureUnpaidSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>' . $subscriptionDefinition->Name . ' - ' . $futureUnpaidSubscription->StartDate . ' - ' . $futureUnpaidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments.</li>';
            $output .= '<ul class="checkbox-list">';
            if ($subscriptionPayments->jsonSerialize()) {
                foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                    $output .= '<li><label><input type="checkbox" class="future-due subscription-payment" data-id="' . $subscriptionPayment->id . '" value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                }
            } else {
                $output .= '<li><label>All Payments are Currently due.</label></li>';
            }
            $output .= '</ul>';
        }
        $output .= '</ul>';

        $output .= '<h3 id="current-due">Current Due: $0.00</h3>';
        $output .= '<h3 class="total-due">Total Due: $0.00</h3><br/>';

        $output .= '<button id="showpaymentbtn" onclick="showPurchase()">Pay Now</button>';

        $billinginfofilepath = plugin_dir_path(__FILE__) . '/templates/purchase-billing.html';
        $billinginfo = file_get_contents($billinginfofilepath);

        $output .= $billinginfo;


        $activeSubscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
        if ($activeSubscriptions->jsonSerialize()) {
            $output .= '<hr>';
            $output .= '<div class="paid-payments">';
            $output .= '<h3>My Past Payments</h3>';
            $output .= '<ul>';
            foreach ($activeSubscriptions->jsonSerialize() as $k => $activeSubscription) {
                $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($activeSubscription->SubscriptionDefinition_id);
                if ($activeSubscription->PaymentFrequency == "yearly") {
                    $paymentFrequency = "Yearly";
                } else if ($activeSubscription->PaymentFrequency == "monthly") {
                    $paymentFrequency = "Monthly";
                }
                $output .= '<li>' . $subscriptionDefinition->Name . ' - ' . $activeSubscription->StartDate . ' - ' . $activeSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments.</li>';
                $output .= '<ul>';
                $paidPayments = GHES\VLP\SubscriptionPayment::GetAllPaidBySubscriptionId($activeSubscription->id);
                foreach ($paidPayments->jsonSerialize() as $k => $paidPayment) {
                    $output .= '<li>Paid - Amount: $' . $paidPayment->Amount . ' - ' . date('m/d/Y', strtotime($paidPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($paidPayment->EndDate)) . '</li>';
                }
                $output .= '</ul>';
            }

            $output .= '</ul>';
            $output .= '</div>';
        }
    }


    return $output;
}
