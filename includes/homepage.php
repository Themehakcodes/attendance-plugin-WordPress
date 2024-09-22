<?php
function display_homepage() {
    global $wpdb;

    // Get total employees
    $total_employees = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}employees");

    // Get today's date
    $today = date('Y-m-d');

    // Get total present employees today
    $total_present_today = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(DISTINCT employee_id) FROM {$wpdb->prefix}attendance WHERE date = %s AND status = 'Present'",
        $today
    ));

    ?>
    <div class="wrap w-full mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Welcome to My Plugin Dashboard</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-lg p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Total Employees</h2>
                    <p class="text-2xl font-bold text-blue-600"><?php echo esc_html($total_employees); ?></p>
                </div>
                <div class="text-blue-600">
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Total Present Today</h2>
                    <p class="text-2xl font-bold text-blue-600"><?php echo esc_html($total_present_today); ?></p>
                </div>
                <div class="text-blue-600">
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold mb-4">Quick Links</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="<?php echo admin_url('admin.php?page=my-plugin-employees'); ?>" class="bg-blue-500 text-white rounded-lg p-4 shadow-md hover:bg-blue-600 transition duration-200 text-center">
                <h3 class="text-lg font-semibold">Manage Employees</h3>
                <p>View and manage employee records</p>
            </a>
            <a href="<?php echo admin_url('admin.php?page=my-plugin-add-new-employee'); ?>" class="bg-blue-500 text-white rounded-lg p-4 shadow-md hover:bg-blue-600 transition duration-200 text-center">
                <h3 class="text-lg font-semibold">Add New Employee</h3>
                <p>Add a new employee record</p>
            </a>
            <a href="<?php echo admin_url('admin.php?page=my-plugin-mark-attendance'); ?>" class="bg-blue-500 text-white rounded-lg p-4 shadow-md hover:bg-blue-600 transition duration-200 text-center">
                <h3 class="text-lg font-semibold">Mark Attendance</h3>
                <p>Record attendance for today</p>
            </a>
            <a href="<?php echo admin_url('admin.php?page=my-plugin-view-attendance'); ?>" class="bg-blue-500 text-white rounded-lg p-4 shadow-md hover:bg-blue-600 transition duration-200 text-center">
                <h3 class="text-lg font-semibold">View Attendance Records</h3>
                <p>Filter and view attendance history</p>
            </a>
        </div>
    </div>
    <?php
}
