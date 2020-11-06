<?php

use GHES\VLP;
use GHES\VLP\Lesson;
use GHES\VLP\Theme;
use GHES\VLP\AgeGroup;
use GHES\VLP\Resource;
use GHES\Children;



/*****************************************
     Cookies Authentication
 *****************************************/

function enqueue_browse_themes_scripts()
{
    wp_enqueue_script('wp-api-browse-themes');
}

function vlp_browse_themes($atts, $content = null)
{
    GHES\VLP\Utils::CheckLoggedInVLPParent();
    enqueue_browse_themes_scripts();

    $output = '';

    if (isset($_COOKIE['VLPSelectedChild'])) {
        $childid = $_COOKIE['VLPSelectedChild'];
        $child = Children::Get($childid);
        $childFirstName = $child->FirstName;
        $childLastName = $child->LastName;
        $todaysdate = new DateTime(date("Y-m-d"));
        $childdob = $child->DOB;
        $interval = $todaysdate->diff($childdob);
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $childagemonths = ($years * 12) + $months;

        $agegroup = AgeGroup::GetByAgeMonths($childagemonths);
        $agegroupid = $agegroup->id;
        $agegroupname = $agegroup->name;
    } else if (isset($_COOKIE['VLPAgeGroupId'])) {
        $agegroupid = $_COOKIE['VLPAgeGroupId'];
        $agegroupname = AgeGroup::Get($agegroupid)->name;
    } else {
        $output .= 'No Child or Age Group Selected';
        return $output;
    }
    $completionIcon = plugin_dir_url(dirname(__FILE__)) . 'assets/Star.png';


    //$BrowseThemeTemplate = file_get_contents(plugin_dir_path(__FILE__) . 'templates/browse-theme.html');
    $output .= '<div class="browse-themes-header"><span class="browse-themes-age">' . $agegroupname . ' / </span><span class="weely-theme">Weekly Themes</span><span class="child-name">' . $childFirstName . ' ' . $childLastName . '</span></div>';
    //$output .= $BrowseThemeTemplate;
    $output .= '<div class="k-content wide">';
    $output .= '<div id="themes-listView"></div>';
    $output .= '</div>';

    $output .= '<script type="text/x-kendo-template" id="template">';
    $output .= '<div class="theme">';
    $output .= '    <div class="theme-title Completed-#:Completed#">#:Title#<span class="theme-completion-icon"><img src="' . $completionIcon . '" /></span></div>';
    $output .= '    <div class="theme-date">#: kendo.format("{0:MM/dd/yyyy}", StartDate)# - #: kendo.format("{0:MM/dd/yyyy}", EndDate)#</div>';
    $output .= '<a class="themeaccess" href="' . get_permalink(get_option("vlp-gameboard")) . '" data-theme-id="#:id#" onClick="SetTheme(this)">Launch Gameboard</a>';
    $output .= ' </div>';
    $output .= '</script>';

    return $output;
}
