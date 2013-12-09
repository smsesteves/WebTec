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

$stmt = $db->prepare('DELETE FROM products WHERE ProductCode = ?');
$stmt->execute(array($_GET['ProductCode']));
?>
