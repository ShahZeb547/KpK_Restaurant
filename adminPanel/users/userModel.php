<?php
include '../includes/config.php';
ob_start();

/* ================================
   1️⃣ Add New User Logic
================================ */
if(isset($_POST['addUser'])){
    $name     = $_POST['userName'];
    $email    = $_POST['userEmail'];
    $phone    = $_POST['uPhone'];
    $type     = $_POST['role'];
    $password = $_POST['password'];
    $address  = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO user (userName, userEmail, uPhone, role, password, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $email, $phone, $type, $password, $address);

    if($stmt->execute()){
        echo "<script>alert('User Added Successfully!'); window.location.href='user-manage.php';</script>";
    } else {
        echo "<script>alert('Error adding user!');</script>";
    }
    $stmt->close();
}

/* ================================
   2️⃣ Update User Logic
================================ */
if(isset($_POST['updateUser'])){
    $userId   = $_POST['id'];
    $userName = $_POST['userName'];
    $userEmail= $_POST['userEmail'];
    $uPhone   = $_POST['uPhone'];
    $role    = $_POST['role'];

    $stmt = $conn->prepare("UPDATE user SET userName=?, userEmail=?, uPhone=?, role=? WHERE id=?");
    $stmt->bind_param("sssii", $userName, $userEmail, $uPhone, $role, $userId);

    if($stmt->execute()){
        echo "<script>alert('User Updated Successfully!'); window.location.href='user-manage.php';</script>";
    } else {
        echo "<script>alert('Error updating user!');</script>";
    }
    $stmt->close();
}

/* ================================
   3️⃣ Delete User Logic
================================ */
if(isset($_POST['deleteUser'])){
    $deleteId = $_POST['deleteUserId'];

    $stmt = $conn->prepare("DELETE FROM user WHERE id=?");
    $stmt->bind_param("i", $deleteId);

    if($stmt->execute()){
        echo "<script>alert('User Deleted Successfully!'); window.location.href='user-manage.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
    $stmt->close();
}

/* ================================
   4️⃣ Fetch User for Edit or View
================================ */
$user = null;
if(isset($_GET['userId'])){
    $userId = (int)$_GET['userId'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i",$userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

$viewUser = null;
if(isset($_GET['userView'])){
    $viewId = (int)$_GET['userView'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i",$viewId);
    $stmt->execute();
    $result = $stmt->get_result();
    $viewUser = $result->fetch_assoc();
}

/* ================================
   5️⃣ Modals HTML Start
================================ */
?>

<!-- Add New User Modal -->
<div class="modal fade" id="addNewUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New User</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" name="userName" class="form-control" placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User Email</label>
                                <input type="email" name="userEmail" class="form-control" placeholder="Enter Email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="uPhone" class="form-control" placeholder="Enter Phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User Type</label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Sub-Admin</option>
                                    <option value="3">Accountant</option>
                                    <option value="4">User</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Enter Address" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" name="addUser" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<?php if($user): ?>
<div class="modal fade" id="updateUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit User</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="userName" class="form-control" value="<?= $user['userName'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="userEmail" class="form-control" value="<?= $user['userEmail'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="uPhone" class="form-control" value="<?= $user['uPhone'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>User Type</label>
                        <select name="role" class="form-control" required>
                            <option value="Admin" <?= ($user['role']=='Admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="Accountant" <?= ($user['role']=='Accountant') ? 'selected' : '' ?>>Accountant</option>
                            <option value="Director" <?= ($user['role']=='Director') ? 'selected' : '' ?>>Director</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="updateUser" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- View User Modal -->
<?php if($viewUser): ?>
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">User Information</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><th>Name</th><td><?= $viewUser['userName'] ?></td></tr>
                    <tr><th>Email</th><td><?= $viewUser['userEmail'] ?></td></tr>
                    <tr><th>Phone</th><td><?= $viewUser['uPhone'] ?></td></tr>
                    <tr><th>User Type</th><td><?= $viewUser['role'] ?></td></tr>
                    <tr><th>Address</th><td><?= $viewUser['address'] ?></td></tr>
                    <tr><th>Joining Date</th><td><?= $viewUser['date'] ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete User Modal -->
<?php if(isset($_GET['userdel'])):
    $delId = (int)$_GET['userdel'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bind_param("i",$delId);
    $stmt->execute();
    $res = $stmt->get_result();
    $delUser = $res->fetch_assoc();
?>
<div class="modal fade show" id="deleteUserModal" tabindex="-1" style="display:block;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <button type="button" class="close" data-dismiss="modal" onclick="window.location.href='user-manage.php';">&times;</button>
                <h4 class="modal-title">Delete User</h4>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this user?</p>
                <strong><?= $delUser['userName'] ?><br> (<?= $delUser['userEmail'] ?>)</strong>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="deleteUserId" value="<?= $delUser['id'] ?>">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='user-manage.php';">Cancel</button>
                    <button type="submit" name="deleteUser" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php ob_end_flush(); ?>
