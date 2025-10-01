<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .navbar-nav > li > a {
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .navbar-brand {
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">HR System</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="../vendor/dashboard.php">Dashboard</a></li>
            <li><a href="../vendor/employee.php">Employee</a></li>
            <li><a href="../vendor/department.php">Department</a></li>
            <li><a href="../vendor/attendance.php">Attendance</a></li>
            <li><a href="../vendor/Leave_request.php">Leave Requests</a></li>
            <li><a href="../vendor/payrol.php">Payroll</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="../vendor/logout.php"><span class="glyphicon glyphicon-user"></span> Logout</a></li>
        </ul>
    </div>
</nav>


<style>
    .card {
        border: 2px solid #dee2e6;  /* stronger border */
        border-radius: 10px;        /* smooth corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* soft shadow */
    }

    .card-header {
        font-weight: bold;
        border-bottom: 2px solid #ccc; /* strong separation between header & body */
    }

    .card + .card {
        margin-top: 1.5rem; /* spacing between stacked cards */
    }

    table.table-bordered th,
    table.table-bordered td {
        border: 1.5px solid #dee2e6; /* stronger table borders */
    }
    .card-header {
        font-weight: bold;
        border-bottom: 2px solid #ccc;
        padding: 1rem 1.25rem;   /* increase padding inside */
        margin-bottom: 0.5rem;   /* extra space before card-body */
        font-size: 2rem;       /* slightly bigger text */
    }
</style>

