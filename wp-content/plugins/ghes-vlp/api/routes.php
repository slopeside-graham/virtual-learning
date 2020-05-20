<?php
// Include Enum class
include_once(plugin_dir_path(__FILE__) . '/../classes/enum.php');  // MyCLabs\Enum class

/*  Include all API files in Routes  */
include_once(plugin_dir_path(__FILE__) . '/../classes/base.php');  // object base class
include_once(plugin_dir_path(__FILE__) . '/../classes/nestedserializable.php');  // Class for serializing 

// Include Lesson REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/lesson.php');  // Lesson class
include_once(plugin_dir_path(__FILE__) . '/lesson_rest.php');    // Leeson REST controller

// Include Theme REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/theme.php');  // Theme class
include_once(plugin_dir_path(__FILE__) . '/theme_rest.php');    // Theme REST controller

/**
 * Register our API routes.
 */

/**
 * Function to register our new routes from the controller.
 */
function register_controllers()
{
    // Parent Controller
    $controller = new GHES\VLP\Lesson_Rest;
    $controller->register_routes();

    // Children Controller
    $controller = new GHES\VLP\Theme_Rest();
    $controller->register_routes();
}

add_action('rest_api_init', 'register_controllers');
