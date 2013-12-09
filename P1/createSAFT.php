<?php

header("Content-Type:text/xml");

$db = new PDO('sqlite:./db/db_t1.db');

$xmlDoc = new DOMDocument('1.0', 'UTF-8');
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->formatOutput = true;

// AUDIT FILE INFO 
$auditFile = $xmlDoc->createElement("AuditFile");
$xmlns = $xmlDoc->createAttribute('xmlns');
$xmlns->value = 'urn:OECD:StandardAuditFile-Tax:PT_1.03_01';
$auditFile->appendChild($xmlns);

$xmlns_xsi = $xmlDoc->createAttribute('xmlns:xsi');
$xmlns_xsi->value = 'http://www.w3.org/2001/XMLSchema-instance';
$auditFile->appendChild($xmlns_xsi);

$xmlns_spi = $xmlDoc->createAttribute('xmlns:spi');
$xmlns_spi->value = 'http://Empresa.pt/invoice1';
$auditFile->appendChild($xmlns_spi);

$xmlns_saf = $xmlDoc->createAttribute('xmlns:saf');
$xmlns_saf->value = 'urn:OECD:StandardAuditFile-Tax:PT_1.03_01';
$auditFile->appendChild($xmlns_saf);

$xsi_schemaLocation = $xmlDoc->createAttribute('xsi:schemaLocation');
$xsi_schemaLocation->value = 'urn:OECD:StandardAuditFile-Tax:PT_1.03_01 http://serprest.pt/tmp/SAFTPT-1.03_01.xsd';
$auditFile->appendChild($xsi_schemaLocation);


// HEADER 
$header = $xmlDoc->createElement("Header");

$header->appendChild($xmlDoc->createElement('AuditFileVersion', '1.03_01'));
$header->appendChild($xmlDoc->createElement('CompanyID', 'ISV 1234'));
$header->appendChild($xmlDoc->createElement('TaxRegistrationNumber', '500213093'));
$header->appendChild($xmlDoc->createElement('TaxAccountingBasis', 'F'));
$header->appendChild($xmlDoc->createElement('CompanyName', 'ISVDesfalquesLda'));

$companyAddress = $xmlDoc->createElement("CompanyAddress");
$companyAddress->appendChild($xmlDoc->createElement('AddressDetail', 'Rua da Travessa da Avenida, s/n'));
$companyAddress->appendChild($xmlDoc->createElement('City', 'Terra do Nunca'));
$companyAddress->appendChild($xmlDoc->createElement('PostalCode', '1234-666'));
$companyAddress->appendChild($xmlDoc->createElement('Country', 'PT'));
$header->appendChild($companyAddress);

$header->appendChild($xmlDoc->createElement('FiscalYear', '2014'));
$header->appendChild($xmlDoc->createElement('StartDate', '2014-02-06'));
$header->appendChild($xmlDoc->createElement('EndDate', '2014-08-22'));
$header->appendChild($xmlDoc->createElement('CurrencyCode', 'EUR'));
$header->appendChild($xmlDoc->createElement('DateCreated', '2014-02-06'));
$header->appendChild($xmlDoc->createElement('TaxEntity', 'Global'));
$header->appendChild($xmlDoc->createElement('ProductCompanyTaxID', '654987654'));
$header->appendChild($xmlDoc->createElement('SoftwareCertificateNumber', '1'));
$header->appendChild($xmlDoc->createElement('ProductID', 'Software/NaoFunciona'));
$header->appendChild($xmlDoc->createElement('ProductVersion', '1'));

$auditFile->appendChild($header);

// MasterFiles 
$masterFiles = $xmlDoc->createElement("MasterFiles");

/*
 * * CLIENTE 
 */
$queryCustomer = "SELECT * FROM customers";
$stmt = $db->prepare($queryCustomer);
$stmt->execute();
while ($invoice = $stmt->fetch()) {
    $customer = $xmlDoc->createElement("Customer");
    $customer->appendChild($xmlDoc->createElement('CustomerID', $invoice['CustomerID']));
    $customer->appendChild($xmlDoc->createElement('AccountID', $invoice['AccountID']));
    $customer->appendChild($xmlDoc->createElement('CustomerTaxID', $invoice['CustomerTaxID']));
    $customer->appendChild($xmlDoc->createElement('CompanyName', $invoice['CompanyName']));
    $billingAddress = $xmlDoc->createElement("BillingAddress");
    $billingAddress->appendChild($xmlDoc->createElement('AddressDetail', $invoice['AddressDetail']));
    $billingAddress->appendChild($xmlDoc->createElement('City', $invoice['City']));
    $billingAddress->appendChild($xmlDoc->createElement('PostalCode', $invoice['PostalCode']));
    $billingAddress->appendChild($xmlDoc->createElement('Country', $invoice['Country']));
    $customer->appendChild($billingAddress);
    $customer->appendChild($xmlDoc->createElement('Email', $invoice['Email']));
    $customer->appendChild($xmlDoc->createElement('SelfBillingIndicator', $invoice['SelfBillingIndicator']));
    $masterFiles->appendChild($customer);
}

