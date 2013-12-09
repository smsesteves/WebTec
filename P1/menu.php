<?php 
session_start();
?>

<div id="menu">
    <ul>
        <li><a href="index.php">Home</a></li>

        <?php  if (isset($_SESSION['login']) && $_SESSION['login'] == TRUE) { ?>

            <li><a href="index.php?pagina=clientes">Clientes</a></li>
            <li><a href="index.php?pagina=produtos">Produtos</a></li>
            <li><a href="index.php?pagina=faturas">Faturas</a></li>
            <?php  if ($_SESSION['role_id'] == 0) { ?>
                <li><a href="index.php?pagina=admin">Utilizadores</a></li>
            <?php  } ?>
			<li><a href="index.php?pagina=exportar">Exportar</a></li>
			<li><a href="index.php?pagina=importar">Importar</a></li>
            <li><a href="index.php?pagina=contato">Contato</a></li>
            
            <li><?php  include 'painel_logout.php'; ?></li>

        <?php  } else {
            ?>
            <li><a href="index.php?pagina=contato">Contato</a></li>
            <li><?php 
            include 'login.php';
        }
        ?></li>
    </ul>
</div>
