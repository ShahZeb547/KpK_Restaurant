<?php
include '../includes/header2.php';
include 'usermodel.php'; // Add/Edit/Delete logic included

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>User Manage Record!</h2>
                <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you back. </h5>   
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button type="button" class="btn btn-success pull-right" style="padding:6px 15px;" data-toggle="modal" data-target="#addNewUserModal"> Add Now </button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Joining Date</th>
                                        <th>User Type</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM user";
                                    $result = $conn->query($query);
                                    if($result->num_rows > 0){
                                        while($row = $result->fetch_assoc()){
                                    ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['userName'] ?></td>
                                        <td><?= $row['userEmail'] ?></td>
                                        <td><?= $row['uPhone'] ?></td>
                                        <td><?= $row['date'] ?></td>
                                        <td><?= $row['role'] ?></td>
                                        <td><?= $row['address'] ?></td>
                                        <td class="d-flex justify-content-center gap-2">
                                            <a href="user-manage.php?userView=<?= $row['id'] ?>" class="btn btn-info">View</a>
                                            <a href="user-manage.php?userId=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                                            <a href="user-manage.php?userdel=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php }} else { ?>
                                    <tr><td colspan="8" class="text-center">No Records Found</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer2.php';
?>
