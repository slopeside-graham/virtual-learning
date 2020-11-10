<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_manage_scripts()
{
    wp_enqueue_script('wp-api-manage');
}

function vlp_manage_subscription($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_manage_scripts();

    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    // Get All Subscriptions by PArent, not necessary as we are going to get them in a more specific way.
    $subscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
    if ($subscriptions->jsonSerialize()) {
        $output .= '<h3 class="successful-payment">Thank you for your payment, please see your subscription details below.</h3>';

        $unpaidSubscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
        if ($unpaidSubscriptions->jsonSerialize()) {

            $output .= '<h3>My Subscriptions</h3>';
            $output .= '<ul>';
            foreach ($unpaidSubscriptions->jsonSerialize() as $k => $currentUnpaidSubscription) {
                $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($currentUnpaidSubscription->SubscriptionDefinition_id);
                $subscriptionPayments = \GHES\VLP\SubscriptionPayment::GetCurrentDueBySubscriptionId($currentUnpaidSubscription->id);
                if ($currentUnpaidSubscription->PaymentFrequency == "yearly") {
                    $paymentFrequency = "Yearly";
                } else if ($currentUnpaidSubscription->PaymentFrequency == "monthly") {
                    $paymentFrequency = "Monthly";
                }
                $output .= '<li data-subscriptionId="' . $currentUnpaidSubscription->id . '" data-subscriptionType="' . $currentUnpaidSubscription->PaymentFrequency . '">' . $subscriptionDefinition->Name . ' - ' . $currentUnpaidSubscription->StartDate . ' - ' . $currentUnpaidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments. <button class="cancel-button" onclick="openCancelDialog(this)">Cancel Subscription</button></li>';
                $output .= '<ul class="checkbox-list">';
                if ($subscriptionPayments->jsonSerialize()) {
                    foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                        $output .= '<li><label><input class="current-due subscription-payment" data-id="' . $subscriptionPayment->id . '" data-status="' . $subscriptionPayment->Status . '" type="checkbox" checked disabled value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                    }
                } else {
                    $output .= '<li>You have no payments currently due.</li>';
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
                        if ($subscriptionPayment->Status == "Pending") {
                            $checked = 'checked';
                        } else {
                            $checked = null;
                        }
                        $output .= '<li><label><input type="checkbox" ' .  $checked . ' class="future-due subscription-payment" data-id="' . $subscriptionPayment->id . '" data-status="' . $subscriptionPayment->Status . '"" value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                    }
                } else {
                    $output .= '<li>No Upcoming Payments.</li>';
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
            $output .= '<div id="cancel-subscription"></div>';
        }
    } else {
        $selectSubscriptionPage = get_permalink(esc_attr(get_option('vlp-purchase')));
        $output .= '<a href="' . $selectSubscriptionPage . '">You have no active subscriptions, Please select a subscription first -></a>';
    }
    $paidSubscriptions = GHES\VLP\Subscription::GetAllByParentId($parentid);
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
            $paidPayments = GHES\VLP\SubscriptionPayment::GetAllPaidBySubscriptionId($paidSubscription->id);
            if ($paidPayments->jsonSerialize()) {
                foreach ($paidPayments->jsonSerialize() as $k => $paidPayment) {
                    $output .= '<li>Paid - Amount: $' . $paidPayment->Amount . ' - ' . date('m/d/Y', strtotime($paidPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($paidPayment->EndDate)) . '<br/>';
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
