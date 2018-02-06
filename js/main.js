colorRojo = "C38D8D";
colorVerde = "8EAD74";
listaColores = getColoresDegradados(colorRojo, colorVerde, 101);

$(document).ready(function() {

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-34397631-6']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();


    $(".colorfondo").each(function() {
        var porcentaje = $(this).attr("data-color");
        $(this).css('background-color', "#" + listaColores[porcentaje.split(".")[0]]);
    });

    //carrusel inicio
    $("#carousel").oneByOne({
        className: "oneByOne1",
        easeType: "random",
        slideShow: true
    });
    //Aviso
    $("#cerrar").click(function() {
        $("#shadow").animate({height: "0%"}, 500, function() {
        })
        $("#error").animate({marginTop: -200, opacity: 0}, 400, function() {
        })
    });

    $(".validacioncomasimple").keyup(function() {
        var str = this.value;
        this.value = str.replace(/'/g, "");
    });


    $("#listmaterias ul li").click(function() {
        var obj2 = $(this).next("ul");
        var visible2 = $(obj2).css("display");
        if (visible2 == "none") {
            $(obj2).slideDown();
        } else {
            $(obj2).slideUp();
        }

    });




    $(".listadmin.categoriasli li .show").click(function() {
        var obj = $(this).parent("li").find(">ul");
        var visible = $(obj).css("display");
        if (visible == "none") {
            $(obj).slideDown();
            $(this).attr("src", "css/icos/icominus.png");
        } else {
            $(obj).slideUp()
            $(this).attr("src", "css/icos/icoplus.png");
        }

    });

    $(".postit").click(function() {
        var stilo = $(this).attr("data-style");
        if ($(this).hasClass(stilo)) {
            $(this).removeClass(stilo);
        } else {
            $(this).addClass(stilo);
        }
    });
});

function mensajecargando(texto) {
    var html = "<div id='shadow'><div id='error'><img src='/css/icos/close.png' id='cerrar' /><p>" + texto + "</p><div class='clear15'></div><img src='/images/cargando.gif' /><div class='clear'></div></div></div>";
    $('body').append(html);
}

function mensajeclear() {
    $("#shadow").remove();
}

function borrar(elemento) {
    var padre = $(elemento).parent('div');
    $(padre).remove();
}



function getComponenteColor(numero) {
    var res = new Array();
    res[0] = parseInt("0x" + numero.substr(0, 2));
    res[1] = parseInt("0x" + numero.substr(2, 2));
    res[2] = parseInt("0x" + numero.substr(4, 2));
    return res;
}

function getComponenteDegradado(n1, n2, z) {
    var i = 0;
    var n = 0;
    var res = new Array();
    n = (n1 - n2) / (z - 1);
    for (i = 0; i < z; i++) {
        res[i] = n2 + (n * ((z - 1) - i));
        res[i] = Math.floor(res[i]);
        res[i] = res[i].toString(16);

        if (res[i].length < 2) {
            res[i] = "0" + res[i];
        }
    }
    return res;
}

function getColoresDegradados(colorInicio, colorFin, zonas) {
    var components1 = getComponenteColor(colorInicio);
    var components2 = getComponenteColor(colorFin);
    var i = 0;
    var colors = new Array();
    var res = new Array();

    for (i = 0; i < components1.length; i++) {
        colors[i] = getComponenteDegradado(components1[i], components2[i], zonas);
    }

    for (i = 0; i < zonas; i++) {
        res[i] = colors[0][i] + colors[1][i] + colors[2][i];
    }

    return res;
}