<?php
require_once '../includes/config.php';
include '../includes/header2.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<div id="page-wrapper">
    <div id="page-inner">

        <!-- ===== PAGE HEADER ===== -->
        <div class="row">
            <div class="col-md-12">
                <h2>Financial Accounts</h2>
                <h5>Track income, expenses, and payments status.</h5>
            </div>
        </div>
        <hr />

        <!-- ===== FINANCIAL CARDS ===== -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="card bg-gradient-blue text-white">
                    <div class="card-body">
                        <div class="card-icon"><i class="fa fa-money"></i></div>
                        <div class="card-text">
                            <h4>Total Income</h4>
                            <p><?php
                                $res = $conn->query("SELECT SUM(amount) as total FROM bookings WHERE pay_status='Paid'");
                                $data = $res->fetch_assoc();
                                echo '$' . number_format($data['total'] ?? 0,2);
                            ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="card bg-gradient-orange text-white">
                    <div class="card-body">
                        <div class="card-icon"><i class="fa fa-credit-card"></i></div>
                        <div class="card-text">
                            <h4>Pending Payments</h4>
                            <p><?php
                                $res = $conn->query("SELECT SUM(amount) as total FROM bookings WHERE pay_status='Partial'");
                                $data = $res->fetch_assoc();
                                echo '$' . number_format($data['total'] ?? 0,2);
                            ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="card bg-gradient-green text-white">
                    <div class="card-body">
                        <div class="card-icon"><i class="fa fa-arrow-down"></i></div>
                        <div class="card-text">
                            <h4>Total Expenses</h4>
                            <p><?php
                                $res = $conn->query("SELECT SUM(amount) as total FROM expenses");
                                $data = $res->fetch_assoc();
                                echo '$' . number_format($data['total'] ?? 0,2);
                            ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="card bg-gradient-purple text-white">
                    <div class="card-body">
                        <div class="card-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="card-text">
                            <h4>Complated Transactions</h4>
                            <p><?php
                                $res = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE pay_status='Paid'");
                                $data = $res->fetch_assoc();
                                echo $data['total'] ?? 0;
                            ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr />

        <!-- ===== RECENT TRANSACTIONS TABLE ===== -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Recent Transactions</div>
                    <div class="panel-body table-responsive">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT cus_name, amount, payment_method, pay_status, created_at FROM bookings ORDER BY id DESC LIMIT 10");
                                $count=1;
                                while($row=$res->fetch_assoc()){
                                    $statusClass = 'secondary';
                                    if($row['pay_status']=='Paid') $statusClass='success';
                                    if($row['pay_status']=='Pending') $statusClass='warning';
                                    echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$row['cus_name']}</td>
                                        <td>$".number_format($row['amount'],2)."</td>
                                        <td>{$row['payment_method']}</td>
                                        <td><span class='badge badge-{$statusClass}'>{$row['pay_status']}</span></td>
                                        <td>{$row['created_at']}</td>
                                    </tr>";
                                    $count++;
                                }
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ===== INTERNAL CSS ===== -->
<style>
.card {
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
}
.card-body {
    display: flex;
    align-items: center;
}
.card-icon {
    font-size: 50px;
    margin-right: 15px;
    opacity: 0.7;
}
.bg-gradient-blue { background: linear-gradient(135deg,#3498db,#2980b9);}
.bg-gradient-orange { background: linear-gradient(135deg,#e67e22,#d35400);}
.bg-gradient-green { background: linear-gradient(135deg,#2ecc71,#27ae60);}
.bg-gradient-purple { background: linear-gradient(135deg,#9b59b6,#8e44ad);}
.card-text h4 { margin:0; font-size:18px; }
.card-text p { margin:0; font-size:24px; font-weight:bold; }

.badge {
    font-size: 0.9em;
    padding: 0.5em 0.8em;
    border-radius: 12px;
}
.badge-success { background-color: #28a745; }
.badge-warning { background-color: #ffc107; color:#212529; }
.badge-secondary { background-color: #6c757d; }
</style>

<?php include '../includes/footer2.php'; ?>