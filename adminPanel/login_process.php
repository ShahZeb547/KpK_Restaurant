<?php
// session_start();

include 'config.php';  // DB connection

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    $_SESSION['username'] = $username;
    header("dashboard.php");  // success page
} else {
    echo "<script>alert('Invalid Username or Password'); window.location='login.php';</script>";
}
}



// $username = $_POST['username'];
// $password = $_POST['password'];

// // Query
// $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     $_SESSION['username'] = $username;
//     header("Location: dashboard.php");  // success page
// } else {
//     echo "<script>alert('Invalid Username or Password'); window.location='login.php';</script>";
// }
?>
