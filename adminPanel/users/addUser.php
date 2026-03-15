<?php
include '../includes/header2.php';
include '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {

    $name = $_POST['userName'];
    $email = $_POST['userEmail'];
    $phone = $_POST['uPhone'];
    $type = $_POST['role'];
    $password = $_POST['password'];
    $address = $_POST['address'];

    // Correct Prepared
    //  Statement
    $adduser = $conn->prepare("INSERT INTO `user` 
    (`userName`, `userEmail`, `uPhone`, `role`, `password`, `address`) 
    VALUES ('$name', '$email', '$phone', '$type', '$password', '$address')");


    if ($adduser->execute()) {
        echo "<script>alert('User Added Successfully!');</script>";
    } else {
        echo "<script>alert('Error: Something Went Wrong!');</script>";
    }

    $adduser->close();
}
?>

    <div id="page-wrapper" >
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h2>Add New User</h2>   
                    <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you back. </h5>   
                </div>
            </div>
                <hr />
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="panel panel-default">
                            <div class="panel-heading">New User Adding Form</div>
                            <div class="panel-body">
                                <form action="#" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userName">User Name</label>
                                                <input type="text" placeholder="Enter Your Good Name" name="userName" id="userName" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userEmail">User E-mail</label>
                                                <input type="email" placeholder="Enter Your E-mail" name="userEmail" id="userEmail" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uPhone">User Phone No</label>
                                                <input type="text" placeholder="Enter Your Phone Number" name="uPhone" id="userPhone" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role">select User Type</label>
                                                <select name="role" id="role" class="form-control" required>
                                                    <option value="">---</option>
                                                    <option value="1">Admin</option>
                                                    <option value="2">Sub-Admin</option>
                                                    <option value="3">Accountant</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Enter Your Password</label>
                                                <input type="password" placeholder="Enter Your Password" name="password" id="password" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Enter Your  addres</label>
                                                <input type="text" placeholder="Enter Your addres" name="address" class="form-control" required>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group checkbox">
                                                <label>
                                                    <input type="checkbox" name="" required> <span>Are You Agree</span>
                                                </label>
                                            </div> -->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success" name="submit">Submit</button>
                                                <button type="reset" class="btn btn-danger">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
        </div>
    </div>
        

<?php
    include '../includes/footer2.php';
?>