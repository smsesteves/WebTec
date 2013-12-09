function limparFormulario(idFormulario){
    var formulario = document.getElementById(idFormulario);

    for (var i = 0; i < formulario.elements.length; i++){
        if ('submit' != formulario.elements[i].type && 'reset' != formulario.elements[i].type){
            formulario.elements[i].checked = false;
            formulario.elements[i].value = '';
            formulario.elements[i].selectedIndex = 0;
        }
    }
}

function formSuccess(string){

var idValue = '';

if(string == 'Produto')
	idValue = '' + document.getElementById("ProductDescription").value;

if(string == 'Cliente')
	idValue = '' + document.getElementById("CustomerID").value;	

if(string == 'Utilizador')
        idValue = '' + document.getElementById("username").value; 
	
var string = string + ' ' + idValue + ' adicionado/atualizado com sucesso!';
return setTimeout(function () { window.location.reload();}, 10) && alert(string);
}

