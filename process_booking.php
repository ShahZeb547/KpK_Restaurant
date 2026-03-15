<?php
session_start();
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

if (!isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Invalid Request");
}

$cus_name = $_POST['cus_name'];
$cus_phone = $_POST['cus_phone'];
$cus_email = $_POST['cus_email'];
$passport_no = $_POST['passport_no'];
$address = $_POST['address'];
$checkin = $_POST['check_in'];
$checkout = $_POST['check_out'];
$booking_type = $_POST['booking_type'];
$room_type = (int)$_POST['room_type']; // dropdown id
$rooms = (int)$_POST['rooms'];
$adults = (int)$_POST['adults'];
$numGuest = (int)$_POST['numGuest'];
$payment_method = $_POST['payment_method'];
$pay_status = $_POST['pay_status'];
$special_request = $_POST['special_request'];

$checkin_date = new DateTime($checkin);
$checkout_date = new DateTime($checkout);

if ($checkout_date <= $checkin_date) {
    header("Location: index.php?error=1");
    exit;
}

$nights = $checkin_date->diff($checkout_date)->days;

// Get price
$stmt = $conn->prepare("SELECT price FROM roomtables WHERE id=?");
$stmt->bind_param("i", $room_type);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0){
    header("Location: index.php?error=1");
    exit;
}

$row = $res->fetch_assoc();
$price = $row['price'];

if($booking_type == "Table"){
    $amount = $price * $rooms;
}else{
    $amount = $price * $rooms * $nights;
}

$status = "new";

//  FIXED INSERT QUERY
$stmt = $conn->prepare("INSERT INTO bookings
(cus_name, cus_phone, cus_email, passport_no, address,
booking_type, room_type, rooms, adults, numGuest,
check_in, check_out, amount, payment_method,
pay_status, special_request, booking_status)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->bind_param(
"ssssssiiiissdssss",
$cus_name,$cus_phone,$cus_email,$passport_no,$address,
$booking_type,$room_type,$rooms,$adults,$numGuest,
$checkin,$checkout,$amount,$payment_method,
$pay_status,$special_request,$status
);

$stmt->execute();

header("Location: index.php?success=1");
exit;
}
?>