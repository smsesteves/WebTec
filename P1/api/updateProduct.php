<?php

session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}


header('Content-type: application/json');
require("db.php");

$db = getDB();

$j = $_REQUEST['json'];
if (get_magic_quotes_gpc()) {
    $j = stripslashes($j);
}

$var = json_decode($j, true);

$sql = $db->prepare('SELECT * from products WHERE ProductCode = ?');
$sql->execute(array($var['ProductCode']));

$idP = "";

if ($sql->fetch()) {
    $stmt1 = $db->prepare('UPDATE products SET ProductDescription = ?, UnitPrice = ?, UnitOfMeasure = ?, ProductNumberCode = ? WHERE ProductCode = ?');
    $stmt1->execute(array($var['ProductDescription'], $var['UnitPrice'], $var['UnitOfMeasure'], $var['ProductNumberCode'], $var['ProductCode']));

    $idP = $var['ProductCode'];
} else {
    //new 
    $stmt1 = $db->prepare('INSERT INTO products (ProductDescription, UnitPrice, UnitOfMeasure, ProductNumberCode) VALUES (?,?,?,?) ');
    $stmt1->execute(array($var['ProductDescription'], $var['UnitPrice'], $var['UnitOfMeasure'], $var['ProductNumberCode']));

    $sql = $db->prepare('SELECT MAX(ProductCode) as maximo from products');
    $sql->execute();
    $result = $sql->fetch();
    $idP = $result['maximo'];
}

$product = array('ProductCode' => $idP,
    'ProductDescription' => $var['ProductDescription'],
    'UnitPrice' => $var['UnitPrice'],
    'UnitOfMeasure' => $var['UnitOfMeasure'],
    'ProductNumberCode' => $var['ProductNumberCode']);
echo json_encode($product);




echo $j;
?>
