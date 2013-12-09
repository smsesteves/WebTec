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
