<?php
session_start();
include 'config.php';
include '../includes/header.php';



//total employees
$employee_count = $con->query("SELECT COUNT(*) AS total FROM employee")->fetch_assoc
()['total'];
//total departments
$department_count = $con->query("SELECT COUNT(*) AS total FROM department")->fetch_assoc()['total'];
//total attendance records
$attendance_count = $con->query("SELECT COUNT(*) AS total FROM attendance")->fetch_assoc()['total'];

//total_pending_leave_requests
$pending_leave_requests = $con->query("SELECT COUNT(*) AS total FROM leave_requests WHERE status = 'Pending'")->fetch_assoc()['total'];

//list of employees with their departments
$employee_list = $con->query("SELECT e.id, e.firstname, e.lastname, d.name AS department
                               FROM employee e
                               LEFT JOIN department d ON e.department_id = d.id
                               ORDER BY e.id");  

//total attendance records
$Absent_count = $con->query("SELECT COUNT(*) AS total FROM attendance WHERE status = 'Absent'")->fetch_assoc()['total'];
$Present_count = $con->query("SELECT COUNT(*) AS total FROM attendance WHERE status = 'Present'")->fetch_assoc()['total'];

// Data for Chart.js
$dataPoints = array(
	array("label"=> "Absent", "y"=> $Absent_count ),
	array("label"=> "Present", "y"=> $Present_count ),
);

// total leave requests by type (example data)
$pending_leave_requests = $con->query("SELECT COUNT(*) AS total FROM leave_requests WHERE status = 'Pending'")->fetch_assoc()['total'];
$approved_leave_requests = $con->query("SELECT COUNT(*) AS total FROM leave_requests WHERE status = 'Approved'")->fetch_assoc()['total'];
$rejected_leave_requests = $con->query("SELECT COUNT(*) AS total FROM leave_requests WHERE status = 'Rejected'")->fetch_assoc()['total'];

$dataPoints2 = array(
	array("label"=> "Pending", "y"=> $pending_leave_requests),
	array("label"=> "Approved", "y"=> $approved_leave_requests),
	array("label"=> "Rejected", "y"=> $rejected_leave_requests),
);
?>
<!DOCTYPE html>
<html lang="en">
<h2 class="text-center"> <b>Dashboard</b></h2>
<p class="text-center">Welcome to the HR System Dashboard. Use the navigation links above to manage employees, departments, and attendance.</p>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3 text-center">
            <div class="card-body">
                <h5 class="card-title">Total Employees</h5>
                <p class="card-text" style="font-size: 24px;"><?= $employee_count ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3 text-center">
            <div class="card-body">
                <h5 class="card-title">Total Departments</h5>
                <p class="card-text" style="font-size: 24px;"><?= $department_count ?></p>
            </div>      
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3 text-center">
            <div class="card-body">
                <h5 class="card-title">Total Attendance Records</h5>
                <p class="card-text" style="font-size: 24px;"><?= $attendance_count ?></p>
            </div>      
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3 text-center">
            <div class="card-body">
                <h5 class="card-title">Pending Leave Requests</h5>
                <p class="card-text" style="font-size: 24px;"><?= $pending_leave_requests ?></p>
            </div>      
        </div>
    </div>
</div>

<div class="row mt-4 mb-4" style="margin-top: 20px;">
    <div class="col-md-6 ">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Attendance Chart</div>
            <div class="card-body">
                <div id="attendanceChart" style="height: 270px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Leave Requests Chart</div>
            <div class="card-body">
                <div id="leaveChart" style="height: 270px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
window.onload = function () {
    // Attendance Chart
    var attendanceChart = new CanvasJS.Chart("attendanceChart", {
        animationEnabled: true,
        theme: "light2",
        title: { text: "Attendance Status" },
        axisY: { title: "Number of Records" },
        data: [{
            type: "column",
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    attendanceChart.render();

    // Leave Requests Chart
    var leaveChart = new CanvasJS.Chart("leaveChart", {
        animationEnabled: true,
        exportEnabled: true,
        title: { text: "Leave Requests by Type" },
        legend: {
            cursor: "pointer",
            itemclick: function(e) {
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                leaveChart.render();
            }
        },
        data: [{
            type: "pie",
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 16,
            indexLabel: "{label} - #percent%",
            dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
        }]
    });
    leaveChart.render();
}
</script>
</div>
<div class="row" style="margin-top: 20px;">

    <div class="col-md-8 offset-md-2 align-center">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white ">Employee List</div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($emp = $employee_list->fetch_assoc()): ?>
                            <tr>
                                <td><?= $emp['id'] ?></td>
                                <td><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></td>
                                <td><?= htmlspecialchars($emp['department'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>