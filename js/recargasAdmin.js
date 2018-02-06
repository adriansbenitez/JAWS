function losCursos(elemento) {

    var ele = $(elemento);

    if (ele.attr('estado') == undefined) {
        ele.attr('estado', 0);
    }

    if (ele.attr('estado') == 0) {
        $.get('ajax/cargaTemasAdmin.php?id_curso=' + ele.attr('rel'),
                function (res) {
                    var conte = $('#dentroCurso' + ele.attr('rel'));
                    $(conte).html(res);
                    var obj2 = $(conte).children();
                    $(obj2).slideDown();
                });
        ele.attr('estado', 1);
        ele.attr('src', 'css/icos/icominus.png');
    } else {
        var obj2 = $('#dentroCurso' + ele.attr('rel')).children();
        obj2.slideUp();
        ele.attr('estado', 0);
        ele.attr('src', 'css/icos/icoplus.png');
    }
}


function losTemas(elemento) {

    var ele = $(elemento);

    if (ele.attr('estado') == undefined) {
        ele.attr('estado', 0);
    }

    if (ele.attr('estado') == 0) {
        $.get('ajax/cargaUnidadesAdmin.php?id_tema=' + ele.attr('rel'),
                function (res) {
                    var conte = $('#dentroTema' + ele.attr('rel'));
                    $(conte).html(res);
                    var obj2 = $(conte).children();
                    $(obj2).slideDown();
                });
        ele.attr('estado', 1);
        ele.attr('src', 'css/icos/icominus.png');
    } else {
        var obj2 = $('#dentroTema' + ele.attr('rel')).children();
        obj2.slideUp();
        ele.attr('estado', 0);
        ele.attr('src', 'css/icos/icoplus.png');
    }
}


function losUnidades(elemento) {

    var ele = $(elemento);

    if (ele.attr('estado') == undefined) {
        ele.attr('estado', 0);
    }

    if (ele.attr('estado') == 0) {
        $.get('ajax/cargaAnyosAdmin.php?id_unidad=' + ele.attr('rel'),
                function (res) {
                    var conte = $('#dentroUnidad' + ele.attr('rel'));
                    $(conte).html(res);
                    var obj2 = $(conte).children();
                    $(obj2).slideDown();
                });
        ele.attr('estado', 1);
        ele.attr('src', 'css/icos/icominus.png');
    } else {
        var obj2 = $('#dentroUnidad' + ele.attr('rel')).children();
        obj2.slideUp();
        ele.attr('estado', 0);
        ele.attr('src', 'css/icos/icoplus.png');
    }
}