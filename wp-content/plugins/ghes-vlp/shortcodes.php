<?php
/*    Shortcodes should be for displaying information  */

include_once(plugin_dir_path( __FILE__ ) . 'classes/utils.php'); 

include_once(plugin_dir_path( __FILE__ ) . 'views/gameboard.php'); 
add_shortcode( 'vlp_gameboard', 'vlp_gameboard' );

include_once(plugin_dir_path( __FILE__ ) . 'views/gameboard-archive.php'); 
add_shortcode( 'vlp_gameboard_archive', 'vlp_gameboard_archive' );

include_once(plugin_dir_path( __FILE__ ) . 'views/browse-themes.php'); 
add_shortcode( 'vlp_browse_themes', 'vlp_browse_themes' );

include_once(plugin_dir_path( __FILE__ ) . 'views/browse-lessons.php'); 
add_shortcode( 'vlp_browse_lessons', 'vlp_browse_lessons' );

include_once(plugin_dir_path( __FILE__ ) . 'views/agetree.php'); 
add_shortcode( 'vlp_agetree', 'vlp_agetree' );