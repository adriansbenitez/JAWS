<? include 'trozos/session.php';
include 'trozos/menuPerfil.php';
seguridad("nodemo");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Plataforma de Formacion Inspiracle</title>
        <? include 'trozos/head.php'; ?>
        <script type="text/javascript" src="js/materias.js?ver=1.1"></script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Materias<img src="css/icos/icobloques.png" /></h1>
                <div class="colun2-3 perfilcolumn">
                    <? $usuario = $_SESSION["usuario"];
                    if(isset($_REQUEST["id_usuario"]) && isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1){
                        $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
                    } ?>
                    <? menuPerfil($usuario); ?>
                    <div id="listmaterias">
                        <? $bd = new BD();
                        $bd->setConsulta("select c.id_curso, c.nombre," .
                            " (select COUNT(*)" .
                            " from temas t inner join temas_usuarios tu on tu.id_tema = t.id_tema" .
                            " where t.id_curso = c.id_curso and tu.id_usuario = ?) activa" .
                            " from cursos c");
                        $bd->ejecutar($usuario->getIdUsuario()); ?>
                        <ul class="listadmin categoriasli">
                            <? while ($it = $bd->resultado()) {
                                $clase = " activado ";
                                if ($it["activa"] == 0) {
                                    $clase = " bloqueado ";
                                } ?>
                                <li class="<? echo $clase ?> curso_click lista-cursos">
                                    <img src="css/icos/icoplus.png" height="23" border="0" class="show" align="absmiddle"
                                        onclick="cargaCursosAdmin(this)" rel="<? echo $it["id_curso"]; ?>"
                                        rel2="<? echo $usuario->getIdUsuario(); ?>"/>
                                    <? echo $it["nombre"]; ?>
                                    <div id="dentroCurso<? echo $it["id_curso"]; ?>"></div>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <? include '/trozos/pie.php'; ?>
    </body>
</html>
