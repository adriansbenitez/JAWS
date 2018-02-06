$(document).ready(function() {
    $('#buscaPreguntas').click(function() {

        var cadena = "";
        $('.borraPregunta').each(function(ind, ele) {
            cadena += $(ele).attr('rel') + ",";
        });

        cadena += "0";

        var campo1 = $('#txt_codigo').attr('value');
        var campo2 = $('#txt_en_pregunta').attr('value');
        var campo3 = $('#txt_en_respuesta').attr('value');

        $.post('ajax/buscaPreguntas.php', {'campo1': campo1, 'campo2': campo2, 'campo3': campo3, 'preguntas' : cadena},
            function(res) {
                $('#listaPreguntas').html(res);
            }
        );

    });

    $('.botonEnviar').click(function() {
        var cadena = "";
        $('.borraPregunta').each(function(ind, ele) {
            cadena += $(ele).attr('rel') + ",";
        });

        cadena = cadena.substring(0, cadena.length - 1);

        $('#preguntasExamen').attr('value', cadena);

        cadena = ""
        $('.borraAlumno').each(function(indi, ele) {
            cadena += $(ele).attr('rel') + ",";
        });

        cadena = cadena.substring(0, cadena.length - 1);

        $('#listaAlumnos').attr('value', cadena);

        $('#frmcrearexamen').submit();
    });

    $('#lstperfiles').change(function() {
        $("#alumnosElegidos").append('<div class="alumnoAgregado" id="alumnoAgregado_'+$('#lstperfiles').val()+'"><div class="ubicaTema">'+$('#lstperfiles>option:selected').text() +'</div><div class="borraAlumno" rel="' + $('#lstperfiles').val()+'"'+'onClick="quitarAlumno(\'#alumnoAgregado_' + $('#lstperfiles').val() + '\')"><img src="css/icos/admindelete.png" /></div></div>');
    });

});

function agrega(control) {
    if (control.attr('estado') == "0") {

        $("#preguntasElegidas").append('<div class="preguntaAgregada" id="preguntaAgregada_' + control.attr('rel3') + '"><div class="ubicaTema">' + control.attr('rel1') + '<br/>' + control.attr('rel5') + '<br/></div><div class="borraPregunta" rel="' + control.attr('rel3') + '@'+control.attr('rel4')+'" ' +'onClick="quitarPregunta(\'#preguntaAgregada_' + control.attr('rel3') + '\')"><img src="css/icos/admindelete.png" /></div></div>');
        $('[rel3='+control.attr('rel3') +']').html('<img src="css/icos/correcionokmin.png"/>');
        control.attr('estado', "1");

        var cuentaPreguntas = parseInt($('#cuentaPreguntas').attr('rel'));
        cuentaPreguntas++;
        $('#cuentaPreguntas').attr('rel', cuentaPreguntas);
        $('#cuentaPreguntas').html(cuentaPreguntas);
    }
}

function quitarPregunta(aQuitar) {
    var cad = aQuitar.split("_");

    $('.preguntaAgregable').each(function(ind, ele) {
        if ($(ele).attr('rel3') == cad[1]) {
            $(ele).html('<img src="css/icos/adminadd.png"/>')
            $(ele).attr('estado', "0");
        }
    });
    $(aQuitar).remove();

    var cuentaPreguntas = parseInt($('#cuentaPreguntas').attr('rel'));
    cuentaPreguntas--;
    $('#cuentaPreguntas').attr('rel', cuentaPreguntas);
    $('#cuentaPreguntas').html(cuentaPreguntas);
}

function quitarAlumno(aQuitar) {
    $(aQuitar).remove();
}