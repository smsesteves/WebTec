<?php

session_start();

$db = new PDO('sqlite:db/db_t1.db');

$adm = (isset($_POST['admin']) && $_SESSION['role_id'] == 0);

$usuario = ($adm) ? $_POST['username'] : $_SESSION['username'];

$sql = "SELECT password FROM users WHERE username = '" . $usuario . "';";

$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();


if ($result != null) {
    if ((!$adm && $result['password'] == md5($_POST['senha_atual'])) || $adm) {

        $sql2 = "UPDATE users SET password='" . md5($_POST['senha_nova']) . "' WHERE username='" . $_SESSION['username'] . "';";

        $stmt2 = $db->prepare($sql2);
        $stmt2->execute();
    }
} else {
    $_SESSION['erro_senha_atual'] = true;
}

if ($adm) {
    header("Location:index.php?pagina=admin");
}
else{
    header("Location:index.php?pagina=painel_usuario");
}
?>
