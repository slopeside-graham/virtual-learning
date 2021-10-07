<?php

use Awf\Date\Date;
use GHES\Utils;
use GHES\ghes_base;
use GHES\VLP\Subscription;

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_vlp_welcome_api_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-vlp-welcome');
}

function vlp_welcome()
{

    // This is for testing only. Remove once done
    /*
    $freeSubscription = new GHES\VLP\Subscription();

    $freeSubscription->ParentID = 26;
    $freeSubscription->StartDate = new Date();
    $freeSubscription->EndDate = new Date();
    $freeSubscription->SubscriptionDefinition_id = 1;
    */

    Utils::redirectNotLoggedIn();
    GHES\VLP\Utils::CheckSubscriptionStatus();

    enqueue_vlp_welcome_api_scripts();

    if (ghes_base::UserIsVLPParent()) {
        $hasvlp = 1;
    } else {
        $hasvlp = 0;
    }

    // Page links
    $pastpaymentslink = get_permalink(esc_attr(get_option('vlp-past-payments')));

    $output = '';
    $output .= "<script type='text/javascript'>var hasvlp = " . $hasvlp . "</script>";

    $output .= '<div class="parent-section vlp-section">';
    // $output .= '<div class="vlp-icon welcome-section-icon"><i class="far fa-lightbulb"></i></div>';
    $output .= '<div class="section-details">';
    // $output .= '<div class="section-header"><span>Virtual Learning Library</span></div>';
    if (ghes_base::UserIsVLPParent()) {
        /*
        $output .= '<ul id="parent-is-vlp-links" class="section-links">';
        $output .= '<li><a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Gameboard">Launch Gameboard</a></li>';
        // $output .= '<li><a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Lessons">View Lessons</a></li>';
        // $output .= '<li><a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Themes">View Themes</a></li>';
        $output .= '<li><a href="' . get_permalink(get_option("add_edit_children_url")) . '">Add/Edit Children</a></li>';
        $output .= '<li><a href="' . get_permalink(get_option("vlp-manage")) . '">Manage Subscription</a></li>';
        //$output .= '<li><a href="' . $pastpaymentslink . '">View Past Payments</a></li>';
        $output .= '</ul>';
        $output .= '</ul>'; // close section links
        */
        // Re-written to be more visual
        $launchGameBTN = plugin_dir_url(__DIR__) . 'assets/Buttons/launch-game-btn.png';
        $viewAllLessonsBTN = plugin_dir_url(__DIR__) . 'assets/Buttons/View All Lessons Circle Button.png';
        $viewMyChildrenBTN = plugin_dir_url(__DIR__) . 'assets/Buttons/View My Children Circle Button.png';

        $output .= '<div id="parent-is-vlp-links" class="section-links button-links">';
        $output .= '<a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Gameboard"><img src="' . $launchGameBTN . '" /></a>';
        $output .= '<a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Lessons"><img src="' . $viewAllLessonsBTN . '" /></a>';
        if (!GHES\VLP\Utils::isUserStaff()) {
            $output .= '<a href="' . get_permalink(get_option("add_edit_children_url")) . '"><img src="' . $viewMyChildrenBTN . '" /></a>';
        }
        $output .= '</div>';
    } else {
        /*
        $output .= '<ul class="section-links">';
        $output .= '<li><a href="' . get_permalink(get_option("vlp-purchase")) . '">Subscribe Now</a></li>';
        $output .= '</ul>'; // close section links
        */
        // Re-written to be more visual
        if (!GHES\VLP\Utils::isUserStaff()) {
            $output .= '<div id="parent-is-vlp-links" class="section-links button-links">';
            $output .= '<a href="' . get_permalink(get_option("vlp-purchase")) . '">Subscribe Now</a>';
            $output .= '</div>';
        }
    }
    $output .= '</div>'; // close section details
    $output .= '</div>'; // close vlp-section


    return $output;
}
