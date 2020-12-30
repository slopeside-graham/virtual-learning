<?php

use GHES\VLP;
use GHES\VLP\Lesson;
use GHES\VLP\Theme;
use GHES\VLP\AgeGroup;
use GHES\VLP\Resource;
use GHES\Children;
use GHES\VLP\Utils;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_agetree_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-agetree');
}

function vlp_agetree($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInVLPParent();
    enqueue_agetree_scripts();

    //Uset all  Cookies
    setcookie('VLPAgeGroupId', '', 1, '/');
    setcookie('VLPSelectedChild', '', 1, '/');
    setcookie('VLPThemeId', '', 1, '/');

    $destination = $_GET["destination"];
    $destinationURL = '';




    $output = '';
    $output .= '<div class="vlp-intro">Select Curriculum By Age</div>';
    $output .= '<div id="vlp-age-tree">';
    $agegroups = AgeGroup::GetAll();

    foreach ($agegroups->jsonSerialize() as $k => $agegroup) {
        $agegroupid = $agegroup->id;
        $agegroupName = $agegroup->Name;
        $agegroupstart = $agegroup->AgeStart;
        $agegroupend = $agegroup->AgeEnd;
        $agegroupimage = $agegroup->Image_id;
        $agegroupposition = $agegroup->Position;

        $output .= '<a class="age-group-icon position-' . $agegroupposition . '" href="' . get_permalink(get_option('vlp-select-child')) . '?destination=' . $destination . '&VLPAgeGroupId=' . $agegroupid . '" data-agegroupid="' . $agegroupid . '">' . wp_get_attachment_image($agegroupimage, 'large') . '</a>';
    }

    $output .= '</div>';

    return $output;
}
