<?php
// ================= SESSION =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/config.php';

// ================= ROLE CHECK =================
if (!isset($_SESSION['role']) || 
   ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Director')) {
    die("Access Denied!");
}


// ================= UPDATE QUERY SECTION =================
if (isset($_POST['submit'])) {

    $id = (int)$_POST['id'];
    $item_num = trim($_POST['item_num']);
    $item_type = $_POST['item_type'];
    $item_category = $_POST['item_category'];
    $price = trim($_POST['price']);
    $createdby = trim($_POST['createdby']);

    // Validation
    if (empty($item_num) || empty($item_type) || empty($item_category) || empty($price)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: manageRoomTable.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE roomtables SET item_num=?, item_type=?, item_category=?, price=?, createdby=? 
        WHERE id=?");

    $stmt->bind_param("sssssi", 
        $item_num, 
        $item_type, 
        $item_category, 
        $price, 
        $createdby, 
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Record updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update record!";
    }

    $stmt->close();
    header("Location: manageRoomTable.php");
    exit();
}


// ================= DELETE QUERY SECTION =================
if (isset($_POST['delete'])) {

    $deleteId = (int)$_POST['deleteUserId'];

    $stmt = $conn->prepare("DELETE FROM roomtables WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Record Deleted Successfully!";
    } else {
        $_SESSION['error'] = "Delete Failed!";
    }

    $stmt->close();
    header("Location: manageRoomTable.php");
    exit();
}

// ================= FETCH FOR EDIT =================
$data = null;
if (isset($_GET['edit'])) {
    $edit = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM roomtables WHERE id = ?");
    $stmt->bind_param("i", $edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
}

 if ($data) { ?>
<div class="modal fade" id="updateData" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Edit Record</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form method="post" action="#">

            <input type="hidden" name="id" value="<?= $data['id']; ?>">

            <div class="form-group">
                <label>Room / Table Number</label>
                <input type="text" name="item_num" class="form-control"
                   value="<?= $data['item_num']; ?>" 
                   <?= ($_SESSION['role']=='Accountant') ? 'readonly' : ''; ?> required>
            </div>

            <div class="form-group">
                <label>Type</label>
                <select name="item_type" class="form-control" <?= ($_SESSION['role']=='Accountant') ? 'disabled' : ''; ?> required>
                    <option value="Room" <?= ($data['item_type']=='Room')?'selected':''; ?>>Room</option>
                    <option value="Table" <?= ($data['item_type']=='Table')?'selected':''; ?>>Table</option>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="item_category" class="form-control" <?= ($_SESSION['role']=='Accountant') ? 'disabled' : ''; ?> required>
                    <option value="Single" <?= ($data['item_category']=='Single')?'selected':''; ?>>Single</option>
                    <option value="Double" <?= ($data['item_category']=='Double')?'selected':''; ?>>Double</option>
                    <option value="VIP" <?= ($data['item_category']=='VIP')?'selected':''; ?>>VIP</option>
                    <option value="Family" <?= ($data['item_category']=='Family')?'selected':''; ?>>Family</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" class="form-control" value="<?= $data['price']; ?>" <?= ($_SESSION['role']=='Accountant') ? 'readonly' : ''; ?> required>
            </div>

            <div class="form-group">
                <label>Created By</label>
                <input type="text" class="form-control" value="<?= $data['created']; ?>" name="createdby">
            </div>

            <?php if($_SESSION['role'] != 'Accountant') { ?>
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            <?php } else { ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <?php } ?>

        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<!-- ================= DELETE SECTION ================= -->
<?php
// ================= FETCH FOR DELETE CONFIRM =================
$del = null;
if (isset($_GET['del'])) {
    $delId = (int)$_GET['del'];
    $stmt = $conn->prepare("SELECT * FROM roomtables WHERE id=?");
    $stmt->bind_param("i", $delId);
    $stmt->execute();
    $res = $stmt->get_result();
    $del = $res->fetch_assoc();
    $stmt->close();
}

?>
<!-- ================= DELETE MODAL ================= -->
<div class="modal fade show" id="deleteModal" tabindex="-1" style="display:block;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <button type="button" class="close" data-dismiss="modal" onclick="window.location.href='manageRoomTable.php';">&times;</button>
                <h4 class="modal-title">Delete User</h4>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this user?</p>
                <strong>
                  <?= $del['item_num'] ?><br> <?= $del['item_type'] ?> <br> <?= $del['item_category'] ?>
                </strong>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="deleteUserId" value="<?= $del['id'] ?>">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='manageRoomTable.php';">Cancel</button>
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- ================= END DELETE ================= -->
