<? include 'trozos/session.php';
include 'trozos/menuPerfil.php';
require_once 'clases/Periodo.php';
seguridad("usuario");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
	<div id="content">

	<div class="contplataforma">
        <h1>Exámenes realizados<img src="css/icos/icoexamenes.png" /></h1>
		<div class="colun2-3 perfilcolumn">
        	<? $usuario = $_SESSION["usuario"];
                if (isset($_REQUEST["id_usuario"]) && isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
                    $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
                } ?>
                <?= menuPerfil($usuario)?>
            <div>
                
                <? $peri = 0;
                $periodos = Periodo_BD::cargar($usuario->getIdUsuario());
                if(count($periodos) > 0){ ?>
                <form action="examenesrealizados.php" method="post" id="elige-periodo">
                    <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                        <input type="hidden" name="id_usuario" value="<?=$usuario->getIdUsuario()?>"/>
                    <? } 
                    if(isset($_POST["periodo_elegido"])){
                        $peri = $_POST["periodo_elegido"];
                    } ?>
                    <div class="periodos">
                        Períodos: 
                        <select name="periodo_elegido">
                            <option value="0" <? if($peri == 0){echo "selected";} ?>>Todos</option>
                            <? foreach ($periodos as $periodo){ ?>
                            <option value="<?=$periodo->getIdPeriodo()?>" <? if($peri == $periodo->getIdPeriodo()){echo "selected";} ?>><?=$periodo->getTitulo()?></option>
                            <? } ?>
                            <option value="-1" <? if($peri == -1){echo "selected";} ?>>Actual</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </form>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('[name=periodo_elegido]').change(function(){
                            $('#elige-periodo').submit();
                        });
                    });
                </script>
                <? } ?>
                
            <? $fecha = null;
            $antes = null;
            $despues = null;
            
            if($_SESSION["usuario"]->getNivel() == 1){
                $idUsuario = $_REQUEST["id_usuario"];
            }else{
                $idUsuario = $_SESSION["usuario"]->getIdUsuario();
            }
            
            if($peri > 0){
                $bd = new BD();
                $bd->setConsulta("select p.fecha, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha < p.fecha order by pr.fecha desc limit 1) antes, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha > p.fecha limit 1) despues from periodos p where p.id_periodo = ?");
                $bd->ejecutar(array($idUsuario, $idUsuario, $peri));
                $it = $bd->resultado();
                
                $fecha = str_replace("-", "", $it["fecha"]);
                $despues = str_replace("-", "", $it["despues"]);
                $antes = str_replace("-", "", $it["antes"]);
            }else if($peri == -1){
                $bd = new BD();
                $bd->setConsulta("select MAX(fecha) as fecha from periodos where id_usuario = ?");
                $bd->ejecutar($idUsuario, $peri);
                $it = $bd->resultado();
                $fecha = str_replace("-", "", $it["fecha"]);
            }
            
            $sql = null;
            $parametros = null;
            if($peri != 0){
                $parametros = array();
                if($peri == -1){
                    $sql .=" and v.fecha >= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues != null){
                    $sql .=" and v.fecha >= ? and v.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $despues != null){
                    $sql .=" and v.fecha <= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues == null){
                    $sql .=" and v.fecha >= ? and v.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $antes == null){
                    $sql .=" and v.fecha <= ?";
                    array_push($parametros, $fecha);
                }
            }
            
            
            $listaExamen = Examen_BD::listarExamenesRealizados($usuario->getIdUsuario(), $sql, $parametros);
            $examen = new Examen(null, null, null, null, null);
            foreach ($listaExamen as $examen){
                $clase = "";
                if(!$examen->getFinalizado()){
                    $clase= "sinfinalizar";
                } ?>
                <div class="resultadoexamenrendido <?=$clase?>">
                        <span class="puntuacion">
                        <div class="puntosobtenidos"><?=$examen->puntuacion()?> Puntos</div>
                            <? $puntos = explode(",",$examen->porcentaje()); 
                            echo $puntos[0];?><span>,<?= $puntos[1]?>%</span>
                         </span>
                        <div class="tituloexamen">
                            <a target="_blank" href="correccionexamen.php?id_resultado=<? echo $examen->getIdResultado();
                                    if($_SESSION["usuario"]->getNivel()==1){ echo "&id_usuario=".$usuario->getIdUsuario();}?>"><img src="css/icos/admindetalles.png" class="agranda" style="float: left;"/></a>
				<? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                                    <a target="_blank" href="analizadorexamen.php?id_examen=<? echo $examen->getIdExamen();
                                        ?>&id_resultado=<? echo $examen->getIdResultado(); ?>"><img src="css/icos/adminstats.png" class="agranda"/></a>
                            <a target="_blank" href="funciones/controlador.php?accion=borrarResultado&id_examen=<? echo $examen->getIdExamen();
                            ?>&id_resultado=<? echo $examen->getIdResultado(); ?>"><img src="css/icos/admindelete.png" class="agranda"/></a>
                                <? }else { ?>
                                    <form class="examenredo" action="rendirexamen.php" method="post">
                                        <input type="hidden" class="agranda" name="id_examen" value="<? echo $examen->getIdExamen(); ?>" />
                                        <input type="hidden" class="agranda" name="id_resultado" value="<? echo $examen->getIdResultado(); ?>" />
                                	<input type="image" class="agranda" src="css/icos/adminredo.png" style="float: left;"/>
                                    </form>
                                <? } ?>
                            <div class="clear"></div>
                            <? echo $examen->getTitulo(); ?>
                        </div>
                        <? if($examen->getFinalizado()){ ?>
                            <div class="fechaexamen">Realizado el <? $fecha = new Fecha($examen->getFecha());
                            echo $fecha->conFormatoLargoConHora(); ?> </div>
                            <div class="resultadopreguntas">
                                <span class="acertadas"><? echo $examen->getAcertadas(); ?> acertadas </span>
                                <span class="erradas"><? echo $examen->getFallidas(); ?> erradas </span>
                                <span class="blancas"><? echo $examen->blancas(); ?> en blanco </span>
                            </div>
                        <? }else{ ?>
                            <div class="fechaexamen">Comenzado el <? $fecha = new Fecha($examen->getFecha());
                            echo $fecha->conFormatoLargoConHora(); ?> </div>
                        <? } ?>
                    	<div class="clear"></div>
                    </div>
                <? } ?>
            </div>
        
        </div>
    </div>
    <style type="text/css">
		.agranda{
			margin-right: 5px;
			margin-bottom: 5px;}
    </style>
<div class="clear"></div>
</div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
