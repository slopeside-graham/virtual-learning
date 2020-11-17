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

include_once(plugin_dir_path( __FILE__ ) . 'views/select-child.php'); 
add_shortcode( 'vlp_slelect_child', 'vlp_select_child' );

include_once(plugin_dir_path( __FILE__ ) . 'views/select-subscription.php'); 
add_shortcode( 'vlp_select_subscription', 'vlp_select_subscription' );

include_once(plugin_dir_path( __FILE__ ) . 'views/manage-subscription.php'); 
add_shortcode( 'vlp_manage_subscription', 'vlp_manage_subscription' );

include_once(plugin_dir_path( __FILE__ ) . 'views/past-payments.php'); 
add_shortcode( 'vlp_past_payments', 'vlp_past_payments' );

include_once(plugin_dir_path( __FILE__ ) . 'views/payment-confirmation.php'); 
add_shortcode( 'vlp_payment_confirmation', 'vlp_payment_confirmation' );

include_once(plugin_dir_path( __FILE__ ) . 'views/cancel-confirmation.php'); 
add_shortcode( 'vlp_cancel_confirmation', 'vlp_cancel_confirmation' );