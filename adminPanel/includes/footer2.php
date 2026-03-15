<!-- SCRIPTS - AT THE BOTTOM TO REDUCE LOAD TIME -->

<!-- JQUERY - Sabse pehle -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BOOTSTRAP -->
<script src="../assets/js/bootstrap.min.js"></script>

<!-- METISMENU -->
<script src="../assets/js/jquery.metisMenu.js"></script>

<!-- DATA TABLES -->
<script src="../assets/js/dataTables/jquery.dataTables.js"></script>
<script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>

<!-- CUSTOM SCRIPTS -->
<script src="../assets/js/custom.js"></script>

<script>
$(document).ready(function () {
//     // DataTables Initialization
    $('#dataTables-example').dataTable();

    // $(document).ready(function () {
    //     $('#dataTables-example').dataTable();
    // });

    // Show Modals Based on GET Parameters
    <?php if(isset($_GET['userView']) && $viewUser): ?>
        $('#viewUserModal').modal('show');
    <?php endif; ?>

    <?php if(isset($_GET['userId']) && $user): ?>
        $('#updateUserModal').modal('show');
    <?php endif; ?>

    <?php if(isset($_GET['userdel']) && $delUser): ?>
        $('#deleteUserModal').modal('show');
    <?php endif; ?>

    // Room Table data editing 
    <?php if(isset($_GET['edit']) && $data): ?>
        $('#updateData').modal('show');
    <?php endif; ?>

    // Room Table data deleting 
    <?php if(isset($_GET['del']) && $delId): ?>
        $('#deleteModal').modal('show');
    <?php endif; ?>

});
</script>

</body>
</html>
