<?php
ob_start(); // bufferiza a mensagem
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
        <title>Sistema de Faturação Online FEUP</title>


        <link rel="stylesheet" href="geral.css">
        <link rel="icon" 
      type="image/ico" 
      href="/images/favicon.ico">
    </head>
    <body>
        <div id="bodywrap">
            <?php
            include 'header.php';
            include 'menu.php';

            // carrega o conteúdo (se houver)
            $paginas['home'] = 'home.php';
            $paginas['produtos'] = 'pesquisa_produto.php';
            $paginas['clientes'] = 'pesquisa_cliente.php';
            $paginas['faturas'] = 'faturas.php';
            $paginas['contato'] = 'contato.php';
            $paginas['painel_usuario'] = 'painel_usuario.php';
            $paginas['editar_usuario'] = 'painel_editar_usuario_adm.php';
            $paginas['admin'] = 'usuarios.php';
            $paginas['exportar'] = 'createSAFT.php';



            if(isset($_GET['pagina']) && isset($paginas[$_GET['pagina']]) && $_GET['pagina']=='exportar'){
                header("Location:createSAFT.php");
            }
            
            if (empty($_GET))
                include 'home.php';
                else
                {
            
            
            if(isset($paginas[$_GET['pagina']])){
                include $paginas[$_GET['pagina']];
            }
            }
            
            include 'footer.php';
            ?>
        </div>
    </body>
</html>
