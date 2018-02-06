$(document).ready(function(){
    
    $('#frmregistrar input[type="button"]').click(function(){
        var seguir = true;
        var lista = $('#frmregistrar .requerido');
        
        $.each(lista, function(clave, valor) {
            if (validarCampo(valor)) {
                seguir = false;
            }
        });

        if (seguir) {
            $('#llamarRegistro').click();
        }
    });
    
    $('.emergente').magnificPopup({
        type: 'inline',
        alignTop: true,
        closeOnContentClick: false,
        modal: true
    });
    
    $('.cerrarVentana').click(function(e) {
        e.preventDefault();
	$.magnificPopup.close();
    });
    
});

function registrar(){
    $('#confirmaRegistro input[name="nombre"]').val($('#frmregistrar input[name="nombre"]').val());
    $('#confirmaRegistro input[name="apellidos"]').val($('#frmregistrar input[name="apellidos"]').val());
    $('#confirmaRegistro input[name="email"]').val($('#frmregistrar input[name="email"]').val());
    $('#confirmaRegistro input[name="clave"]').val($('#frmregistrar input[name="clave"]').val());
    $('#confirmaRegistro input[name="interes"]').val($('#frmregistrar select[name="interes"]').val());
    $('#confirmaRegistro').submit();
}