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

$stmt = $db->prepare('SELECT username FROM users');
$stmt->execute();

while ($result = $stmt->fetch()) {
    $usernames[] = $result['username'];
}

echo json_encode($usernames);
?>
