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
