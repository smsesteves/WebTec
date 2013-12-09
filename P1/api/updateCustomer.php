<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php



header('Content-type: application/json');
require("db.php");

$db = getDB();

$idC = "";

$j = $_REQUEST['json'];
if (get_magic_quotes_gpc()) {
    $j = stripslashes($j);
}

$var = json_decode($j, true);

$sql = $db->prepare('SELECT * from customers WHERE CustomerID = ?');
$sql->execute(array($var['CustomerID']));

if ($result = $sql->fetch()) {
    $stmt1 = $db->prepare('UPDATE customers SET AccountID = ?, CustomerTaxID = ?, CompanyName = ?, AddressDetail = ?,City = ?,PostalCode = ?,Country = ?,Email = ? WHERE CustomerID = ?');
    $stmt1->execute(array($var['AccountID'], $var['CustomerTaxID'], $var['CompanyName'], $var['AddressDetail'], $var['City'], $var['PostalCode'], $var['Country'], $var['Email'], $var['CustomerID']));

    $idC = $var['CustomerID'];
} else {
    $stmt1 = $db->prepare('INSERT INTO customers (AccountID, CustomerTaxID, CompanyName, AddressDetail,City,PostalCode,Country,Email) VALUES (?,?,?,?,?,?,?,?)');
    $stmt1->execute(array($var['AccountID'], $var['CustomerTaxID'], $var['CompanyName'], $var['AddressDetail'], $var['City'], $var['PostalCode'], $var['Country'], $var['Email']));

    $sql = $db->prepare('SELECT MAX(CustomerID) as maximo from customers');
    $sql->execute();
    $result = $sql->fetch();
    $idC = $result['maximo'];
}


$product = array('CustomerID' => $idC,
    'AccountId' => $var['AccountId'],
    'CustomerTaxID' => $var['CustomerTaxID'],
    'CompanyName' => $var['CompanyName'],
    'BillingAdress' => array(
        'AddressDetail' => $var['AddressDetail'],
        'City' => $var['City'],
        'PostalCode' => $var['PostalCode'],
        'Country' => $var['Country'],
    ),
    'SelfBillingIndicator' => $var['SelfBillingIndicator'],
    'Email' => $var['Email']);

echo json_encode($product);
?>

