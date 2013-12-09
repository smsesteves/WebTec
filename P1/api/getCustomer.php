<?php

header('Content-Type: application/json');
require("db.php");

$db = getDB();

$stmt = $db->prepare('SELECT * FROM customers WHERE CustomerID = ?');
$stmt->execute(array($_GET['CustomerID']));
$result = $stmt->fetch();

if ($result != null) {
    $product = array('CustomerID' => $result['CustomerID'],
        'AccountID' => $result['AccountID'],
        'CustomerTaxID' => $result['CustomerTaxID'],
        'CompanyName' => $result['CompanyName'],
        'BillingAddress' => array(
            'AddressDetail' => $result['AddressDetail'],
            'City' => $result['City'],
            'PostalCode' => $result['PostalCode'],
            'Country' => $result['Country'],
        ),
        'SelfBillingIndicator' => $result['SelfBillingIndicator'],
        'Email' => $result['Email']);
    echo json_encode($product);
} else {
    echo '{"error":{"code":404,"reason":"Customer not found"}}';
}
?>
