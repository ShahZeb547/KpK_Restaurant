<?php 
require_once '../includes/config.php';
include 'includes/header.php';
// ================= ROLE CHECK =================
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Modern Calculator</h2>   
                <h5>Welcome <?= htmlspecialchars($_SESSION['userName'] ?? 'User'); ?>, Love to see you!</h5>   
            </div>
        </div>
        <hr />

        <!-- ================= MODERN GLASS CALCULATOR ================= -->
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="calc-card">
                    <input type="text" id="calc-screen" readonly placeholder="0">
                    <div class="calc-buttons">
                        <button onclick="press('7')">7</button>
                        <button onclick="press('8')">8</button>
                        <button onclick="press('9')">9</button>
                        <button onclick="press('/')">÷</button>

                        <button onclick="press('4')">4</button>
                        <button onclick="press('5')">5</button>
                        <button onclick="press('6')">6</button>
                        <button onclick="press('*')">×</button>

                        <button onclick="press('1')">1</button>
                        <button onclick="press('2')">2</button>
                        <button onclick="press('3')">3</button>
                        <button onclick="press('-')">−</button>

                        <button onclick="press('0')">0</button>
                        <button onclick="press('.')">.</button>
                        <button onclick="calculate()">=</button>
                        <button onclick="press('+')">+</button>

                        <button onclick="clearScreen()" class="clear-btn">C</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= INTERNAL CSS ================= -->
<style>
.calc-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    color: #fff;
}
#calc-screen {
    width: 100%;
    height: 60px;
    font-size: 28px;
    text-align: right;
    border-radius: 15px;
    border: none;
    padding: 10px;
    margin-bottom: 15px;
    background: rgba(255,255,255,0.15);
    color: #fff;
}

/* Grid Layout for Buttons */
.calc-buttons {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    grid-gap: 15px;
}

/* Number & Operator Buttons */
.calc-buttons button {
    padding: 18px;
    font-size: 20px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
    color: #fff;
    background: linear-gradient(135deg,#1abc9c,#16a085); /* Teal gradient */
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Hover effect */
.calc-buttons button:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    background: linear-gradient(135deg,#16a085,#1abc9c);
}

/* Clear Button */
.clear-btn {
    grid-column: span 4;
    background: linear-gradient(135deg,#e74c3c,#c0392b); /* Red gradient */
    color: #fff;
}

/* Clear Button Hover */
.clear-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    background: linear-gradient(135deg,#c0392b,#e74c3c);
}
</style>

<!-- ================= INTERNAL JS ================= -->
<script>
let screen = document.getElementById('calc-screen');

function press(num){
    if(screen.value === "0") screen.value = "";
    screen.value += num;
}

function clearScreen(){
    screen.value = '';
}

function calculate(){
    try {
        screen.value = eval(screen.value);
    } catch(e){
        screen.value = 'Error';
        setTimeout(()=> screen.value='',1000);
    }
}
</script>

<?php include 'includes/footer.php'; ?>