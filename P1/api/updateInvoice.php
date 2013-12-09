<?php

session_start();

header('Content-type: application/json');
require("db.php");

$db = getDB();

$j = $_REQUEST['json'];
if (get_magic_quotes_gpc()) {
    $j = stripslashes($j);
}



$var = json_decode($j, true);




$inserir = true;

if (isset($var['InvoiceNo'])) {
    $sql = $db->prepare('SELECT * FROM invoices WHERE InvoiceNo = ?');
    $sql->execute(array($var['InvoiceNo']));
    if ($sql->fetch()) {
        $inserir = false;
    }
}




if ($inserir) {
    $sql = "INSERT INTRO invoices(InvoiceStatus, InvoiceStatusDate, SourceBilling, SourceID, Hash, InvoiceDate, InvoiceType, SelfBillingIndicator, CashVATSchemeIndicator, ThirdPartiesBillingIndicator, SystemEntryDate, CustomerID,TaxPayable,NetTotal,GrossTotal) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt->prepare($sql);
    $stmt->execute(array($var['InvoiceStatus'], $var['InvoiceStatusDate'], $var['SourceID'], $var['InvoiceDate'], $var['InvoiceType'], $var['SystemEntryDate'], $var['CustomerID']));

    $sql = "SELECT MAX(InvoiceNo) as maximo FROM invoices";
    $stmt->prepare($sql);
    $stmt->execute();

    $result = $sql->fetch();
    $novoID = $result['maximo'];

    foreach ($var['Line'] as $line) {
        $sql = "INSERT INTRO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (?,?,?,?)";
        $stmt->prepare($sql);
        $stmt->execute(array($novoID, $var['InvoiceStatus'], $line['TaxType'], $line['ProductCode'], $line['Quantity']));
    }



} /*else {
    $sql = "DELETE FROM lines WHERE InvoiceNo = ?";
    $stmt->prepare($sql);
    $stmt->execute(array($var['InvoiceNo']));

    $sql = "UPDATE invoices SET InvoiceStatus = ?, InvoiceStatusDate = ?, SourceID = ?, InvoiceDate = ?, InvoiceType = ?, SystemEntryDate = ?, CustomerID = ? WHERE InvoiceNo = ?";
    $stmt->prepare($sql);
    $stmt->execute(array($var['InvoiceStatus'], $var['InvoiceStatusDate'], $var['SourceID'], $var['InvoiceDate'], $var['InvoiceType'], $var['SystemEntryDate'], $var['CustomerID'], $var['InvoiceNo']));

    foreach ($var['Line'] as $line) {
        $sql = "INSERT INTRO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (?,?,?,?)";
        $stmt->prepare($sql);
        $stmt->execute(array($var['InvoiceNo'], $var['InvoiceStatus'], $line['TaxType'], $line['ProductCode'], $line['Quantity']));
    }
}*/

/*$product = array('CustomerID' => $var['CustomerID'],
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
*/
echo $j;
?>

