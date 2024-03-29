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

$stmt = $db->prepare('SELECT * FROM taxes');
$stmt->execute();

while ($result = $stmt->fetch()) {
    $taxes[] = array('TaxType' => $result['TaxType'],
        'TaxPercentage' => $result['TaxPercentage']);
}

echo json_encode($taxes);
?>
