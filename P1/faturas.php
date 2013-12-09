<?php
// session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="Sumario.js"></script>
<script src="funcoesFormulario.js"></script>

<script>
    $(document).ready(function() {
        var numerosInvoices;
        var cntLines = 0;
        var taxTypes = [];
         var prodCodes = [];
        var taxPercentages = [];

        function getNInvoice(){
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
        }
        getNInvoice();
        var getTaxes = $.getJSON("api/getTaxes.php");
        getTaxes.done(function(taxes) {
            console.log("TAXESSSSS = " + JSON.stringify(taxes));
            for (t in taxes) {
                taxTypes[t] = taxes[t].TaxType;
                taxPercentages[t] = taxes[t].TaxPercentage;
            }
            console.log("TAXtypes = " + taxTypes);
        });
        getTaxes.fail(function(data, textStatus, errorThrown) {
            console.log("error " + textStatus);
        });

        var getProdCodes = $.getJSON("api/getProductCode.php");
        getProdCodes.done(function(products) {
            console.log("Products = " + JSON.stringify(products));
            for (t in products) {
                prodCodes[t] = products[t];
            }
            console.log("Products = " + prodCodes);
        });
        getProdCodes.fail(function(data, textStatus, errorThrown) {
            console.log("error " + textStatus);
        });

        function gerarSelectTaxes(tax) {
            var classe = 'line' + cntLines;
            var htmlSelect = '<select id="TaxType" class = "' + classe + '">';
            for (t in taxTypes) {
                if (taxTypes[t] == tax) {
                    
                    console.log ("BCCCCCCCCC = " + taxTypes[t]);
                    htmlSelect += '<option value="' + taxTypes[t] + '" selected>' + taxTypes[t] + "</option>";
                } else {
                    console.log("BCCCCCCCCC = " + taxTypes[t]);
                    htmlSelect += '<option value="' + taxTypes[t] + '">' + taxTypes[t] + "</option>";
                }
            }
            htmlSelect += '</select>';
            return htmlSelect;
        }

         function gerarSelectProdCode(pcode) {
            var classe = 'line' + cntLines;
            var htmlSelect = '<select id="ProductCode" class = "' + classe + '">';
            for (t in prodCodes) {
                if (prodCodes[t] == pcode) {
                    
                    console.log ("BCCCCCCCCC = " + prodCodes[t]);
                    htmlSelect += '<option value="' + prodCodes[t] + '" selected>' + prodCodes[t] + "</option>";
                } else {
                    console.log("BCCCCCCCCC = " + prodCodes[t]);
                    htmlSelect += '<option value="' + prodCodes[t] + '">' + prodCodes[t] + "</option>";
                }
            }
            htmlSelect += '</select>';
            return htmlSelect;
        }

        function carregarInvoice(n) {
            console.log("InvoiceNo = " + n);

            // busca invoice
            console.log("AQUI1");
            var invoice = $.getJSON("api/getInvoice.php?InvoiceNo=" + n);
            console.log("api/getInvoice.php?InvoiceNo=" + n);
            console.log(invoice);
            invoice.done(function(data) {
                //$("#tabela").remove(".dinamico");

                console.log("json invoice = " + JSON.stringify(data));
                delete data.InvoiceNo;
                console.log("\n\njson invoice MOD = " + JSON.stringify(data));

                $("#InvoiceStatusDate").val(data["DocumentStatus"]["InvoiceStatusDate"]);
                $("#SourceBilling").val(data["DocumentStatus"]["SourceBilling"]);
                $("#SourceID").val(data["DocumentStatus"]["SourceID"]);
                $("#Hash").val(data["Hash"]);
                $("#InvoiceDate").val(data["InvoiceDate"]);
                $("#InvoiceType").val(data["InvoiceType"]);
                $("#SelfBillingIndicator").val(data["SpecialRegimes"]["SelfBillingIndicator"]);
                $("#CashVATSchemeIndicator").val(data["SpecialRegimes"]["CashVATSchemeIndicator"]);
                $("#ThirdPartiesBillingIndicator").val(data["SpecialRegimes"]["ThirdPartiesBillingIndicator"]);
                $("#InvoiceType").val(data["InvoiceType"]);
                $("#SystemEntryDate").val(data["SystemEntryDate"]);
             
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

        function adicionarLine(line) {
            var aux;
            var classe = 'line' + cntLines;

            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"><th class="lineName">' + "Line X" + '</th><td><button id="btnRemover" class="' + classe + '"type="button">Remover</button></td></tr>');

            aux = (!line) ? "" : line.LineNumber;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>LineNumber:</th> <td><input id="LineNumber" required disabled type="text" name="nome" value="' + aux + '"></td> </tr>');

            aux = (!line) ? "" : line.ProductCode;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>ProductCode:</th> <td>' + gerarSelectProdCode(aux) + '</td></tr>');

            aux = (!line) ? "" : line.Quantity;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>Quantity:</th> <td><input id="Quantity"  required  class = "' + classe + '" type="text" name="nome" value="' + aux + '"   pattern="^[0-9]+(?:\.[0-9]+)?$" oninvalid="this.setCustomValidity('+"'Preencha este campo com um número positivo com ou sem casa decimal. Ex: 2.50'"+')" oninput="setCustomValidity('+"''"+')"+></td> </tr>');

            aux = (!line) ? "" : line.UnitPrice;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>UnitPrice:</th> <td><input id="UnitPrice" disabled type="text" name="nome" value="' + aux + '"></td> </tr>');

            aux = (!line) ? "" : line.CreditAmount;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>CreditAmount:</th> <td><input id="CreditAmount" class = "' + classe + '" disabled type="text" name="nome" value="' + aux + '"></td> </tr>');

            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"><th colspan="2">Tax</th></tr>');
            aux = (!line) ? "IVA" : line.Tax.TaxType;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>TaxType:</th> <td>' + gerarSelectTaxes(aux) + '</td></tr>');
            aux = (!line) ? "23.0" : line.Tax.TaxPercentage;
            $('#btnAddLine').before('<tr class="dinamico ' + classe + '"> <th>TaxPercentage:</th> <td><input id="TaxPercentage" class = "' + classe + '" disabled type="text" name="nome" value="' + aux + '"></td> </tr>');

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
            var imposto = 0;
            var aux;
            var aux2;
            $("[id=CreditAmount]").each(function(i) {
                aux = parseFloat($(this).val());
                if (!isNaN(aux)){
                    total += aux;
                    
                    var porcentagem = $("." + $(this).attr('class') + " [id=TaxPercentage]");
                    aux2 = parseFloat(porcentagem.val());
                    if (!isNaN(aux2)){
                        imposto += aux * (aux2 / 100.0);
                    }
                }
                console.log(i + " = " + total);
            });
            $("[id=GrossTotal]").val((total + imposto).toFixed(2));
            $("[id=TaxPayable]").val(imposto.toFixed(2));
            $("[id=NetTotal]").val(total.toFixed(2));
        }

        $("#btnAddLine").click(function() {
            adicionarLine();
        });

        $("#tabela").on("click", "#btnAddTax", function() {
            adicionarTax(this);
        });

        function getTaxPercetage(tax){
            for(i in taxTypes){
                if(taxTypes[i] == tax) return taxPercentages[i];
            }
            return -1;
        }

        $("#tabela").on("change", "#TaxType", function() {
            var porcentagem = $("." + $(this).attr('class') + " [id=TaxPercentage]");
            porcentagem.val(getTaxPercetage($(this).val()));
            atualizarTotal();
        });

        // atualiza total quando invoice muda
        $("#tabela").on("blur", "#CreditAmount", function() {
            atualizarTotal();
        });

        $("#tabela").on("blur", "#ProductCode", function() {
            var quantidade = $("." + $(this).attr('class') + " [id=Quantity]");
            var precoU = $("." + $(this).attr('class') + " [id=UnitPrice]");
            var total = $("." + $(this).attr('class') + " [id=CreditAmount]");

            var getProduct = $.getJSON("api/getProduct.php?ProductCode=" + $(this).val());
            getProduct.done(function(data) {
                precoU.val(data.UnitPrice);

                var precoNovo = quantidade.val() * precoU.val();
                total.val(precoNovo);
            });
            getProduct.fail(function(data, textStatus, errorThrown) {
                console.log("error " + textStatus);
            });
            atualizarTotal();

        });

        $("#tabela").on("blur", "#Quantity", function() {
            var quantidade = $("." + $(this).attr('class') + " [id=Quantity]");
            var precoU = $("." + $(this).attr('class') + " [id=UnitPrice]");

            var total = $("." + $(this).attr('class') + " [id=CreditAmount]");
            var precoNovo = quantidade.val() * precoU.val();
            total.val(precoNovo);
            atualizarTotal();
        });

        $("#tabela").on("click", "#btnRemover", function() {
            var className = $(this).attr('class');
            $("." + className).remove();
            atualizarTudo();
        });


        $("#InvoiceNo").blur(function() {
            limparLines();
			
			console.log(numerosInvoices);
			
            if (numerosInvoices.indexOf($("#InvoiceNo").val()) !== -1) {
                carregarInvoice($("#InvoiceNo").val());
            } else {
                limparFormulario('formularioInvoice');
            }
        });


        $('#formularioInvoice').submit(function() {
             gerarJSON();
             getNInvoice();

             
             
        });

        function gerarJSON() {
            var lines = [];
            var jsonInvoice = {
                "InvoiceNo": $("#InvoiceNo").val(),
                "DocumentStatus": {
                    "InvoiceStatusDate": $("#InvoiceStatusDate").val(),
                    "SourceBilling": $("#SourceBilling").val(),
                    "SourceID": $("#SourceID").val()
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
                lines[index].Tax = {};
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            $("[id=TaxType]").each(function(index) {
                lines[index].Tax.TaxType = $(this).val();
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            $("[id=TaxPercentage]").each(function(index) {
                lines[index].Tax.TaxPercentage = $(this).val();
                console.log("=== " + $(this).val());
                //console.log("ln = " + $(this).next("#LineNumber").val());
            });

            console.log("aqui" + JSON.stringify(jsonInvoice));

            var retorno = $.ajax({
                type: 'POST',
                dataType : "json",
                url: 'api/updateInvoice.php',
                data: {json: JSON.stringify(jsonInvoice)}
            });

            retorno.done(function(data) {
                console.log("dados recebidos = " + data);
            });

            retorno.fail(function(jqXHR, textStatus, errorThrown) {
                console.log("1234 - " + jqXHR);
                console.log("error " + textStatus);
            });
            return false;
        }
    });
</script>


<?php if ($_SESSION['role_id'] == 0 || $_SESSION['role_id'] == 1) { ?>
<div id="pesquisa_produto" style="text-align:center;">
    <br><h3 style="text-align:left;">  Gerir Faturas</h3><br>
    <form id="formularioInvoice" onsubmit="formSuccess('Fatura')">
        <table id="tabela" >
           <tr> <th>InvoiceNo:</th> <td><input id="InvoiceNo" type="text" name="nome" value="" pattern=".{1,30}$"  oninvalid="this.setCustomValidity('Preencha este campo com uma sequência de números com um máximo de 60 carateres.')" oninput="setCustomValidity('')"></td> </tr>
            <tr><th colspan="2">DocumentStatus</th></tr>
            <tr> <th>InvoiceStatusDate:</th> <td><input id="InvoiceStatusDate" required type="text" name="nome" value="" pattern="^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}$"   oninvalid="this.setCustomValidity('Por favor preencha este campo seguindo o seguinte formato: AAAA-MM-DD HH:MM:SS')" oninput="setCustomValidity('')"></td> </tr>
          <tr> <th>SourceBilling:</th> <td><input id="SourceBilling" required type="text" name="nome" value="" pattern="[PIM]"   oninvalid="this.setCustomValidity('Por favor preencha este campo com P, I ou M.')" oninput="setCustomValidity('')"></td> </tr>
            <tr> <th>SourceID:</th> <td><input id="SourceID" type="text" required name="nome" disabled></td> </tr>
            <tr> <th>Hash:</th> <td><input id="Hash" type="text" name="nome" required value="" pattern=".{1,172}" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 172 carateres.')" oninput="setCustomValidity('')"></td> </tr>
            <tr> <th>InvoiceDate:</th> <td><input id="InvoiceDate" type="text" required name="nome" value="" pattern="\d{4}-\d{2}-\d{2}"  oninvalid="this.setCustomValidity('Por favor preencha este campo seguindo o seguinte formato: AAAA-MM-DD')" oninput="setCustomValidity('')"></td> </tr>
            <tr> <th>InvoiceType:</th> <td><input id="InvoiceType" type="text" name="nome" value="" pattern="FT|FS|FR|ND|NC|VD|TV|TD|AA|DA"  oninvalid="this.setCustomValidity('Por favor preencha este campo com FT, FS, FR, ND, NC, VD, TV, TD, AA ou DA')" oninput="setCustomValidity('')"></td> </tr>
            <tr><th colspan="2">SpecialRegimes</th></tr>
            <tr> <th>SelfBillingIndicator:</th> <td><input id="SelfBillingIndicator" required type="text" name="nome" value="" pattern="[0-1]" oninvalid="this.setCustomValidity('Preencha este campo apenas com 0 ou 1.')" oninput="setCustomValidity('')"></td> </tr>
            <tr> <th>CashVATSchemeIndicator:</th> <td><input id="CashVATSchemeIndicator" required type="text" name="nome" value="" pattern="[0-1]" oninvalid="this.setCustomValidity('Preencha este campo apenas com 0 ou 1.')" oninput="setCustomValidity('')"></td> </tr>
            <tr> <th>ThirdPartiesBillingIndicator:</th> <td><input id="ThirdPartiesBillingIndicator" required type="text" name="nome" value="" pattern="[0-1]" oninvalid="this.setCustomValidity('Preencha este campo apenas com 0 ou 1.')" oninput="setCustomValidity('')"></td> </tr>
            <tr> <th>SystemEntryDate:</th> <td><input id="SystemEntryDate" type="text" required name="nome" value=""></td> </tr>
            
            <tr> <th>CustomerID:</th> <td><input id="CustomerID" type="text" name="nome" required value="" pattern=".{1,30}" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 30 carateres.')" oninput="setCustomValidity('')"></td> </tr><tr id="Lines"><th colspan="2">Lines</th></tr>
            <tr id="btnAddLine"><th colspan="2"><button type="button" id="bt_editar">Add Line</button> </th></tr>
            <tr id="DocumentTotal"><th colspan="2">DocumentTotal</th></tr>
            <tr> <th>TaxPayable:</th> <td><input id="TaxPayable" type="text" name="nome" disabled value=""></td> </tr>
            <tr> <th>NetTotal:</th> <td><input id="NetTotal" type="text" name="nome" disabled value=""></td> </tr>
            <tr> <th>GrossTotal:</th> <td><input id="GrossTotal" type="text" name="nome" disabled value=""></td> </tr>
            <tr id="btAtualizar"><th><input id="bt_limpar" name="reset" type="reset" value="Limpar" onclick="limparFormulario('formularioInvoice');
        return false;" /></th><td colspan="2"><input id="btnAtualizar" type="submit" class="bt_atualizar" value="Inserir | Editar"> </td></tr>
        </table>
    </form>
</div>



<br>
<?php } ?>
<div id="pesquisa_produto" style="text-align:center;">
    <form id="busca"   style="margin-bottom:6px;padding-top:10px;">
        <input type="text" id="value1" name="value">
        <input type="text" id="value2" name="value" disabled="true">
        <select id ="field" name="field">
            <option value="InvoiceNo" selected>InvoiceNo</option>
            <option value="InvoiceDate" >InvoiceDate</option>	
            <option value="CompanyName">CompanyName</option>
            <option value="GrossTotal">GrossTotal</option>
        </select>
        <select id="op" name="op">
            <option value="range">range</option>
            <option value="equal">equal</option>
            <option value="contains" selected>contains</option>
            <option value="min">min</option>
            <option value="max">max</option>
        </select>
        <input type="button" id="bt_buscar" value="Buscar">
    </form>

    <table id="tbl_resultados" class="tbl_accor">
    </table>

    <script>
    var tabela = new Sumario('searchInvoicesByField.php', 'getInvoice.php', ['InvoiceNo', 'InvoiceDate', 'CompanyName', 'GrossTotal'], ['InvoiceNo', 'InvoiceDate', 'CompanyName', 'GrossTotal'], true);
    </script>

    <form id="btns_mover">
        <input type="button" id="bt_prev" value="<">
        <input type="button" id="bt_next" value=">">
    </form>
</div>
