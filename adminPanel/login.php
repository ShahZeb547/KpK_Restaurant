<?php
session_start();
include 'includes/config.php';  // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['userEmail']);
    $pass  = trim($_POST['password']);

    if (!empty($email) && !empty($pass)) {

        // Prepared Statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE userEmail=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

    $row = $result->fetch_assoc();

    // Plain Password Check
    if ($pass === $row['password']) {

        $_SESSION['user_id']   = $row['id'];
        $_SESSION['userName']  = $row['userName'];
        $_SESSION['role']      = $row['role'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid Password!";
    }

}

    } else {
        $error = "Please fill in all fields!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/font-awesome.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #2b5876, #4e4376);
    font-family: 'Open Sans', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}

.login-card {
    width: 100%;
    max-width: 400px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    background: #ffffff;
    overflow: hidden;
}

.panel-heading {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: white;
    padding: 18px;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #4e73df, #224abe);
    border: 0;
}

.error-msg {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<div class="login-card">
    <div class="panel-heading sm">Login With User-Email and Password</div>
    <div class="panel-body p-4">

        <?php if (!empty($error)) { ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="userEmail" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa fa-sign-in"></i> Login
            </button>

        </form>

    </div>
</div>

</body>
</html>



