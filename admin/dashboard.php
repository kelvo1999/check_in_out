<?php
// Start session & check authentication
session_start();
require_once "../includes/config.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('location:logout.php');
    exit;
}

// Fetch stats from DB
$total_students = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM students"))['total'];
$total_staff = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM users WHERE role='staff'"))['total'];
$checked_in_today = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM checkins WHERE DATE(checkin_time) = CURDATE()"))['total'];
$checked_out_today = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM checkins WHERE DATE(checkout_time) = CURDATE() AND checkout_time IS NOT NULL"))['total'];
$absent_students = $total_students - $checked_in_today;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check_In Check_Out System - Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- bootstrap theme -->
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <!--external css-->
  <!-- font icon -->
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
</head>
<body>

<!-- Header -->
<header>
    <h1>Check_In Check_Out System - Admin Dashboard</h1>
    <div class="header-right">
        <a href="profile.php"><i class="icon_profile"></i>My Profile</a>
        <a href="logout.php"><i class="icon_key_alt"></i>Log Out</a>
    </div>
</header>

<!-- Sidebar -->
<aside class="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <h2>Menu</h2>
    <ul>
        <li><a href="#">Dashboard</a></li>
        <li>
            <a href="#">Student Management ▼</a>
            <ul>
                <li><a href="add_student.php">Add Student</a></li>
                <li><a href="edit_student.php">Edit Student</a></li>
                <li><a href="remove_student.php">Remove Student</a></li>
                <li><a href="check_logs.php">Check Logs</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Staff Management ▼</a>
            <ul>
                <li><a href="add_staff.php">Add Staff</a></li>
                <li><a href="edit_staff.php">Edit Staff</a></li>
                <li><a href="remove_staff.php">Remove Staff</a></li>
                <li><a href="staff_logs.php">Check Logs</a></li>
            </ul>
        </li>
    </ul>
</aside>

<!-- Main Content -->
<main class="dashboard">
    <div class="stats">
        <div class="stat-box">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Staff</h3>
            <p><?php echo $total_staff; ?></p>
        </div>
        <div class="stat-box">
            <h3>Checked-in Today</h3>
            <p><?php echo $checked_in_today; ?></p>
        </div>
        <div class="stat-box">
            <h3>Checked-out Today</h3>
            <p><?php echo $checked_out_today; ?></p>
        </div>
        <div class="stat-box">
            <h3>Absent Students</h3>
            <p><?php echo $absent_students; ?></p>
        </div>
    </div>
</main>


<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }
</script>

</body>
</html>
