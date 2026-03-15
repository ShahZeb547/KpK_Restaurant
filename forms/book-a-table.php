<?php
session_start();
include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

    if (!isset($_POST['csrf_token']) || 
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid Session / CSRF token mismatch!");
    }

    $cus_name  = trim($_POST['cus_name']);
    $cus_phone = trim($_POST['cus_phone']);
    $cus_email = filter_var($_POST['cus_email'], FILTER_VALIDATE_EMAIL);
    $passport_no = trim($_POST['passport_no']);
    $address   = trim($_POST['address']);
    $checkin   = $_POST['check_in'];
    $checkout  = $_POST['check_out'];
    $booking_type = $_POST['booking_type'];
    $rooms     = max(1, (int)$_POST['rooms']);
    $adults    = max(0, (int)$_POST['adults']);
    $children  = max(0, (int)$_POST['children']);
    $room_type = $_POST['room_type'];
    $payment_method = $_POST['payment_method'];
    $pay_status = $_POST['pay_status'];
    $special_request = trim($_POST['special_request']);

    if(!$cus_email){
        $_SESSION['error'] = "Invalid Email!";
        header("Location: ../index.php");
        exit;
    }

    $checkin_date  = new DateTime($checkin);
    $checkout_date = new DateTime($checkout);

    if($checkout_date <= $checkin_date){
        $_SESSION['error'] = "Check-out must be after Check-in!";
        header("Location: ../index.php");
        exit;
    }

    $nights = $checkin_date->diff($checkout_date)->days;

    $stmt = $conn->prepare("SELECT price FROM roomtables WHERE item_type = ? AND item_category = ?");
    $stmt->bind_param("ss", $room_type, $booking_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        $_SESSION['error'] = "Invalid Selection!";
        header("Location: ../index.php");
        exit;
    }

    $row = $result->fetch_assoc();
    $price = (float)$row['price'];

    if($booking_type == "Table")
        $amount = $price * $rooms;
    else
        $amount = $price * $rooms * $nights;

    $booking_status = "new";
    $totalGuests = $adults + $children;

    $insert = $conn->prepare("INSERT INTO bookings 
        (cus_name, cus_phone, cus_email, passport_no, address,
        booking_type, rooms, adults, room_type, numGuest,
        check_in, check_out, amount, payment_method,
        pay_status, special_request, booking_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insert->bind_param(
        "ssssssiisissdssss",
        $cus_name, $cus_phone, $cus_email, $passport_no, $address,
        $booking_type, $rooms, $adults, $room_type, $totalGuests,
        $checkin, $checkout, $amount, $payment_method,
        $pay_status, $special_request, $booking_status
    );

    if($insert->execute()){
        $_SESSION['success'] = "Booking Successful! Total Amount: Rs " . number_format($amount,2);
    } else {
        $_SESSION['error'] = "Error: " . $insert->error;
    }

    $insert->close();
    header("Location: ../index.php");
    exit;
}
?>



<?php
  // Replace contact@example.com with your real receiving email address
  // $receiving_email_address = 'contact@example.com';

  // if( file_exists($php_email_form = '../assets/vendor/php-email-formss/php-email-form.phpss' )) {
  //   include( $php_email_form );
  // } else {
  //   die( 'Unable to load the "PHP Email Form" Library!');
  // }

  // $book_a_table = new PHP_Email_Form;
  // $book_a_table->ajax = true;
  
  // $book_a_table->to = $receiving_email_address;
  // $book_a_table->from_name = $_POST['name'];
  // $book_a_table->from_email = $_POST['email'];
  // $book_a_table->subject = "New table booking request from the website";

  // Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
  /*
  $book_a_table->smtp = array(
    'host' => 'example.com',
    'username' => 'example',
    'password' => 'pass',
    'port' => '587'
  );
  */

  // $book_a_table->add_message( $_POST['name'], 'Name');
  // $book_a_table->add_message( $_POST['email'], 'Email');
  // $book_a_table->add_message( $_POST['phone'], 'Phone', 4);
  // $book_a_table->add_message( $_POST['date'], 'Date', 4);
  // $book_a_table->add_message( $_POST['time'], 'Time', 4);
  // $book_a_table->add_message( $_POST['people'], '# of people', 1);
  // $book_a_table->add_message( $_POST['message'], 'Message');

  // echo $book_a_table->send();
  
?>
