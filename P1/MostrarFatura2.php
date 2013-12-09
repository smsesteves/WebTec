<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="LimparFormulario.js"></script>

<script>
    $(document).ready(function() {
        var numerosInvoices;
        var cntLines = 0;
        var cntTaxes = 0;

        var getNumerosInvoices = $.getJSON("api/getInvoicesNo.php");
        getNumerosInvoices.done(function(data) {
            console.log("json numeros faturas = " + JSON.stringify(data));

            numerosInvoices = data;

            for (abc in numerosInvoices) {
                console.log(">>> " + numerosInvoices[abc]);
            }
        });

        getNumerosInvoices.fail(function(data, textStatus, errorThrown) {
            console.log("error " + textStatus);
        });

        function carregarInvoice(n) {
            console.log("InvoiceNo = " + n);

            // busca invoice
            var invoice = $.getJSON("api/getInvoice.php?InvoiceNo=" + n);
            invoice.done(function(data) {
                //$("#tabela").remove(".dinamico");

                console.log("json invoice = " + JSON.stringify(data));
                delete data.InvoiceNo;
                console.log("\n\njson invoice MOD = " + JSON.stringify(data));

                $("#InvoiceStatusDate").val(data["DocumentStatus"]["InvoiceStatusDate"]);
                $("#SourceBilling").val(data["DocumentStatus"]["SourceBilling"]);
                $("#SourceId").val(data["DocumentStatus"]["SourceId"]);
                $("#Hash").val(data["Hash"]);
                $("#InvoiceDate").val(data["InvoiceDate"]);
                $("#InvoiceType").val(data["InvoiceType"]);
                $("#SelfBillingIndicator").val(data["SpecialRegimes"]["SelfBillingIndicator"]);
                $("#CashVATSchemeIndicator").val(data["SpecialRegimes"]["CashVATSchemeIndicator"]);
                $("#ThirdPartiesBillingIndicator").val(data["SpecialRegimes"]["ThirdPartiesBillingIndicator"]);
                $("#InvoiceType").val(data["InvoiceType"]);
                $("#SourceID").val(data["SourceID"]);
                $("#SystemEntryDate").val(data["SystemEntryDate"]);
                $("#TransactionID").val(data["TransactionID"]);
                $("#CustomerID").val(data["CustomerID"]);
                $("#TaxPayable").val(data["DocumentTotal"]["TaxPayable"]);
                $("#NetTotal").val(data["DocumentTotal"]["NetTotal"]);
                $("#GrossTotal").val(data["DocumentTotal"]["GrossTotal"]);

                var lines = data["Line"];
                for (l in lines) {
                    adicionarLine(lines[l]);
                }
            });
            invoice.fail(function(jqXHR, textStatus, errorThrown) {
                console.log("error " + textStatus);
            });
        }

        function adicionarTax(btnAddTax) {
            var classeTax = "tax" + cntTaxes;
            var todasClasses = classeTax + " " + $(btnAddTax).attr('class');

            $(btnAddTax).closest('tr').before('<tr class="dinamico ' + todasClasses + '"><th>' + "Tax " + 'X' + '</th> <td><button id="btnRemoverTax" class="' + classeTax + '"type="button">Remover</button></td></tr>');
            $(btnAddTax).closest('tr').before('<tr class="dinamico ' + todasClasses + '"> <th>TaxType:</th> <td><input id="TaxType" type="text" name="nome" value=""></td> </tr>');
            $(btnAddTax).closest('tr').before('<tr class="dinamico ' + todasClasses + '"> <th>TaxPercentage:</th> <td><input id="TaxPercentage" type="text" name="nome" value=""></td> </tr>');

            ++cntTaxes;
            //atualizarNoLines();
        }

        function adicionarLine(line) {
            var aux;
            var classe = 'line' + cntLines;

            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"><th class="lineName">' + "Line X" + '</th><td><button id="btnRemover" class="' + classe + '"type="button">Remover</button></td></tr>');

            aux = (!line) ? "" : line.LineNumber;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>LineNumber:</th> <td><input id="LineNumber" type="text" name="nome" value="' + aux + '"></td> </tr>');

            aux = (!line) ? "" : line.ProductCode;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>ProductCode:</th> <td><input id="ProductCode" type="text" name="nome" value="' + aux + '"></td> </tr>');

            aux = (!line) ? "" : line.Quantity;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>Quantity:</th> <td><input id="Quantity" type="text" name="nome" value="' + aux + '"></td> </tr>');

            aux = (!line) ? "" : line.UnitPrice;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>UnitPrice:</th> <td><input id="UnitPrice" type="text" name="nome" value="' + aux + '"></td> </tr>');

            aux = (!line) ? "" : line.CreditAmount;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>CreditAmount:</th> <td><input id="CreditAmount" type="text" name="nome" value="' + aux + '"></td> </tr>');
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"><th colspan="2">Taxes</th></tr>');

            var classeTax;
            var todasClasses;
            if (line) {
                var taxes = line.Tax;
                for (t in taxes) {
                    classeTax = "tax" + cntTaxes;
                    todasClasses = classe + " tax" + cntTaxes; 
                
                    $('#btnAddLine').before('<tr class="dinamico ' + todasClasses + '"><th>' + "Tax " + t + '</th><td><button id="btnRemoverTax" class="' + classeTax + '"type="button">Remover</button></td></tr>');
                    $('#btnAddLine').before('<tr class="dinamico ' + todasClasses + '"> <th>TaxType:</th> <td><input id="TaxType" type="text" name="nome" value="' + taxes[t].TaxType + '"></td> </tr>');
                    $('#btnAddLine').before('<tr class="dinamico ' + todasClasses + '"> <th>TaxPercentage:</th> <td><input id="TaxPercentage" type="text" name="nome" value="' + taxes[t].TaxPercentage + '"></td> </tr>');
                    
                    ++cntTaxes;
                }
            }
            
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th colspan="2"><button id="btnAddTax" class="dinamico ' + classe + '" type="button">Add Tax</button></th></tr>');

            ++cntLines;
            atualizarTudo();
        }

        function limparLines() {
            $(".dinamico").remove();
        }

        function atualizarTudo() {
            atualizarNoLines();
            atualizarTotal();
        }

        function atualizarNoLines() {
            $(".lineName").each(function(i) {
                $(this).text("Line " + i);
            });
        }

        function atualizarTotal() {
            var total = 0;
            var aux;
            $("[id=CreditAmount]").each(function(i) {
                aux = parseFloat($(this).val());
                if (!isNaN(aux))
                    total += aux;
                console.log(i + " = " + total);
            });
            $("[id=GrossTotal]").val(total);
        }

        $("#btnAddLine").click(function() {
            adicionarLine();
        });

        $("#tabela").on("click", "#btnAddTax", function() {
            adicionarTax(this);
        });

        // atualiza total quando invoice muda
        $("#tabela").on("blur", "#CreditAmount", function() {
            atualizarTotal();
        });

        $("#tabela").on("click", "#btnRemover", function() {
            var className = $(this).attr('class');
            //alert("classe = " + className);
            $("." + className).remove();
            atualizarTudo();
        });
        
        $("#tabela").on("click", "#btnRemoverTax", function() {
            var className = $(this).attr('class');
            //alert("classe = " + className);
            $("." + className).remove();
            atualizarTudo();
        });

        $("#tabela").on("click", "#btnRemover", function() {
            var className = $(this).attr('class');
            //alert("classe = " + className);
            $("." + className).remove();
            atualizarTudo();
        });

        $("#btnAtualizar").click(function() {
            gerarJSON();
        });

        $("#InvoiceNo").blur(function() {
            limparLines();
            if (numerosInvoices.indexOf($("#InvoiceNo").val()) !== -1) {
                carregarInvoice($("#InvoiceNo").val());
            } else {
                var aux = $("#InvoiceNo").val();
                limparFormulario('formularioInvoice');
                $("#InvoiceNo").val(aux);
            }
        });

        function gerarJSON() {
            var lines = [];
            var jsonInvoice = {
                "InvoiceNo": $("#InvoiceNo").val(),
                "DocumentStatus": {
                    "InvoiceStatusDate": $("#InvoiceStatusDate").val(),
                    "SourceBilling": $("#SourceBilling").val(),
                    "SourceId": $("#SourceId").val()
                },
                "Hash": $("#Hash").val(),
                "InvoiceDate": $("#InvoiceDate").val(),
                "InvoiceType": $("#InvoiceType").val(),
                "SpecialRegimes": {
                    "SelfBillingIndicator": $("#SelfBillingIndicator").val(),
                    "CashVATSchemeIndicator": $("#CashVATSchemeIndicator").val(),
                    "ThirdPartiesBillingIndicator": $("#ThirdPartiesBillingIndicator").val()
                },
                "SourceID": $("#SourceID").val(),
                "SystemEntryDate": $("#SystemEntryDate").val(),
                "TransactionID": $("#TransactionID").val(),
                "CustomerID": $("#CustomerID").val(),
                "Line": lines,
                "DocumentTotal": {
                    "TaxPayable": $("#TaxPayable").val(),
                    "NetTotal": $("#NetTotal").val(),
                    "GrossTotal": $("#GrossTotal").val()
                }
            };

            $("[id=LineNumber]").each(function(index) {
                lines[index] = {"LineNumber": $(this).val()};
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            $("[id=ProductCode]").each(function(index) {
                lines[index].ProductCode = $(this).val();
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            $("[id=Quantity]").each(function(index) {
                lines[index].Quantity = $(this).val();
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            $("[id=UnitPrice]").each(function(index) {
                lines[index].UnitPrice = $(this).val();
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            $("[id=CreditAmount]").each(function(index) {
                lines[index].CreditAmount = $(this).val();
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            console.log("json gerado === " + JSON.stringify(jsonInvoice));
        }
    });
</script>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form id="formularioInvoice">
            <table id="tabela">
                <tr> <th>InvoiceNo:</th> <td><input id="InvoiceNo" type="text" name="nome" value=""></td> </tr>
                <tr><th colspan="2">DocumentStatus</th></tr>
                <tr> <th>InvoiceStatusDate:</th> <td><input id="InvoiceStatusDate" type="text" name="nome" value=""></td> </tr>
                <tr> <th>SourceBilling:</th> <td><input id="SourceBilling" type="text" name="nome" value=""></td> </tr>
                <tr> <th>SourceId:</th> <td><input id="SourceId" type="text" name="nome" value=""></td> </tr>
                <tr> <th>Hash:</th> <td><input id="Hash" type="text" name="nome" value=""></td> </tr>
                <tr> <th>InvoiceDate:</th> <td><input id="InvoiceDate" type="text" name="nome" value=""></td> </tr>
                <tr> <th>SourceId:</th> <td><input id="InvoiceType" type="text" name="nome" value=""></td> </tr>
                <tr><th colspan="2">SpecialRegimes</th></tr>
                <tr> <th>SelfBillingIndicator:</th> <td><input id="SelfBillingIndicator" type="text" name="nome" value=""></td> </tr>
                <tr> <th>CashVATSchemeIndicator:</th> <td><input id="CashVATSchemeIndicator" type="text" name="nome" value=""></td> </tr>
                <tr> <th>ThirdPartiesBillingIndicator:</th> <td><input id="ThirdPartiesBillingIndicator" type="text" name="nome" value=""></td> </tr>
                <tr> <th>SourceID:</th> <td><input id="SourceID" type="text" name="nome" value=""></td> </tr>
                <tr> <th>SystemEntryDate:</th> <td><input id="SystemEntryDate" type="text" name="nome" value=""></td> </tr>
                <tr> <th>TransactionID:</th> <td><input id="TransactionID" type="text" name="nome" value=""></td> </tr>
                <tr> <th>CustomerID:</th> <td><input id="CustomerID" type="text" name="nome" value=""></td> </tr>
                <tr id="Lines"><th colspan="2">Lines</th></tr>
                <tr id="btnAddLine"><th colspan="2"><button type="button">Add Line</button> </th></tr>
                <tr id="DocumentTotal"><th colspan="2">DocumentTotal</th></tr>
                <tr> <th>TaxPayable:</th> <td><input id="TaxPayable" type="text" name="nome" value=""></td> </tr>
                <tr> <th>NetTotal:</th> <td><input id="NetTotal" type="text" name="nome" value=""></td> </tr>
                <tr> <th>GrossTotal:</th> <td><input id="GrossTotal" type="text" name="nome" value=""></td> </tr>
                <tr id="btnAtualizar"><th colspan="2"><button type="button">Atualizar</button> </th></tr>
            </table>
        </form>
    </body>
</html>
