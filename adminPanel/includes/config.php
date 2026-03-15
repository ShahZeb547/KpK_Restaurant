<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



$host = "localhost";
$user = "root";   // Localhost me root theek hai
$pass = "";       // Default XAMPP password empty hota hai
$db   = "hotel";

// Enable exception mode
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = new mysqli($host, $user, $pass, $db);

    // Set proper charset (important for security)
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {

    // Localhost me debugging allowed
    die("Connection failed: " . $e->getMessage());
}
?>
