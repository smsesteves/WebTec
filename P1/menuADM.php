<?php
session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == TRUE && $_SESSION['role_id'] == 0)) {
    header('Location:index.php');
}
?>

<div id="menu">
    <ul>
        <li><a href="adicionarUsuario.php">Adicionar Usuário</a></li>
        <li><a href="editarUsuario.php">Editar Usuário</a></li>
        <li><a href="removerUsuario.php">Remover Usuário</a></li>
        <li><a href="index.php">Site</a></li>
        <li><?php include 'painel_logout.php'; ?></li>
    </ul>
</div>
