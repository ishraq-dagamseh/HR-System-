<?php
session_start();
include 'config.php';
include '../includes/header.php';

// ADD EMPLOYEE
if (isset($_POST['add'])) {
    $id     = $_POST['id'];
    $Fname  = $_POST['firstname'];
    $Lname  = $_POST['lastname'];
    $Hdate  = $_POST['hire_date'];
    $Dept   = $_POST['department_id'];
    $Salary = $_POST['salary'];

    $stmt = $con->prepare("INSERT INTO employee (id, firstname, lastname, hire_date, department_id, salary) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssid", $id, $Fname, $Lname, $Hdate, $Dept, $Salary);
    $stmt->execute();
    $stmt->close();

    header("Location: employee.php");
    exit;
}

// DELETE EMPLOYEE
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // 1- احذف الحضور المرتبط
    $stmt = $con->prepare("DELETE FROM attendance WHERE employee_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 2- احذف الموظف نفسه
    $stmt = $con->prepare("DELETE FROM employee WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: employee.php");
    exit;
}

// UPDATE EMPLOYEE
if (isset($_POST['update'])) {
    $id     = $_POST['id'];
    $Fname  = $_POST['firstname'];
    $Lname  = $_POST['lastname'];
    $Hdate  = $_POST['hire_date'];
    $Dept   = $_POST['department_id'];
    $Salary = $_POST['salary'];

    $stmt = $con->prepare("UPDATE employee 
                           SET firstname=?, lastname=?, hire_date=?, department_id=?, salary=? 
                           WHERE id=?");
    $stmt->bind_param("sssidi", $Fname, $Lname, $Hdate, $Dept, $Salary, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: employee.php");
    exit;
}

// Fetch employees
$employees = $con->query("SELECT e.*, d.name AS dept_name 
                          FROM employee e 
                          LEFT JOIN department d ON e.department_id = d.id");

// Fetch departments
$departments = $con->query("SELECT id, name FROM department ORDER BY name");
?>
<!DOCTYPE html>
<html>

<body class="container my-4">

  <h2 class="text-center"><b>Employee Management</b></h2>
  <p class="text-center">Manage employee records and view associated departments.</p>

  <!-- Card 1: Add Employee -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">Add Employee</div>
    <div class="card-body">
      <form method="post" action="">
        <input type="hidden" name="add" value="1">
        <div class="mb-3">
          <label class="form-label">Employee ID</label>
          <input type="number" name="id" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">First Name</label>
          <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last Name</label>
          <input type="text" name="lastname" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Hire Date</label>
          <input type="date" name="hire_date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Department</label>
          <select name="department_id" class="form-select" required>
            <option value="">-- choose department --</option>
            <?php while ($row = $departments->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Salary</label>
          <input type="number" step="0.01" name="salary" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
      </form>
    </div>
  </div>

  <!-- Card 2: Employee List -->
  <div class="card">
    <div class="card-header bg-primary text-white">Employee List</div>
    <div class="card-body">
      <table class="table table-striped table-bordered">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Hire Date</th>
            <th>Department</th>
            <th>Salary</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($emp = $employees->fetch_assoc()): ?>
            <tr>
              <td><?= $emp['id'] ?></td>
              <td><?= htmlspecialchars($emp['firstname'] . " " . $emp['lastname']) ?></td>
              <td><?= $emp['hire_date'] ?></td>
              <td><?= htmlspecialchars($emp['dept_name']) ?></td>
              <td><?= $emp['salary'] ?></td>
              <td>
                <form method="post" action="" class="d-inline">
                  <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                  <button type="submit" name="delete" value="1" class="btn btn-danger btn-sm"
                          onclick="return confirm('Are you sure you want to delete this employee?')">
                    Delete
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Card 3: Edit Employee -->
  <div class="card mb-4 mt-4">
    <div class="card-header bg-warning text-white">Edit Employee</div>
    <div class="card-body">
      <form method="post" action="">
        <input type="hidden" name="update" value="1">
        <div class="mb-3">
          <label class="form-label">Employee ID</label>
          <input type="number" name="id" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">First Name</label>
          <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last Name</label>
          <input type="text" name="lastname" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Hire Date</label>
          <input type="date" name="hire_date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Department</label>
          <select name="department_id" class="form-select" required>
            <option value="">-- choose department --</option>
            <?php 
            $departments->data_seek(0);
            while ($row = $departments->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Salary</label>
          <input type="number" step="0.01" name="salary" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
    </div>
  </div>

</body>
</html>
