<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>โปรแกรมคำนวณส่วนลดร้านค้า</title>
<style>
    body{
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f5e7a2;
        color:#222;
    }
    .container{
        width:440px;
        margin:60px auto;
        background:#ffffff;
        padding:28px;
        border-radius:10px;
        border:1px solid #eae9d6;
    }
    h2{
        text-align:center;
        margin-bottom:22px;
        color:#1f2a44;
    }
    label{
        font-weight:600;
        color:#1f2a44;
    }
    input[type=number], select{
        width:100%;
        padding:9px;
        margin:6px 0 18px;
        border:1px solid #52aeff;
        border-radius:6px;
        font-size:15px;
        color:#222;
    }
    button{
        width:100%;
        padding:11px;
        background:#4b5fd2;
        border:none;
        border-radius:6px;
        color:#fff;
        font-size:16px;
        cursor:pointer;
    }
    button:hover{
        background:#3f51c4;
    }
    .result{
        margin-top:22px;
        padding:16px;
        background:#f7f9ff;
        border-radius:8px;
        border:1px solid #d6dbea;
    }
    .recommend{
        margin-top:12px;
        padding:12px;
        background:#e9edff;
        border:2px dashed #4b5fd2;
        font-weight:700;
        color:#1f2a44;
        text-align:center;
    }
    .final-price{
        margin-top:12px;
        padding:10px;
        background:#eae9d6;
        border:1.5px solid #4b5fd2;
        border-radius:8px;
        text-align:center;
        color:#1f2a44;
        font-weight:700;
        font-size:16px;
    }
    .final-price span{
        font-size:18px;
        color:#243b8a;
    }
    .error{
        color:#b00020;
        text-align:center;
        font-weight:600;
        margin-top:15px;
    }
</style>
</head>
<body>

<div class="container">
<h2>โปรแกรมคำนวณส่วนลด</h2>

<form method="post">
    <label>ยอดซื้อสินค้า (บาท)</label>
    <input type="number" name="amount" step="0.01" required>

    <label>สถานะลูกค้า</label>
    <select name="member">
        <option value="no">ไม่เป็นสมาชิก</option>
        <option value="yes">เป็นสมาชิก</option>
    </select>

    <button type="submit">คำนวณ</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = $_POST["amount"];
    $member = $_POST["member"];

    if ($amount < 0) {
        echo "<p class='error'>ยอดซื้อต้องไม่เป็นค่าติดลบ</p>";
        exit;
    }

    $rate = 0;
    $level = "";
    $nextTarget = 0;
    $nextRate = 0;

    if ($amount >= 5000) {
        $rate = 20;
        $level = "Platinum";
    } elseif ($amount >= 3000) {
        $rate = 15;
        $level = "Gold";
        $nextTarget = 5000;
        $nextRate = 20;
    } elseif ($amount >= 1000) {
        $rate = 10;
        $level = "Silver";
        $nextTarget = 3000;
        $nextRate = 15;
    } elseif ($amount >= 500) {
        $rate = 5;
        $level = "Bronze";
        $nextTarget = 1000;
        $nextRate = 10;
    } else {
        $nextTarget = 500;
        $nextRate = 5;
    }

    $memberRate = 0;
    if ($member == "yes" && $amount >= 500) {
        $memberRate = 5;
    }

    $totalRate = $rate + $memberRate;
    $discount = ($amount * $totalRate) / 100;
    $net = $amount - $discount;

    echo "<div class='result'>";
    echo "ยอดซื้อ: " . number_format($amount,2) . " บาท<br>";

    if ($rate > 0) {
        echo "ได้รับส่วนลดระดับ $level {$rate}%<br>";
    } else {
        echo "ไม่ได้รับส่วนลด<br>";
    }

    if ($memberRate > 0) {
        echo "ได้รับส่วนลดพิเศษสมาชิก +5%<br>";
    }

    if ($totalRate > 0) {
        echo "ส่วนลดที่ได้รับ: " . number_format($discount,2) . " บาท<br>";
    }

    echo "<div class='final-price'>
            ราคาที่ต้องจ่าย<br>
            <span>" . number_format($net,2) . " บาท</span>
          </div>";

    if ($nextTarget > 0 && $amount < 5000) {
        echo "<div class='recommend'>
                แนะนำ: ซื้อเพิ่มอีก " .
                number_format($nextTarget - $amount,2) .
                " บาท รับส่วนลด {$nextRate}%
              </div>";
    }

    echo "</div>";
}
?>

</div>
</body>
</html>
