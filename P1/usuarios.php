<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>

$(document).ready(function() {

    var usernaoexiste = true;
    var string_aux = '';
    var userNames;

    var getUsernames = $.getJSON("api/getUsernames.php");
        getUsernames.done(function(data) {
            console.log("json usernames = " + JSON.stringify(data));

            userNames = data;


        });
        getUsernames.fail(function(data, textStatus, errorThrown) {
            console.log("error " + textStatus);
        });

    function validacao() {
        var novaSenha = $('#nova_senha');
        var novaSenhaConf = $('#nova_senha2');

        //novaSenha.removeClass("error");
        //novaSenhaConf.removeClass("error");


            console.log("teste1234566");
            if (novaSenha.val() != novaSenhaConf.val()) {
                novaSenha.addClass("error");
                novaSenhaConf.addClass("error");
                alert("Novas são diferentes!!!");
                return false;
            } 


        var usernovo = $('#username');


        if($.inArray(usernovo.val(), userNames)){
            alert("User já existente!");
            usernovo.addClass("error");
            return false;
        } 

            alert('Utilizador inserido com sucesso!');
            return true;
        


    }
        $('#editar_form').submit(function() {
             return (validacao());
        });

});
    
</script>




<?php



function getUsuarios() {
    $db = new PDO('sqlite:db/db_t1.db');

    $sql = "select u.id, u.username, ur.roles_desc, u.password from users u, user_roles ur where ur.id = u.role_id;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    echo '<br><h3 style="text-align:left;">  Lista de utilizadores</h3><br>';
    echo "<table id='tbl_editarperfil'> <tr> <th>ID</th> <th>Username</th> <th>Tipo</th> <th style='visibility:hidden;'>Editar</th> <th style='visibility:hidden;'>Remover</th></tr>";
    while ($row = $stmt->fetch()) {

        echo "<tr style='height: 25px;'> <td style='padding-left: 10px;'>" . $row['id'] . "</td> <td style='padding-left: 10px;'>" . $row['username'] . "</td> <td style='padding-left: 10px;'>" . $row['roles_desc'] . "</td> <td style='padding-left: 10px;text-align:right;'> <button id='bt_editar' onclick=\" location.href='index.php?pagina=editar_usuario&user=" . $row['username'] . "' \">Editar</button>  </td> <td style='padding-left: 10px;'> <button id='bt_remover' onclick=\" location.href='remover.php?id=" . $row['id'] . "' \">Remover</button>  </td></tr>";
    }

    echo "</table>";
}

function cadastrarUsuário() {
    
}
?>

<script src="funcoesFormulario.js"></script>
<div id="editar_perfil">
    <br><h3 style="text-align:left;">  Adicionar utilizador</h3><br>
    <form id="editar_form" action="cadastrar.php" method="post" >
        <table id="tbl_editarperfil">
            <tr><th>Username: </th> <td><input type="text" id="username"  required  name="username"></td></tr>
            <tr><th>Senha: </th> <td> <input type="password" id="nova_senha" name="senha"  required  pattern=".{6,20}$" oninvalid="this.setCustomValidity('A password tem de ter entre 6 e 20 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><th>Confirmar senha: </th> <td> <input type="password"  id="nova_senha2" required name="senha" pattern=".{6,20}$" oninvalid="this.setCustomValidity('A password tem de ter entre 6 e 20 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><th>Nome: </th> <td><input type="text"  required  name="nome"></td></tr>
            <tr><th>Email: </th> <td><input type="email"  required  name="email"></td></tr>
            <tr><th>Morada: </th> <td><input type="text"  required  name="morada"></td></tr>
            <tr><th>Contacto: </th> <td><input type="text"  required  name="contacto"></td></tr>
            <tr><td><input id="bt_limpar" name="reset" type="reset"  value="Limpar" onclick="limparFormulario('editar_form');
                    return false;" /></td> <td>  <input type="submit" class="bt_atualizar" id="bt_atualizar" value="Adicionar"></td></tr>
        </table>
    </form>
    <br>

    <?php 
    getUsuarios();
    ?>
</div>

