<?php   
session_start();
include '../includes/header2.php';
require '../includes/config.php';
include 'booking_Model.php'; // Modals ka HTML

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];

?>

<div id="page-wrapper">
    <div id="page-inner">
        <h2>All Booking Manage Record!</h2>
        <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you back.</h5>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Booking Type</th>
                        <th>Check In</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM bookings ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cus_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cus_phone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cus_email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['booking_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['check_in']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['pay_status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['booking_status']) . "</td>";
                            echo "<td style='white-space:nowrap;'>";

                            // Admin & Director -> View, Edit
                            if ($role == 'Admin' || $role == 'Director') {
                                echo "
                                <button class='btn btn-info view-btn' data-id='".$row['id']."' data-toggle='modal'                  data-target='#editModal'>
                                    <i class='fas fa-eye'></i>
                                </button>
                                <button class='btn btn-warning edit-btn' data-id='".$row['id']."' data-toggle='modal'                   data-target='#editModal'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                ";
                            }

                            // Only Admin -> Delete
                            if ($role == 'Admin') {
                                echo "
                                <button class='btn btn-danger delete-btn' data-id='".$row['id']."' data-toggle='modal'                  data-target='#deleteModal'>
                                    <i class='fas fa-trash'></i>
                                </button>
                                ";
                            }

                            // Accountant -> Only View
                            if ($role == 'Accountant') {
                                echo "
                                <button class='btn btn-info view-btn' data-id='".$row['id']."' data-toggle='modal' data-target='#editModal'>
                                    <i class='fas fa-eye'></i>
                                </button>
                                ";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No booking records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ----------------- View / Edit -----------------
    document.querySelectorAll('.edit-btn, .view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const isEdit = btn.classList.contains('edit-btn');
            document.getElementById('modalTitle').innerText = isEdit ? 'Edit Booking' : 'View Booking';
            
            fetch(`BookingAPI.php?action=view&id=${id}`)
            .then(res => res.json())
            .then(data => {
                if(isEdit){
                    document.getElementById('modalBody').innerHTML = `
                        <form id="editForm">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="${data.cus_name}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" value="${data.cus_email}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Booking Status</label>
                                <select name="booking_status" class="form-control">
                                    <option value="New" ${data.booking_status === 'New' ? 'selected' : ''}>New</option>
                                    <option value="Accepted" ${data.booking_status === 'Accepted' ? 'selected' : ''}>Accepted</option>
                                    <option value="Rejected" ${data.booking_status === 'Rejected' ? 'selected' : ''}>Rejected</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form>
                    `;
                    document.getElementById('editForm').addEventListener('submit', e=>{
                        e.preventDefault();
                        const formData = new FormData(e.target);
                        fetch(`BookingAPI.php?action=edit&id=${id}`, {
                            method:'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(resp => {
                            if(resp.success){
                                const row = document.querySelector(`button.edit-btn[data-id='${id}']`).closest('tr');
                                row.querySelector('td:nth-child(8)').innerText = formData.get('booking_status');
                                $('#editModal').modal('hide');
                            } else {
                                alert('Update failed!');
                            }
                        });
                    });
                } else {
                    // Set status class for color
                    let statusClass = '';
                    if(data.booking_status.toLowerCase() === 'new') statusClass = 'bg-warning text-dark';
                    else if(data.booking_status.toLowerCase() === 'accepted') statusClass = 'bg-success text-white';
                    else if(data.booking_status.toLowerCase() === 'rejected') statusClass = 'bg-danger text-white';

                    // View modal 2-column with bootstrap classes
                    document.getElementById('modalBody').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr><th>Name</th><td>${data.cus_name}</td></tr>
                                    <tr><th>Phone</th><td>${data.cus_phone}</td></tr>
                                    <tr><th>Email</th><td>${data.cus_email}</td></tr>
                                    <tr><th>Passport No</th><td>${data.passport_no}</td></tr>
                                    <tr><th>Address</th><td>${data.address}</td></tr>
                                    <tr><th>Booking Type</th><td>${data.booking_type}</td></tr>
                                    <tr><th>Room Type</th><td>${data.room_type}</td></tr>
                                    <tr><th>Created At</th><td>${data.created_at}</td></tr>
                                    <tr><th>Rooms</th><td>${data.rooms}</td></tr>
                                    <tr><th>Adults</th><td>${data.adults}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr><th>Number of Guests</th><td>${data.numGuest}</td></tr>
                                    <tr><th>Check In</th><td>${data.check_in}</td></tr>
                                    <tr><th>Check Out</th><td>${data.check_out}</td></tr>
                                    <tr><th>Amount</th><td>${data.amount}</td></tr>
                                    <tr><th>Payment Method</th><td>${data.payment_method}</td></tr>
                                    <tr><th>Payment Status</th><td>${data.pay_status}</td></tr>
                                    <tr><th>Special Request</th><td>${data.special_request}</td></tr>
                                    <tr><th>Booking Status</th><td class="${statusClass}">${data.booking_status}</td></tr>
                                </table>
                            </div>
                        </div>
                    `;
                }
            });
        });
    });

    // ----------------- Delete ----------------- //
    let deleteId = null;
    document.querySelectorAll('.delete-btn').forEach(btn=>{
        btn.addEventListener('click', () => {
            deleteId = btn.dataset.id;
            fetch(`BookingAPI.php?action=view&id=${deleteId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('deleteInfo').innerHTML = `
                    Delete booking for: <strong>${data.cus_name}</strong> (${data.cus_email}) ?
                `;
            });
        });
    });

    document.getElementById('confirmDelete').addEventListener('click', ()=>{
        fetch(`BookingAPI.php?action=delete&id=${deleteId}`, { method:'DELETE' })
        .then(res => res.json())
        .then(data=>{
            if(data.success){
                const row = document.querySelector(`button.delete-btn[data-id='${deleteId}']`).closest('tr');
                row.remove();
                $('#deleteModal').modal('hide');
            } else {
                alert('Delete failed!');
            }
        });
    });

});
</script>

<?php include '../includes/footer2.php'; ?>
