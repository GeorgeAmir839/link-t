<?php
/*
 * Plugin Name:       Recruitment System
 * Description:       Custom plugin to manage job vacancies and applications.
 * Version:           1.0.0
 * Author:            George Amir
 * Author URI:        https://github.com/GeorgeAmir839
 * Text Domain:       recruitment-system
 */

// Activation hook to create the table
register_activation_hook(__FILE__, 'create_job_titles_table');
register_activation_hook(__FILE__, 'recruitment_system_activate');
register_activation_hook(__FILE__, 'create_vacancy_table');
add_action('wp_enqueue_scripts', 'enqueue_custom_script');

function enqueue_custom_script() {
    // Define the path to your JavaScript file within your plugin directory
    $script_url = plugins_url('app.js', __FILE__);

    // Enqueue the script
    wp_enqueue_script('custom-script', $script_url, array('jquery'), '1.0', true);
}


function create_vacancy_table() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'my_custom_table';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        column1 varchar(255) NOT NULL,
        column2 text,
        column3 datetime,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
function recruitment_system_activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $applications_table_name = $wpdb->prefix . 'job_applications';

    // SQL statement to create the applications table
    $sql = "CREATE TABLE $applications_table_name (
        id mediumint(11) NOT NULL AUTO_INCREMENT,
        job_id mediumint(11) NOT NULL,
        email varchar(100) NOT NULL,
        application_date datetime NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
function create_job_titles_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'jobs';
    $sql = "CREATE TABLE $table_name (
        id mediumint(11) NOT NULL AUTO_INCREMENT,
        title varchar(250) NOT NULL,
        description text,
        department varchar(250),
        start_date date,
        end_date date,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('admin_menu', 'job_titles_menu');

function job_titles_menu()
{
    add_menu_page('Job Titles', 'Job List', 'manage_options', 'job-list', 'da_jobs_list_callback');
    add_submenu_page('job-list', 'Job List', 'Job List', 'manage_options', 'job-list', 'da_jobs_list_callback');
    add_submenu_page('job-list', 'Add New Job', 'Add New', 'manage_options', 'add-new-job-title', 'da_job_add_callback');
    add_submenu_page(null, 'Update Job', 'Update Job', 'manage_options', 'update-job', 'da_job_update_call');
    add_submenu_page(null, 'Delete Job', 'Delete Job', 'manage_options', 'delete-job', 'da_job_delete_call');
    add_submenu_page('job-list', 'Job List Shortcode', 'Job List Shortcode', 'edit_others_posts', 'job-shortcode', 'da_job_shortcode_call');
    add_submenu_page('job-list', 'Plugin Settings', 'Settings', 'manage_options', 'job-list-settings', 'job_titles_settings_page');

}
function job_titles_settings_page()
{
    $job_count = get_option('job_titles_count', 10); // Retrieve the current count from the database

    if (isset($_POST['submit'])) {
        $job_count = intval($_POST['job_count']);
        update_option('job_titles_count', $job_count);
    }
    ?>
    <div class="wrap">
        <h2>Recruitment System Settings</h2>
        <form method="post" action="">
            <label for="job_count">Number of Job Titles to Display:</label>
            <input type="number" id="job_count" name="job_count" value="<?php echo $job_count; ?>" min="1" required>
            <p class="description">Set the number of job titles to display on the listing page.</p>
            <p><input type="submit" name="submit" class="button button-primary" value="Save Changes"></p>
        </form>
    </div>
    <?php
}
function da_job_add_callback()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'jobs';
    $msg = '';

    if (isset($_POST['submit'])) {
        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_text_field($_POST['description']);
        $department = sanitize_text_field($_POST['department']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);

        // Title validation
        if (empty($title)) {
            $msg = "Title is required.";
        }

        // Description validation
        if (empty($description)) {
            $msg = "Description is required.";
        } elseif (strlen($description) > 500) {
            $msg = "Description must be 500 characters or less.";
        }

        // Department validation
        $valid_departments = array('hr', 'eng', 'employee');
        if (!in_array($department, $valid_departments)) {
            $msg = "Invalid department selected.";
        }

        // Start Date and End Date validation
        $today = date('Y-m-d');
        if ($start_date < $today) {
            $msg = "Start date must be today or in the future.";
        } elseif ($end_date <= $start_date) {
            $msg = "End date must be greater than the start date.";
        }

        if (empty($msg)) {
            // All validations passed; proceed with inserting data and notifying users.
            $wpdb->insert($table_name, array(
                'title' => $title,
                'description' => $description,
                'department' => $department,
                'start_date' => $start_date,
                'end_date' => $end_date
            ));

            if ($wpdb->insert_id > 0) {
                $msg = "Saved Successfully";
                notify_users_about_vacancy($title, $description);
            } else {
                $msg = "Failed to save data";
            }
        }
    }
    ?>

    <h4 id="msg"><?php echo $msg; ?></h4>

    <form method="post">
        <p>
            <label>Title</label>
            <input type="text" name="title" placeholder="Enter title" required>
        </p>
        <p>
            <label>Description</label>
            <input type="text" name="description" placeholder="Enter description" required>
        </p>
        <p>
            <label>Department</label>
            <select name="department" required>
                <option value="hr">HR</option>
                <option value="eng">ENG</option>
                <option value="employee">Employee</option>
            </select>
        </p>
        <p>
            <label>Start date</label>
            <input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>" required>
        </p>
        <p>
            <label>End date</label>
            <input type="date" name="end_date" min="<?php echo date('Y-m-d'); ?>" required>
        </p>
        <p>
            <button type="submit" name="submit">Submit</button>
        </p>
    </form>
    <?php
}

