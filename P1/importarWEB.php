<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>

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
        ob_start();

        //error_reporting(E_ALL);
        //ini_set("display_errors", 1);
        $db = new PDO('sqlite:./db/db_t2.db');

        $url = "http://paginas.fe.up.pt/~ext1300535/t2/";

        $cnt = 0;
        $products = json_decode(file_get_contents($url . 'api/searchProductsByField.php?value[]=&value[]=&op=contains&field=ProductCode'));
        $nProducts = count($products);
        foreach ($products as $p) {
            $product = json_decode(file_get_contents($url . 'api/getProduct.php?ProductCode=' . $p->ProductCode));
            $stmt = $db->prepare("INSERT INTO products VALUES ('P',?,?,?,?,?)");
            $stmt->execute(array($product->ProductCode, $product->ProductDescription, $product->UnitPrice, $product->UnitOfMeasure, $product->ProductNumberCode));

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
        $customers = json_decode(file_get_contents($url . 'api/searchCustomersByField.php?value[]=&value[]=&op=contains&field=CustomerID'));
        $nCustomers = count($customers);
        foreach ($customers as $c) {
            $customer = json_decode(file_get_contents($url . 'api/getCustomer.php?CustomerID=' . $c->CustomerID));
            $stmt = $db->prepare('INSERT INTO customers (CustomerID, AccountID, CustomerTaxID, CompanyName, AddressDetail,City,PostalCode,Country,Email) VALUES (?,?,?,?,?,?,?,?,?)');
            $stmt->execute(array($customer->CustomerID, $customer->AccountID, $customer->CustomerTaxID, $customer->CompanyName, $customer->BillingAddress->AddressDetail, $customer->BillingAddress->City, $customer->BillingAddress->PostalCode, $customer->BillingAddress->Country, $customer->Email));

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

        $stmt = $db->prepare("INSERT INTO taxes VALUES ('IVA','PT', 'NOR', 23.00, 'IVA Normal');");
        $stmt->execute();
        $stmt = $db->prepare("INSERT INTO taxes VALUES ('IS','PT','1', 11.5, 'IS Escala')");
        $stmt->execute();

        $cnt = 0;
        $invoices = json_decode(file_get_contents($url . 'api/searchInvoicesByField.php?value[]=&value[]=&op=contains&field=InvoiceNo'));
        $nInvoices = count($invoices);
        foreach ($invoices as $i) {
            $var = json_decode(file_get_contents($url . 'api/getInvoice.php?InvoiceNo=' . $i->InvoiceNo));

            $stmt = $db->prepare("INSERT INTO invoices (InvoiceNo,InvoiceStatus,InvoiceStatusDate,SourceBilling,SourceID,Hash,InvoiceDate,InvoiceType,SelfBillingIndicator,CashVATSchemeIndicator,ThirdPartiesBillingIndicator,SystemEntryDate,CustomerID,TaxPayable,NetTotal,GrossTotal) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            //$num = substr($var->InvoiceNo, 7);
            //sleep(1);

            $stmt->execute(array($var->InvoiceNo,
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
                $var->DocumentTotals->GrossTotal
            ));


            $cnt2 = 0;
            $nLines = count($var->Line);
            foreach ($var->Line as $lineaux) {
                $stmt2 = $db->prepare("INSERT INTO lines (LineNumber, TaxType, InvoiceNo,ProductCode,ProductDescription,Quantity,UnitOfMeasure,UnitPrice, CreditAmount) VALUES (?,?,?,?,?,?,?,?,?)");
                $stmt2->execute(array(
                    $lineaux->LineNumber,
                    $lineaux->Tax->TaxType,
                    $var->InvoiceNo,
                    $lineaux->ProductCode,
                    $lineaux->ProductDescription,
                    $lineaux->Quantity,
                    $lineaux->UnitOfMeasure,
                    $lineaux->UnitPrice,
                    $lineaux->CreditAmount
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
    </body>
</html>
