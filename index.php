<?php
/**
 * Plugin Name: Attendance
 * Plugin URI: http://example.com
 * Description: Developed By Mehak Singh.
 * Version: 1.0
 * Author: Mehak Preet Singh
 * Author URI: http://yourwebsite.com
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include necessary files
include(plugin_dir_path(__FILE__) . './includes/employees.php');
include(plugin_dir_path(__FILE__) . './includes/Fourms/add-new-employee.php');
include(plugin_dir_path(__FILE__) . './includes/Fourms/mark-attendance.php');
include(plugin_dir_path(__FILE__) . './includes/view-attendance.php');
include(plugin_dir_path(__FILE__) . './includes/homepage.php');
include(plugin_dir_path(__FILE__) . './includes/Fourms/edit-employee.php');

// Add the custom dashboard and submenu to the WordPress admin menu
add_action('admin_menu', 'my_plugin_add_admin_menu');

function my_plugin_add_admin_menu() {
    // Main menu page
    add_menu_page(
        'Attendance Dashboard',    // Page Title
        'Attendance',              // Menu Title
        'manage_options',          // Capability
        'my-plugin-dashboard',     // Menu Slug
        'my_plugin_home_page',     // Callback function for homepage
        'dashicons-admin-users', // Icon
        6                          // Position
    );

    // Submenu page for Employees
    add_submenu_page(
        'my-plugin-dashboard',     // Parent Slug
        'Employees',               // Page Title
        'Employees',               // Menu Title
        'manage_options',          // Capability
        'my-plugin-employees',     // Menu Slug
        'my_plugin_employees_page' // Callback function
    );

    // Submenu page for adding a new employee
    add_submenu_page(
        'my-plugin-dashboard',          // Parent Slug
        'Add New Employee',             // Page Title
        'Add New Employee',             // Menu Title
        'manage_options',               // Capability
        'my-plugin-add-new-employee',   // Menu Slug
        'display_add_new_employee_form'  // Callback function
    );

    // Submenu page for marking attendance
    add_submenu_page(
        'my-plugin-dashboard',          // Parent Slug
        'Mark Attendance',              // Page Title
        'Mark Attendance',              // Menu Title
        'manage_options',               // Capability
        'my-plugin-mark-attendance',    // Menu Slug
        'display_mark_attendance_form'  // Callback function
    );

    // Submenu page for viewing attendance
    add_submenu_page(
        'my-plugin-dashboard',          // Parent Slug
        'View Attendance',              // Page Title
        'View Attendance',              // Menu Title
        'manage_options',               // Capability
        'my-plugin-view-attendance',    // Menu Slug
        'display_attendance_records'    // Callback function
    );

    // Submenu page for editing an employee
    add_submenu_page(
        'my-plugin-employees',          // Parent Slug
        'Edit Employee',                // Page Title
        'Edit Employee',                // Menu Title
        'manage_options',               // Capability
        'my-plugin-edit-employee',      // Menu Slug
        'display_edit_employee_form'    // Callback function
    );
}

// Homepage content
function my_plugin_home_page() {
    display_homepage();
}

// Employees page content
function my_plugin_employees_page() {
    display_employees(); // Call the function to display employees
}

// Create the database table for employees on plugin activation
function my_plugin_create_employee_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'employees'; // Your table name
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        photo varchar(255) NOT NULL,
        date_of_birth date NOT NULL,
        position tinytext NOT NULL,
        salary float NOT NULL,
        starting_date date NOT NULL,
        father_name tinytext NOT NULL,
        phone_number varchar(15) NOT NULL,
        second_phone_number varchar(15),
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create the database table for attendance on plugin activation
function my_plugin_create_attendance_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'attendance';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        employee_id mediumint(9) NOT NULL,
        date date NOT NULL,
        status varchar(10) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY (employee_id, date)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Enqueue Tailwind CSS
function my_plugin_enqueue_styles() {
    wp_enqueue_style('tailwind-cdn', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
}
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_styles');

// Hook the functions to plugin activation
register_activation_hook(__FILE__, 'my_plugin_create_employee_table');
register_activation_hook(__FILE__, 'my_plugin_create_attendance_table');
