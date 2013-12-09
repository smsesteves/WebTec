<?php


header('Content-type: application/json');
require("db.php");

$db = getDB();

$stmt = $db->prepare('SELECT * FROM invoices WHERE InvoiceNo = ?');
$stmt->execute(array($_GET['InvoiceNo']));
$result = $stmt->fetch();

if ($result != null) {
    $invoice = array('InvoiceNo' => $result['InvoiceNo'],
        'DocumentStatus' => array(
            'InvoiceStatusDate' => $result['InvoiceStatusDate'],
            'SourceBilling' => $result['SourceBilling'],
            'SourceID' => $result['SourceID']
        ),
        'Hash' => $result['Hash'],
        'InvoiceDate' => $result['InvoiceDate'],
        'InvoiceType' => $result['InvoiceType'],
        'SpecialRegimes' => array(
            'SelfBillingIndicator' => $result['SelfBillingIndicator'],
            'CashVATSchemeIndicator' => $result['CashVATSchemeIndicator'],
            'ThirdPartiesBillingIndicator' => $result['ThirdPartiesBillingIndicator']
        ),
        'SourceID' => $result['SourceID'],
        'SystemEntryDate' => $result['SystemEntryDate'],
        'CustomerID' => $result['CustomerID'],
        'Line' => array(),
        'DocumentTotal' => array(
            'TaxPayable' => $result['TaxPayable'],
            'NetTotal' => $result['NetTotal'],
            'GrossTotal' => $result['GrossTotal']
        )
    );


    $stmt = $db->prepare('SELECT * FROM lines WHERE InvoiceNo = ?');
    $stmt->execute(array($_GET['InvoiceNo']));
    while ($row = $stmt->fetch()) {
        $line = array('LineNumber' => $row['LineNumber'],
            'ProductCode' => $row['ProductCode'],
            'Quantity' => $row['Quantity'],
            'UnitPrice' => $row['UnitPrice'],
            'CreditAmount' => $row['CreditAmount'],
            'Tax' => array());

        $stmt2 = $db->prepare('select * from taxes where taxtype = ?;');
        $stmt2->execute(array($row['TaxType']));
        while ($row2 = $stmt2->fetch()) {
            $tax = array('TaxType' => $row2['TaxType'],
                'TaxPercentage' => $row2['TaxPercentage']);

            $line['Tax'] = $tax;
        }

        $invoice['Line'][] = $line;
    }

    echo json_encode($invoice);
} else {
    echo '{"error":{"code":404,"reason":"Invoice not found"}}';
}
?>
