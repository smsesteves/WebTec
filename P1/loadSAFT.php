<?php

$db = new PDO('sqlite:./db/db_t1.db');

// TODO: Filename e sempre o mesmo
$filename = "LTW_T2G4.xml";
//$filename = $_POST['filename'];

$data = implode("", file($filename));
$parser = xml_parser_create();
xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
xml_parse_into_struct($parser, $data, $values, $tags);
xml_parser_free($parser);

echo "\n\n\n\n ---------- DATA ---------  \n\n\n";
echo $data;

echo "\nb\n\n ----------- VALUES --------- \n\n\n";
print_r($values);

echo "\n\n\n ---------- TAGS ----------  \n\n\n";
print_r($tags);

echo "\n\n\n ---------- PARSER ----------  \n\n\n";
echo $parser;

foreach ($tags as $key => $val) {
    switch ($key) {
        case"Customer":
            $customers = $val;
            for ($i = 0; $i < count($customers); $i+=2) {
                $offset = $customers[$i] + 1;
                $len = $customers[$i + 1] - $offset;
                $tdb[] = parseCustomer(array_slice($values, $offset, $len));
            }
            break;
        case "Invoice":
            $invoices = $val;
            for ($i = 0; $i < count($invoices); $i+=2) {
                $offset = $invoices[$i] + 1;
                $len = $invoices[$i + 1] - $offset;
                $tdb[] = parseInvoice(array_slice($values, $offset, $len));
            }
            break;
        case "Product":
            $products = $val;
            for ($i = 0; $i < count($products); $i+=2) {
                $offset = $products[$i] + 1;
                $len = $products[$i + 1] - $offset;
                $tdb[] = parseProducts(array_slice($values, $offset, $len));
            }
            break;
    }
}

function getMaxTaxID() {
    $db = new PDO('sqlite:./db/db_t1.db');
    $getmax = "SELECT MAX(id) FROM Tax";
    $stmt = $db->prepare($getmax);
    $stmt->execute();
    $result = $stmt->fetch();
    $newno = $result[0];
    return $newno;
}

function parseCustomer($mvalues) {
    //echo "<br>Encontrou Customer<br>";

    $db = new PDO('sqlite:./db/db_t1.db');

    for ($i = 0; $i < count($mvalues); $i+=1) {
        if ($mvalues[$i]['tag'] == "CustomerID")
            $customerID = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "CustomerTaxID")
            $customerTaxID = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "CompanyName")
            $companyName = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "AddressDetail")
            $addressDetail = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "City")
            $city = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "PostalCode")
            $postalCode = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "Country")
            $country = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "Email")
            $email = $mvalues[$i]["value"];
    }


    $insert = "INSERT INTO customers(CustomerID, CustomerTaxID , CompanyName , Email , AddressDetail , City , PostalCode , Country)
                VALUES(:CustomerID, :CustomerTaxID, :CompanyName, :Email , :AddressDetail, :City, :PostalCode, :Country )";

    $stmt = $db->prepare($insert);
    $stmt->bindParam(':CustomerID', $customerID, PDO::PARAM_INT);
    $stmt->bindParam(':CustomerTaxID', $customerTaxID, PDO::PARAM_INT);
    $stmt->bindParam(':CompanyName', $companyName, PDO::PARAM_STR);
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':AddressDetail', $addressDetail, PDO::PARAM_STR);
    $stmt->bindParam(':City', $city, PDO::PARAM_STR);
    $stmt->bindParam(':PostalCode', $postalCode, PDO::PARAM_STR);
    $stmt->bindParam(':Country', $country, PDO::PARAM_STR);
    $stmt->execute();
    //echo "Submeteu Customer <br>";
}

function parseProducts($mvalues) {
    //echo "Encontrou Produto<br>";
    $db = new PDO('sqlite:./db/db_t1.db');

    //var_dump($mvalues);

    for ($i = 0; $i < count($mvalues); $i+=1) {
        if ($mvalues[$i]['tag'] == "ProductCode")
            $productCode = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "ProductDescription")
            $productDescription = $mvalues[$i]["value"];
    }
    // TODO: Estes campos nao estao no XML

    $selectUnitPrice = "SELECT UnitPrice FROM Lines WHERE Lines.ProductCode = " . $productCode;
    $stmt = $db->prepare($selectUnitPrice);
    $stmt->execute();
    $result = $stmt->fetch();

    $unitPrice = $result[0];
    $unitOfMeasure = "grama";


    $insert = "INSERT INTO products(ProductCode , ProductDescription , UnitPrice , UnitOfMeasure)
                   VALUES(:ProductCode , :ProductDescription , :UnitPrice , :UnitOfMeasure)";

    $stmt = $db->prepare($insert);
    $stmt->bindParam(':ProductCode', $productCode, PDO::PARAM_STR);
    $stmt->bindParam(':ProductDescription', $productDescription, PDO::PARAM_STR);
    $stmt->bindParam(':UnitPrice', $unitPrice, PDO::PARAM_STR);
    $stmt->bindParam(':UnitOfMeasure', $unitOfMeasure, PDO::PARAM_STR);
    $stmt->execute();
    //echo "Submeteu Produto<br>";
}

