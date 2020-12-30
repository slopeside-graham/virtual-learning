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
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-manage');
}

function vlp_manage_subscription($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_manage_scripts();

    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    // Page Links
    $pastpaymentslink = get_permalink(esc_attr(get_option('vlp-past-payments')));
    $paymentconfirmationlink = get_permalink(esc_attr(get_option('vlp-payment-confirmation')));
    $cancelconfirmationlink = get_permalink(esc_attr(get_option('vlp-cancel-confirmation')));

    $output .= '<script>var paymentconfirmationlink = "' . $paymentconfirmationlink . '"</script>';
    $output .= '<script>var cancelconfirmationlink = "' . $cancelconfirmationlink . '"</script>';

    $output .= '<h3 class="successful-payment">Thank you for your payment, please see your subscription details below.</h3>';
    $output .= '<h3 class="successful-refund">Your cancellation was successful, please see below details below.</h3>';

    $subscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
    if ($subscriptions->jsonSerialize()) {
        foreach ($subscriptions->jsonSerialize() as $k => $subscription) {
            if ($subscription->Status != "Cancelled") {
                $output .= "Subscription Active";
            }
        } 

        $unpaidSubscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);
        if ($unpaidSubscriptions->jsonSerialize()) {

            $output .= '<h3>My Subscriptions</h3>';
            $output .= '<ul>';
            foreach ($unpaidSubscriptions->jsonSerialize() as $k => $currentUnpaidSubscription) {
                $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($currentUnpaidSubscription->SubscriptionDefinition_id);
                $subscriptionPayments = GHES\VLP\SubscriptionPayment::GetCurrentDueBySubscriptionId($currentUnpaidSubscription->id);
                $activeSubscriptionPayments = GHES\VLP\SubscriptionPayment::GetAllPaidBySubscriptionId($currentUnpaidSubscription->id);
                if ($currentUnpaidSubscription->PaymentFrequency == "yearly") {
                    $paymentFrequency = "Yearly";
                } else if ($currentUnpaidSubscription->PaymentFrequency == "monthly") {
                    $paymentFrequency = "Monthly";
                }
                if ($currentUnpaidSubscription->Status == 'Unpaid') {
                    $output .= '<li data-subscriptionId="' . $currentUnpaidSubscription->id . '" data-subscriptionType="' . $currentUnpaidSubscription->PaymentFrequency . '">' . $subscriptionDefinition->Name . ' - ' . $currentUnpaidSubscription->StartDate . ' - ' . $currentUnpaidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments. ';
                } else {
                    $output .= '<li data-subscriptionId="' . $currentUnpaidSubscription->id . '" data-subscriptionType="' . $currentUnpaidSubscription->PaymentFrequency . '">' . $currentUnpaidSubscription->Status . ': ' . $subscriptionDefinition->Name . ' - ' . $currentUnpaidSubscription->StartDate . ' - ' . $currentUnpaidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments. ';
                }
                if ($currentUnpaidSubscription->Status != 'Cancelled' && $currentUnpaidSubscription->Status !=  'Unpaid') {
                    $output .= '<button class="cancel-button cancel-btn" onclick="openCancelDialog(this)">Cancel Subscription</button>';
                }
                $output .= '</li>';
                $output .= '<ul class="checkbox-list">';
                if ($subscriptionPayments->jsonSerialize()) {
                    foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                        $output .= '<li><label><input class="current-due subscription-payment" data-id="' . $subscriptionPayment->id . '" data-status="' . $subscriptionPayment->Status . '" type="checkbox" checked disabled value="' . $subscriptionPayment->Amount . '"> Due Today - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                    }
                } else {
                    $output .= '<li>You have no payments currently due.</li>';
                    if ($activeSubscriptionPayments->jsonSerialize()) {
                        foreach ($activeSubscriptionPayments->jsonSerialize() as $k => $activeSubscriptionPayment) {
                            $output .= '<li>Your subscription will stay active until ' . date('m/d/Y', strtotime($activeSubscriptionPayment->EndDate)) . '.</li>';
                        }
                    }
                }
                $pastPayments = GHES\VLP\Payment::GetAllBySubscriptionId($currentUnpaidSubscription->id);
                if ($pastPayments->jsonSerialize()) {
                    $output .= '<li><a href="' . $pastpaymentslink . '">View Past Payments &#8594;</a></li>';
                }
                $output .= '</ul>';
            }
            $output .= '</ul>';


            foreach ($unpaidSubscriptions->jsonSerialize() as $k => $futureUnpaidSubscription) {
                $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($futureUnpaidSubscription->SubscriptionDefinition_id);
                $subscriptionPayments = GHES\VLP\SubscriptionPayment::GetUpcomingBySubscriptionId($futureUnpaidSubscription->id);
                if ($subscriptionPayments->jsonSerialize()) {
                    $output .= '<h3>Upcoming Payments</h3>';
                    $output .= '<p>The below items are due in the future. You are welcome to pay for them early if you like. If you are enrolled in automatic billing the payments will be automatically paid.</p>';
                    $output .= '<p>If you would like to pay fo future months, please select the items to pay for and click the "Pay Now" button.</p>';
                    $output .= '<ul>';
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
                            if ($subscriptionPayment->Status == 'Unpaid') {
                                $output .= '<li><label><input type="checkbox" ' .  $checked . ' class="future-due subscription-payment" data-id="' . $subscriptionPayment->id . '" data-status="' . $subscriptionPayment->Status . '"" value="' . $subscriptionPayment->Amount . '"> Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                            } else {
                                $output .= '<li><label><input type="checkbox" ' .  $checked . ' class="future-due subscription-payment" data-id="' . $subscriptionPayment->id . '" data-status="' . $subscriptionPayment->Status . '"" value="' . $subscriptionPayment->Amount . '"> ' . $subscriptionPayment->Status . ' - Amount: $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</label></li>';
                            }
                        }
                    } else {
                        $output .= '<li>No Upcoming Payments.</li>';
                    }
                    $output .= '</ul>';
                }
            }
            $output .= '</ul>';

            $output .= '<h3 class="total-due">Total Due: $0.00</h3><br/>';

            $output .= '<button id="showpaymentbtn" class="green-btn" onclick="showPurchase()">Pay Now</button>';
            // Use this in the future
            /*
            $output .= '<h3>Select Existing Payment Method</h3>';
            $output .= '<div id="customer-payment-methods"></div>';

            $output .= '<script type="text/x-kendo-template" id="customer-payment-methods-list-template">';
            $output .= '<div class="payment-method">';
            $output .= '<h3>#:AccountType#</h3>';
            $output .= '</div>';
            $output .= '</script>';
            */

            $billinginfofilepath = plugin_dir_path(__FILE__) . '/templates/purchase-billing.html';
            $billinginfo = file_get_contents($billinginfofilepath);

            $output .= $billinginfo;
            $output .= '<div id="cancel-subscription"></div>';
        }
    } else {
        $selectSubscriptionPage = get_permalink(esc_attr(get_option('vlp-purchase')));
        $output .= '<a href="' . $selectSubscriptionPage . '">You have no active subscriptions, Please select a subscription first -></a>';
    }
    $output .= '<hr>';

    $managesubscriptionlink = get_permalink(esc_attr(get_option('vlp-manage')));
    $launchgameboardlink = get_permalink(esc_attr(get_option('vlp-agetree'))) . '?destination=Gameboard';
    $myprofilelink = get_permalink(esc_attr(get_option('registration_welcome_url')));

    $output .= '<div class="vll-links">';
        $output .= '<a href="' . $managesubscriptionlink . '">Manage Subscription</a>';
        $output .= '<a href="' . $launchgameboardlink . '">Launch Gameboard</a>';
        $output .= '<a href="' . $myprofilelink . '">My Profile</a>';
    $output .= '</div>';

    return $output;
}
