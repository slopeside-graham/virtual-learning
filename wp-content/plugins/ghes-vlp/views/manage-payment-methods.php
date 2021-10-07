<?php

use Awf\Date\Date;
use GHES\Parents;
use GHES\VLP;
use GHES\VLP\SubscriptionDefinition;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_manage_payment_methods_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-manage-payment-methods');
}

function vlp_parent_payment_method($atts, $content = null)
{

    enqueue_manage_payment_methods_scripts();

    $output = '';
    $output .= '<div id="vlp-payment-methods">';
    $output .= "<div class='ghes-grid' id='vlpManagePaymentMethodsGrid'></div>";
    $output .= "<div id='vlpManagePaymentMethodsList'></div>";

    // Custom Popup Editor
    $output .= '<script id="popup_editor" type="text/x-kendo-template">';
    $output .= '<div class="k-edit-label">';
    $output .= '<label for="FirstName">First Name</label>';
    $output .= '</div>';
    $output .= '<input name="FirstName" 
        data-bind="value:FirstName" 
        data-value-field="FirstName" 
        data-text-field="FirstName" />';
    $output .= '<div id="billing-form"></div>';
    $output .= '</script>';


    // The template to display the list:
    $output .= "<script type='text/x-kendo-template' id='payment-template'>";
    $output .= "<div class='payment-method'>";
    $output .= "   <div>#:CardNumber#</div>";
    $output .= "   <div>#:CardType#</div>";
    $output .= "   <div>#:ExpirationDate#</div>";
    $output .= "   <div>#:FirstName# #:LastName#</div>";

    $output .= "  </div>";
    $output .= "</script>";
    $output .= "</div>";

    return $output;
}
