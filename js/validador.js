$(document).ready(function() {
    $('.requerido').change(function() {
        validarCampo($(this));
    });
    
    $('.requerido').blur(function() {
        validarCampo($(this));
    });

    $('form').submit(function() {
        
        var seguir = true;
        var lista = $('#'+$(this).attr('id')+' .requerido');
        
        $.each(lista, function(clave, valor) {
            if (validarCampo(valor)) {
                seguir = false;
            }
        });

        if (seguir) {
            return true;
        }else{
            if($('#mensajeError').length > 0){
                $('#mensajeError').css('opacity', '1.0');
            }
            return false;
        }
    });
});

function validarCampo(campo) {
    var error = false;
    switch ($(campo).attr('name')) {
        case "nombre":
        case "apellidos":
        case "apellido":
        case "ciudad":
        case "direccion":
        case "universidad":
            if (trim($(campo).val()).length < 3) {
                error = true;
            } else {
                error = false;
            }
            break;
        
        case "clave":
            if (trim($(campo).val()).length < 6) {
                error = true;
            } else {
                error = false;
            }
            break;
            
        case "titulo":
            if (trim($(campo).val()).length < 5 || trim($(campo).val()).length > 90) {
                error = true;
            } else {
                error = false;
            }
            break;
        
        case "email":
            if (!trim($(campo).val()).match("^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,3})$")) {
                error = true;
            } else {
                error = false;
            }
            break;

        case "telefono":
        case "movil":
            if (trim($(campo).val()).length == 9 && !isNaN(trim($(campo).val()))) {
                error = false;
            } else {
                error = true;
            }
            break;

        case "comentarios":
            if (trim($(campo).val()).length < 8) {
                error = true;
            } else {
                error = false;
            }
            break;

        case "tiempo":
            if (isNaN(trim($(campo).val())) || $(campo).val() < 0) {
                error = true;
            } else {
                error = false;
            }
            break;

        case "manual":
        case "interes":
        case "tiempo_curso":
        case "provincia":
            if ($(campo).val() == "0") {
                error = true;
            } else {
                error = false;
            }
            break;
            
        case "dni":
            if ($(campo).val() == "" || compruebaNif($(campo).val().toUpperCase())){
                error = false;
            } else {
                error = true;
            }
            break;
            
        case "nif":
            if (compruebaNif($(campo).val().toUpperCase())){
                error = false;
            } else {
                error = true;
            }
            break;
            
        case "chkpolitica":
            if($(campo).attr("checked") == "checked"){
                error = false;
            }else{
                error = true;
            }
            break;
    }

    if (error) {
        $(campo).css('border', '1px solid #ff0000');
        if($(campo).attr('name') == 'politica'){
            $('enlacemini').css('border', '1px solid #ff0000');
        }
    } else {
        $(campo).css('border', '1px solid #00ff00');
        if($(campo).attr('name') == 'politica'){
            $('enlacemini').css('border', '1px solid #00ff00');
        }
    }

    return error;
}

function trim(myString) {
    return myString.replace(/^\s+/g, '').replace(/\s+$/g, '')
}

function compruebaNif(dni){
    var numero;
    var let;
    var letra;
    var expresion_regular_dni;
    	 
    expresion_regular_dni = /^([X]|\d)\d{7}[A-Z]$/;
	 
    if(expresion_regular_dni.test(dni)){
        if(dni.substr(0,1) == "X"){
            numero = dni.substr(1,dni.length-2);
        }else{
            numero = dni.substr(0,dni.length-1);
        }
        let = dni.substr(dni.length-1,1);
        numero = numero % 23;
        letra='TRWAGMYFPDXBNJZSQVHLCKET';
        letra=letra.substring(numero,numero+1);
        if (letra!=let){
            return false;
        }else{
            return true;
        }
    }else{
        return false;
    }
}