<!-- SCRIPTS - AT THE BOTTOM TO REDUCE LOAD TIME -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.metisMenu.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script src="assets/js/custom.js"></script>

<script>
$(document).ready(function () {
    // DataTables Initialization
    $('#dataTables-example').dataTable();

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
});
</script>

</body>
</html>