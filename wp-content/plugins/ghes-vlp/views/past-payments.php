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
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-past-payments');
    wp_enqueue_style('print-vlp-style');
}

function vlp_past_payments($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    enqueue_past_payments_scripts();

    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

    $output = '';
    $parentid = GHES\Parents::GetByUserID(get_current_user_id())->id;

    $paidSubscriptions = GHES\VLP\Subscription::GetAllActiveByParentId($parentid);

    if ($paidSubscriptions->jsonSerialize()) {
        $output .= '<hr>';
        $output .= '<div class="paid-payments">';
        $output .= '<h3>My Past Payments</h3>';
        $output .= '<div class="tax">Georgetownhill Early School Tax ID: ' . get_option('registration-taxid') . '</div>';
        $output .= '<ul>';

        foreach ($paidSubscriptions->jsonSerialize() as $k => $paidSubscription) {
            $payments = GHES\VLP\Payment::GetAllBySubscriptionId($paidSubscription->id);

            $subscriptionDefinition = GHES\VLP\SubscriptionDefinition::Get($paidSubscription->SubscriptionDefinition_id);
            if ($paidSubscription->PaymentFrequency == "yearly") {
                $paymentFrequency = "Yearly";
            } else if ($paidSubscription->PaymentFrequency == "monthly") {
                $paymentFrequency = "Monthly";
            }
            $output .= '<li>Subscription: ' . $paidSubscription->Status . ' - ' .  $subscriptionDefinition->Name . ' - ' . $paidSubscription->StartDate . ' - ' . $paidSubscription->EndDate . ' - ' . $paymentFrequency . ' Payments</li>';
            $output .= '<ul>';
            foreach ($payments->jsonSerialize() as $k => $payment) {
                $output .= '<li>Payment: ' . $formatter->formatCurrency($payment->Amount, 'USD') . ' - ' . date('m/d/Y', strtotime($payment->DateCreated)) . ' - ' . $payment->accountType . ' - ' . $payment->accountNumber . '</li>';
                $subscriptionPayments = GHES\VLP\SubscriptionPayment::GetAllPaidBySubscriptionIdandPaymentId($paidSubscription->id, $payment->id);
                $output .= '<ul>';
                foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                    $output .= '<li>' . $subscriptionPayment->Status . ' - ' . $formatter->formatCurrency($subscriptionPayment->Amount, 'USD') . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</li>';
                }
                $output .= '</ul>';
            }
            $output .= '</ul>';
        }

        $output .= '</ul>';
        $output .= '</div>';

        $output .= '<div class="vll-links">';
        $output .= '<hr>';

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
