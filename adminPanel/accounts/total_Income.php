<?php
require_once '../includes/config.php';
include '../includes/header2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

?>

<div class="page-wrapper">
    <div class="page-inner">

        <!-- ===== PAGE HEADER ===== -->
         <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="panel panel-defauld">
                    <div class="panel-heading">
                        <h2>Completed Transactions</h2>
                        <h5><?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Track Total & Completed Transactions</h5>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <hr>

         <!-- <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                
            </div>
         </div> -->
    </div>
</div>


<?php include '../includes/footer2.php'; ?>