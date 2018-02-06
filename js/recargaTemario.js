$(document).ready(function() {
    $("#btn-asociar, #btn-asociar_v").click(function() {
        if($(this).attr("id") == "btn-asociar"){
            $('#muestraTemas').css('display', 'table');
            $.get('ajax/cargaCursos.php', function(res) {
                $('#muestraTemas').html(res);
            });
        }else{
            $('#muestraTemas_v').css('display', 'table');
            $.get('ajax/cargaCursos.php', function(res) {
                $('#muestraTemas_v').html(res);
            });
        }        
    });
    
    $("#addrespuestabutton").click(function() {
        var totalrespuestas = $("#hidcontrespuestas").attr("value")
        $("#hidcontrespuestas").attr("value", parseInt(totalrespuestas) + 1)
        var nextrespuesta = $("#hidcontrespuestas").attr("value")
        $("#contrespuestasform").append('<div><textarea  placeholder="Respuesta' + nextrespuesta +
                '" name="respuesta_' + nextrespuesta + '" id="respuesta_' + nextrespuesta +
                '"></textarea><input type="checkbox" class="uncheck" name="chkrespuesta_' + nextrespuesta +
                '" id="chkrespuesta_' + nextrespuesta + ' onclick="chkRespuesta(this)"/>' +
                '<input name="imagen_respuesta_' + nextrespuesta + '" type="file" value=""/>' +
                '<img src="css/icos/admindelete.png" onclick="borrar(this)" style="cursor: pointer;"></div>' +
                '<div class="clear"></div>');
    });
});

function borrarTema(elemento) {
    $('#tem_' + $(elemento).attr('rel')).remove();
}

function borrarUnidadVirtual(num){
    $('#contiene-temario_v [rel='+num+']').remove();
    
    var lista = "";
    $.each($('#contiene-temario_v > .temario'), function(indice, ele){
        if(lista != ""){
            lista += ",";
        }
        lista += $(ele).attr("rel");
    });
    
    $('[name=id_unidad]').val(lista);
}

function cargaTemas(elem) {
    var num = $(elem).attr('rel');
    var ele = $(elem);

    if (ele.attr('estado') == 0) {
        $.get('ajax/cargaTemas.php?id_curso=' + num, function(res) {
            $('#dentroCurso' + num).html(res);
            $('#dentroCurso' + num).children().slideDown();
        });
        ele.attr('estado', 1);
        ele.attr('src', 'css/icos/icominus.png');
    } else {
        $('#dentroCurso' + num).children().slideUp();
        ele.attr('estado', 0);
        ele.attr('src', 'css/icos/icoplus.png');
    }
}


function cargaUnidades(elem) {
    var num = $(elem).attr('rel');
    var ele = $(elem);
    
    var virtual = 0;
    
    if($('#muestraTemas_v').css('display') == "table"){
        virtual = 1;
    }

    if (ele.attr('estado') == 0) {
        $.get('ajax/cargaUnidades.php?id_tema=' + num+"&virtual="+virtual, function(res) {
            $('#dentroTema' + num).html(res);
            $('#dentroTema' + num).children().slideDown();
        });
        ele.attr('estado', 1);
        ele.attr('src', 'css/icos/icominus.png');
    } else {
        $('#dentroTema' + num).children().slideUp();
        ele.attr('estado', 0);
        ele.attr('src', 'css/icos/icoplus.png');
    }
}

function cargaAnyos(elem) {
    var num = $(elem).attr('rel');
    var ele = $(elem);

    if (ele.attr('estado') == 0) {
        $.get('ajax/cargaAnyos.php?id_unidad=' + num, function(res) {
            $('#dentroUnidad' + num).html(res);
            $('#dentroUnidad' + num).children().slideDown();
        });
        ele.attr('estado', 1);
        ele.attr('src', 'css/icos/icominus.png');
    } else {
        $('#dentroUnidad' + num).children().slideUp();
        ele.attr('estado', 0);
        ele.attr('src', 'css/icos/icoplus.png');
    }
}

function agregaAnyo(elem) {
    var anyo = parseInt($('#ultimoTemario').attr('value')) + 1;
    $("#contiene-temario").append('<div class="temario" id="tem_' + anyo + '">' +
            '<div class="ubicaTema">' + $(elem).attr('rel2') +
            '<input type="hidden" name="anyo_' + anyo + '" value="' + $(elem).attr('rel') + '"/>' +
            '</div>' +
            '<div class="borra" rel="' + anyo + '" onclick="borrarTema(this)"><img src="css/icos/admindelete.png" /></div>' +
            '</div>');
    $('.seccionli').slideUp();
    $('#ultimoTemario').attr('value', parseInt($('#ultimoTemario').attr('value')) + 1);
    setTimeout(function() {
        $('#muestraTemas').css('display', 'none');
        $('#muestraTemas').html('');
    }, 1000);
}

function agregaUnidadVirtual(elem){
    $("#contiene-temario_v").append('<div class="temario" rel="'+$(elem).attr('rel')+'">' +
            '<div class="ubicaTema">' + $(elem).attr('rel2') +
            '</div>' +
            '<div class="borra" onclick="borrarUnidadVirtual('+$(elem).attr('rel')+')"><img src="css/icos/admindelete.png"/></div>' +
            '</div>');
    $('.seccionli').slideUp();
    
    var lista = "";
    $.each($('#contiene-temario_v > .temario'), function(indice, ele){
        if(lista != ""){
            lista += ",";
        }
        lista += $(ele).attr("rel");
    });
    
    $('[name=id_unidad]').val(lista);
    
    setTimeout(function() {
        $('#muestraTemas_v').css('display', 'none');
        $('#muestraTemas_v').html('');
    }, 1000);
}

function chkRespuesta(elem) {
    $('.uncheck').removeAttr('checked');
    $(elem).attr('checked', 'checked');
}