function Tabela(p_url,
        p_cabecalho, p_op
        ) {
    $(document).ready(function() {

        console.log(p_url);
        var cabecalho = p_cabecalho;

        var html_cabecalho = "<tr>";
        for (var i = 0; i < cabecalho.length; ++i) {
            html_cabecalho += ('<th>' + cabecalho[i] + '</th>');
        }
        html_cabecalho += "</tr>";
        
        var url = p_url;
        var id_tb_resultados = '#tbl_resultados';
        var id_bt_next = "#bt_next";
        var id_bt_prev = "#bt_prev";
        var id_bt_buscar = "#bt_buscar";
        var page = 0;
        var maxPage = 0;
        var nResultados = 0;
        var tPage = 15;
        var dados;
        $(id_bt_next).click(function() {
            if (page < maxPage) {
                ++page;
            }
            atualizar_resultados();
        });
        $(id_bt_prev).click(function() {
            if (page > 0) {
                --page;
            }
            atualizar_resultados();
        });

        $(id_bt_buscar).click(function() {
            // cria a URL
            jQuery.ajaxSettings.traditional = true;
            var params = {};
            if ($("#op").val() === "range") {
                console.log("op = " + $("#op").val());

                params = {value: [$("#value1").val(), $("#value2").val()], op: $("#op").val(), field: $("#field").val()};
            } else {
                params = {value: $("#value1").val(), op: $("#op").val(), field: $("#field").val()};
            }

            // Busca os resultados da pesquisa
            var jqxhr = $.getJSON("api/" + url + "?" + $.param(params));
            jqxhr.fail(function(jqXHR, textStatus, errorThrown) {
                console.log("error " + textStatus);
                console.log("error2 " + errorThrown);
                console.log("incoming Text " + jqXHR.responseText);
                $('#resultado').append("<p>" + jqXHR.responseText + "</p>");
            });
            jqxhr.done(function(data) {
                page = 0;
                maxPage = Math.floor(data.length / tPage);
                nResultados = data.length;
                dados = data;

                atualizar_resultados();
            });
        });

        ativar_next_prev();




        function ativar_next_prev() {
            if (page === 0) {
                $(id_bt_prev).attr("disabled", "disabled");
            } else {
                $(id_bt_prev).removeAttr("disabled");
            }

            if (page === maxPage) {
                $(id_bt_next).attr("disabled", "disabled");
            } else {
                $(id_bt_next).removeAttr("disabled");
            }

        }
        function atualizar_resultados() {
            ativar_next_prev();

            $(id_tb_resultados).contents().remove();
            $(id_tb_resultados).append(html_cabecalho);

            var inicio = page * tPage;
            var fim = (nResultados < inicio + tPage) ? nResultados : inicio + tPage;

            for (var i = inicio; i < fim; ++i) {
                if(p_op ==0) //PRODUTOS
                {
                    $(id_tb_resultados).append(
                        '<tr><td>' + dados[i].ProductCode +
                        '</td><td>' + dados[i].ProductDescription +
                        '</td><td>' + dados[i].UnitPrice +
                        '</td><td>' + dados[i].UnitMeasure + '</td><td>');    
                }
                if(p_op ==1) //Clientes
                {
                    $(id_tb_resultados).append(
                        '<tr><td>' + dados[i].CustomerID +
                        '</td><td>' + dados[i].CustomerTaxID +
                        '</td><td>' + dados[i].AddressDetail +
                        '</td><td>' + dados[i].City +
                        '</td><td>' + dados[i].PostalCode +
                        '</td><td>' + dados[i].Country + 
                        '</td><td>' + dados[i].Email + 
                        '</td><td>');    
                }
            }

        }
    });
}
