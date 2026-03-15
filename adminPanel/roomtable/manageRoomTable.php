<?php 
session_start();
include '../includes/header2.php';
require '../includes/config.php';

// ================= ROLE SECURITY =================
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Include table-model
include 'table-model.php';
?>

<div id="page-wrapper">
    <div id="page-inner">

        <div class="row">
            <div class="col-md-12">
                <h2>Room / Table Manage Record!</h2>
                <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you back.</h5>
            </div>
        </div>

        <hr>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Number</th>
                        <th>Item Type</th>
                        <th>Item Category</th>
                        <th>Price</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                $query = "SELECT * FROM roomtables";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?= (int)$row['id']; ?></td>
                        <td><?= htmlspecialchars($row['item_num']); ?></td>
                        <td><?= htmlspecialchars($row['item_type']); ?></td>
                        <td><?= htmlspecialchars($row['item_category']); ?></td>
                        <td><?= htmlspecialchars($row['price']); ?></td>
                        <td><?= htmlspecialchars($row['createdby']); ?></td>

                        <td style="display:flex; gap:5px;">
                            <?php if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Director') { ?>
                                <a href="manageRoomTable.php?edit=<?= $row['id']; ?>" class="btn btn-primary ">Edit</a>
                                <a href="manageRoomTable.php?del=<?= $row['id']; ?>" class="btn btn-danger ">Delete</a>
                            <?php } else { ?>
                                <span class="text-muted">No Access</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include '../includes/footer2.php'; ?>
