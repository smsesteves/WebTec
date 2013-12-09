function gerarHTMLTabela(json, fatura) {
    if (fatura)
        return ('<div class="page"><div class="subpage"><table cellpadding="10" border="1px" width="100%">' + gerarHTMLTabelaAux(json, fatura, '', '') + '</table></div></div>');
    else
        return ('<table >' + gerarHTMLTabelaAux(json, false) + '</table>');
}

var pagecounter = 0;

function gerarHTMLTabelaAux(json, fatura, print1, print2) {
    if (fatura) {
        console.log('fatura');
        var faturahtml = '';
        var cabecalho = print1;
        var cabecalholinha = print2;

        var e;
        var se;
        for (e in json) {
            if (json.hasOwnProperty(e)) {
                if (json[e] instanceof Object ) {

                    for (se in json[e])
					{
					if(e=='Tax')
						{
							if (se == 'TaxType')
							{
							console.log('Taxas');
							cabecalho += '<table><tr><th>' + se + ':</th>' + '<td style="text-align:center;">' + json[e][se] + '</td>';
							cabecalholinha += '';
							}
							if (se == 'TaxPercentage')
							{
							cabecalho += '<th>' + se + '</th>' + '<td style="text-align:center;">' + json[e][se] + '</td></tr></table>';
							cabecalholinha += '';
							cabecalho += cabecalholinha;
							faturahtml += cabecalho;
							cabecalho = '';
							cabecalholinha = '';
							}				
						}
						else if(e=='DocumentTotal')
						{
						            if (se == 'TaxPayable')
					{console.log('cenas');
                        cabecalho += '<br><br><table style="font-size:16px;"><tr><th style>' + se + ':</th>' + '<td style="text-align:center;">' + json[e][se] + '</td>';
                        cabecalholinha += '';
                    }
                    if (se == 'NetTotal')
                    {
                        cabecalho += '<th sty>' + se + ':</th>' + '<td style="text-align:center;">' + json[e][se]+ '</td>';
                        cabecalholinha += '';

                    }
                    if (se == 'GrossTotal')
                    {
                        cabecalho += '<th>' + se + ':</th>' + '<td style="text-align:center;">' + json[e][se] + '</td></tr></table>';
                        cabecalholinha += '';
                        cabecalho += cabecalholinha;
                        faturahtml += cabecalho;

                    }
						}
						
						else{
						faturahtml += gerarHTMLTabelaAux(json[e][se], fatura, cabecalho, cabecalholinha);}
     
					}
					
                } else {
                    if (e == 'InvoiceNo') {
                        cabecalho += '<h6>P&aacute;gina 1</h6>';
                        cabecalho += '<h2>Fatura - Sistema de Fatura&ccedil;&atilde;o Online</h2><h5>Grupo 4 da Turma 2 ano 2013 || Linguagens e Tecnologias Web - LTW</h5><tr><th>' + e + '</th>';
                        cabecalholinha += '<tr><td style="text-align:center;">' + json[e] + '</td>';

                    }

                    if (e == 'InvoiceDate') {
                        cabecalho += '<th>' + e + '</th>';
                        cabecalholinha += '<td style="text-align:center;">' + json[e] + '</td>';
                    }
                    if (e == 'CustomerID') {
                        cabecalho += '<th>' + e + '</th></tr>';
                        cabecalholinha += '<td style="text-align:center;">' + json[e] + '</td></tr></table><br>';
                        cabecalho += cabecalholinha;
                        faturahtml += cabecalho;
                        cabecalho = "";
                        cabecalholinha = "";
                    }


                    if (e == 'LineNumber') {
                        if (pagecounter % 3 == 0 && pagecounter >= 3) {
                            cabecalho += '</table></div></div><div class="page"><div class="subpage">';
                            cabecalho += '<h6>P&aacute;gina ' + (pagecounter / 3 + 1) + '</h6>';

                        }
                        cabecalho += '<br><br><font style="font-size: 0.67em;font-weight: bold;line-height: 17px;">Informa&ccedil;&atilde;o produto-----------------------------------------------------------------------------' + (pagecounter + 1) + '</font><br><table border="1px" width="100%">';
                        cabecalholinha += '';
                        pagecounter += 1;
                    }
                    if (e == 'ProductCode') {
                        cabecalho += '<tr><th>' + e + '</th>';
                        cabecalholinha += '<tr><td style="text-align:center;">' + json[e] + '</td>';
                    }
                    if (e == 'Quantity') {
                        cabecalho += '<th>' + e + '</th>';
                        cabecalholinha += '<td style="text-align:center;">' + json[e] + '</td>';
                    }

                    if (e == 'UnitPrice') {
                        cabecalho += '<th>' + e + '</th>';
                        cabecalholinha += '<td style="text-align:center;">' + json[e] + '</td>';
                    }
                    if (e == 'CreditAmount') {
                        cabecalho += '<th>' + e + '</th>';
                        cabecalholinha += '<td style="text-align:center;">' + json[e] + '</td></table>';
                        cabecalho += cabecalholinha;
                        faturahtml += cabecalho;
                        cabecalho = '';
                        cabecalholinha = '';
                    }

                }
            }
        }
        return faturahtml;
    }

    else
    {
        console.log('correto');
        var html = '';
        var e;
        var se;
        var aaa = new Array();
        for (e in json) {
            if (json.hasOwnProperty(e)) {
                if (json[e] instanceof Object) {
                    console.log('eh array  = ' + e);
                    html += '<tr><th colspan="2">' + e + '</th></tr>';
                    //for (se in json[e]) {
                        html += gerarHTMLTabelaAux(json[e]);
                    //}
                } else {
                    console.log('nao eh array = ' + e);
                    html += '<tr><th>' + e + '</th><td>' + json[e] + '</td></tr>';
                }
            }
        }
        return html;
    }
}


