<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php

header('Content-Type: application/json');
require("db.php");

$db = getDB();

$stmt = $db->prepare('DELETE FROM customers WHERE CustomerID = ?');
$stmt->execute(array($_GET['CustomerID']));
?>