/*
 * * PRODUTOS 
 */
$queryProducts = "SELECT * FROM products";
$stmt = $db->prepare($queryProducts);
$stmt->execute();
while ($qproduct = $stmt->fetch()) {
    $product = $xmlDoc->createElement("Product");
    $product->appendChild($xmlDoc->createElement('ProductType', $qproduct['ProductType']));
    $product->appendChild($xmlDoc->createElement('ProductCode', $qproduct['ProductCode']));
    $product->appendChild($xmlDoc->createElement('ProductGroup', '1'));
    $product->appendChild($xmlDoc->createElement('ProductDescription', $qproduct['ProductDescription']));
    //$product->appendChild($xmlDoc->createElement('UnitPrice', $qproduct['UnitPrice']));
    //$product->appendChild($xmlDoc->createElement('UnitMeasure', $qproduct['UnitMeasure']));
    $product->appendChild($xmlDoc->createElement('ProductNumberCode', $qproduct['ProductCode']));
    $masterFiles->appendChild($product);
}

/*
 * * TAX 
 */
$taxTable = $xmlDoc->createElement("TaxTable");
$queryAllTaxes = "SELECT * FROM taxes";
$stmt = $db->prepare($queryAllTaxes);
$stmt->execute();
while ($qtax = $stmt->fetch()) {
    $tax = $xmlDoc->createElement("TaxTableEntry");
    $tax->appendChild($xmlDoc->createElement('TaxType', $qtax['TaxType']));
    $tax->appendChild($xmlDoc->createElement('TaxCountryRegion', $qtax['TaxCountryRegion']));
    $tax->appendChild($xmlDoc->createElement('TaxCode', $qtax['TaxCode']));
    $tax->appendChild($xmlDoc->createElement('Description', $qtax['Description']));
    $tax->appendChild($xmlDoc->createElement('TaxPercentage', $qtax['TaxPercentage']));
    $taxTable->appendChild($tax);
}
$masterFiles->appendChild($taxTable);

$auditFile->appendChild($masterFiles);

// MasterFiles 
$sourceDocuments = $xmlDoc->createElement("SourceDocuments");
$salesInvoices = $xmlDoc->createElement("SalesInvoices");

$sql = "SELECT COUNT(*) as cnt, SUM(GrossTotal) as soma FROM invoices";
$stmt = $db->prepare($sql);
$stmt->execute();

$total = 0.0;
$cnt = 0;
if ($row = $stmt->fetch()) {
    $total = ($row['soma'] == null) ? 0.0 : $row['soma'];
    $cnt = $row['cnt'];
}

$salesInvoices->appendChild($xmlDoc->createElement('NumberOfEntries', $cnt));
$salesInvoices->appendChild($xmlDoc->createElement('TotalDebit', '0.0'));
$salesInvoices->appendChild($xmlDoc->createElement('TotalCredit', $total));

