<?php

use GHES\VLP;
use GHES\VLP\Lesson;
use GHES\VLP\Theme;
use GHES\VLP\AgeGroup;
use GHES\VLP\Resource;
use GHES\Children;


function enqueue_select_child_scripts()
{
    enqueue_kendo_scripts();
    wp_enqueue_script('wp-api-frontend-utils');
    wp_enqueue_script('wp-api-select-child');
}

function vlp_select_child($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInVLPParent();
    enqueue_select_child_scripts();

    $output = '';

    $agegroupid = $_GET["VLPAgeGroupId"];

    $destination = $_GET["destination"];
    $destinationURL = '';

    if ($destination == "Gameboard") {
        $destinationURL = get_permalink(get_option("vlp-gameboard"));
    } else if ($destination == "Lessons") {
        $destinationURL = get_permalink(get_option("vlp-lessons"));
    } else if ($destination == "Themes") {
        $destinationURL = get_permalink(get_option("vlp-themes"));
    } else {
        $destinationURL = get_permalink(get_option("vlp-gameboard"));
    }

    if ($agegroupid) {
        $ageGroup = AgeGroup::Get($agegroupid);
        $ageStartInterval = new DateInterval('P' . $ageGroup->AgeStart . 'M');
        $ageEndInterval = new DateInterval('P' . $ageGroup->AgeEnd . 'M');
        $ageEndDate = (new DateTime(date("Y-m-d")))->sub($ageStartInterval);
        $ageStartDate = (new DateTime(date("Y-m-d")))->sub($ageEndInterval);

        $children = Children::GetAllByAge($ageEndDate, $ageStartDate);
    } else {
        $children = Children::GetAll();
    }

    if ($children->count() == 1) {

        setcookie('VLPSelectedChild', $children->jsonSerialize()[0]->id, 0, '/');
        setcookie('VLPAgeGroupId', $agegroupid, 0, '/');
        header('Location: ' . $destinationURL);
        exit;
    } else if ($children->count() < 1) {
        setcookie('VLPAgeGroupId', $agegroupid, 0, '/');
        $output .= "<p>You have no children registered in this age group, so your progress will not be tracked.</p>";
        $output .= '<ul>';
        $output .= '<li><a href="' . get_permalink(get_option('vlp-agetree')) . '?destination=' . $destination . '">Select a Different Age Group</a></li>';
        $output .= '<li><a href="' . $destinationURL . '">Continue to Virtual Learning</a></li>';
        $output .= '<li><a href="' . get_permalink(get_option('add_edit_children_url')) . '">Add/Edit Children</a></li>';
        return $output;
    } else if ($children->count() > 1) {
        setcookie('VLPAgeGroupId', $agegroupid, 0, '/');

        $output .= '<div class="list-children">';

        foreach ($children->jsonSerialize() as $k => $child) {
            $childFirstName = $child->FirstName;
            $childLastName = $child->LastName;
            $childDOB = $child->DOB->format('l F j, Y');
            $childAgeMonths = $child->DOB->diff(new DateTime('today'))->m;
            $childAgeYears = ($child->DOB->diff(new DateTime('today'))->y) * 12;
            $childAgeTotalMonths = $childAgeMonths + $childAgeYears;
            $childAgeGroup = AgeGroup::GetByAgeMonths($childAgeTotalMonths);
            if ($childAgeGroup) {
                $output .= '<div class="single-child">';
                $output .= '<span class="Child-First-Name">' . $childFirstName . '</span> ';
                $output .= '<span class="Child-Last-Name">' . $childLastName . '</span><br/>';
                $output .= '<a href="' . $destinationURL . '" data-child-id="' . $child->id . '" data-child-age-group="' . $childAgeGroup->id . '" onclick="setChildID(this)">Select Child</a>';
                $output .= '</div>';
            }
        }

        $output .= '</div>';

        return $output;
    }
}
