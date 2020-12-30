<?php

use GHES\Utils;
use GHES\VLP\Utils as VLPUtils;

const vlpscriptver = '1.0.7-7-2020-1';  // Use this in register script calls to bypass cache.
/**
 * Plugin Name: GHES Virtual Learning Platform
 * Version: 0.1
 * Plugin URI: http://www.slopesidetechnology.com/
 * Description: This is Georgetown Hill's virtual learning platform
 * Author: Brian Brown & Graham Holland
 * Author URI: http://www.slopesidetechnology.com/
 * Requires at least: 5.2
 * Tested up to: 5.3.2
 *
 */
/**
 * Checks if the GHES Registration plugin is activated
 */
// Plugin can only be active if GHES Registration is active
function ghesregcheck()
{
  if (!is_plugin_active('ghes-registration/ghes-registration.php')) {
    deactivate_plugins(plugin_basename(__FILE__));
    header("Location: /wp-admin/plugins.php");
    exit;
  }
}
add_action('admin_init', 'ghesregcheck');

/* If the GHES Registration plugin is not active, then don't allow the
* activation of this plugin.
*/
function ghes_vlp_activate()
{
  if (!function_exists('is_plugin_active_for_network')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
  }
  if (current_user_can('activate_plugins') && !is_plugin_active('ghes-registration/ghes-registration.php')) {
    // Deactivate the plugin.
    deactivate_plugins(plugin_basename(__FILE__));
    // Throw an error in the WordPress admin console.
    $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__('This plugin requires ', 'ghes-registration') . 'GHES Registration' . esc_html__(' plugin to be active.', 'ghes-registration') . '</p>';
    die($error_message); // WPCS: XSS ok.
  }
}
register_activation_hook(__FILE__, 'ghes_vlp_activate');

/*     Include API Routes                                 */
/*     Routes file will include all API related files     */
/*     WP routes start:  /wp-json/                        */
include_once(plugin_dir_path(__FILE__) . '/api/routes.php');

// Include shortcodes
include_once(plugin_dir_path(__FILE__) . '/shortcodes.php');

$vlpdbhost = Utils::getunencryptedsetting('vlp-dbhost');
$vlpdbport = Utils::getunencryptedsetting('vlp-dbport');
$vlpdbuser = Utils::getunencryptedsetting('vlp-dbuser');
$vlpdbpassword = Utils::getencryptedsetting('vlp-dbpassword');
$vlpdbname = Utils::getunencryptedsetting('vlp-dbname');

VLPUtils::$db = new MeekroDB($vlpdbhost, $vlpdbuser, $vlpdbpassword, $vlpdbname, $vlpdbport);

// Include Setting Pages
include_once(plugin_dir_path(__FILE__) . '/admin/admin.php');
include_once(plugin_dir_path(__FILE__) . '/admin/manage-lessons.php');
include_once(plugin_dir_path(__FILE__) . '/admin/manage-themes.php');
include_once(plugin_dir_path(__FILE__) . '/admin/manage-agegroups.php');

// Include Manage Views
include_once(plugin_dir_path(__FILE__) . '/views/admin/manage-themes.php');
include_once(plugin_dir_path(__FILE__) . '/views/admin/manage-lessons.php');
include_once(plugin_dir_path(__FILE__) . '/views/admin/manage-agegroups.php');

