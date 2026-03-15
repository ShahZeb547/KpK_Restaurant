<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['role'])) {
    echo json_encode(['success'=>false]);
    exit();
}

$role = $_SESSION['role'];
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// VIEW (All roles allowed)
if ($action == 'view') {

    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
    exit();
}

// EDIT (Admin & Director only)
if ($action == 'edit') {

    if ($role != 'Admin' && $role != 'Director') {
        echo json_encode(['success'=>false,'message'=>'Permission denied']);
        exit();
    }

    $status = $_POST['booking_status'];

    $stmt = $conn->prepare("UPDATE bookings SET booking_status=? WHERE id=?");
    $stmt->bind_param("si",$status,$id);

    if($stmt->execute()){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false]);
    }
    exit();
}

// DELETE (Admin only)
if ($action == 'delete') {

    if ($role != 'Admin') {
        echo json_encode(['success'=>false,'message'=>'Permission denied']);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
    $stmt->bind_param("i",$id);

    if($stmt->execute()){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false]);
    }
    exit();
}
?>