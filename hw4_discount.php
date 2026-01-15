<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>โปรแกรมคำนวณส่วนลดร้านค้า</title>
<style>
body{
    font-family: "Segoe UI", Arial, sans-serif;
    background:#efe6a8;
    color:#1e1e1e;
}
.wrapper{
    width:460px;
    margin:70px auto;
}
.header{
    background:#243b8a;
    color:#fff;
    padding:18px;
    border-radius:12px 12px 0 0;
    text-align:center;
    font-size:20px;
    font-weight:700;
}
.card{
    background:#ffffff;
    padding:26px;
    border-radius:0 0 12px 12px;
    border:1px solid #dcdcdc;
}
label{
    display:block;
    margin-bottom:6px;
    font-weight:600;
    color:#243b8a;
}
input[type=number], select{
    width:100%;
    padding:10px;
    margin-bottom:18px;
    border-radius:6px;
    border:1px solid #8ea2ff;
    font-size:15px;
}
button{
    width:100%;
    padding:12px;
    background:#3f51c4;
    color:#fff;
    font-size:16px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
button:hover{
    background:#3343b8;
}
.result{
    margin-top:22px;
    background:#f5f7ff;
    padding:18px;
    border-radius:10px;
    border-left:5px solid #3f51c4;
}
.result p{
    margin:6px 0;
}
.pay-box{
    margin-top:14px;
    background:#fffbe6;
    padding:12px;
    border-radius:8px;
    text-align:center;
    border:1px solid #3f51c4;
}
.pay-box .label{
    font-size:14px;
    font-weight:600;
}
.pay-box .price{
    font-size:18px;
    font-weight:700;
    color:#243b8a;
}
.recommend{
    margin-top:14px;
    background:#fffbe6;
    border:2px dashed #243b8a;
    padding:12px;
    text-align:center;
    font-weight:700;
}
.error{
    margin-top:15px;
    color:#b00020;
    font-weight:600;
    text-align:center;
}
</style>
</head>
<body>

<div class="wrapper">
    <div class="header">โปรแกรมคำนวณส่วนลด</div>

    <div class="card">
        <form method="post">
            <label>ยอดซื้อสินค้า (บาท)</label>
            <input type="number" name="amount" step="0.01" required>

            <label>สถานะลูกค้า</label>
            <select name="member">
                <option value="no">ไม่เป็นสมาชิก</option>
                <option value="yes">เป็นสมาชิก</option>
            </select>

            <button type="submit">คำนวณส่วนลด</button>
        </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = $_POST["amount"];
    $member = $_POST["member"];

    if ($amount < 0) {
        echo "<div class='error'>ยอดซื้อต้องไม่เป็นค่าติดลบ</div>";
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
    echo "<p>ยอดซื้อ: <strong>" . number_format($amount,2) . "</strong> บาท</p>";

    if ($rate > 0) {
        echo "<p>ได้รับส่วนลดระดับ $level {$rate}%</p>";
    } else {
        echo "<p>ไม่ได้รับส่วนลด</p>";
    }

    if ($memberRate > 0) {
        echo "<p>ส่วนลดสมาชิก +5%</p>";
    }

    if ($totalRate > 0) {
        echo "<p>ส่วนลดที่ได้รับ: " . number_format($discount,2) . " บาท</p>";
    }

    echo "<div class='pay-box'>
            <div class='label'>ราคาที่ต้องจ่าย</div>
            <div class='price'>" . number_format($net,2) . " บาท</div>
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
</div>

</body>
</html>
