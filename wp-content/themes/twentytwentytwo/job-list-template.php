<?php
// Include WordPress functions
get_header();

// Get a global reference to the database
global $wpdb;

// Define your custom table name
$table_name = $wpdb->prefix . 'jobs'; // Replace 'jobs' with the actual table name.

// Query job vacancies from your custom table
$jobs = $wpdb->get_results("SELECT * FROM $table_name ");

// Check if there are job vacancies to display
if ($jobs) {
    echo '<ul>';
    foreach ($jobs as $job) {
        echo '<li>';
        echo '<h2>' . esc_html($job->title) . '</h2>';
        
        echo '<p>' . esc_html($job->description) . '</p>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo 'No published job vacancies found.';
}

get_footer();
?>