function Sumario(pUrl, p2Url, pCampos, pCampos2, enableprint) {
    $(document).ready(function() {
        var print = enableprint;
        var campos = pCampos; // Campos do DB que serão retornados e apresentados no sumario
        var campos2 = pCampos2;
        var htmlCabecalhoTbl = gerarHTMLCabecalhoTbl(); // Codigo HTML do cabecalho da tabela do sumario
        var url = pUrl; // URL que retornara o JSON com a resposta da consulta
        var url2 = p2Url;
        var id_tb_resultados = '#tbl_resultados';
        var id_bt_next = "#bt_next";
        var id_bt_prev = "#bt_prev";
        var id_bt_buscar = "#bt_buscar";

        var paginaAtual = 0; // Pagina do sumario atualmente apresentada
        var ultimaPagina = 0; // Numero da ultima pagina de resultados 
        var nResultados = 0; // Numero de resultados que a consulta gerou
        var tPagina = 15; // Tamanho da pagina (numero de resultados exibido por pagina)
        var dados; // Dados retornados pela consulta
        var detalhes = new Array();

        function gerarHTMLCabecalhoTbl() {
            var n = campos.length;

            var ret = '<tr class="linha_cabecalho">';
            for (var i = 0; i < n; ++i) {
                ret += ('<th>' + campos[i] + '</th>');
            }
            if (enableprint)
                ret += '<th></th>';
            ret += "</tr>";

            return ret;
        }

        function ativarBtnsNextPrev() {
            if (paginaAtual === 0) {
                $(id_bt_prev).attr("disabled", "disabled");
            } else {
                $(id_bt_prev).removeAttr("disabled");
            }

            if (paginaAtual === ultimaPagina) {
                $(id_bt_next).attr("disabled", "disabled");
            } else {
                $(id_bt_next).removeAttr("disabled");
            }
        }

        function atualizarResultados() {
            ativarBtnsNextPrev();

            $(id_tb_resultados).contents().remove();
            $(id_tb_resultados).append(htmlCabecalhoTbl);

            var inicio = paginaAtual * tPagina;
            var fim = (nResultados < inicio + tPagina) ? nResultados : inicio + tPagina;
            var n = campos.length;

            for (var i = inicio; i < fim; ++i) {
                var htmlLinha = '<tr class="accordion">';

                for (var j = 0; j < n; ++j) {
                    htmlLinha += '<td>' + dados[i][campos[j]] + '</td>';
                }
                if (enableprint)
                    htmlLinha += '<td><a href="./imprimir.php?invoicenumber=' + dados[i][campos[0]] + '"><img width="32px" src="./images/printer.png" onmouseover="this.src=' + "'" + "./images/printer2.png" + "'" + '"' + 'onmouseout="this.src=' + "'" + "./images/printer.png'" + '"></a></td>';
                htmlLinha += '</tr>';

                htmlLinha += '<tr class="descricao"></tr>';

                $(id_tb_resultados).append(htmlLinha);
            }

            $(".descricao").hide();
        }

        function criarHTMLDetalhes(detalhesLinha) {
            if (print)
                return ('<td colspan="' + (campos.length + 1) + '" style="background-color:#6f6f6f"><table>' + gerarHTMLTabela(detalhesLinha) + '</table></td>');
            else
                return ('<td colspan="' + campos.length + '" style="background-color:#6f6f6f"><table>' + gerarHTMLTabela(detalhesLinha, false) + '</table></td>');
        }

        $("#tbl_resultados").on('click', 'tr', function(e) {
            var linha = $(this).index() - 1;
            if (linha >= 0 && linha % 2 === 0) {
                var linha = Math.floor(linha / 2);
                var instancia = paginaAtual * tPagina + linha;

                var abc = $(this).next('.descricao');

                if (typeof(detalhes[instancia]) == 'undefined') {
                    var jqxhr = $.getJSON("api/" + url2 + "?" + campos2[0] + "=" + dados[instancia][campos[0]]);

                    jqxhr.done(function(data) {
                        detalhes[instancia] = data;
                        console.log(detalhes[instancia]);

                        abc.contents().remove();
                        //var html2 = gerarHTMLTabela(detalhes[instancia]);
                        var html2 = criarHTMLDetalhes(detalhes[instancia]);
                        //console.log(gerarHTMLTabela(detalhes[instancia]));
                        console.log(html2);
                        abc.append(html2);
                        abc.fadeToggle(500);
                    });

                    jqxhr.fail(function(data, textStatus, errorThrown) {
                        console.log("error " + textStatus);
                    });
                } else {
                    abc.contents().remove();
                    abc.append(criarHTMLDetalhes(detalhes[instancia]));
                    abc.fadeToggle(500);
                }
            }
        });

        /*       function enable_accord() {
         $(".tbl_accor tr:not(.accordion)").hide();
         //$(".tbl_accor tr:first-child").show();
         var n2 = campos2.length;
         var str = ".tbl_accor tr:first-child";
         
         $(str).show();
         for (var i = 0; i < n2; ++i) {
         str += "+tr";
         $(str).show();
         }
         
         $(".tbl_accor tr.accordion").click(function() {
         var nInstancia = $(this).index() - 1;
         if (nInstancia >= 0 && nInstancia % 2 === 1) {
         alert('clicou na linha: ' + nInstancia);
         
         console.log("op = " + $("#op").val());
         
         var nLinha = Math.floor(nInstancia / 2);
         
         // Busca os resultados da pesquisa
         var jqxhr = $.getJSON("api/" + url2 + "?" + campos2[0] + "=" + dados[Math.floor(nInstancia / 2)][0]);
         
         jqxhr.done(function(data) {
         detalhes = data;
         });
         
         jqxhr.fail(function(jqXHR, textStatus, errorThrown) {
         console.log("error " + textStatus);
         });
         atualizarResultados();
         }
         $(this).next("tr").fadeToggle(500);
         });
         }
         */
        $("#op").change(function() {
            var hiddenelement = $('#value2');

            if (($(this).find(":selected").attr('value')) === 'range') {
                hiddenelement.prop('disabled', false);
            } else {
                hiddenelement.prop('disabled', true);
                hiddenelement.prop('value', "");
            }
        });

        $(id_bt_next).click(function() {
            if (paginaAtual < ultimaPagina) {
                ++paginaAtual;
            }

            atualizarResultados();
        });

        $(id_bt_prev).click(function() {
            if (paginaAtual > 0) {
                --paginaAtual;
            }
            atualizarResultados();
        });

        $(id_bt_buscar).click(function() {
            // cria a URL
            //jQuery.ajaxSettings.traditional = true;
            detalhes = new Array();
            var params = {};
            console.log("op = " + $("#op").val());

            params = {value: [$("#value1").val(), $("#value2").val()], op: $("#op").val(), field: $("#field").val()};

            // Busca os resultados da pesquisa
            var jqxhr = $.getJSON("api/" + url + "?" + decodeURIComponent($.param(params)));

            jqxhr.done(function(data) {
                paginaAtual = 0;
                ultimaPagina = Math.floor(data.length / tPagina);
                nResultados = data.length;
                dados = data;

                atualizarResultados();
            });

            jqxhr.fail(function(jqXHR, textStatus, errorThrown) {
                console.log("error " + textStatus);
            });
        });

        ativarBtnsNextPrev();

    });
}