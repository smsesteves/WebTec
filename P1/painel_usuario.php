<?php


if (isset($_GET['user'])) {
    $currentuser = $_GET['user'];
} else {
    $session = $_SESSION['username'];
    $currentuser = $session;
}

$db = new PDO('sqlite:db/db_t1.db');
$sql = "SELECT * FROM users WHERE username='$currentuser'";

$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

$roleid = 0;
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="funcoesFormulario.js"></script>

<script>
    $(document).ready(function() {
        var novaSenha = $('#nova_senha');
        var novaSenhaConf = $('#nova_senha2');

        //novaSenha.removeClass("error");
        //novaSenhaConf.removeClass("error");

        function validarSenhas() {
            console.log("teste1234566");
            if (novaSenha.val() != novaSenhaConf.val()) {
                novaSenha.addClass("error");
                novaSenhaConf.addClass("error");
                
                return false;
            }   
            return true;
        }

        $('#editar_senha_form').submit(function() {
             return validarSenhas();
        });
    });
</script>


<div  id="editar_perfil" ><br>
    <h3 style="text-align:left;">  Modificar Senha</h3><br>
    <?php 
        if(isset($_SESSION['erro_senha_atual']) && $_SESSION['erro_senha_atual'] == true){
            echo "senha atual incorreta!!!";
            unset($_SESSION['erro_senha_atual']);
        }
    ?>
    <form id="editar_senha_form" action="atualizar_senha_usuario.php" method="post">
        <table id="tbl_editarperfil">
            <tr><th>Senha:</th> <td><input id="senha_atual" type="password" name="senha_atual"></td></tr>
            <tr><th>Nova senha:</th> <td><input id="nova_senha" type="password" name="senha_nova" pattern=".{6,20}$" oninvalid="this.setCustomValidity('A password tem de ter entre 6 e 20 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><th>Confirmar nova senha: </th> <td><input id="nova_senha2" type="password" name="senha_nova_conf" pattern=".{6,20}$" oninvalid="this.setCustomValidity('A password tem de ter entre 6 e 20 carateres.')" oninput="setCustomValidity('')"></td></tr>
            <tr><td></td> <td><input type="submit" class="bt_atualizar" id="bt_atualizar" value="Modificar"></td></tr>
        </table>
    </form>

    <br>
    <h3 style="text-align:left;">  Editar Informações</h3><br>
    <form id="editar_info_form" action="atualizar_usuario.php" method="post">
        <table id="tbl_editarperfil">
            <tr><th>Username: </th> <td><input type="text" id="username"  name="username" value="<?php echo $result['username']; ?>" readonly></td></tr>
            <tr><th>Nome: </th> <td><input id="nome" type="text" name="nome" value="<?php echo $result['nome']; ?>"></td></tr>
            <tr><th>Email: </th> <td><input id="email" type="email" name="email" value=<?php echo $result['email']; ?>></td></tr>
            <tr><th>Morada: </th> <td><input id="morada" type="text" name="morada" value="<?php echo $result['morada']; ?>"></td></tr>
            <tr><th>Contacto: </th> <td><input id="contacto" type="text" name="contacto" value=<?php echo $result['contacto']; ?>></td></tr>
            <tr><td><input id="bt_limpar" name="reset" type="reset" value="Limpar" onclick="limparFormulario('editar_info_form');
        return false;" /></td> <td>  <input type="submit" class="bt_atualizar" id="bt_atualizar" value="Editar"></td></tr>
        </table>
    </form>
</div>