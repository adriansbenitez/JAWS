function cargaCursosAdmin(elemento) {
    var ele = $(elemento);
    if ($(ele).attr('estado') == undefined) {
        $(ele).attr('estado', 0);
    }

    if ($(ele).attr('estado') == 0) {
        $.get('ajax/materiasTemas.php?id_curso=' + $(ele).attr('rel') + "&id_usuario=" + $(ele).attr('rel2'), function(res) {
            var conte = $('#dentroCurso' + $(ele).attr('rel'));
            $(conte).html(res);
            var obj2 = $(conte).children();
            $(obj2).slideDown();
            eval($('#scri-resto').text());
        });
        $(ele).attr('estado', 1);
    } else {
        var obj2 = $('#dentroCurso' + ele.attr('rel')).children();
        $(obj2).slideUp();
        $(ele).attr('estado', 0);
    }
}

function cargaTemasAdmin(elemento) {
    var ele = $(elemento);
    if ($(ele).attr('estado') == undefined) {
        $(ele).attr('estado', 0);
    }

    if ($(ele).attr('estado') == 0) {
        $.get('ajax/materiasUnidades.php?id_tema=' + $(ele).attr('rel') + "&id_usuario=" + $(ele).attr('rel2'), function(res) {
            var conte = $('#dentroTema' + $(ele).attr('rel'));
            $(conte).html(res);
            var obj2 = $(conte).children();
            $(obj2).slideDown();
            eval($('#scri-resto').text());
        });
        $(ele).attr('estado', 1);
    } else {
        var obj2 = $('#dentroTema' + ele.attr('rel')).children();
        $(obj2).slideUp();
        $(ele).attr('estado', 0);
    }
}

function cargaAnyosAdmin(elemento) {
    var ele = $(elemento);
    if ($(ele).attr('estado') == undefined) {
        $(ele).attr('estado', 0);
    }

    if ($(ele).attr('estado') == 0) {
        $.get('ajax/materiasAnyos.php?id_unidad=' + $(ele).attr('rel') + "&id_usuario=" + $(ele).attr('rel2'), function(res) {
            var conte = $('#dentroUnidad' + $(ele).attr('rel'));
            $(conte).html(res);
            var obj2 = $(conte).children();
            $(obj2).slideDown();
            eval($('#scri-resto').text());
        });
        $(ele).attr('estado', 1);
    } else {
        var obj2 = $('#dentroUnidad' + ele.attr('rel')).children();
        $(obj2).slideUp();
        $(ele).attr('estado', 0);
    }
}

function activaTema(ele){
    $.get("funciones/controlador.php",
        {"accion": "activaTema",
        "activado": $(ele).attr("activado"),
        "id_usuario" : $(ele).attr("usuario"),
        "id_tema" : $(ele).attr("id_tema")}, function(respuesta){
            if(respuesta == 1){
                $(ele).parent("li").toggleClass("activado");
                $(ele).parent("li").toggleClass("bloqueado");
                
                if($(ele).attr("activado") == 1){
                    $(ele).attr("activado", 0);
                }else{
                    $(ele).attr("activado", 1);
                }
                
            }else{
                alert("No se ha podido realizar el cambio");
            }
        });
}

function deshabilitaAnyo(ele){
    $.get("funciones/controlador.php",
        {"accion": "activaAnyo",
        "activado": $(ele).attr("activado"),
        "id_usuario" : $(ele).attr("usuario"),
        "id_anyo" : $(ele).attr("id_anyo")}, function(respuesta){
            if(respuesta == 1){
                $(ele).parent("li").toggleClass("activado");
                $(ele).parent("li").toggleClass("bloqueado");
                
                if($(ele).attr("activado") == 0){
                    $(ele).attr("activado", 1);
                }else{
                    $(ele).attr("activado", 0);
                }
                
            }else{
                alert("No se ha podido realizar el cambio");
            }
        });
}