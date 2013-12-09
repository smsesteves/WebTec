<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php 
header('Content-Type: application/json');

$db = new PDO('sqlite:../db/db_t1.db');

// verificar se o campo existe
$sql = "SELECT * FROM customers WHERE " . $_GET['field'];

switch ($_GET['op']) {
    case "range":
        $sql .= " BETWEEN '" . $_GET['value'][0] . "' AND '" . $_GET['value'][1]. "'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "equal":
        $sql .= " = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($_GET['value'][0]));
        break;
    case "contains":
        $sql .= " LIKE '%" . $_GET['value'][0] . "%'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "min":
        $sql .= " = (SELECT MIN(" . $_GET['field'] . ") FROM customers)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "max":
        $sql .= " = (SELECT MAX(" . $_GET['field'] . ") FROM customers)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
}

$customers = array();

while ($row = $stmt->fetch()) {
    $customers[] = array('CustomerID' => $row['CustomerID'],
        'CustomerTaxID' => $row['CustomerTaxID'],
        'CompanyName' => $row['CompanyName']);
}

echo json_encode($customers);
?>