function notify_users_about_vacancy($title, $description) {
    $subject = 'New Job Vacancy Created: ' . $title;
    $message = 'A new job vacancy has been created with the following details:\n\n';
    $message .= 'Title: ' . $title . '\n';
    $message .= 'Description: ' . $description . '\n';

    $users = get_users(); // You may want to customize this to get the list of users to notify.

    foreach ($users as $user) {
        wp_mail($user->user_email, $subject, $message);
    }

    echo 'Email sent successfully';

}

function da_jobs_list_callback()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'jobs';
    $job_count = get_option('job_titles_count', 5);
    $job_list = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE end_date > CURDATE() LIMIT %d", $job_count),
        ARRAY_A
    );
    if (count($job_list) > 0) {
    ?>
        <div style="margin-top: 40px;">
            <table border="1" cellpadding="10">
                <tr>
                    <th>S.No.</th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
                <?php
                $i = 1;
                foreach ($job_list as $job) {
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $job['id']; ?></td>
                        <td><?php echo $job['title']; ?></td>
                        <td><?php echo $job['description']; ?></td>
                        <td><?php echo $job['department']; ?></td>
                        <td>
                            <a href="admin.php?page=update-job&id=<?php echo $job['id']; ?>">Edit</a>
                            <a href="admin.php?page=delete-job&id=<?php echo $job['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    <?php
    } else {
        echo "<h2>Jobs Record Not Found</h2>";
    }
}

function da_job_update_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'jobs';
    $msg = '';
    $id = isset($_GET['id']) ? intval($_GET['id']) : "";

    if (isset($_POST['update'])) {
        if (!empty($id)) {
            $wpdb->update(
                $table_name,
                array(
                    'title' => sanitize_text_field($_POST['title']),
                    'description' => sanitize_text_field($_POST['description']),
                    'department' => sanitize_text_field($_POST['department']),
                    'start_date' => sanitize_text_field($_POST['start_date']),
                    'end_date' => sanitize_text_field($_POST['end_date'])
                ),
                array('id' => $id)
            );
            $msg = 'Data updated';
        }
    }
    $job_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
    ?>
    <h4><?php echo $msg; ?></h4>
    <form method="post">
        <p>
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $job_details['title']; ?>" required>
        </p>
        <p>
            <label>Description</label>
            <input type="text" name="description" value="<?php echo $job_details['description']; ?>" required>
        </p>
        <p>
            <label>Department</label>
            <select name="department" required>
                <option value="hr" <?php selected($job_details['department'], 'hr'); ?>>HR</option>
                <option value="eng" <?php selected($job_details['department'], 'eng'); ?>>ENG</option>
                <option value="employee" <?php selected($job_details['department'], 'employee'); ?>>Employee</option>
            </select>
        </p>
        <p>
            <label>Start date</label>
            <input type="date" name="start_date" value="<?php echo $job_details['start_date']; ?>" required>
        </p>
        <p>
            <label>End date</label>
            <input type="date" name="end_date" value="<?php echo $job_details['end_date']; ?>" required>
        </p>
        <p>
            <button type="submit" name="update">Update</button>
        </p>
    </form>
    <?php
}

function da_job_delete_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'jobs';
    $id = isset($_GET['id']) ? intval($_GET['id']) : "";
    if (isset($_POST['delete'])) {
        $confirmation = isset($_POST['conf']) ? sanitize_text_field($_POST['conf']) : 'no';
        if ($confirmation === 'yes') {
            $wpdb->delete($table_name, array('id' => $id));
            ?>
            <script>
                location.href = "<?php echo admin_url('admin.php?page=job-list'); ?>";
            </script>
            <?php
        }
    }
    ?>
    <form method="post">
        <p>
            <label>Are you sure you want to delete?</label><br>
            <input type="radio" name="conf" value="yes">Yes
            <input type="radio" name="conf" value="no" checked>No
        </p>
        <p>
            <button type="submit" name="delete">Delete</button>
        </p>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
    </form>
    <?php
}

function da_job_shortcode_call()
{
    ?>
    <p>
        <label>Shortcode</label>
        <input type="text" value="[job_list]">
    </p>
    <?php
}

add_shortcode('job_list', 'da_jobs_list_callback');
