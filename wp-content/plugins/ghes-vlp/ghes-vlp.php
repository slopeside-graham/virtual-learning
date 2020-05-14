<?php

use GHES\Utils;

const vlpscriptver = '1.0.5142020-1';  // Use this in register script calls to bypass cache.
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
 *
*/
// Plugin can only be active if GHES Registration is active
if (!is_plugin_active('ghes-registration/ghes-registration.php')) {
  deactivate_plugins(plugin_basename(__FILE__));
  header("Location: /wp-admin/plugins.php");
  exit;
}

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

// Include shortcodes
//include_once(plugin_dir_path(__FILE__) . '/shortcodes.php');
$dbhost = Utils::getunencryptedsetting('vlp-dbhost');
$dbport = Utils::getunencryptedsetting('vlp-dbport');
$dbuser = Utils::getunencryptedsetting('vlp-dbuser');
$dbpassword = Utils::getencryptedsetting('vlp-dbpassword');
$dbname = Utils::getunencryptedsetting('vlp-dbname');


DB::$host = $dbhost;
DB::$port = $dbport;
DB::$user = $dbuser;
DB::$password = $dbpassword;
DB::$dbName = $dbname;
DB::$encoding = 'utf8'; // defaults to latin1 if omitted


// Include Setting Pages
include_once(plugin_dir_path(__FILE__) . '/admin/admin.php');
include_once(plugin_dir_path(__FILE__) . '/admin/manage.php');

// Register Admin Stylesheets and JS
/*
function ghes_vlp_admin_scripts_styles()
{
    // Kendo  styles first
    wp_register_style('ghes-style-kendo-common', plugins_url('/ghes-registration/styles/kendo.common.min.css', dirname(__FILE__)), array(), vlpscriptver);
    wp_register_style('ghes-style-kendo-default', plugins_url('/ghes-registration/styles/kendo.silver.min.css', dirname(__FILE__)), array(), vlpscriptver);
    wp_enqueue_style('ghes-style-kendo-common');
    wp_enqueue_style('ghes-style-kendo-default');

    // Kendo Script
    wp_register_script('ghes-script-kendo-kendo-all', plugins_url('/ghes-registration/kendo/kendo.all.min.js', dirname(__FILE__)), array('jquery'), vlpscriptver, true);
    wp_enqueue_script('ghes-script-kendo-kendo-all');

    // Regular Style Sheets
    wp_register_style('admin-style', plugins_url('/ghes-vlp/css/admin.css'), array(), vlpscriptver);
    wp_enqueue_style('admin-style');
}
add_action('admin_enqueue_scripts', 'ghes_vlp_admin_scripts_styles');
*/

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
    'Manage VLP Items',
    'Manage VLP Items',
    'vlp_manage_entries',
    'ghes-vlp/admin/manage.php',
    'ghes_manage_vlp'
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