function parseInvoice($mvalues) {
    //echo "<br>Encontrou Invoice<br>";
    $db = new PDO('sqlite:../DB/dataBase.db');

    //var_dump($mvalues);

    for ($i = 0; $i < count($mvalues); $i+=1) {
        if ($mvalues[$i]['tag'] == "InvoiceNo")
            $invoiceNo = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "InvoiceDate")
            $invoiceDate = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "CustomerID")
            $customerID = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "TaxPayable")
            $taxPayable = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "NetTotal")
            $netTotal = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "GrossTotal")
            $grossTotal = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "InvoiceStatusDate")
            $invoiceStatusDate = $mvalues[$i]["value"];
        else if ($mvalues[$i]['tag'] == "SourceID")
            $sourceID = $mvalues[$i]["value"];
    }

    //echo "<br>", $invoiceNo ;
    //echo "<br>", $invoiceDate;
    //echo "<br>", $customerID;
    //echo "<br>", $taxPayable;
    //echo "<br>", $netTotal;
    //echo "<br>", $grossTotal;
    //echo "<br>", $invoiceStatusDate;
    //echo "<br>", $sourceID ;

    $insertInvoice = "INSERT INTO Invoice(InvoiceNo , InvoiceDate , CustomerID, TaxPayable, NetTotal, GrossTotal, InvoiceStatusDate, SourceID)
                            VALUES(:InvoiceNo, :InvoiceDate, :CustomerID, :TaxPayable, :NetTotal, :GrossTotal, :InvoiceStatusDate, :SourceID)";

    $stmt = $db->prepare($insertInvoice);
    $stmt->bindParam(':InvoiceNo', $invoiceNo, PDO::PARAM_STR);
    $stmt->bindParam(':InvoiceDate', $invoiceDate, PDO::PARAM_STR);
    $stmt->bindParam(':CustomerID', $customerID, PDO::PARAM_STR);
    $stmt->bindParam(':TaxPayable', $taxPayable, PDO::PARAM_STR);
    $stmt->bindParam(':NetTotal', $netTotal, PDO::PARAM_STR);
    $stmt->bindParam(':GrossTotal', $grossTotal, PDO::PARAM_STR);
    $stmt->bindParam(':InvoiceStatusDate', $invoiceStatusDate, PDO::PARAM_STR);
    $stmt->bindParam(':SourceID', $sourceID, PDO::PARAM_STR);
    $stmt->execute();

    for ($i = 0; $i < count($mvalues); $i+=2) { // Porque abre e fecha
        if ($mvalues[$i]['tag'] == "Line") {
            $lineNumber = $mvalues[$i + 1]["value"];
            $productCode = $mvalues[$i + 2]["value"];
            $quantity = $mvalues[$i + 4]["value"];
            $unitOfMeasure = $mvalues[$i + 5]["value"];
            $unitPrice = $mvalues[$i + 6]["value"];
            $invoiceID = $invoiceNo;
            $taxType = $mvalues[$i + 11]["value"];
            $taxPercen = $mvalues[$i + 14]["value"];


            $insertTax = "INSERT INTO Tax(TaxType,TaxPercentage) VALUES(:TaxType,:TaxPercentage)";
            $stmt = $db->prepare($insertTax);
            $stmt->bindParam(':TaxType', $taxType, PDO::PARAM_STR);
            $stmt->bindParam(':TaxPercentage', $taxPercen, PDO::PARAM_STR);
            $stmt->execute();

            $thisTaxID = getMaxTaxID();


            $insertLine = "INSERT INTO Lines(LineNumber,ProductCode,Quantity,UnitPrice,InvoiceID,TaxId) VALUES
                                                                                    (:LineNumber,:ProductCode,:Quantity,:UnitPrice,:InvoiceId,:TaxId)";

            $stmt = $db->prepare($insertLine);
            $stmt->bindParam(':LineNumber', $lineNumber, PDO::PARAM_STR);
            $stmt->bindParam(':ProductCode', $productCode, PDO::PARAM_STR);
            $stmt->bindParam(':Quantity', $quantity, PDO::PARAM_STR);
            $stmt->bindParam(':UnitPrice', $unitPrice, PDO::PARAM_STR);
            $stmt->bindParam(':InvoiceId', $invoiceID, PDO::PARAM_STR);
            $stmt->bindParam(':TaxId', $thisTaxID, PDO::PARAM_INT);

            //echo "<br> Inseriu Linha ", $lineNumber, "<br>";
            $stmt->execute();
        }
    }
}

//echo "LISTAGEM Depois:<br>";
//listInvoices();
//listProducts();
echo "Carregamento com sucesso. Apenas foram carregados os elementos com ID's diferentes dos ja existentes.";
?>

