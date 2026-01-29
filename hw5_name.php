<?php
$title = $fname = $lname = "";
$fullname = "";

if (!empty($_POST['fullname'])) {
    $fullname = trim($_POST['fullname']);

    $titles = [
        // แบบเต็ม
        "เด็กหญิง","เด็กชาย","นางสาว","นาง","นาย",

        // แบบย่อ
        "ด.ญ.","ด.ช.","น.ส.",

        // ยศ / ตำแหน่ง
        "ร.ต.ต.","ร.ต.","ด.ต.",
        "ดร.","ผศ.","รศ.","ศ.",
        "ม.ร.ว.","มรว."
    ];

    // เรียงจากยาวไปสั้น (กันชนกัน)
    usort($titles, function($a, $b){
        return mb_strlen($b) - mb_strlen($a);
    });

    // แยกคำนำหน้า
    foreach ($titles as $t) {
        if (mb_strpos($fullname, $t) === 0) {
            $title = $t;
            $fullname = trim(mb_substr($fullname, mb_strlen($t)));
            break;
        }
    }

    // แยกชื่อ - สกุล
    $parts = preg_split('/\s+/', $fullname, 2);
    $fname = $parts[0] ?? "";
    $lname = $parts[1] ?? "";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>โปรแกรมแยกชื่อ - สกุล</title>

<style>
body{
    margin:0;
    font-family: Tahoma, sans-serif;
    background: #f0e0a0;
}
.wrapper{
    width:900px;
    margin:80px auto;
    background:#ffffff;
    border-radius:12px;
    border:1px solid #f0e0a0;
    display:grid;
    grid-template-columns:1fr 1fr;
}
.left{
    padding:40px;
    background:#fffdf2;
    border-right:3px solid #cfe8f6;
}
.left h2{
    margin-bottom:30px;
    color:#8a6d00;
}
.right{
    padding:40px;
}
.right h3{
    margin-bottom:25px;
    color:#2f6f8f;
}
.group{
    margin-bottom:22px;
}
label{
    display:block;
    margin-bottom:6px;
    font-weight:bold;
    color:#555;
}
input{
    width:100%;
    padding:10px 12px;
    font-size:17px;
    border-radius:6px;
    border:1px solid #ccc;
}
input[readonly]{
    background:#f2f6f8;
}
button{
    margin-top:25px;
    padding:12px 30px;
    font-size:17px;
    background:#f3c623;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{
    background:#e0b000;
}
</style>
</head>

<body>

<div class="wrapper">
    <div class="left">
        <h2>โปรแกรมแยกส่วนชื่อ - สกุล</h2>
        <form method="post">
            <div class="group">
                <label>ชื่อ-สกุลแบบเต็ม</label>
                <input type="text" name="fullname"
                       placeholder="เช่น ดร.ทรงศิล ความมั่น"
                       required>
            </div>
            <button type="submit">แยกชื่อ - สกุล</button>
        </form>
    </div>

    <div class="right">
        <h3>ผลลัพธ์การแยกข้อมูล</h3>

        <div class="group">
            <label>คำนำหน้า</label>
            <input type="text" value="<?= htmlspecialchars($title) ?>" readonly>
        </div>

        <div class="group">
            <label>ชื่อ</label>
            <input type="text" value="<?= htmlspecialchars($fname) ?>" readonly>
        </div>

        <div class="group">
            <label>สกุล</label>
            <input type="text" value="<?= htmlspecialchars($lname) ?>" readonly>
        </div>
    </div>
</div>

</body>
</html>
