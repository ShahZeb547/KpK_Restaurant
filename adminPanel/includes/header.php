<?php
include 'config.php';

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userName = $_SESSION['userName'];
$role     = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>My Resturant Dashboard</title>

    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <style>
.dashboard-card {
    border-radius: 15px;
    padding: 25px;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: 0.3s ease-in-out;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
}

.dashboard-icon {
    font-size: 35px;
    position: absolute;
    top: 20px;
    right: 20px;
    opacity: 0.2;
}

.card-title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 10px;
}

.card-number {
    font-size: 28px;
    font-weight: bold;
}

/* Custom Colors */
.bg-booking { background: linear-gradient(135deg,#667eea,#764ba2); }
.bg-new { background: linear-gradient(135deg,#43e97b,#38f9d7); }
.bg-revenue { background: linear-gradient(135deg,#ff9966,#ff5e62); }
.bg-users { background: linear-gradient(135deg,#f7971e,#ffd200); }
</style>

</head>

<body>
<div id="wrapper">

<!-- ================= TOP NAV ================= -->

<nav class="navbar navbar-default navbar-cls-top">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand text-center" href="dashboard.php"><?php echo $role; ?></a> 
    </div>

    <div style="color:white;padding:15px 50px 5px 50px;float:right;">
        Welcome <?php echo $userName; ?> 
        (<?php echo $role; ?>)
        &nbsp;
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<!-- ================= SIDEBAR ================= -->

<nav class="navbar-default navbar-side" role="navigation">
<div class="sidebar-collapse">
<ul class="nav" id="main-menu">

<li class="text-center">
    <img src="assets/img/find_user.png" class="user-image img-responsive"/>
</li>

<li>
    <a class="active-menu" href="dashboard.php">
        <i class="fa fa-dashboard"></i> Dashboard
    </a>
</li>

<!-- ================= ADMIN (FULL ACCESS) ================= -->
<?php if($role == "Admin") { ?>

<li>
    <a href="#"><i class="fa fa-users"></i> Users <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li><a href="users/addUser.php">Add New User</a></li>
        <li><a href="users/user-manage.php">Manage User</a></li>
    </ul>
</li>

<li>
    <a href="#"><i class="fa fa-building"></i> Table & Room <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li><a href="roomtable/add_roomtable.php">Add Room & Table</a></li>
        <li><a href="roomtable/manageRoomTable.php">Manage Room & Table</a></li>
    </ul>
</li>

<li>
    <a href="#"><i class="fa fa-book"></i> Booking Record <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li><a href="booking/allBooking.php">All Booking</a></li>
        <li><a href="booking/newBooking.php">New Booking</a></li>
        <li><a href="booking/acceptedBooking.php">Accepted Booking</a></li>
        <li><a href="booking/rejectedBooking.php">Rejected Booking</a></li>
    </ul>
</li>

<li>
    <a href="#"><i class="fa fa-money"></i> Expances <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li><a href="accounts/add_expance.php">Add New Expance</a></li>
        <li><a href="accounts/manage_expance.php">Manage Expance</a></li>
        <li><a href="accounts/total_Income.php">Completed Income</a></li>
    </ul>
</li>

<li>
    <a href="calculator.php"><i class="fa fa-square-o"></i>Calculator</a>
</li>

<?php } ?>

<!-- ================= ACCOUNTANT ================= -->
<?php if($role == "Accountant") { ?>

<li>
    <a href="roomtable/manageRoomTable.php"><i class="fa fa-building"> Manage Room & Table</i></a>
    <!-- <ul class="nav nav-second-level">
        <li><a href="roomtable/add_roomtable.php">Add Room & Table</a></li>
        <li><a href="roomtable/manageRoomTable.php">Manage Room & Table</a></li>
    </ul> -->
</li>

<li>
    <a href="#"><i class="fa fa-money"></i> Expances <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li><a href="accounts/add_expance.php">Add New Expance</a></li>
        <li><a href="accounts/manage_expance.php">Manage Expance</a></li>
    </ul>
</li>


<li>
    <a href="booking/allbooking.php">
        <i class="fa fa-book"></i> Booking
    </a>
</li>

<li>
    <a href="reports.php">
        <i class="fa fa-file"></i> Financial Reports
    </a>
</li>

<li>
    <a href="calculator.php"><i class="fa fa-square-o"></i>Calculator</a>
</li>

<?php } ?>

<!-- ================= DIRECTOR ================= -->
<?php if($role == "Director") { ?>

<li>
    <a href="overview.php">
        <i class="fa fa-line-chart"></i> Company Overview
    </a>
</li>

<li>
    <a href="booking/allbooking.php">
        <i class="fa fa-book"></i> Booking
    </a>
</li>

<li>
    <a href="reports.php">
        <i class="fa fa-bar-chart"></i> Reports
    </a>
</li>

<li>
    <a href="calculator.php"><i class="fa fa-square-o"></i>Calculator</a>
</li>

<?php } ?>

</ul>
</div>
</nav>
