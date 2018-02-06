$(document).ready(function() {
    $('.botonEnviar').click(function() {

        var listaCursos = "";
        var listaTemas = "";
        var listaUnidades = "";
        var totalPreguntas = 0;
        $(".check-curso").each(function() {
            if ($(this).attr("checked")) {
                listaCursos += $(this).attr("value");
                totalPreguntas += parseInt($(this).attr("preg"));
            }
        });

        if (listaCursos != "") {
            $("#arrCursos").attr('value', listaCursos);
            validar(totalPreguntas);
        } else {
            $(".check-tema").each(function() {
                if ($(this).attr("checked")) {
                    listaTemas += $(this).attr("value") + ",";
                    totalPreguntas += parseInt($(this).attr("preg"));
                }
            });

            if (listaTemas != "") {
                listaTemas = listaTemas.substring(0, listaTemas.length - 1);
                $("#arrTemas").val(listaTemas);
                validar(totalPreguntas);
            } else {
                $(".check-unidad").each(function() {
                    if ($(this).attr("checked")) {
                        listaUnidades += $(this).attr("value") + ",";
                        totalPreguntas += parseInt($(this).attr("preg"));
                    }
                });
                if (listaUnidades != "") {
                    listaUnidades = listaUnidades.substring(0, listaUnidades.length - 1);
                    $("#arrUnidades").val(listaUnidades);
                    validar(totalPreguntas);
                }
            }
        }
    });
});

function expandeCursos(ele) {
    if ($(ele).attr('estado') == undefined) {
        $(ele).attr('estado', 0);
    }

    var obj2 = $('#dentroCurso' + $(ele).attr('rel'));

    if ($(ele).attr('estado') == 0) {
        $(ele).attr('src', 'css/icos/icominus.png');
        
        var avisoCarga = jQuery('<ul/>', {html:"<li>Cargando...</li>"});
        $(obj2).append(avisoCarga);
        $(obj2).children().slideDown();
        
        $.post('ajax/cargaTemasExamen_f.php', {'id_curso': +$(ele).attr('rel'), 'check': $("#Curso" + $(ele).attr('rel')).is(":checked")},
        function(res) {
            $(obj2).html(res);
            $(ele).attr('estado', 1);
            $(obj2).children().slideDown();
        });
    } else {
        
        $(obj2).slideUp();
        $(ele).attr('estado', 0);
        $(ele).attr('src', 'css/icos/icoplus.png');
    }
}

function expandeTemas(ele) {
    if ($(ele).attr('estado') == undefined) {
        $(ele).attr('estado', 0);
    }

    var obj2 = $('#dentroTema' + $(ele).attr('rel'));

    if ($(ele).attr('estado') == 0) {
        var avisoCarga = jQuery('<ul/>', {html:"<li>Cargando...</li>"});
        $(obj2).append(avisoCarga);
        $(obj2).children().slideDown();
        
        $.post('ajax/cargarUnidadesExamen_f.php', {'id_tema': $(ele).attr('rel'), 'check': $("#Tema" + $(ele).attr('rel')).is(":checked")},
        function(res) {
            $(obj2).html(res);
            $(obj2).children().slideDown();
        });
        $(ele).attr('estado', 1);
        $(ele).attr('src', 'css/icos/icominus.png');
    } else {
        
        $(obj2).slideUp();
        $(ele).attr('estado', 0);
        $(ele).attr('src', 'css/icos/icoplus.png');
    }

}

function cambiaCurso(ele) {
    if ($(ele).is(":checked")) {
        $(".check-curso").removeAttr("checked");
        $(ele).attr("checked", "checked")
        $(".check-tema").removeAttr("checked");
        $(".check-unidad").removeAttr("checked");
        $(".check-anyo").removeAttr("checked");

        $("#dentro" + $(ele).attr('id') + " .check-tema").attr("checked", "checked");
        $("#dentro" + $(ele).attr('id') + " .check-unidad").attr("checked", "checked");
        $("#dentro" + $(ele).attr('id') + " .check-anyo").attr("checked", "checked");
    } else {
        $("#dentro" + $(ele).attr('id') + " .check-tema").removeAttr("checked");
        $("#dentro" + $(ele).attr('id') + " .check-unidad").removeAttr("checked");
        $("#dentro" + $(ele).attr('id') + " .check-anyo").removeAttr("checked");
    }
}

function cambiaTema(ele) {
    $(".check-curso").removeAttr("checked");
    if ($(ele).is(":checked")) {
        $("#dentro" + $(ele).attr('id') + " .check-unidad").attr("checked", "checked");
        $("#dentro" + $(ele).attr('id') + " .check-anyo").attr("checked", "checked");
    } else {
        $("#dentro" + $(ele).attr('id') + " .check-unidad").removeAttr("checked");
        $("#dentro" + $(ele).attr('id') + " .check-anyo").removeAttr("checked");
    }
}

function cambiaUnidad(ele) {
    $(".check-curso").removeAttr("checked");
    $(".check-tema").removeAttr("checked");
    if ($(ele).is(":checked")) {
        $("#dentro" + $(ele).attr('id') + " .check-anyo").attr("checked", "checked");
    } else {
        $("#dentro" + $(ele).attr('id') + " .check-anyo").removeAttr("checked");
    }
}

function validar(totalPreguntas) {
    totalPreguntas = parseInt(totalPreguntas);
    var error = false;
    if ($('input[name="titulo"]').attr('value') == "") {
        $('input[name="titulo"]').css('border-color', '#f11');
        error = true;
    } else {
        $('input[name="titulo"]').css('border-color', '#BDB7A2');
    }

    if ($('input[name="numpreguntas"]').attr('value') == "" || isNaN($('input[name="numpreguntas"]').attr('value'))) {
        $('input[name="numpreguntas"]').css('border-color', '#f11');
        error = true;
    } else {
        $('input[name="numpreguntas"]').css('border-color', '#BDB7A2');
    }

    if (!error) {
        if (totalPreguntas < $('input[name="numpreguntas"]').attr('value')) {
            $('input[name="numpreguntas"]').css('border-color', '#f11');
            $("#mensajero").html("<p>Las preguntas esperadas son " + $('input[name="numpreguntas"]').attr('value') +
                    " y en el temario seleccionado solo hay " +
                    totalPreguntas);
            $("#mensajero").animate({opacity: "1.0"}, 500);
            setTimeout(function() {
                $("#mensajero").animate({opacity: "0.0"}, 500);
            }, 5000);
        } else {
            meteTapa();
            $('#frmcrearexamen').submit();
        }
    }
}

function meteTapa() {
    $('#tapa').css('display', 'block');
    $('#tapa2').css('display', 'block');
    setTimeout(function() {
        $('#tapa').css('opacity', '0.6');
        $('#tapa2').css('opacity', '1');
    }, 200);
}