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

        var productsData;

        var getProductsData = $.getJSON("api/getProductCode.php");
        getProductsData.done(function(data) {
            console.log("json products = " + JSON.stringify(data));

            productsData = data;

            for (abc in productsData) {
                console.log(">>> " + productsData[abc]);
            }
        });

        function carregarProduct(n) {
            console.log("Product = " + n);


            var products = $.getJSON("api/getProduct.php?ProductCode=" + n);
            products.done(function(data) {
                //$("#tabela").remove(".dinamico");

                console.log("json products = " + JSON.stringify(data));
                delete data.ProductCode;
                console.log("\n\njson products MOD = " + JSON.stringify(data));


                $("#ProductDescription").val(data["ProductDescription"]);
                $("#UnitPrice").val(data["UnitPrice"]);
                $("#UnitOfMeasure").val(data["UnitOfMeasure"]);
                $("#ProductNumberCode").val(data["ProductNumberCode"]);


//                var lines = data["Line"];
//                for (l in lines) {
                //                   adicionarLine(lines[l]);
                //              }
            });
        }

        function limparLines() {
            $(".dinamico").remove();
        }

        $("#bt_remover").click(function() {
            if (productsData.indexOf($("#ProductCode").val()) !== -1) {
                var remove = $.getJSON("api/removeProduct.php?ProductCode=" + $("#ProductCode").val());
                remove.done(function(data) {
                });
            }
            limparFormulario('editar_form');

            false;
        });
        
        $("#ProductCode").blur(function() {
            limparLines();
            if (productsData.indexOf($("#ProductCode").val()) !== -1) {
                carregarProduct($("#ProductCode").val());
            } else {
               // var aux = $("#ProductCode").val();
                limparFormulario('editar_form');
                //$("#ProductCode").val(aux);
            }
        });

        function gerarJSON() {
            var lines = [];
            var jsonProduct = {
                "ProductCode": $("#ProductCode").val(),
                "ProductDescription": $("#ProductDescription").val(),
                "UnitPrice": $("#UnitPrice").val(),
                "UnitOfMeasure": $("#UnitOfMeasure").val(),
                "ProductNumberCode": $("#ProductNumberCode").val()

            };


            console.log("json gerado === " + JSON.stringify(jsonProduct));
        }

    });
</script>

<?php if ($_SESSION['role_id'] == 0 || $_SESSION['role_id'] == 1) { ?>
<div id="pesquisa_produto" style="text-align:center;">
    <br><h3 style="text-align:left;">  Gerir Produtos</h3><br>
    
        <table id="tbl_editarperfil">
        <form id="editar_form" onsubmit="formSuccess('Produto')">
            <tr><th>ProductCode:</th> <td><input id="ProductCode" type="text" name="ProductCode" value=""  pattern=".{1,60}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 60 carateres.')" oninput="setCustomValidity('')"></td> </tr>
            <tr><th>ProductDescription: </th> <td><input id="ProductDescription" type="text" name="ProductDescription" value="" required pattern=".{1,200}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 200 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><th>UnitPrice: </th> <td><input id="UnitPrice" type="text" name="UnitPrice" value=""  required pattern="^[0-9]+(?:\.[0-9]+)?$" oninvalid="this.setCustomValidity('Preencha este campo com um número positivo com ou sem casa decimal. Ex: 2.50')" oninput="setCustomValidity('')"></td></tr>
            <tr><th>UnitOfMeasure: </th> <td><input id="UnitOfMeasure" type="text" required name="UnitOfMeasure" value=""   pattern=".{1,20}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 20 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><th>ProductNumberCode: </th> <td><input id="ProductNumberCode" type="text" name="ProductNumberCode" value="" pattern=".{1,50}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 50 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><td></td><td><input type="submit" class="bt_atualizar" id="bt_atualizar" value="Inserir | Editar"></td></tr>
</form>
            <tr><td><button id="bt_limpar" name="reset" type="reset" onclick="limparFormulario('editar_form')" />Limpar</button> <button class="bt_remover" id="bt_remover" onClick="false;">Remover</button></td></tr>
            </table>
</div>

<?php } ?>

<script>
    $(document).ready(function() {
        $("#editar_form").submit(function() {
            var json2 = {'ProductCode': $('#ProductCode').val(), 'ProductDescription': $('#ProductDescription').val(), 'UnitPrice': $('#UnitPrice').val(), 'UnitOfMeasure': $('#UnitOfMeasure').val(), 'ProductNumberCode': $('#ProductNumberCode').val()};

            console.log(json2);
            console.log(JSON.stringify(json2));
            var retorno = $.ajax({
                type: 'POST',
                url: 'api/updateProduct.php',
                data: {json: JSON.stringify(json2)}
            });

            retorno.done(function(data) {
                console.log("dados recebidos = " + data);
            });

            retorno.fail(function(jqXHR, textStatus, errorThrown) {
                console.log("1234 - " + jqXHR);
                console.log("error " + textStatus);
            });
            return false;
        });
    });
</script>

<div id="pesquisa_produto" style="text-align:center;">
    <form id="busca"   style="margin-bottom:6px;padding-top:10px;">
        <input type="text" id="value1" name="value">
        <input type="text" id="value2" name="value" disabled="true">
        <select id ="field" name="field">
            <option value="ProductCode" selected>Product Code</option>
            <option value="ProductDescription" >Product Description</option>
            <option value="UnitPrice">Unit Price</option>
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
        var tabela = new Sumario('searchProductsByField.php', 'getProduct.php', ['ProductCode', 'ProductDescription', 'UnitPrice'], ['ProductCode', 'ProductDescription', 'UnitPrice', 'UnitOfMeasure', 'ProductNumberCode'], false);
    </script>

    <form id="btns_mover">
        <input type="button" id="bt_prev" value="<">
        <input type="button" id="bt_next" value=">">
    </form>
</div>