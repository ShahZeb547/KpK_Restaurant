<?php
require_once '../includes/config.php';
include '../includes/header2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Agar form submit ho jaye
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $expense_type = $_POST['expense_type'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $expense_date = $_POST['expense_date'] ?? '';
    $description = $_POST['description'] ?? '';

    // Simple validation (aap apne hisaab se improve kar sakte hain)
    if ($name && $email && $expense_type && $amount && $expense_date) {
        $stmt = $conn->prepare("INSERT INTO expenses (name, email, contact, expense_type, amount, expense_date, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdss", $name, $email, $contact, $expense_type, $amount, $expense_date, $description);
        if ($stmt->execute()) {
            $success_message = "Expense record inserted successfully!";
        } else {
            $error_message = "Error inserting record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Please fill all required fields!";
    }
}
?>

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Add New Expense</h2>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?= $success_message ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                <?php endif; ?>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">New Expense Form</div>
                    <div class="panel-body">
                        <form action="#" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Contact</label>
                                        <input type="text" name="contact" id="contact" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expense_type">Expense Type</label>
                                        <select name="expense_type" id="expense_type" class="form-control" required>
                                            <option value="">Select Expense Type</option>
                                            <option value="Salary">Salary</option>
                                            <option value="Maintenance">Maintenance</option>
                                            <option value="Utilities">Utilities</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" name="amount" id="amount" class="form-control" step=".01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expense_date">Expense Date</label>
                                        <input type="date" name="expense_date" id= "expense_date" class ="form-control" required> 
                                    </div> 
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control"></textarea>
                                        <small class="form-text text-muted">Optional</small>
                                    </div>
                                </div>
                            </div> 
                            <br/> 
                            <button type ="submit"class ="btn btn-primary">Add Expense</button> 
                        </form> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
<?php include '../includes/footer2.php'; ?>