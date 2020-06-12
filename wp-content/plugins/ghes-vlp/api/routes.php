<?php

/*  Include all API files in Routes  */
include_once(plugin_dir_path(__FILE__) . '/../classes/base.php');  // object base class
include_once(plugin_dir_path(__FILE__) . '/../classes/nestedserializable.php');  // Class for serializing 

// Include Lesson REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/lesson.php');  // Lesson class
include_once(plugin_dir_path(__FILE__) . '/lesson_rest.php');    // Leeson REST controller

// Include Theme REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/theme.php');  // Theme class
include_once(plugin_dir_path(__FILE__) . '/theme_rest.php');    // Theme REST controller

// Include AgeGroup REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/agegroup.php');  // AgeGroup class
include_once(plugin_dir_path(__FILE__) . '/agegroup_rest.php');    // AgeGroup REST controller

// Include Resource REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/resource.php');  // AgeGroup class
include_once(plugin_dir_path(__FILE__) . '/resource_rest.php');    // AgeGroup REST controller

// Include Child Resource Status REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/childresourcestatus.php');  // AgeGroup class
include_once(plugin_dir_path(__FILE__) . '/childresourcestatus_rest.php');    // AgeGroup REST controller

// Include Lesson Resource Status REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/lessonresourcestatus.php');  // AgeGroup class
include_once(plugin_dir_path(__FILE__) . '/lessonresourcestatus_rest.php');    // AgeGroup REST controller

// Include Theme Resource Status REST controller and class
include_once(plugin_dir_path(__FILE__) . '/../classes/themeresourcestatus.php');  // AgeGroup class
include_once(plugin_dir_path(__FILE__) . '/themeresourcestatus_rest.php');    // AgeGroup REST controller

/**
 * Register our API routes.
 */

/**
 * Function to register our new routes from the controller.
 */
function register_vlp_controllers()
{
    // Lesson Controller
    $controller = new GHES\VLP\Lesson_Rest;
    $controller->register_routes();

    // Theme Controller
    $controller = new GHES\VLP\Theme_Rest();
    $controller->register_routes();

    // AgeGroup Controller
    $controller = new GHES\VLP\AgeGroup_Rest();
    $controller->register_routes();

    // Resource Controller
    $controller = new GHES\VLP\Resource_Rest();
    $controller->register_routes();

    // Resource Controller
    $controller = new GHES\VLP\ChildResourceStatus_Rest();
    $controller->register_routes();

        // Resource Controller
        $controller = new GHES\VLP\ChildLessonStatus_Rest();
        $controller->register_routes();

                // Resource Controller
                $controller = new GHES\VLP\ChildThemeStatus_Rest();
                $controller->register_routes();
}

add_action('rest_api_init', 'register_vlp_controllers');
