<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to display attendance marking form
function display_mark_attendance_form() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'employees';
    $employees = $wpdb->get_results("SELECT * FROM $table_name");
    ?>
    <div class="wrap">
        <h1 class="text-2xl font-bold">Mark Attendance for Today</h1>
        <form method="post" action="" class="mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($employees): ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->name); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select name="attendance[<?php echo esc_attr($employee->id); ?>]" class="border border-gray-300 rounded-md p-2">
                                        <option value="present">Present</option>
                                        <option value="half_day">Half Day</option>
                                        <option value="absent">Absent</option>
                                        <option value="absent">Leave</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center">No employees found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <p class="submit mt-4">
                <input type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" value="Save Attendance">
            </p>
        </form>
    </div>
    <?php
}

// Handle attendance submission
function handle_mark_attendance() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance'])) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'attendance';
        $date = date('Y-m-d');

        foreach ($_POST['attendance'] as $employee_id => $status) {
            // Insert or update attendance record
            $wpdb->replace(
                $table_name,
                array(
                    'employee_id' => intval($employee_id),
                    'date' => $date,
                    'status' => sanitize_text_field($status),
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                )
            );
        }

        // Redirect after saving
        wp_redirect(admin_url('admin.php?page=my-plugin-mark-attendance'));
        exit;
    }
}

// Hook into admin_init
add_action('admin_init', 'handle_mark_attendance');
