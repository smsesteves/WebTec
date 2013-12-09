<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php

$db = new PDO('sqlite:../db/db_t1.db');

$sql = "SELECT ProductCode FROM products";

$stmt = $db->prepare($sql);
$stmt->execute();
$nos = array();

while ($row = $stmt->fetch()) {
    $nos[] = $row['ProductCode'];
}
echo json_encode($nos);
?>