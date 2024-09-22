<?php
function display_edit_employee_form() {
    global $wpdb;

    // Get employee ID from the URL
    $employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Fetch employee details from the database
    $table_name = $wpdb->prefix . 'employees';
    $employee = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $employee_id));

    if (!$employee) {
        echo '<div class="wrap"><h1 class="text-2xl font-bold">Employee Not Found</h1></div>';
        return;
    }

    ?>
    <div class="wrap">
        <h1 class="text-2xl font-bold">Edit Employee</h1>
        <form method="post" action="" class="mt-4">
            <input type="hidden" name="employee_id" value="<?php echo esc_attr($employee->id); ?>">
            <table class="min-w-full bg-white border border-gray-300">
                <tr>
                    <td class="px-6 py-4"><label>Name:</label></td>
                    <td class="px-6 py-4"><input type="text" name="name" value="<?php echo esc_attr($employee->name); ?>" class="border border-gray-300 p-2 rounded-md" required></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Position:</label></td>
                    <td class="px-6 py-4"><input type="text" name="position" value="<?php echo esc_attr($employee->position); ?>" class="border border-gray-300 p-2 rounded-md" required></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Date of Birth:</label></td>
                    <td class="px-6 py-4"><input type="date" name="date_of_birth" value="<?php echo esc_attr($employee->date_of_birth); ?>" class="border border-gray-300 p-2 rounded-md" required></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Salary:</label></td>
                    <td class="px-6 py-4"><input type="number" name="salary" value="<?php echo esc_attr($employee->salary); ?>" class="border border-gray-300 p-2 rounded-md" required></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Starting Date:</label></td>
                    <td class="px-6 py-4"><input type="date" name="starting_date" value="<?php echo esc_attr($employee->starting_date); ?>" class="border border-gray-300 p-2 rounded-md" required></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Father's Name:</label></td>
                    <td class="px-6 py-4"><input type="text" name="father_name" value="<?php echo esc_attr($employee->father_name); ?>" class="border border-gray-300 p-2 rounded-md"></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Phone Number:</label></td>
                    <td class="px-6 py-4"><input type="text" name="phone_number" value="<?php echo esc_attr($employee->phone_number); ?>" class="border border-gray-300 p-2 rounded-md"></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Second Phone Number:</label></td>
                    <td class="px-6 py-4"><input type="text" name="second_phone_number" value="<?php echo esc_attr($employee->second_phone_number); ?>" class="border border-gray-300 p-2 rounded-md"></td>
                </tr>
                <tr>
                    <td class="px-6 py-4"><label>Photo:</label></td>
                    <td class="px-6 py-4">
                        <input type="text" name="photo" id="photo" value="<?php echo esc_attr($employee->photo); ?>" class="border border-gray-300 p-2 rounded-md" required>
                        <button type="button" class="button button-secondary" id="upload-button">Upload Photo</button>
                    </td>
                </tr>
            </table>
            <p class="mt-4">
                <input type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" value="Update Employee">
            </p>
        </form>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#upload-button').on('click', function(e) {
                e.preventDefault();
                // If the uploader object has already been created, reopen the dialog
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                // Extend the wp.media object
                mediaUploader = wp.media({
                    title: 'Upload Photo',
                    button: {
                        text: 'Select Photo'
                    },
                    multiple: false // Set to true to allow multiple files to be selected
                });
                // When a file is selected, grab the URL and set it as the input value
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#photo').val(attachment.url);
                });
                // Open the uploader dialog
                mediaUploader.open();
            });
        });
    </script>
    <?php
}

// Handle the form submission
function handle_employee_update() {
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
        $employee_id = intval($_POST['employee_id']);
        $data = [
            'name' => sanitize_text_field($_POST['name']),
            'position' => sanitize_text_field($_POST['position']),
            'date_of_birth' => sanitize_text_field($_POST['date_of_birth']),
            'salary' => floatval($_POST['salary']),
            'starting_date' => sanitize_text_field($_POST['starting_date']),
            'father_name' => sanitize_text_field($_POST['father_name']),
            'phone_number' => sanitize_text_field($_POST['phone_number']),
            'second_phone_number' => sanitize_text_field($_POST['second_phone_number']),
            'photo' => esc_url_raw($_POST['photo']),
        ];

        // Update the employee record in the database
        $wpdb->update($wpdb->prefix . 'employees', $data, ['id' => $employee_id]);

        // Redirect to the employees list page
        wp_redirect(admin_url('admin.php?page=my-plugin-employees'));
        exit;
    }
}

// Call the update handler
handle_employee_update();
