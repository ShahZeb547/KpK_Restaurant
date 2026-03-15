<?php 

require 'config.php'; // DB Connection 

// Login check 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['userName'];
$role     = $_SESSION['role'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Resturant Dashboard</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
   
    <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <!-- DATA TABLES MODERN CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />


   <style>
.modal {
    overflow: visible;
}
</style>


</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../dashboard.php"><?php echo $role; ?></a> 
            </div>
        <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?> (<?=  htmlspecialchars($_SESSION['role']);?>) &nbsp; 
            <a href="../logout.php" class="btn btn-danger square-btn-adjust">Logout</a> 
        </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
                <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <img src="../assets/img/find_user.png" class="user-image img-responsive"/>
					</li>
                    <li>
                        <a class="active-menu"  href="../dashboard.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    </li>
                  
<!-- ================= ADMIN (FULL ACCESS) ================= -->
<?php if($role == "Admin") { ?>

                    <li>
                        <a href="#"><i class="fa-solid fa-users"></i> Users <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="../users/addUser.php">Add New User</a>
                            </li>
                            <li>
                                <a href="../users/user-manage.php">Manage User</a>
                            </li>
                        </ul>                  
                      </li>
                    <li> <!-- Table & Room tab Start -->
                        <a href="#"><i class="fa-solid fa-users"></i> Table & Room <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="../roomtable/add_roomtable.php">Add New Room & Table</a>
                            </li>
                            <li>
                                <a href="../roomtable/manageRoomTable.php">Manage Room & Table</a>
                            </li>
                        </ul>                  
                    </li> <!-- Table & Room tab End -->
                    

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
                            <li><a href="../accounts/add_expance.php">Add New Expance</a></li>
                            <li><a href="../accounts/manage_expance.php">Manage Expance</a></li>
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
                        <a href="../roomtable/manageRoomTable.php"><i class="fa-solid fa-users"></i> Manage Room & Table</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-money"></i> Expances <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="../accounts/add_expance.php">Add New Expance</a></li>
                            <li><a href="../accounts/manage_expance.php">Manage Expance</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="../booking/allbooking.php"><i class="fa-solid fa-users"></i> Booking</a>
                    </li>
                    <li>
                        <a href="../reports.php">
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




