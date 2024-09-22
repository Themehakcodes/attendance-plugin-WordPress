<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to display the add new employee form
function display_add_new_employee_form() {
    ?>
    <div class="wrap">
        <h1>Add New Employee</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th><label for="employee_name">Name</label></th>
                    <td><input type="text" id="employee_name" name="employee_name" required></td>
                </tr>
                <tr>
                    <th><label for="employee_photo">Photo</label></th>
                    <td><input type="file" id="employee_photo" name="employee_photo" accept="image/*" required></td>
                </tr>
                <tr>
                    <th><label for="date_of_birth">Date of Birth</label></th>
                    <td><input type="date" id="date_of_birth" name="date_of_birth" required></td>
                </tr>
                <tr>
                    <th><label for="employee_position">Position</label></th>
                    <td><input type="text" id="employee_position" name="employee_position" required></td>
                </tr>
                <tr>
                    <th><label for="salary">Salary</label></th>
                    <td><input type="number" id="salary" name="salary" required></td>
                </tr>
                <tr>
                    <th><label for="starting_date">Starting Date</label></th>
                    <td><input type="date" id="starting_date" name="starting_date" required></td>
                </tr>
                <tr>
                    <th><label for="father_name">Father's Name</label></th>
                    <td><input type="text" id="father_name" name="father_name" required></td>
                </tr>
                <tr>
                    <th><label for="phone_number">Phone Number</label></th>
                    <td><input type="tel" id="phone_number" name="phone_number" required></td>
                </tr>
                <tr>
                    <th><label for="second_phone_number">Second Phone Number</label></th>
                    <td><input type="tel" id="second_phone_number" name="second_phone_number"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button button-primary" value="Add Employee">
            </p>
        </form>
    </div>
    <?php
}

// Process the form submission
function handle_add_new_employee() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        global $wpdb;

        // Sanitize input data
        $employee_name = sanitize_text_field($_POST['employee_name']);
        $employee_position = sanitize_text_field($_POST['employee_position']);
        $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
        $salary = floatval($_POST['salary']);
        $starting_date = sanitize_text_field($_POST['starting_date']);
        $father_name = sanitize_text_field($_POST['father_name']);
        $phone_number = sanitize_text_field($_POST['phone_number']);
        $second_phone_number = sanitize_text_field($_POST['second_phone_number']);

        // Handle file upload
        if (!empty($_FILES['employee_photo']['name'])) {
            $uploaded_file = $_FILES['employee_photo'];
            $upload_overrides = array('test_form' => false);

            // Use WordPress function to handle file upload
            $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                // Insert data into the database
                $wpdb->insert(
                    $wpdb->prefix . 'employees', // Your table name
                    array(
                        'name' => $employee_name,
                        'photo' => $movefile['url'],
                        'date_of_birth' => $date_of_birth,
                        'position' => $employee_position,
                        'salary' => $salary,
                        'starting_date' => $starting_date,
                        'father_name' => $father_name,
                        'phone_number' => $phone_number,
                        'second_phone_number' => $second_phone_number,
                    )
                );

                // Redirect to employees list after submission
                wp_redirect(admin_url('admin.php?page=my-plugin-employees'));
                exit;
            } else {
                echo '<div class="error"><p>Error uploading file: ' . $movefile['error'] . '</p></div>';
            }
        }
    }
}

// Hook into admin_init to ensure WP functions are available
add_action('admin_init', 'handle_add_new_employee');