// Register Frontend Scripts and Styles
function register_vlp_script_style_frontend()
{
  wp_register_script('wp-api-gameboard', plugins_url('ghes-vlp/js/gameboard.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-gameboard', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_style('gameboard-global-style', plugins_url('/ghes-vlp/css/gameboard-global.css'), array(), scriptver);
  wp_enqueue_style('gameboard-global-style');

  wp_register_style('gameboard-1-style', plugins_url('/ghes-vlp/css/gameboard-1.css'), array(), scriptver);
  wp_enqueue_style('gameboard-1-style');

  wp_register_style('gameboard-archive-style', plugins_url('/ghes-vlp/css/gameboard-archive.css'), array(), scriptver);
  wp_enqueue_style('gameboard-archive-style');

  wp_register_script('wp-api-gameboard-archive', plugins_url('ghes-vlp/js/gameboard-archive.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-gameboard-archive', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_style('browse-themes-style', plugins_url('/ghes-vlp/css/browse-themes.css'), array(), scriptver);
  wp_enqueue_style('browse-themes-style');

  wp_register_style('browse-lessons-style', plugins_url('/ghes-vlp/css/browse-lessons.css'), array(), scriptver);
  wp_enqueue_style('browse-lessons-style');

  wp_register_style('agetree-style', plugins_url('/ghes-vlp/css/agetree.css'), array(), scriptver);
  wp_enqueue_style('agetree-style');

  wp_register_style('select-child-style', plugins_url('/ghes-vlp/css/select-child.css'), array(), scriptver);
  wp_enqueue_style('select-child-style');

  wp_register_style('purchase-vlp-style', plugins_url('/ghes-vlp/css/purchase.css'), array(), scriptver);
  wp_enqueue_style('purchase-vlp-style');

  wp_register_style('print-vlp-style', plugins_url('/ghes-vlp/css/print-area.css'), array(), scriptver);

  wp_register_script('wp-api-browse-themes', plugins_url('ghes-vlp/js/browse-themes.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-browse-themes', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-browse-lessons', plugins_url('ghes-vlp/js/browse-lessons.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-browse-lessons', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-agetree', plugins_url('ghes-vlp/js/agetree.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-agetree', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-select-child', plugins_url('ghes-vlp/js/select-child.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-select-child', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-select-subscription', plugins_url('ghes-vlp/js/select-subscription.js', dirname(__FILE__)), 'wp-api-frontend-utils', scriptver, true);
  wp_localize_script('wp-api-select-subscription', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-manage', plugins_url('ghes-vlp/js/purchase.js', dirname(__FILE__)), 'wp-api-frontend-utils', scriptver, true);
  wp_localize_script('wp-api-manage', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-payment-confirmation', plugins_url('ghes-vlp/js/payment-confirmation.js', dirname(__FILE__)), 'wp-api-frontend-utils', scriptver, true);
  wp_localize_script('wp-api-payment-confirmation', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-vlp-payment-history', plugins_url('ghes-vlp/js/payment-history.js', dirname(__FILE__)), 'wp-api-frontend-utils', scriptver, true);
  wp_localize_script('wp-api-vlp-payment-history', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-frontend-utils', plugins_url('ghes-vlp/js/utils.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-frontend-utils', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
  

  // Include Vimeo's Player API Library
  wp_register_script('wp-api-vimeo', 'https://player.vimeo.com/api/player.js', ['jquery'], scriptver, true);
  wp_localize_script('wp-api-vimeo', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_enqueue_script('wp-api-vimeo');
}
add_action('wp_enqueue_scripts', 'register_vlp_script_style_frontend');

// Register Backend Scripts and Styles
function register_vlp_script_style_backend()
{
  wp_register_script('wp-api-manage-themes', plugins_url('ghes-vlp/js/admin/manage-themes.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-manage-themes', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-manage-lessons', plugins_url('ghes-vlp/js/admin/manage-lessons.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-manage-lessons', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-manage-agegroups', plugins_url('ghes-vlp/js/admin/manage-agegroups.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-manage-agegroups', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_script('wp-api-utils', plugins_url('ghes-vlp/js/admin/utils.js', dirname(__FILE__)), ['jquery'], scriptver, true);
  wp_localize_script('wp-api-utils', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));

  wp_register_style('manage-themes-style', plugins_url('/ghes-vlp/css/admin/manage-themes.css'), array(), scriptver);

  wp_register_style('manage-agegroups-style', plugins_url('/ghes-vlp/css/admin/manage-agegroups.css'), array(), scriptver);

  wp_register_style('manage-lessons-style', plugins_url('/ghes-vlp/css/admin/manage-lessons.css'), array(), scriptver);

  // Enqueue WP Media Scripts
  wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'register_vlp_script_style_backend');

// Create VLP Admin Pages
function ghes_vlp_register_menu_pages()
{
  add_menu_page(
    'VLP Settings',
    'Virtual Learning Platform',
    'vlp_manage_options',
    'ghes-vlp/admin/admin.php',
    'vlp_page_admin',
    'dashicons-book',
    2
  );
  add_submenu_page(
    'ghes-vlp/admin/admin.php',
    'Themes',
    'Themes',
    'vlp_manage_entries',
    'ghes-vlp/admin/manage-themes.php',
    'vlp_manage_themes'
  );
  add_submenu_page(
    'ghes-vlp/admin/admin.php',
    'Lesson Plans',
    'Lesson Plans',
    'vlp_manage_entries',
    'ghes-vlp/admin/manage-lessons.php',
    'vlp_manage_lessons'
  );
  add_submenu_page(
    'ghes-vlp/admin/admin.php',
    'Age Groups',
    'Age Groups',
    'vlp_manage_entries',
    'ghes-vlp/admin/manage-agegroups.php',
    'vlp_manage_agegroups'
  );
}
add_action('admin_menu', 'ghes_vlp_register_menu_pages');

// Create Custom WordPress Roles
function ghes_vlp_add_custom_roles()
{
  add_role(
    'VLP Admin',
    'VLP Admin',
    array(
      'vlp_manage_options' => true,
      'vlp_manage_entries' => true,
    )
  );
  add_role(
    'VLP Parent',
    'VLP Parent',
    array()

  );

  $admin_role = get_role('administrator');
  $admin_role->add_cap('vlp_manage_options');
  $admin_role->add_cap('vlp_manage_entries');
}
register_activation_hook(__FILE__, 'ghes_vlp_add_custom_roles');

// The following script is used to add arole
/*
$u = new \WP_User( 482 );

// Add role
$u->add_role( 'VLP Parent' );
*/