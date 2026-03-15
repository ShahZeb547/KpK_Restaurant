<?php 
session_start();
include '../includes/header2.php';
include '../includes/config.php';

// ================= ROLE SECURITY =================
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
?>

<div id="page-wrapper">
    <div id="page-inner">

        <div class="row">
            <div class="col-md-12">
                <h2>New Booking Manage Record!</h2>
                <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you back.</h5>
            </div>
        </div>

        <hr>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Booking Type</th>
                        <th>Check In</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM bookings WHERE booking_status = 'new'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cus_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cus_phone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cus_email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['booking_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['check_in']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['pay_status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['booking_status']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No booking records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php
include '../includes/footer2.php';
?>