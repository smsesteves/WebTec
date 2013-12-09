
Produtos:
<div id="progressProducts" style="width:500px;border:1px solid #ccc;">
    <div style="width:0;background-color:#ddd;">&nbsp;</div>
</div>
Clientes:
<div id="progressCustomers" style="width:500px;border:1px solid #ccc;">
    <div style="width:0;background-color:#ddd;">&nbsp;</div>
</div>
Faturas/Linhas:
<div id="progressInvoices" style="width:500px;border:1px solid #ccc;">
    <div style="width:0;background-color:#ddd;">&nbsp;</div>
</div>
<div id="progressLines" style="width:500px;border:1px solid #ccc;">
    <div style="width:0;background-color:#ddd;">&nbsp;</div>
</div>
<div id="information" style="width"></div>

<?php
//session_start();

$dbmxl = simplexml_load_file("MARGULIS_LESSA_LDA_VERIFIED_SAFT_1.03_01.xml");
$db = new PDO('sqlite:./db/db_t2.db');

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$cnt = 0;
$nProducts = count($dbmxl->MasterFiles->Product);
for ($i = 0; $dbmxl->MasterFiles->Product[$i]; ++$i) {
    $var = $dbmxl->MasterFiles->Product[$i];

    $stmt1 = $db->prepare("INSERT INTO products VALUES ('P',?,?,?,?,?)");
    $stmt1->execute(array($var->ProductCode, $var->ProductDescription, $var->UnitPrice, $var->UnitMeasure, $var->ProductNumberCode));

    ++$cnt;
    $percent = intval($cnt / $nProducts * 100) . "%";
    echo '<script language="javascript">
            document.getElementById("progressProducts").innerHTML="<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";
            document.getElementById("information").innerHTML="' . $cnt . ' de ' . $nProducts . ' produtos processados.";
          </script>';
    echo str_repeat(' ', 1024 * 64);
    ob_flush();
    flush();
}

$cnt = 0;
$nCustomers = count($dbmxl->MasterFiles->Customer);
for ($i = 0; $dbmxl->MasterFiles->Customer[$i]; ++$i) {
    $var = $dbmxl->MasterFiles->Customer[$i];
    $stmt1 = $db->prepare('INSERT INTO customers (CustomerID, AccountID, CustomerTaxID, CompanyName, AddressDetail,City,PostalCode,Country,Email) VALUES (?,?,?,?,?,?,?,?,?)');
    $stmt1->execute(array($var->CustomerID, $var->AccountID, $var->CustomerTaxID, $var->CompanyName, $var->BillingAddress->AddressDetail, $var->BillingAddress->City, $var->BillingAddress->PostalCode, $var->BillingAddress->Country, $var->Email));

    ++$cnt;
    $percent = intval($cnt / $nCustomers * 100) . "%";
    echo '<script language="javascript">
            document.getElementById("progressCustomers").innerHTML="<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";
            document.getElementById("information").innerHTML="' . $cnt . ' de ' . $nCustomers . ' clientes processados.";
          </script>';
    echo str_repeat(' ', 1024 * 64);
    ob_flush();
    flush();
}

for ($i = 0; $dbmxl->MasterFiles->TaxTable->TaxTableEntry[$i]; ++$i) {
    $var = $dbmxl->MasterFiles->TaxTable->TaxTableEntry[$i];
    $stmt1 = $db->prepare("INSERT INTO taxes VALUES (?,?,?,?,?)");
    $stmt1->execute(array($var->TaxType, $var->TaxCountryRegion, $var->TaxCode, $var->TaxPercentage, $var->Description));
}

$cnt = 0;
$nInvoices = count($dbmxl->SourceDocuments->SalesInvoices->Invoice);
for ($i = 0; $dbmxl->SourceDocuments->SalesInvoices->Invoice[$i]; ++$i) {
    $var = $dbmxl->SourceDocuments->SalesInvoices->Invoice[$i];
    $stmt1 = $db->prepare("INSERT INTO invoices (InvoiceNo,InvoiceStatus,InvoiceStatusDate,SourceBilling,SourceID,Hash,InvoiceDate,InvoiceType,SelfBillingIndicator,CashVATSchemeIndicator,ThirdPartiesBillingIndicator,SystemEntryDate,CustomerID,TaxPayable,NetTotal,GrossTotal) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $num = substr($var->InvoiceNo, 7);

    $stmt1->execute(array($num,
        $var->DocumentStatus->InvoiceStatus,
        str_replace('T', ' ', $var->DocumentStatus->InvoiceStatusDate),
        $var->DocumentStatus->SourceID,
        $var->DocumentStatus->SourceBilling,
        $var->Hash,
        $var->InvoiceDate,
        $var->InvoiceType,
        $var->SpecialRegimes->SelfBillingIndicator,
        $var->SpecialRegimes->CashVATSchemeIndicator,
        $var->SpecialRegimes->ThirdPartiesBillingIndicator,
        str_replace('T', ' ', $var->SystemEntryDate),
        $var->CustomerID,
        $var->DocumentTotals->TaxPayable,
        $var->DocumentTotals->NetTotal,
        $var->DocumentTotals->GrossTotal));

    $cnt2 = 0;
    $nLines = count($var->Line);
    for ($j = 0; $var->Line[$j]; ++$j) {
        $lineaux = $var->Line[$j];

        $stmt3 = $db->prepare("UPDATE products SET UnitPrice = ?, UnitOfMeasure = ? WHERE ProductCode = ?");
        $stmt3->execute(array($lineaux->UnitPrice, $lineaux->UnitOfMeasure, $lineaux->ProductCode));

        $stmt2 = $db->prepare("INSERT INTO lines (LineNumber, TaxType, InvoiceNo,ProductCode,ProductDescription,Quantity,UnitOfMeasure,UnitPrice, CreditAmount, DebitAmount, TaxPointDate,Description)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt2->execute(array(
            $lineaux->LineNumber,
            $lineaux->Tax->TaxType,
            $num,
            $lineaux->ProductCode,
            $lineaux->ProductDescription,
            $lineaux->Quantity,
            $lineaux->UnitOfMeasure,
            $lineaux->UnitPrice,
            $lineaux->CreditAmount,
            $lineaux->DebitAmount,
            $lineaux->TaxPointDate,
            $lineaux->Description
        ));

        ++$cnt2;
        $percent2 = intval($cnt2 / $nLines * 100) . "%";
        echo '<script language="javascript">
                        document.getElementById("progressLines").innerHTML="<div style=\"width:' . $percent2 . ';background-color:#ddd;\">&nbsp;</div>";
                      </script>';
        echo str_repeat(' ', 1024 * 64);
        ob_flush();
        flush();
    }
    ++$cnt;
    $percent = intval($cnt / $nInvoices * 100) . "%";
    echo '<script language="javascript">
                    document.getElementById("progressInvoices").innerHTML="<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";
                    document.getElementById("information").innerHTML="' . $cnt . ' de ' . $nInvoices . ' faturas processadas.";
                    document.getElementById("progressLines").innerHTML="<div style=\"width:' . 0 . ';background-color:#ddd;\">&nbsp;</div>";
                  </script>';
    echo str_repeat(' ', 1024 * 64);
    ob_flush();
    flush();
}

echo '<script language="javascript">document.getElementById("information").innerHTML="Import Completo!!!"</script>';
?>