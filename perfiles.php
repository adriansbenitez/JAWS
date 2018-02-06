<? include 'trozos/session.php';
 require_once 'clases/Curso.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
            .pestanya{
                position: relative;
                display: table;
            }

            .pestanya div{
                float: left;
                margin: 0px 15px 0 0;
                padding: 10px 15px;
                border: 1px solid #000000;
                border-radius: 5px 5px 0 0;
                cursor: pointer;
                transition:all 0.5s;
                -moz-transition:all 0.5s;
                -ms-transition:all 0.5s;
                -o-transition:all 0.5s;
                -webkit-transition:all 0.5s;
                font-weight:bold;
                background: #6D4D1C;
                color: #52340F;
                text-shadow: 1px 1px 0 #CCCCCC;
            }

            .pestanya .todos{
                color: #ffffff;
            }

            .pestanya .basico{
                background: #85A6C8;
            }

            .pestanya .demo{
                background: none;
            }

            .pestanya .tutelado{
                background: #C7C885;
            }

            .pestanya div:hover{
                background: #CCCCCC;
                color: #000000;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                $.get('ajax/cargaPerfiles.php?filtroUsuariosAdmin=1&filtroCursosAdmin=0',
                        function(res) {
                            $('#filtrado').html(res);
                        }
                );

                $('.pestanya div').click(function() {
                    $('#filtrado').slideUp();
                    if ($(this).attr('rel') != null) {
                        $('#elnivel').val($(this).attr('rel'));
                    }
                    if ($(this).attr('cur') != null) {
                        $('#elcurso').val($(this).attr('cur'));
                    }

                    $.get('ajax/cargaPerfiles.php?filtroUsuariosAdmin=' + $('#elnivel').val() + '&filtroCursosAdmin=' +
                            $('#elcurso').val(),
                            function(res) {
                                $('#filtrado').html(res);
                                setTimeout(function() {
                                    $('#filtrado').slideDown();
                                }, 1000);
                            }
                    );


                });
            });
        </script>
    </head>
    <body>
<? include 'trozos/encabezado.php'; ?>
        <div id="content">

            <div class="contplataforma">
                <h1>Perfiles <img src="css/icos/icoperfil.png" /></h1>
                
                <form method="post" action="funciones/controlador.php" class="frmadmin" target="_blank">
                    <input type="hidden" name="accion" value="crear_listado_usuarios"/>
                    <input type="hidden" name="nivel" id="elnivel" value="1"/>
                    <input type="hidden" name="curso" id="elcurso" value="0"/>
                    
                    <input class="botonoscuro" value="Crear listado" type="submit"/>
                </form>
                <div class="clear"></div>
                <br/>
                
                <div class="pestanya">
                    <div class="todos" rel="1">Todos los niveles</div>
                    <div class="demo" rel="2">Demo</div>
                    <div class="basico" rel="3">Basico</div>
                    <div class="tutelado" rel="4">Tutelado</div>

                    <div class="todos" cur="0">Todos los cursos</div>
                    <div class="todos" cur="1">BIR</div>
                    <div class="todos" cur="2">QIR</div>
                    <div class="todos" cur="7">FIR</div>
                </div>
                <div id="filtrado">
                </div>
            </div>


            <div class="clear"></div>
        </div>
<? include 'trozos/pie.php'; ?>
    </body>
</html>