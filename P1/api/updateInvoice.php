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
    $stmt = $db->prepare('INSERT INTO invoices(InvoiceStatus, InvoiceStatusDate, SourceBilling, SourceID, Hash, InvoiceDate,InvoiceType, SelfBillingIndicator, CashVATSchemeIndicator, ThirdPartiesBillingIndicator, SystemEntryDate, CustomerID,TaxPayable,NetTotal,GrossTotal) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute(array($var['InvoiceStatus'], 
        $var['DocumentStatus']['InvoiceStatusDate'], 
        $var['DocumentStatus']['SourceBilling'], 
        $var['DocumentStatus']['SourceID'], 
        $var['Hash'], 
        $var['InvoiceDate'], 
        $var['InvoiceType'], 
        $var['SpecialRegimes']['SelfBillingIndicator'],
        $var['SpecialRegimes']['CashVATSchemeIndicator'],
        $var['SpecialRegimes']['ThirdPartiesBillingIndicator'],
        $var['SystemEntryDate'],
        $var['CustomerID'],
        $var['DocumentTotal']['TaxPayable'],
        $var['DocumentTotal']['NetTotal'],
        $var['DocumentTotal']['GrossTotal']
        ));

    $sql = "SELECT MAX(InvoiceNo) as maximo FROM invoices";
    $stmt =$db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch();
    $novoID = $result['maximo'];

    foreach ($var['Line'] as $line) {
        $sql = "INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($novoID, $line['Tax']['TaxType'], $line['ProductCode'], $line['Quantity']));
    }
} 


else {
    $sql = "DELETE FROM lines WHERE InvoiceNo = ?";
    $stmt=$db->prepare($sql);
    $stmt->execute(array($var['InvoiceNo']));

    $sql = "UPDATE invoices SET InvoiceStatus = ?, InvoiceStatusDate = ?, SourceBilling = ?, SourceID = ?, Hash = ?, InvoiceDate = ?,InvoiceType = ?, SelfBillingIndicator = ?, CashVATSchemeIndicator = ?, ThirdPartiesBillingIndicator = ?, SystemEntryDate = ?, CustomerID = ?,TaxPayable = ?,NetTotal = ?,GrossTotal = ? WHERE InvoiceNo = ?";
    $stmt=$db->prepare($sql);
        $stmt->execute(array($var['InvoiceStatus'], 
        $var['DocumentStatus']['InvoiceStatusDate'], 
        $var['DocumentStatus']['SourceBilling'], 
        $var['DocumentStatus']['SourceID'], 
        $var['Hash'], 
        $var['InvoiceDate'], 
        $var['InvoiceType'], 
        $var['SpecialRegimes']['SelfBillingIndicator'],
        $var['SpecialRegimes']['CashVATSchemeIndicator'],
        $var['SpecialRegimes']['ThirdPartiesBillingIndicator'],
        $var['SystemEntryDate'],
        $var['CustomerID'],
        $var['DocumentTotal']['TaxPayable'],
        $var['DocumentTotal']['NetTotal'],
        $var['DocumentTotal']['GrossTotal'],
        $var['InvoiceNo']
        ));

    foreach ($var['Line'] as $line) {
        $sql = "INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($var['InvoiceNo'], $line['Tax']['TaxType'], $line['ProductCode'], $line['Quantity']));
    }
}

echo json_encode($var);


?>

