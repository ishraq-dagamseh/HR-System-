<?php
session_start();
include 'config.php';
include '../includes/header.php';

// ADD ATTENDANCE
if (isset($_POST['add'])) {
    $employee_id = $_POST['employee_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    $stmt = $con->prepare("INSERT INTO attendance (employee_id, attendance_date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $employee_id, $attendance_date, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: attendance.php");
    exit;
}

// DELETE ATTENDANCE
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $con->prepare("DELETE FROM attendance WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: attendance.php");
    exit;
}

// Fetch employees for dropdown
$employees = $con->query("SELECT id, firstname, lastname FROM employee ORDER BY firstname");

// Fetch attendance records
$attendance_records = $con->query("
    SELECT a.id, a.attendance_date, a.status, e.firstname, e.lastname
    FROM attendance a
    JOIN employee e ON a.employee_id = e.id
    ORDER BY a.attendance_date DESC
");

// Fetch monthly attendance report
$month = date('Y-m'); // Current month e.g., '2025-09'
$monthly_report = $con->query("
    SELECT e.id, e.firstname, e.lastname,
           SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_days,
           SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) AS absent_days
    FROM employee e
    LEFT JOIN attendance a 
        ON e.id = a.employee_id AND DATE_FORMAT(a.attendance_date, '%Y-%m') = '$month'
    GROUP BY e.id
");
?>

<!DOCTYPE html>
<body class="container my-4">

<h2 class="text-center"> <b> Attendance Management</b></h2>
<p class="text-center">Manage employee attendance records and view monthly reports.</p>
<!-- Card 1: Mark Attendance -->
<div class="card mb-4 ">

    <div class="card-header bg-primary text-white ">Mark Attendance</div>
    <div class="card-body">
        <form method="post" action="">
            <input type="hidden" name="add" value="1">

            <div class="mb-3">
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">-- select employee --</option>
                    <?php while($emp = $employees->fetch_assoc()): ?>
                        <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['firstname'] . " " . $emp['lastname']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="attendance_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save Attendance</button>
        </form>
    </div>
</div>

<!-- Card 2: Attendance Records -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">Attendance Records</div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $attendance_records->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['firstname'] . " " . $row['lastname']) ?></td>
                        <td><?= $row['attendance_date'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <form method="post" action="" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Card 3: Monthly Attendance Report -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        Monthly Attendance Report (<?= date('F Y') ?>)
    </div>
    <div class="card-body">
        <?php while($row = $monthly_report->fetch_assoc()): 
            $total_days = $row['present_days'] + $row['absent_days'];
            $present_percent = $total_days > 0 ? round($row['present_days'] / $total_days * 100) : 0;
            $absent_percent  = 100 - $present_percent;
        ?>
            <h6><?= htmlspecialchars($row['firstname'] . " " . $row['lastname']) ?></h6>
            <div class="progress mb-3" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: <?= $present_percent ?>%;" 
                     aria-valuenow="<?= $present_percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $row['present_days'] ?> Present
                </div>
                <div class="progress-bar bg-danger" role="progressbar" 
                     style="width: <?= $absent_percent ?>%;" 
                     aria-valuenow="<?= $absent_percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $row['absent_days'] ?> Absent
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>




</body>
</html>
