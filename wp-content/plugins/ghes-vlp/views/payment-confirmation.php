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

function enqueue_payment_confirmation_scripts()
{
    wp_enqueue_script('wp-api-payment-confirmation');
    wp_enqueue_style('print-vlp-style');
}

function vlp_payment_confirmation($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_payment_confirmation_scripts();

    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    $output .= '<h2>Thank you for your payment.</h2>';
    $output .= '<p>See payment details below:</p>';

    $paymentid = $_COOKIE['paymentid'];

    $payment  = Payment::Get($paymentid);
    if ($payment != null) {
        if ($payment->jsonSerialize()) {
            $output .= '<p>Payment Amount: ' . $formatter->formatCurrency($payment->Amount, 'USD') . '<br/>';
            $output .= 'Payment Date: ' . date('m/d/Y', strtotime($payment->DateCreated)) . '</p>';
        }
    }
    $output .= '<div class="tax">Georgetownhill Early School Tax ID: ' . get_option('registration-taxid') . '</div>';
    $subscriptions = Subscription::GetAllByPaymentId($paymentid);
    /*
    $output .= '<div id="payments-list"></div>';

    $output .= '<script type="text/x-kendo-template" id="payment-list-template">';
    $output .= '<div class="product">';
    $output .= '<h3>#:Status#</h3>';
    $output .= '</div>';
    $output .= '</script>';
*/
    if ($subscriptions->jsonSerialize()) {
        $output .= '<ul>';
        foreach ($subscriptions->jsonSerialize() as $k => $subscription) {
            $subscriptiondefenition = SubscriptionDefinition::Get($subscription->SubscriptionDefinition_id);
            $subscriptionpayments = SubscriptionPayment::GetAllPaidBySubscriptionIdandPaymentId($subscription->id, $paymentid);
            $output .= '<li>Subscription Type: ' . $subscriptiondefenition->Name . ' - ' . $subscription->Status . '</li>';
            $output .= '<ul>';
            if ($subscriptionpayments->jsonSerialize()) {
                foreach ($subscriptionpayments->jsonSerialize() as $k => $subscriptionpayment) {
                    $output .= '<li>Paid for: ' . date('m/d/Y', strtotime($subscriptionpayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionpayment->EndDate)) . ' - ' . $formatter->formatCurrency($subscriptionpayment->Amount, 'USD') .  '</li>';
                }
            }
            $output .= '</ul>';
        }
        $output .= '</ul>';
    }

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
