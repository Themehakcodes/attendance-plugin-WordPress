<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to display employees
function display_employees() {
    global $wpdb;

    // Fetch employees from the database
    $table_name = $wpdb->prefix . 'employees';
    $employees = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h1 class="text-2xl font-bold">Employees List</h1>
        <a href="<?php echo admin_url('admin.php?page=my-plugin-add-new-employee'); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Add New Employee</a>
        <table class="min-w-full mt-4 bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starting Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Father's Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Second Phone Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($employees): ?>
                    <?php foreach ($employees as $employee): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->position); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->date_of_birth); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->salary); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->starting_date); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->father_name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->phone_number); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo esc_html($employee->second_phone_number); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="<?php echo esc_url($employee->photo); ?>" alt="<?php echo esc_attr($employee->name); ?>" class="w-12 h-auto">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo admin_url('admin.php?page=my-plugin-edit-employee&id=' . $employee->id); ?>" class="text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
