<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
        <title>Painel de Administrador - Sistema de Faturação Online FEUP</title>


        <link rel="stylesheet" href="geral.css">
    </head>
    <body>
        <div id="bodywrap">
            <?php
            include 'headerADM.php';
            include 'menuADM.php';

            // carrega o conteúdo (se houver)
            $paginas['home'] = 'home.php';
            $paginas['produtos'] = 'pesquisa_produto.php';
            $paginas['clientes'] = 'pesquisa_cliente.php';
            $paginas['faturas'] = 'faturas.php';
            $paginas['contato'] = 'contato.php';
            $paginas['admin'] = 'admin.php';





            if (empty($_GET))
                include 'home.php';
            else {
                if (isset($paginas[$_GET['pagina']])) {
                    include $paginas[$_GET['pagina']];
                }
            }

            //include 'footer.php';
            ?></div>
    </body>
</html>
