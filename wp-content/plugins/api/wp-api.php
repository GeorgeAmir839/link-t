<?php
/**
 * Plugin Name: Job Vacancy API
 * Description: Custom API for job vacancies.
 * Version: 1.0
 * Author: Your Name
 */

// Register a custom endpoint to handle job vacancy applications
add_action('rest_api_init', function () {
    register_rest_route('job/v1', 'apply', array(
        'methods' => 'POST',
        'callback' => 'submit_job_application',
        'args' => array(
            'job_id' => array(
                'required' => true,
                'type' => 'integer',
            ),
            'name' => array(
                'required' => true,
                'type' => 'string',
            ),
            'email' => array(
                'required' => true,
                'type' => 'string',
            ),
            'message' => array(
                'required' => true,
                'type' => 'string',
            ),
        ),
    ));
});

// Callback function to handle job vacancy applications
function submit_job_application($request) {
    $job_id = $request->get_param('job_id');
    $name = $request->get_param('name');
    $email = $request->get_param('email');
    $message = $request->get_param('message');

    // Create a custom function to save the application to your database
    $application_id = save_job_application($job_id, $name, $email, $message);

    if ($application_id) {
        return new WP_REST_Response(array('message' => 'Application submitted successfully'), 200);
    } else {
        return new WP_REST_Response(array('message' => 'Failed to submit application'), 500);
    }
}

// Custom function to save job vacancy application to the database
function save_job_application($job_id, $name, $email, $message) {
    // Implement your database-saving logic here
    // Insert data into a custom table or use the WordPress database functions
    // Return the application ID or false on failure
    // Example: Use $wpdb->insert() to insert the application into a custom table

    global $wpdb;
    $table_name = $wpdb->prefix . 'job_applications';

    $result = $wpdb->insert($table_name, array(
        'job_id' => $job_id,
        'name' => $name,
        'email' => $email,
        'message' => $message,
    ));

    if ($result !== false) {
        return $wpdb->insert_id;
    } else {
        return false;
    }
}