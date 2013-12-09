<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="Sumario.js"></script>
<link rel="stylesheet" href="printstyle.css">




<link rel="stylesheet" href="printstyle.css">

<div id="book" class="book">



    <script>
        var numinvoice = <?php echo $_GET['invoicenumber']; ?>;

        $(document).ready(function() {
            var dados;
            //var id_tb_faturas = '#tbl_resultados';
            var jqxhr = $.getJSON('api/getInvoice.php?InvoiceNo=' + numinvoice);

            jqxhr.done(function(data) {
                //dados=data;

                $("#book").append(gerarHTMLTabela(data, true));
            });


        });
    </script>




</div>