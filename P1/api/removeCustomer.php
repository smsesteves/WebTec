<?php

header('Content-Type: application/json');
require("db.php");

$db = getDB();

$stmt = $db->prepare('DELETE FROM customers WHERE CustomerID = ?');
$stmt->execute(array($_GET['CustomerID']));
?>
