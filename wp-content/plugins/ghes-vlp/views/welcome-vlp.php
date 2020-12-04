<?php

use GHES\Utils;
use GHES\ghes_base;

/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_vlp_welcome_api_scripts()
{
    wp_enqueue_script('wp-api-vlp-welcome');
}

function vlp_welcome()
{

    Utils::redirectNotLoggedIn();
    GHES\VLP\Utils::CheckSubscriptionStatus();

    enqueue_vlp_welcome_api_scripts();

    if (ghes_base::UserIsVLPParent())
    {
        $hasvlp = 1;
    } else {
        $hasvlp = 0;
    }

    $output = '';
    $output .= "<script type='text/javascript'>var hasvlp = " . $hasvlp . "</script>";

    $output .= '<div class="parent-section vlp-section">';
        $output .= '<div class="vlp-icon welcome-section-icon"><i class="far fa-lightbulb"></i></div>';
            $output .= '<div class="section-details">';
                $output .= '<div class="section-header"><span>Virtual Learning</span></div>';
                if (ghes_base::UserIsVLPParent())
                {
                $output .= '<ul id="parent-is-vlp-links" class="section-links">';
                    $output .= '<li><a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Gameboard">Launch Gameboard</a></li>';
                    $output .= '<li><a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Lessons">View Lessons</a></li>';
                    $output .= '<li><a href="' . get_permalink(get_option("vlp-agetree")) . '?destination=Themes">View Themes</a></li>';
                    $output .= '<li><a href="' . get_permalink(get_option("vlp-manage")) . '">Manage Subscription</a></li>';
                $output .= '</ul>';// close section links
                } else {
                $output .= '<ul id="parent-isnot-vlp-links" class="section-links">';
                    $output .= '<li><a href="' . get_permalink(get_option("vlp-purchase")) . '">Subscribe Now</a></li>';
                $output .= '</ul>';// close section links
                }
            $output .= '</div>'; // close section details
    $output .= '</div>'; // close vlp-section


    return $output;
}