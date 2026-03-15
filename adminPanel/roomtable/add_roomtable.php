<?php 

include '../includes/header2.php';
include '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {

    $item_num      = $_POST['item_num'];
    $item_type     = $_POST['item_type'];
    $item_category = $_POST['item_category'];
    $price         = $_POST['price'];
    $created       = $_POST['created'];

    $stmt = $conn->prepare(
        "INSERT INTO roomtables 
        (item_num, item_type, item_category, price, created) 
        VALUES (?, ?, ?, ?, ?)"
    );

    // s = string, d = double (for price)
    $stmt->bind_param(
        "sssds",
        $item_num,
        $item_type,
        $item_category,
        $price,
        $created
    );

    if ($stmt->execute()) {
        echo "<script>alert('Room / Table Added Successfully');</script>";
    } else {
        echo "<script>alert('Error: {$stmt->error}');</script>";
    }

    $stmt->close();
}

?>





<div id="page-wrapper">
    <div id="page-inner">

        <div class="row">
            <div class="col-md-12">
                <h2>Create New Room / Table</h2>
                <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you back.</h5>
            </div>
        </div>
        <hr />

        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Room / Table Adding Form</div>
                    <div class="panel-body">

                        <form method="post" action="">

                            <div class="form-group">
                                <label>Room / Table Number</label>
                                <input type="text" name="item_num" class="form-control" placeholder="R-101 / T-05" required>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select name="item_type" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="Room">Room</option>
                                    <option value="Table">Table</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select name="item_category" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="Single">Single</option>
                                    <option value="Double">Double</option>
                                    <option value="VIP">VIP</option>
                                    <option value="Family">Family</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Pirce</label>
                                <input type="text" name="price" class="form-control" placeholder="Enter price per-night or houre" required>
                            </div>

                            <div class="form-group">
                                <label>Createdd By</label>
                                <select name="created" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Director">Director</option>
                                    <option value="Accountant">Accountant</option>
                                </select>
                            </div>

                            <button type="submit" name="submit" class="btn btn-success"> Submit </button>
                            <button type="reset" class="btn btn-danger"> Reset </button>

                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-2"></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive"> <!-- table-responsive start -->
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Item Number</th>
                                                <th>Type</th>
                                                <th>Category</th>
                                                <th>Created By</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $query = "SELECT * FROM roomtables";
                                                $result = $conn->query($query);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?= $row['id']; ?></td>
                                                <td><?= htmlspecialchars($row['item_num']); ?></td>
                                                <td><?= htmlspecialchars($row['item_type']); ?></td>
                                                <td><?= htmlspecialchars($row['item_category']); ?></td>
                                                <td><?= htmlspecialchars($row['created']); ?></td>
                                                <td><?= htmlspecialchars($row['price']); ?></td>
                                                <td style="display: flex; gap: 5px; justify-content: space-evenly;">
                                                <!-- View Button -->
                                                <i class="fa-solid fa-eye bg-primary btn" title="View Details" data-id="<?= $row['id']; ?>" data-toggle="modal" data-target="#viewModal"></i>
                                               
                                                <!-- Edit Button -->
                                                 <i class="fa-solid fa-pen-to-square bg-success btn" title="Edit Details" data-id="<?= $row['id']; ?>" data-toggle="modal" data-target="#editModal<?= $row['id']; ?>"></i>

                                                <!-- Delete Button -->
                                                 <i class="fa-solid fa-trash-can bg-danger btn" title="Delete" data-id="<?= $row['id']; ?>" data-toggle="modal" data-target="#delModal<?= $row['id']; ?>"></i>
                                                </td>
                                            </tr>
                                            <?php }
                                                } else {
                                                echo "<tr><td colspan='6' class='text-center'>No Records Found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div> <!-- table-responsive end -->
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer2.php'; ?>
