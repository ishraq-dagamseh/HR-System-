<?php
session_start();
include 'config.php';
include '../includes/header.php';

// ADD LEAVE REQUEST
if (isset($_POST['add'])) {
    $employee_id = $_POST['employee_id'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $stmt = $con->prepare("INSERT INTO leave_requests (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $employee_id, $start_date, $end_date, $reason);
    $stmt->execute();
    $stmt->close();
    header("Location: Leave_request.php");
    exit;
}

// DELETE LEAVE REQUEST
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $con->prepare("DELETE FROM leave_requests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: Leave_request.php");
    exit;
}
//reply to leave request
if (isset($_POST['reply'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: Leave_request.php");
    exit;
}
// Fetch employees for dropdown
$employees = $con->query("SELECT id, firstname, lastname FROM employee ORDER BY firstname");    
// Fetch leave requests
$leave_requests = $con->query("SELECT lr.*, e.firstname, e.lastname
    FROM leave_requests lr
    JOIN employee e ON lr.employee_id = e.id
    ORDER BY lr.start_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<body class="container my-4">
    <h2 class="text-center"> <b>Leave Request Management</b></h2>
    <p class="text-center">Manage leave requests and view their statuses.</p>
    <div class="card mb-4 ">
        <div class="card-header bg-primary">Add Leave Request</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <select class="form-control" id="employee_id" name="employee_id" required>
                        <option value="">Select Employee</option>
                        <?php while ($emp = $employees->fetch_assoc()): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group
">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="form-group
">
                    <label for="reason">Reason</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                </div>
                <button type="submit" name="add" class="btn btn-primary mt-2
">Submit Request</button>
            </form>
        </div>
    </div>
    <!-- Card 2: Leave Requests Table  -->

    <div class="card mb-4 ">
        <div class="card-header bg-primary">Leave Requests</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($request = $leave_requests->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['firstname'] . ' ' . $request['lastname']) ?></td>
                            <td><?= htmlspecialchars($request['start_date']) ?></td>
                            <td><?= htmlspecialchars($request['end_date']) ?></td>
                            <td><?= htmlspecialchars($request['reason']) ?></td>
                            <td><?= htmlspecialchars($request['status']) ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $request['id'] ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $request['id'] ?>">
                                    <select name="status" class="form-select d-inline w-auto">
                                        <option value="Pending" <?= $request['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Approved" <?= $request['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="Rejected" <?= $request['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                    <button type="submit" name="reply" class="btn btn-secondary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>  
</body>
</html>
    
