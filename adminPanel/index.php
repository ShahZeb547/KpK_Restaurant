<?php
session_start();
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['userEmail']);
    $pass  = trim($_POST['password']);

    if (!empty($email) && !empty($pass)) {

        $stmt = $conn->prepare("SELECT * FROM user WHERE userEmail=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $row = $result->fetch_assoc();

            if ($pass === $row['password']) {

                $_SESSION['user_id']  = $row['id'];
                $_SESSION['userName'] = $row['userName'];
                $_SESSION['role']     = $row['role'];

                header("Location: dashboard.php");
                exit();

            } else {
                $error = "Invalid Password!";
            }

        } else {
            $error = "Email not found!";
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

body{
background: linear-gradient(135deg,#667eea,#764ba2);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
font-family: 'Segoe UI',sans-serif;
overflow:hidden;
}

/* Floating animation */

body::before{
content:'';
position:absolute;
width:500px;
height:500px;
background:rgba(255,255,255,0.1);
border-radius:50%;
top:-100px;
left:-100px;
animation: float 8s infinite linear;
}

@keyframes float{
0%{transform:translateY(0)}
50%{transform:translateY(30px)}
100%{transform:translateY(0)}
}

.login-card{
width:380px;
padding:35px;
border-radius:15px;
background:rgba(255,255,255,0.15);
backdrop-filter: blur(10px);
box-shadow:0 15px 40px rgba(0,0,0,0.3);
animation:fadeIn 1s ease;
}

@keyframes fadeIn{
from{
opacity:0;
transform:translateY(40px);
}
to{
opacity:1;
transform:translateY(0);
}
}

.login-title{
text-align:center;
color:white;
font-size:24px;
margin-bottom:25px;
font-weight:600;
}

.form-group{
position:relative;
margin-bottom:20px;
}

.form-group i{
position:absolute;
top:12px;
left:12px;
color:#888;
}

.form-control{
padding-left:35px;
border-radius:8px;
border:none;
}

.form-control:focus{
box-shadow:0 0 5px rgba(255,255,255,0.8);
}

.btn-login{
width:100%;
background:#4e73df;
border:none;
padding:10px;
border-radius:8px;
color:white;
font-weight:600;
transition:0.3s;
}

.btn-login:hover{
background:#224abe;
transform:scale(1.03);
}

.forgot{
text-align:right;
margin-top:10px;
}

.forgot a{
color:white;
font-size:14px;
text-decoration:none;
}

.forgot a:hover{
text-decoration:underline;
}

.error{
background:#ff4d4d;
color:white;
padding:8px;
border-radius:5px;
margin-bottom:15px;
text-align:center;
}

</style>
</head>

<body>

<div class="login-card">

<div class="login-title">
<i class="fa fa-user-circle"></i> User Login
</div>

<?php if(!empty($error)){ ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="form-group">
<i class="fa fa-envelope"></i>
<input type="email" name="userEmail" class="form-control" placeholder="Enter Email" required>
</div>

<div class="form-group">
<i class="fa fa-lock"></i>
<input type="password" name="password" class="form-control" placeholder="Enter Password" required>
</div>

<button type="submit" class="btn-login">
<i class="fa fa-sign-in"></i> Login
</button>

<div class="forgot">
<a href="forgot-password.php">
<i class="fa fa-key"></i> Forgot Password?
</a>
</div>

</form>

</div>

</body>
</html>