//ciclo faturas 
$queryInvoices = "SELECT * FROM invoices";
$stmt = $db->prepare($queryInvoices);
$stmt->execute();
while ($invoice = $stmt->fetch()) {
    //echo "INVOICE = ", $invoice['InvoiceNo'], "<br>"; 

    $xmlinvoice = $xmlDoc->createElement("Invoice");
    $xmlinvoice->appendChild($xmlDoc->createElement('InvoiceNo', "FT SEQ/" . $invoice['InvoiceNo']));
    //$xmlinvoice->appendChild($xmlDoc->createElement('InvoiceNo','FT SRPT/8')); 
    $documentStatus = $xmlDoc->createElement("DocumentStatus");
    $documentStatus->appendChild($xmlDoc->createElement('InvoiceStatus', $invoice['InvoiceStatus']));
    $documentStatus->appendChild($xmlDoc->createElement('InvoiceStatusDate', str_replace(" ","T",$invoice['InvoiceStatusDate'])));
    $documentStatus->appendChild($xmlDoc->createElement('SourceID', $invoice['SourceID']));
    $documentStatus->appendChild($xmlDoc->createElement('SourceBilling', $invoice['SourceBilling']));
    $xmlinvoice->appendChild($documentStatus);
    $xmlinvoice->appendChild($xmlDoc->createElement('Hash', $invoice['Hash']));
    $xmlinvoice->appendChild($xmlDoc->createElement('InvoiceDate', substr($invoice['InvoiceDate'],0,10)));
    $xmlinvoice->appendChild($xmlDoc->createElement('InvoiceType', $invoice['InvoiceType']));
    $SpecialRegimes = $xmlDoc->createElement("SpecialRegimes");
    $SpecialRegimes->appendChild($xmlDoc->createElement('SelfBillingIndicator', '0'));
    $SpecialRegimes->appendChild($xmlDoc->createElement('CashVATSchemeIndicator', '0'));
    $SpecialRegimes->appendChild($xmlDoc->createElement('ThirdPartiesBillingIndicator', '1'));
    $xmlinvoice->appendChild($SpecialRegimes);
    $xmlinvoice->appendChild($xmlDoc->createElement('SourceID', $invoice['SourceID']));
    $xmlinvoice->appendChild($xmlDoc->createElement('SystemEntryDate', str_replace(" ","T",$invoice['SystemEntryDate'])));
    $xmlinvoice->appendChild($xmlDoc->createElement('CustomerID', $invoice['CustomerID']));


    $queryTaxes = "SELECT * FROM lines WHERE InvoiceNo = '" . $invoice['InvoiceNo'] . "'";
    //echo "<br><br><br><br>", $queryTaxes , "<br><br><br><br>"; 
    $stmt2 = $db->prepare($queryTaxes);
    $stmt2->execute();

    while ($qline = $stmt2->fetch()) {
        //echo "    LINHA NO : ", $qline['LineNumber'], "<br>"; 
        $line = $xmlDoc->createElement('Line');
        $line->appendChild($xmlDoc->createElement('LineNumber', $qline['LineNumber']));
        $line->appendChild($xmlDoc->createElement('ProductCode', $qline['ProductCode']));
        $line->appendChild($xmlDoc->createElement('ProductDescription', $qline['ProductDescription']));
        $line->appendChild($xmlDoc->createElement('Quantity', $qline['Quantity']));
        $line->appendChild($xmlDoc->createElement('UnitOfMeasure', $qline['UnitOfMeasure']));
        $line->appendChild($xmlDoc->createElement('UnitPrice', $qline['UnitPrice']));
        $line->appendChild($xmlDoc->createElement('TaxPointDate', substr($qline['TaxPointDate'],0,10)));
        $line->appendChild($xmlDoc->createElement('Description', $qline['ProductDescription']));
        $line->appendChild($xmlDoc->createElement('CreditAmount', $qline['CreditAmount']));

        // TODO remover tabela taxes_lines
        $sql123 = "SELECT * FROM taxes WHERE TaxType = '" . $qline['TaxType'] . "'";
        $stmt3 = $db->prepare($sql123);
        $stmt3->execute();
        $singleTax = $xmlDoc->createElement("Tax");
        
        if($qline2 = $stmt3->fetch()) {
            
            $singleTax->appendChild($xmlDoc->createElement('TaxType', $qline2['TaxType']));
            $singleTax->appendChild($xmlDoc->createElement('TaxCountryRegion', $qline2['TaxCountryRegion']));
            $singleTax->appendChild($xmlDoc->createElement('TaxCode', $qline2['TaxCode']));
            $singleTax->appendChild($xmlDoc->createElement('TaxPercentage', $qline2['TaxPercentage']));
            
        }
        $line->appendChild($singleTax);
        $line->appendChild($xmlDoc->createElement('SettlementAmount', '0'));
        $xmlinvoice->appendChild($line);
    }

    $documentTotals = $xmlDoc->createElement('DocumentTotals');
    $documentTotals->appendChild($xmlDoc->createElement('TaxPayable', $invoice['TaxPayable']));
    $documentTotals->appendChild($xmlDoc->createElement('NetTotal', $invoice['NetTotal']));
    $documentTotals->appendChild($xmlDoc->createElement('GrossTotal', $invoice['GrossTotal']));
    $xmlinvoice->appendChild($documentTotals);
    $salesInvoices->appendChild($xmlinvoice);
}

// acaba ciclo faturas 

$sourceDocuments->appendChild($salesInvoices);
$auditFile->appendChild($sourceDocuments);

$xmlDoc->appendChild($auditFile);

echo $xmlDoc->saveXML();


$xmlDoc->save("MARGULIS_LESSA_LDA_VERIFIED_SAFT_1.03_01.xml");
?>