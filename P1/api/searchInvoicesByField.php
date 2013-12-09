<?php 
header('Content-Type: application/json');

$db = new PDO('sqlite:../db/db_t1.db');

// verificar se o campo existe
$sql = "SELECT * FROM invoices, customers WHERE invoices.CustomerID = customers.CustomerID AND " . $_GET['field'];

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
        $sql .= " = (SELECT MIN(" . $_GET['field'] . ") FROM invoices)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
    case "max":
        $sql .= " = (SELECT MAX(" . $_GET['field'] . ") FROM invoices)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        break;
}

$invoices = array();

while ($row = $stmt->fetch()) {
    $invoices[] = array('InvoiceNo' => $row['InvoiceNo'],
        'InvoiceDate' => $row['InvoiceDate'],
        'CompanyName' => $row['CompanyName'],
        'GrossTotal' => $row['GrossTotal']);
}

echo json_encode($invoices);
?>
