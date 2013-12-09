<?php
//session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="Sumario.js"></script>
<script src="funcoesFormulario.js"></script>

<script>
    $(document).ready(function() {

        var customersData;

        var getCustomersData = $.getJSON("api/getCustomerID.php");
        getCustomersData.done(function(data) {
            console.log("json clientes = " + JSON.stringify(data));

            customersData = data;

            for (abc in customersData) {
                console.log(">>> " + customersData[abc]);
            }
        });

        function carregarCustomer(n) {
            console.log("Customer = " + n);


            var customer = $.getJSON("api/getCustomer.php?CustomerID=" + n);
            customer.done(function(data) {
                //$("#tabela").remove(".dinamico");

                console.log("json customer = " + JSON.stringify(data));


                $("#AccountID").val(data["AccountID"]);
                $("#CustomerTaxID").val(data["CustomerTaxID"]);
                $("#CompanyName").val(data["CompanyName"]);
                $("#AddressDetail").val(data.BillingAdress["AddressDetail"]);
                $("#City").val(data.BillingAdress["City"]);
                $("#PostalCode").val(data.BillingAdress["PostalCode"]);
                $("#Country").val(data.BillingAdress["Country"]);
                $("#Email").val(data["Email"]);
            });
        }

        function limparLines() {
            $(".dinamico").remove();
        }

        $("#bt_remover").click(function() {
            if (customersData.indexOf($("#CustomerID").val()) !== -1) {
                var remove = $.getJSON("api/removeCustomer.php?CustomerID=" + $("#CustomerID").val());
                remove.done(function(data) {
                    
                });
            }
            limparFormulario('editar_form');

            false;
        });

        $("#CustomerID").blur(function() {
            limparLines();
            if (customersData.indexOf($("#CustomerID").val()) !== -1) {
                carregarCustomer($("#CustomerID").val());
            } else {
                limparFormulario('editar_form');
            }
        });

        function gerarJSON() {
            var jsonCustomer = {
                "CustomerID": $("#CustomerID").val(),
                "AccountID": $("#AccountID").val(),
                "CustomerTaxID": $("#CustomerTaxID").val(),
                "CompanyName": $("#CompanyName").val(),
                "AddressDetail": $("#AddressDetail").val(),
                "City": $("#City").val(),
                "PostalCode": $("#PostalCode").val(),
                "Country": $("#Country").val(),
                "Email": $("#Email").val()
            };


            alert("json gerado === " + JSON.stringify(jsonCustomer));
        }

    });
</script>


