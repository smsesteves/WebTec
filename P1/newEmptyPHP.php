<?php

//session_start();

$dbmxl = simplexml_load_file("MARGULIS_LESSA_LDA_VERIFIED_SAFT_1.03_01.xml");
$db = new PDO('sqlite:./db/db_t2.db');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

for ($i = 0; $dbmxl->MasterFiles->Customer[$i]; ++$i) {
    $var = $dbmxl->MasterFiles->Customer[$i];
    $stmt1 = $db->prepare('INSERT INTO customers (CustomerID, AccountID, CustomerTaxID, CompanyName, AddressDetail,City,PostalCode,Country,Email) VALUES (?,?,?,?,?,?,?,?,?)');

    $stmt1->execute(array($var->CustomerID, $var->AccountID, $var->CustomerTaxID, $var->CompanyName, $var->BillingAddress->AddressDetail, $var->BillingAddress->City, $var->BillingAddress->PostalCode, $var->BillingAddress->Country, $var->Email));
}

for ($i = 0; $dbmxl->MasterFiles->Product[$i]; ++$i) {
    $var = $dbmxl->MasterFiles->Product[$i];
    $stmt1 = $db->prepare("INSERT INTO products VALUES ('P',?,?,?,?,?)");

    $stmt1->execute(array($var->ProductCode, $var->ProductDescription, $var->UnitPrice, $var->UnitMeasure, $var->ProductNumberCode));
}

for ($i = 0; $dbmxl->MasterFiles->TaxTable->TaxTableEntry[$i]; ++$i) {
    $var = $dbmxl->MasterFiles->TaxTable->TaxTableEntry[$i];

    $stmt1 = $db->prepare("INSERT INTO taxes VALUES (?,?,?,?,?)");

    $stmt1->execute(array($var->TaxType, $var->TaxCountryRegion, $var->TaxCode, $var->TaxPercentage, $var->Description));
}


for ($i = 0; $dbmxl->SourceDocuments->SalesInvoices->Invoice[$i]; ++$i) {

    $var = $dbmxl->SourceDocuments->SalesInvoices->Invoice[$i];
    echo "dsadasdsa";
    $stmt1 = $db->prepare("INSERT INTO invoices (InvoiceNo,InvoiceStatus,InvoiceStatusDate,SourceBilling,SourceID,Hash,InvoiceDate,InvoiceType,SelfBillingIndicator,CashVATSchemeIndicator,ThirdPartiesBillingIndicator,SystemEntryDate,CustomerID,TaxPayable,NetTotal,GrossTotal) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
   
    $num = substr($var->InvoiceNo, 7);
 

    //print_r($var);

    $aux23 = array($num,
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
        $var->DocumentTotals->GrossTotal);

    print_r($aux23);

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



    for ($j = 0; $var->Line[$j]; ++$j) {
        $lineaux = $var->Line[$j];
        print_r($lineaux);
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
    }
}
?>