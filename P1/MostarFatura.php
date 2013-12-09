<?php

function gerarSelect() {
    $db = new PDO('sqlite:./db/db_t1.db');

    $sql = "SELECT InvoiceNo FROM invoices";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    echo "<select name='InvoiceNo' id='InvoiceNo'>";
    echo "<option value='' disabled='' selected='' >--Selecione InvoiceNo--</option>";
    while ($row = $stmt->fetch()) {
        echo "<option value='" . $row['InvoiceNo'] . "' >" . $row['InvoiceNo'] . "</option>";
    }
    echo "</select>";
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>
    function gerarHTMLTabela(json) {
        var html = '';
        var e;
        for (e in json) {
            if (json.hasOwnProperty(e)) {
                if (json[e] instanceof Object) {
                    console.log('eh array  = ' + e);
                    html += '<tr><th colspan="2">' + e + '</th></tr>';
                    html += gerarHTMLTabela(json[e]);
                } else {
                    console.log('nao eh array = ' + e);
                    html += '<tr><th>' + e + '</th><td>' + json[e] + '</td></tr>';
                }
            }
        }
        return html;
    }

    $(document).ready(function() {
        $('#InvoiceNo').on('change',function(){
            var invoiceNo = $(this).find("option:selected").val();

            console.log("InvoiceNo = " + invoiceNo);

            // busca invoice
            var invoice = $.getJSON("api/getInvoice.php?InvoiceNo=" + invoiceNo);

            invoice.done(function(data) {
                console.log("json invoice = " + JSON.stringify(data));

                //delete data.InvoiceNo;

                //console.log("\n\njson invoice MOD = " + JSON.stringify(data));

                var html = '<table id="tabela">' + $("#linhaInvoiceNo").html() + gerarHTMLTabela(data) + '';
                console.log(html);
                $("#tabela").html(html);
            });

            invoice.fail(function(jqXHR, textStatus, errorThrown) {
                console.log("error " + textStatus);
            });
        });
    });
</script>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>

        <form>
            <table id="tabela">
                <tr id="linhaInvoiceNo"> <th>InvoiceNo:</th> <td><?php gerarSelect(); ?></td> </tr>
            </table>
        </form>
    </body>
</html>
