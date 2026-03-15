<?php 
require_once 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role     = $_SESSION['role'];
$userName = $_SESSION['userName'];

$profit = 
    ($conn->query("SELECT SUM(amount) as total FROM bookings WHERE pay_status='Paid'")->fetch_assoc()['total'] ?? 0)
  - ($conn->query("SELECT SUM(amount) as total FROM expenses")->fetch_assoc()['total'] ?? 0);


/* ===================== DASHBOARD DATA ===================== */
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'] ?? 0;
$newBookings   = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE booking_status='New'")->fetch_assoc()['total'] ?? 0;
$todayBookings = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['total'] ?? 0;
// $profit  = $conn->query("SELECT SUM(amount) as total FROM bookings WHERE pay_status='Paid'")->fetch_assoc()['total'] ?? 0;

$totalUsers = 0;
if ($role == "Admin") {
    $totalUsers = $conn->query("SELECT COUNT(*) as total FROM user")->fetch_assoc()['total'] ?? 0;
}

/* ===================== REVENUE DATA ===================== */
// Monthly Revenue (last 6 months)
$months = $revenues = [];
$result = $conn->query("
    SELECT DATE_FORMAT(created_at,'%b %Y') as month, SUM(amount) as total
    FROM bookings
    WHERE pay_status='Paid'
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY created_at DESC
    LIMIT 6
");
while($row = $result->fetch_assoc()){
    $months[] = $row['month'];
    $revenues[] = $row['total'];
}
$months = array_reverse($months);
$revenues = array_reverse($revenues);

// Daily Revenue (last 30 days)
$daily_dates = $daily_revenues = [];
$result = $conn->query("
    SELECT DATE(created_at) as day, SUM(amount) as total
    FROM bookings
    WHERE pay_status='Paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
");
while ($row = $result->fetch_assoc()) {
    $daily_dates[] = $row['day'];
    $daily_revenues[] = $row['total'];
}

// Weekly Revenue (last 12 weeks)
$weekly_labels = $weekly_revenues = [];
$result = $conn->query("
    SELECT CONCAT(YEAR(created_at), '-W', WEEK(created_at)) as week, SUM(amount) as total
    FROM bookings
    WHERE pay_status='Paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
    GROUP BY YEAR(created_at), WEEK(created_at)
    ORDER BY week ASC
");
while ($row = $result->fetch_assoc()) {
    $weekly_labels[] = $row['week'];
    $weekly_revenues[] = $row['total'];
}

// Yearly Revenue (last 5 years)
$years = $yearly_revenues = [];
$result = $conn->query("
    SELECT YEAR(created_at) as year, SUM(amount) as total
    FROM bookings
    WHERE pay_status='Paid'
    GROUP BY YEAR(created_at)
    ORDER BY year ASC
    LIMIT 5
");
while ($row = $result->fetch_assoc()){
    $years[] = $row['year'];
    $yearly_revenues[] = $row['total'];
}

/* ===================== FINANCIAL DATA ===================== */
// Daily totals (last 30 days)
$daily_income = $daily_pending = $daily_expenses = [];
$daily_dates_finance = [];
$result = $conn->query("
    SELECT DATE(created_at) as day,
           SUM(CASE WHEN pay_status='Paid' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN pay_status='Partial' THEN amount ELSE 0 END) as pending
    FROM bookings
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
");
while ($row = $result->fetch_assoc()) {
    $daily_dates_finance[] = $row['day'];
    $daily_income[] = $row['income'];
    $daily_pending[] = $row['pending'];
}
// Daily expenses
$daily_expense_map = [];
$result = $conn->query("
    SELECT DATE(created_at) as day, SUM(amount) as total
    FROM expenses
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
");
while ($row = $result->fetch_assoc()){
    $daily_expense_map[$row['day']] = $row['total'];
}
foreach($daily_dates_finance as $day){
    $daily_expenses[] = $daily_expense_map[$day] ?? 0;
}

// Weekly totals (last 12 weeks)
$weekly_labels_finance = $weekly_income = $weekly_pending = $weekly_expenses = [];
$result = $conn->query("
    SELECT CONCAT(YEAR(created_at), '-W', WEEK(created_at)) as week,
           SUM(CASE WHEN pay_status='Paid' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN pay_status='Partial' THEN amount ELSE 0 END) as pending
    FROM bookings
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
    GROUP BY YEAR(created_at), WEEK(created_at)
    ORDER BY week ASC
");
$weekly_income_map = $weekly_pending_map = [];
while($row = $result->fetch_assoc()){
    $weekly_labels_finance[] = $row['week'];
    $weekly_income_map[$row['week']] = $row['income'];
    $weekly_pending_map[$row['week']] = $row['pending'];
}
// Weekly expenses
$result = $conn->query("
    SELECT CONCAT(YEAR(created_at), '-W', WEEK(created_at)) as week, SUM(amount) as total
    FROM expenses
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
    GROUP BY YEAR(created_at), WEEK(created_at)
");
$weekly_expense_map = [];
while($row = $result->fetch_assoc()){
    $weekly_expense_map[$row['week']] = $row['total'];
}
foreach($weekly_labels_finance as $w){
    $weekly_income[] = $weekly_income_map[$w] ?? 0;
    $weekly_pending[] = $weekly_pending_map[$w] ?? 0;
    $weekly_expenses[] = $weekly_expense_map[$w] ?? 0;
}

// Monthly totals (last 6 months)
$monthly_labels_finance = $monthly_income = $monthly_pending = $monthly_expenses = [];
$result = $conn->query("
    SELECT DATE_FORMAT(created_at,'%b %Y') as month,
           SUM(CASE WHEN pay_status='Paid' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN pay_status='Partial' THEN amount ELSE 0 END) as pending
    FROM bookings
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY created_at ASC
");
$monthly_income_map = $monthly_pending_map = [];
while($row = $result->fetch_assoc()){
    $monthly_labels_finance[] = $row['month'];
    $monthly_income_map[$row['month']] = $row['income'];
    $monthly_pending_map[$row['month']] = $row['pending'];
}
// Monthly expenses
$result = $conn->query("
    SELECT DATE_FORMAT(created_at,'%b %Y') as month, SUM(amount) as total
    FROM expenses
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(created_at), MONTH(created_at)
");
$monthly_expense_map = [];
while($row = $result->fetch_assoc()){
    $monthly_expense_map[$row['month']] = $row['total'];
}
foreach($monthly_labels_finance as $m){
    $monthly_income[] = $monthly_income_map[$m] ?? 0;
    $monthly_pending[] = $monthly_pending_map[$m] ?? 0;
    $monthly_expenses[] = $monthly_expense_map[$m] ?? 0;
}

// Yearly totals (last 5 years)
$yearly_labels_finance = $yearly_income = $yearly_pending = $yearly_expenses = [];
$result = $conn->query("
    SELECT YEAR(created_at) as year,
           SUM(CASE WHEN pay_status='Paid' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN pay_status='Partial' THEN amount ELSE 0 END) as pending
    FROM bookings
    GROUP BY YEAR(created_at)
    ORDER BY year ASC
    LIMIT 5
");
$yearly_income_map = $yearly_pending_map = [];
while($row = $result->fetch_assoc()){
    $yearly_labels_finance[] = $row['year'];
    $yearly_income_map[$row['year']] = $row['income'];
    $yearly_pending_map[$row['year']] = $row['pending'];
}
// Yearly expenses
$result = $conn->query("
    SELECT YEAR(created_at) as year, SUM(amount) as total
    FROM expenses
    GROUP BY YEAR(created_at)
    ORDER BY year ASC
    LIMIT 5
");
$yearly_expense_map = [];
while($row = $result->fetch_assoc()){
    $yearly_expense_map[$row['year']] = $row['total'];
}
foreach($yearly_labels_finance as $y){
    $yearly_income[] = $yearly_income_map[$y] ?? 0;
    $yearly_pending[] = $yearly_pending_map[$y] ?? 0;
    $yearly_expenses[] = $yearly_expense_map[$y] ?? 0;
}
?>

<div id="page-wrapper">
<div id="page-inner">

<!-- HEADER -->
<div class="row">
<div class="col-md-12">
<h2><?php echo htmlspecialchars($role); ?> Dashboard</h2>
<h5>Welcome <?php echo htmlspecialchars($userName); ?>, Love to see you! </h5>
</div>
</div>
<hr/>

<!-- STAT CARDS -->
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="dashboard-card bg-booking">
<i class="fa fa-calendar dashboard-icon"></i>
<div class="card-title">Total Bookings</div>
<div class="card-number"><?php echo $totalBookings; ?></div>
</div>
</div>

<div class="col-md-3 col-sm-6">
<div class="dashboard-card bg-new">
<i class="fa fa-bell dashboard-icon"></i>
<div class="card-title">New Bookings</div>
<div class="card-number"><?php echo $newBookings; ?></div>
</div>
</div>

<div class="col-md-3 col-sm-6">
<div class="dashboard-card bg-revenue">
<i class="fa fa-money dashboard-icon"></i>
<div class="card-title">Total Profit</div>
<div class="card-number"><?php echo "$" . number_format($profit, 2); ?></div>
</div>
</div>

<?php if($role == "Admin") { ?>
<div class="col-md-3 col-sm-6">
<div class="dashboard-card bg-users">
<i class="fa fa-users dashboard-icon"></i>
<div class="card-title">Total Users</div>
<div class="card-number"><?php echo $totalUsers; ?></div>
</div>
</div>
<?php } ?>
</div>

<br>
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

<br>

<!-- TODAY BOOKINGS -->
<div class="row">
<div class="col-md-12">
<div class="alert alert-warning">
<strong>Today's Bookings:</strong> <?php echo $todayBookings; ?>
</div>
</div>
</div>

<!-- CHARTS ROW -->
<div class="row">

<div class="col-md-8">
<div class="panel panel-default">
    <div class="panel-heading">
        Revenue Tracking
        <select id="timeframe" class="pull-right">
            <option value="daily" selected>Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>
    <div class="panel-body">
        <canvas id="revenueChart" height="150"></canvas>
    </div>
</div>
</div>

<div class="col-md-4">
<div class="panel panel-default">
    <div class="panel-heading">Booking Status</div>
    <div class="panel-body">
        <canvas id="statusChart" height="100"></canvas>
    </div>
</div>
</div>

</div>

<!-- FINANCIAL OVERVIEW -->
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading">
        Financial Overview
        <select id="financeTimeframe" class="pull-right">
            <option value="daily" selected>Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>
    <div class="panel-body">
        <canvas id="financeChart" height="100"></canvas>
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

<!-- PASS DATA TO JS -->
<script>
const dailyData = { labels: <?php echo json_encode($daily_dates); ?>, values: <?php echo json_encode($daily_revenues); ?> };
const weeklyData = { labels: <?php echo json_encode($weekly_labels); ?>, values: <?php echo json_encode($weekly_revenues); ?> };
const monthlyData = { labels: <?php echo json_encode($months); ?>, values: <?php echo json_encode($revenues); ?> };
const yearlyData = { labels: <?php echo json_encode($years); ?>, values: <?php echo json_encode($yearly_revenues); ?> };

const statusData = [
    <?php echo $conn->query("SELECT COUNT(*) as c FROM bookings WHERE booking_status='New'")->fetch_assoc()['c']; ?>,
    <?php echo $conn->query("SELECT COUNT(*) as c FROM bookings WHERE booking_status='Accepted'")->fetch_assoc()['c']; ?>,
    <?php echo $conn->query("SELECT COUNT(*) as c FROM bookings WHERE booking_status='Rejected'")->fetch_assoc()['c']; ?>
];

// Financial data
const financeData = {
    daily: { labels: <?php echo json_encode($daily_dates_finance); ?>, income: <?php echo json_encode($daily_income); ?>, pending: <?php echo json_encode($daily_pending); ?>, expenses: <?php echo json_encode($daily_expenses); ?> },
    weekly: { labels: <?php echo json_encode($weekly_labels_finance); ?>, income: <?php echo json_encode($weekly_income); ?>, pending: <?php echo json_encode($weekly_pending); ?>, expenses: <?php echo json_encode($weekly_expenses); ?> },
    monthly: { labels: <?php echo json_encode($monthly_labels_finance); ?>, income: <?php echo json_encode($monthly_income); ?>, pending: <?php echo json_encode($monthly_pending); ?>, expenses: <?php echo json_encode($monthly_expenses); ?> },
    yearly: { labels: <?php echo json_encode($yearly_labels_finance); ?>, income: <?php echo json_encode($yearly_income); ?>, pending: <?php echo json_encode($yearly_pending); ?>, expenses: <?php echo json_encode($yearly_expenses); ?> }
};
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/js/charts.js"></script>

<?php include 'includes/footer.php'; ?>