<?php if ($_SESSION['role_id'] == 0 || $_SESSION['role_id'] == 1) { ?>
    <div id="pesquisa_produto" style="text-align:center;">
        <br><h3 style="text-align:left;">  Gerir Clientes</h3><br>
        
            <table id="tbl_editarperfil">
            <form id="editar_form" onsubmit="formSuccess('Cliente')">
                <tr><th>CustomerID: </th> <td><input type="text" id="CustomerID" pattern=".{1,30}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 30 carateres.')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>AccountID: </th> <td> <input type="text" required id="AccountID" pattern="(([0-9a-zA-Z\-/._+*]*)|Desconhecido){1,30}" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 30 carateres.')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>CustomerTaxID: </th> <td> <input type="text" required id="CustomerTaxID" pattern=".{1,20}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 20 carateres.')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>CompanyName: </th> <td><input type="text" required id="CompanyName" pattern=".{1,100}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 100 carateres.')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>AddressDetail: </th> <td><input type="text" required id="AddressDetail" pattern=".{1,100}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 100 carateres.')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>City: </th> <td><input type="text" required id="City"  pattern=".{1,50}$" oninvalid="this.setCustomValidity('Preencha este campo com um máximo de 50 carateres.')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>PostalCode: </th> <td><input type="text"  id="PostalCode" pattern="([0-9]{4}-[0-9]{3})" oninvalid="this.setCustomValidity('Código postal inválido. Ex: 1111-111')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>Country: </th> <td><input type="text"  id="Country" pattern="AD|AE|AF|AG|AI|AL|AM|AO|AQ|AR|AS|AT|AU|AW|AX|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BJ|BL|BM|BN|BO|BQ|BR|BS|BT|BV|BW|BY|BZ|CA|CC|CD|CF|CG|CH|CI|CK|CL|CM|CN|CO|CR|CU|CV|CW|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EE|EG|EH|ER|ES|ET|FI|FJ|FK|FM|FO|FR|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GP|GQ|GR|GS|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|IL|IM|IN|IO|IQ|IR|IS|IT|JE|JM|JO|JP|KE|KG|KH|KI|KM|KN|KP|KR|KW|KY|KZ|LA|LB|LC|LI|LK|LR|LS|LT|LU|LV|LY|MA|MC|MD|ME|MF|MG|MH|MK|ML|MM|MN|MO|MP|MQ|MR|MS|MT|MU|MV|MW|MX|MY|MZ|NA|NC|NE|NF|NG|NI|NL|NO|NP|NR|NU|NZ|OM|PA|PE|PF|PG|PH|PK|PL|PM|PN|PR|PS|PT|PW|PY|QA|RE|RO|RS|RU|RW|SA|SB|SC|SD|SE|SG|SH|SI|SJ|SK|SL|SM|SN|SO|SR|SS|ST|SV|SX|SY|SZ|TC|TD|TF|TG|TH|TJ|TK|TL|TM|TN|TO|TR|TT|TV|TW|TZ|UA|UG|UM|US|UY|UZ|VA|VC|VE|VG|VI|VN|VU|WF|WS|XK|YE|YT|ZA|ZM|ZW|Desconhecido|" oninvalid="this.setCustomValidity('Input para país inválido. Utilize apenas 2 caracteres. Ex: PT')" oninput="setCustomValidity('')"></td></tr>
                <tr><th>Email: </th> <td><input type="email" id="Email" pattern=".{1,60}$"></td></tr>
                <tr><td></td> <td>  <input type="submit" class="bt_atualizar" id="bt_atualizar" value="Inserir | Editar"></td></tr>
            </form>
            <tr><td><button id="bt_limpar" name="reset" type="reset" onclick="limparFormulario('editar_form')" />Limpar</button> <button class="bt_remover" id="bt_remover" onClick="false;">Remover</button></td></tr>
            </table>
        <br>
    </div>
<?php } ?>


<script>
    $(document).ready(function() {
        $("#editar_form").submit(function() {
            var json2 = {'CustomerID': $('#CustomerID').val(), 'AccountID': $('#AccountID').val(), 'CustomerTaxID': $('#CustomerTaxID').val(), 'CompanyName': $('#CompanyName').val(), 'AddressDetail': $('#AddressDetail').val(), 'City': $('#City').val(), 'PostalCode': $('#PostalCode').val(), 'Country': $('#Country').val(), 'Email': $('#Email').val()};

            console.log(json2);
            console.log(JSON.stringify(json2));
            var retorno = $.ajax({
                type: 'POST',
                url: 'api/updateCustomer.php',
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
    <form id="busca"  style="margin-bottom:6px;padding-top:10px;">
        <input type="text" id="value1" name="value">
        <input type="text" id="value2" name="value" disabled="true">
        <select id ="field" name="field">
            <option value="CustomerID" selected>Customer ID</option>
            <option value="CustomerTaxID">Customer Tax ID</option>
            <option value="CompanyName">Company Name</option>


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
        var sumario = new Sumario('searchCustomersByField.php', 'getCustomer.php',
                ['CustomerID', 'CustomerTaxID', 'CompanyName'], ['CustomerID', 'CustomerTaxID', 'CompanyName', 'AddressDetail', 'City', 'PostalCode', 'Country', 'SelfBillingIndicator', 'Email'], false);
    </script>

    <form id="btns_mover">
        <input type="button" id="bt_prev" value="<">
        <input type="button" id="bt_next" value=">">
    </form>
</div>
