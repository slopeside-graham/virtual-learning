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
    enqueue_browse_themes_scripts();

    $output = '';

    if (isset($_COOKIE['VLPAgeGroupId'])) {
        $agegroupid = $_COOKIE['VLPAgeGroupId'];
        $agegroupname = AgeGroup::Get($agegroupid)->name;
        
    } else if (isset($_COOKIE['VLPSelectedChild'])) {
        $childid = $_COOKIE['VLPSelectedChild'];
        $child = Children::Get($childid);
        $todaysdate = new DateTime(date("Y-m-d"));
        $childdob = $child->DOB;
        $interval = $todaysdate->diff($childdob);
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $childagemonths = ($years * 12) + $months;

        $agegroup = AgeGroup::GetByAgeMonths($childagemonths);
        $agegroupid = $agegroup->id;
        $agegroupname = $agegroup->name;
    } else {
        $output .= 'No Child or Age Group Selected';
        return $output;
    }



    //$BrowseThemeTemplate = file_get_contents(plugin_dir_path(__FILE__) . 'templates/browse-theme.html');
    $output .= '<div class="browse-themes-header"><span class="browse-themes-age">' . $agegroupname . ' / </span><span class="weely-theme">Weekly Themes</span></div>';
    //$output .= $BrowseThemeTemplate;
    $output .= '<div class="k-content wide">';
    $output .= '<div id="themes-listView"></div>';
    $output .= '</div>';

    $output .= '<script type="text/x-kendo-template" id="template">';
    $output .= '<div class="theme">';
    $output .= '    <div class="theme-title">#:Title#</div>';
    $output .= '<a class="themeaccess" href="' . get_permalink(get_option("vlp-lessons")) . '" data-theme-id="#:id#" onClick="SetTheme(this)">Access Content</a>';
    $output .= ' </div>';
    $output .= '</script>';

    return $output;
}
