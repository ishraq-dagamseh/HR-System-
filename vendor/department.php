<?php
session_start();
include 'config.php';
include '../includes/header.php';


// ADD DEPARTMENT
if (isset($_POST['add'])) {
    $id     = $_POST['id'];
    $name   = $_POST['name'];

    $stmt = $con->prepare("INSERT INTO department (id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $id, $name);
    $stmt->execute();
    $stmt->close();

    header("Location: department.php");
    exit;
}

// DELETE DEPARTMENT
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $con->prepare("DELETE FROM department WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: department.php");
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
<body class="container my-4">

<h2 class="text-center"> <b>Department Management</b></h2>
<p class="text-center">Manage departments and view associated employees.</p>

  <!-- Card 1: Add Department -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      Add Department
    </div>
    <div class="card-body">
      <form method="post" action="">
        <input type="hidden" name="add" value="1">

        <div class="mb-3">
          <label class="form-label">Department ID</label>
          <input type="number" name="id" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Department Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
      </form>
    </div>
  </div>

  <!-- Card 2: Department List & employees view -->
  <div class="card">
    <div class="card-header bg-primary text-white">
      Department List
    </div>
    <div class="card-body">
      <table class="table table-striped table-bordered">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Number of Employees</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($dept = $departments->fetch_assoc()): ?>
            <tr>
              <td><?= $dept['id'] ?></td>
              <td><?= htmlspecialchars($dept['name']) ?></td>
              <td>
                <?php
                // Count employees in this department
                $count = $con->query("SELECT COUNT(*) as count FROM employee WHERE department_id = " . $dept['id']);
                $count = $count->fetch_assoc();
                echo $count['count'];
                ?>
              </td>
              <td>
                <form method="post" action="" class="d-inline">
                  <input type="hidden" name="id" value="<?= $dept['id'] ?>">
                  <button type="submit" name="delete" value="1" class="btn btn-danger btn-sm">Delete</button>
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

 
