<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Subscription;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_select_subscription_scripts()
{
    wp_enqueue_script('wp-api-select-subscription');
}

function vlp_select_subscription($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInParent();
    $parentid = GHES\Parents::UserID();

    $allSubscriptions = Subscription::GetAllActiveByParentId($parentid);

    if (!$allSubscriptions->jsonSerialize()) {

        enqueue_select_subscription_scripts();

    $output = '';
?>
    <script>
        var manageSubscriptionPage = "<?php echo (get_permalink(get_option('vlp-manage'))); ?>";
    </script>
<?php

    $output .= '<form id="select-subscription-vll">';
    $output .= '<h3>Select Subscription Level:</h3>';
    $subscriptiondefinitions = SubscriptionDefinition::GetAll();

    foreach ($subscriptiondefinitions->jsonSerialize() as $k => $subscriptiondefinition) {
        $output .= '<input type="radio" id="subscription-' . $subscriptiondefinition->id . '" name="subscription-select" value="' . $subscriptiondefinition->id . '" data-monthly-price="' . $subscriptiondefinition->MonthlyAmount . '" data-yearly-price="' . $subscriptiondefinition->YearlyAmount . '" required validationMessage="Please Select a Subscription Level">';
        $output .= '<label for="subscription-' . $subscriptiondefinition->id . '">&nbsp;' . $subscriptiondefinition->Name . '</label><br/>';
    }
    $output .= '<span class="k-invalid-msg" data-for="subscription-select"></span><br/>';
    $output .= '<h3>Select Payment Frequency:</h3>';

    $output .= '<input type="radio" id="monthly" name="payment-frequency" value="monthly" data-price="0" required validationMessage="Please Select a Payment Frequency">';
    $output .= '<label for="monthly">&nbsp;Monthly</label><br/>';
    $output .= '<input type="radio" id="yearly" name="payment-frequency" value="yearly" data-price="0" required validationMessage="Please Select a Payment Frequency">';
    $output .= '<label for="yearly">&nbsp;Yearly</label><br/>';
    $output .= '<span class="k-invalid-msg" data-for="payment-frequency"></span><br/>';
    $output .= '<div class="recurring-billing"><label for="recurring"><input type="checkbox" name="recurring" id="recurring">&nbsp;Enable Recurring Billing</label></div>';
    $today = date("m/d/Y");
    $oneyear = date("m/d/Y", strtotime('+1 years, -1 days'));
    $output .= '<h3>Subscription Start Date: <span id="sub-start-date">' . $today . '</span><h3>';
    $output .= '<h3>Subscription End Date: <span id="sub-end-date">' . $oneyear . '</span><h3>';
    $output .= '<h3 id="subscription-total-area">Total Due Today: </h3>';

    $output .= '<button name="continue-payment-vlp" id="continue-payment-vlp" class="ghes-save" type="submit">Continue to Payment</button>';
    $output .= '<div id="window"></div>';
    $output .= '</form>';
    // $output .= $billinginfo;
    return $output;
} else {
    $managesubscription = get_permalink(esc_attr(get_option('vlp-manage')));
    header("Location: $managesubscription");
}
}
