<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to display attendance records with salary calculations
function display_attendance_records() {
    global $wpdb;

    // Fetch attendance records
    $attendance_table = $wpdb->prefix . 'attendance';
    $employees_table = $wpdb->prefix . 'employees';
    $attendance_records = $wpdb->get_results("SELECT * FROM $attendance_table");

    // Fetch all employees to calculate total salary
    $employees = $wpdb->get_results("SELECT id, name, salary FROM $employees_table");
    
    // Initialize salary totals
    $total_salary = 0;
    $total_deductions = 0;

    // Prepare an array to store salaries based on attendance
    $employee_salaries = [];
    
    if ($employees) {
        foreach ($employees as $employee) {
            $employee_salaries[$employee->id] = [
                'name' => $employee->name,
                'salary' => $employee->salary,
                'deductions' => 0,
                'attendance_count' => 0
            ];
        }
    }

    // Calculate deductions and attendance count
    foreach ($attendance_records as $record) {
        if (isset($employee_salaries[$record->employee_id])) {
            $employee_salaries[$record->employee_id]['attendance_count']++;

            if ($record->status === 'half_day') {
                $employee_salaries[$record->employee_id]['deductions'] += $employee_salaries[$record->employee_id]['salary'] / 2;
            } elseif ($record->status === 'absent') {
                $employee_salaries[$record->employee_id]['deductions'] += $employee_salaries[$record->employee_id]['salary'];
            }
        }
    }

    // Calculate total salary and total deductions
    foreach ($employee_salaries as $salary_info) {
        $total_salary += $salary_info['salary'];
        $total_deductions += $salary_info['deductions'];
    }
    
    ?>
    <div class="wrap w-full mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Attendance Records</h1>
        <form method="get" action="" class="mb-4">
            <input type="hidden" name="page" value="my-plugin-view-attendance">
            <label for="date_filter" class="mr-2">Filter by Date:</label>
            <input type="date" id="date_filter" name="date" value="<?php echo isset($_GET['date']) ? esc_attr($_GET['date']) : ''; ?>" class="border border-gray-300 rounded-lg p-2">
            <input type="submit" class="bg-blue-500 text-white rounded-lg p-2 ml-2 hover:bg-blue-600 transition duration-200" value="Filter">
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="py-3 px-4 border-b">Employee ID</th>
                        <th class="py-3 px-4 border-b">Employee Name</th>
                        <th class="py-3 px-4 border-b">Date</th>
                        <th class="py-3 px-4 border-b">Status</th>
                        <th class="py-3 px-4 border-b">Salary</th>
                        <th class="py-3 px-4 border-b">Deductions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($attendance_records): ?>
                        <?php foreach ($attendance_records as $record): ?>
                            <tr class="hover:bg-gray-100 transition duration-150">
                                <td class="py-2 px-4 border-b"><?php echo esc_html($record->employee_id); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo esc_html($employee_salaries[$record->employee_id]['name']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo esc_html($record->date); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo esc_html($record->status); ?></td>
                                <td class="py-2 px-4 border-b text-right"><?php echo esc_html(number_format($employee_salaries[$record->employee_id]['salary'], 2)); ?></td>
                                <td class="py-2 px-4 border-b text-right"><?php echo esc_html(number_format($employee_salaries[$record->employee_id]['deductions'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-2">No attendance records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <h2 class="text-xl font-bold">Summary</h2>
            <p>Total Salary of All Employees: <strong><?php echo esc_html(number_format($total_salary, 2)); ?></strong></p>
            <p>Total Deductions: <strong><?php echo esc_html(number_format($total_deductions, 2)); ?></strong></p>
        </div>
    </div>
    <?php
}
