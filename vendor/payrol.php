<?php
session_start();
include 'config.php';
include '../includes/header.php';

if (isset($_POST['add'])) {
    $employee_id   = $_POST['employee_id'];
    $pay_date      = $_POST['payroll_date'];
    $basic_salary  = $_POST['basic_salary'];
    $allowances    = $_POST['allowances'];
    $deductions    = $_POST['deductions'];
    $net_salary    = $basic_salary + $allowances - $deductions;

    // Insert payroll record (id auto-increment → don't insert manually)
   $stmt = $con->prepare("INSERT INTO payroll2 (employee_id, payroll_date, basic_salary, allowances, deductions, net_salary) 
                       VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isdddd", $employee_id, $pay_date, $basic_salary, $allowances, $deductions, $net_salary);

    $stmt->execute();
    $stmt->close();

    header("Location: payrol.php");
    exit;
}

// Fetch employees for dropdown
$employees = $con->query("SELECT id, firstname, lastname FROM employee ORDER BY firstname");

// Fetch payroll records
$payroll_records = $con->query("
    SELECT p.id, p.payroll_date, p.basic_salary, p.net_salary, e.firstname, e.lastname
    FROM payroll2 p
    JOIN employee e ON p.employee_id = e.id
    ORDER BY p.payroll_date DESC    
");
?>
<!DOCTYPE html>
<html lang="en">
<body class="container my-4">
    <h2 class="text-center"><b> Payroll Management</b></h2>
    <p class="text-center">Manage payroll records and view salary details for employees.</p>

    <div>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Add Payroll Record</div>
            <div class="card-body">
                <form method="POST" action="payrol.php">
                    <div class="form-group mb-3">
                        <label for="employee_id">Employee</label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php while ($emp = $employees->fetch_assoc()): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group
    mb-3">
                            <label for="payroll_date">Payroll Date</label>
                            <input type="date" class="form-control" id="payroll_date" name="payroll_date" required>
                        </div>
                        <div class="form-group
    mb-3">
                            <label for="basic_salary">Basic Salary</label>
                            <input type="number" step="0.01" class="form-control" id="basic_salary" name="basic_salary" required>
                                
                        </div>
                        <div class="form-group mb-3">
                            <label for="allowances">Allowances</label>
                            <input type="number" step="0.01" class="form-control" id="allowances" name="allowances" value="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="deductions">Deductions</label>
                            <input type="number" step="0.01" class="form-control" id="deductions" name="deductions" value="0" required>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Add Payroll
                                
                        </button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">Payroll Records</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Payroll Date</th>
                            <th>Basic Salary</th>
                            <th>Net Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($record = $payroll_records->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['firstname'] . ' ' . $record['lastname']) ?></td>
                                <td><?= htmlspecialchars($record['payroll_date']) ?></td>
                                <td><?= htmlspecialchars(number_format($record['basic_salary'], 2)) ?></td>
                                <td><?= htmlspecialchars(number_format($record['net_salary'], 2)) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>      
        </div>
    </div>  
</body>
</html>
