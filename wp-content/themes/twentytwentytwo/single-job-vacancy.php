<?php
/*
Template Name: Job Vacancie
*/
get_header();

// Get the job ID from the URL
$job_id = get_query_var('job_id');

// Query your custom table to get the job details
global $wpdb;
$table_name = $wpdb->prefix . 'jobs'; // Replace 'jops' with your table name
$job = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", 5));

if ($job) {
    echo '<h1>' . esc_html($job->title) . '</h1>';
    echo '<p>' . esc_html($job->description) . '</p>';
} else {
    echo 'Job not found.';
}

get_footer();
?>