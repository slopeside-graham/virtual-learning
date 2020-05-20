<?php
/*    Shortcodes should be for displaying information  */

include_once(plugin_dir_path( __FILE__ ) . 'classes/utils.php'); 

include_once(plugin_dir_path( __FILE__ ) . 'views/gameboard.php'); 
add_shortcode( 'vlp_gameboard', 'vlp_gameboard' );