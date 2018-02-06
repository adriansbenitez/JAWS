<?php
require_once 'BD.php';
require_once 'Fecha.php';
require_once 'Usuario.php';

if(!isset($_SESSION)){
    session_start();
}

if(isset($_GET["seleccionDia"])){
    $calendario = new Calendario();
    $elementos = explode("-", $_GET["seleccionDia"]);
    $calendario->anyo = $elementos["0"];
    $calendario->mes = $elementos["1"];
    
    $calendario->anyoMuestra = $elementos["0"];
    $calendario->mesMuestra = $elementos["1"];
    
    if($elementos["2"] > 9){
        $calendario->dia = $elementos["2"];
    }else{
        $calendario->dia = "0".$elementos["2"];
    }
    
    $_SESSION["fecha_guardada"] = $calendario->anyo."-".$calendario->mes."-".$calendario->dia;
    
    $calendario->montar();
}

if(isset($_GET["seleccionMes"])){
    $calendario = new Calendario();
    $elementos = explode("-", $_GET["seleccionMes"]);
    if($elementos["1"] == ""){
        $calendario->mesMuestra = 12;
        $calendario->anyoMuestra = $elementos["0"]-1;
    }elseif($elementos["1"] > 12) {
        $calendario->mesMuestra = 1;
        $calendario->anyoMuestra = $elementos["0"]+1;
    }else{
        $calendario->mesMuestra = $elementos["1"];
        $calendario->anyoMuestra = $elementos["0"];
    }
    
    if(isset($_SESSION["usuario_cal"])){
        $calendario->montar($_SESSION["usuario_cal"]);
    }else if($_SESSION["usuario"]->getNivel() == 1){
        $calendario->montar();
    }else{
        $calendario->montar($_SESSION["usuario"]->getIdUsuario());
    }
}

if(isset($_GET["contenidoDia"])){
    $elementos = explode("-", $_GET["contenidoDia"]);
    $dia = $elementos[2];
    $mes = $elementos[1];
    $anyo = $elementos[0];
    
    if($_SESSION["usuario"]->getNivel() == 1){
        $listaAgenda = Calendario_BD::tareasDia($dia, $mes, $anyo);
    }else if(isset($_SESSION["usuario_cal"])){
        $listaAgenda = Calendario_BD::tareasDia($dia, $mes, $anyo, $_SESSION["usuario_cal"]);
    }else{
        $listaAgenda = Calendario_BD::tareasDia($dia, $mes, $anyo, $_SESSION["usuario"]->getIdUsuario());
    }
    
    $agenda = new Agenda(null, null, null, null, null, null, null);
    
    foreach ($listaAgenda as $agenda){ ?>
        <div class="cal-entrada">
            <div class="titulo"><?=$agenda->getTitulo()?></div>
            
            <div class="marca">Fecha elegida</div>
            <div class="fecha"><?=$agenda->getFecha()->conFormatoLargo()?></div>
            <div class="clear"></div>
            
            <div class="marca">Inicio</div>
            <div class="fecha"><?=$agenda->getPrimerDia()->conFormatoLargoConHora()?></div>
            <div class="clear"></div>
            
            <div class="marca">Fin</div>
            <div class="fecha"><?=$agenda->getUltimoDia()->conFormatoLargo()?></div>
            <div class="clear"></div>
            
            <hr style="width: 85%; margin: 0 auto;"/>
            <div class="texto"><?=$agenda->getTexto()?></div>
            <? if($_SESSION["usuario"]->getNivel() == 1 || $agenda->getCreador() == $_SESSION["usuario"]->getIdUsuario()){ ?>
            <form action="edit_calendario.php" method="post" style="float: right; margin: 20px;">
                <input type="hidden" name="id_calendario" value="<?=$agenda->getIdCalendario()?>"/>
                <input type="submit" value="modificar" class="botonoscuro"/>
            </form>
            
            <form action="funciones/controlador.php" method="post" style="float: right; margin: 20px;">
                <input type="hidden" name="accion" value="borrar_calendario"/>
                <input type="hidden" name="id_calendario" value="<?=$agenda->getIdCalendario()?>"/>
                <input type="submit" value="borrar" class="botonoscuro"/>
            </form>
            <? } ?>
            <div class="clear"></div>
        </div>
        
    <? }   
}

if(isset($_GET["filtrar"])){
    $calendario = new Calendario();
    $_SESSION["filtro_grupo"] = $_GET["id_grupo"];
    $_SESSION["filtro_curso"] = $_GET["id_curso"];
    $_SESSION["filtro_usuarios"] = $_GET["lista-usuarios"];
    
    $calendario->montar();
}


class Calendario {

    public $fecha;
    public $dia;
    public $mes;
    public $anyo;
    public $mesMuestra;
    public $anyoMuestra;

    function __construct() {
        $this->fecha = time();

        if (isset($_SESSION["fecha_guardada"])) {
            $elementos = explode("-", $_SESSION["fecha_guardada"]);
            $this->anyo = $elementos["0"];
            $this->mes = $elementos["1"];
            $this->dia = $elementos["2"];
        } else {
            $this->dia = date("d");
            $this->mes = date("m");
            $this->anyo = date("Y");
        }

        $this->mesMuestra = date("m");
        $this->anyoMuestra = date("Y");
    }
    
    public function importar(){
        $_SESSION["fecha_guardada"] = date("Y")."-".date("m").
                "-".date("d");
        $this->dia = date("d");
        $this->mes = date("m");
        $this->anyo = date("Y");
        ?>
        <link type="text/css" rel="stylesheet" href="css/calendario.css"/>
        <link type="text/css" rel="stylesheet" href="css/AntColorPicker.css"/>
        <script type="text/javascript" src="js/AntColorPicker.js?ver=1.1"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".lista-cursos > .boton").click(function(){
                    if($(this).attr("estado") == "0"){
                        $(".lista-cursos > .boton").attr("estado","0");
                        $(".lista-cursos > .boton").css({"background": "#EFEACF"});
                        
                        $(this).attr("estado", "1");
                        $(this).css({"background": "#d9d9d9"});
                        
                        $('#id_curso').val($(this).attr("rel"));
                    }else{
                        $(this).attr("estado", "0");
                        $(this).css({"background": "#EFEACF"});
                        $('#id_curso').val("");
                    }
                });
                
                $(".lista-grupos > .boton").click(function(){
                    if($(this).attr("estado") == "0"){
                        $(".lista-grupos > .boton").attr("estado","0");
                        $(".lista-grupos > .boton").css({"background": "#EFEACF"});
                        
                        $(this).attr("estado", "1");
                        $(this).css({"background": "#d9d9d9"});
                        
                        $('#id_grupo').val($(this).attr("rel"));
                    }else{
                        $(this).attr("estado", "0");
                        $(this).css({"background": "#EFEACF"});
                        $('#id_grupo').val("");
                    }
                });
                
                $(".alta-cal").click(function(){
                    var cadena = "";
                    $('.lista-usuarios-cal .usuario input').each(function(indice, ele){
                        if($(ele).attr('checked') == 'checked'){
                            cadena+=$(ele).attr('value')+",";
                        }
                    });
                    
                    if(cadena.length > 0){
                        $('#lista_usuarios').val(cadena.substring(0, cadena.length-1));
                    }
                    
                });
                
                $('#cierra').click(function(){
                    $('#fondo-emer').css({"height": "0%"});
                    $('#mensa-emer').css({"margin-top": "-500px", "opacity": 0});
                });
                
                $(".color").AntColorPicker({
                    "labelClose":"Close color picker",
                    "labelRAZColor":"Clear field"
                });
                
            });
            
            function actualizarFechaCalendario(fecha) {
                $.get('clases/Calendario.php?contenidoDia=' + fecha,
                function(res) {
                    if(res.length > 0){
                        $('#interior').html(res);
                        $('#fondo-emer').css({"height": "100%"});
                        $('#mensa-emer').css({"margin-top": "60px",
                            "opacity": 1});
                    }
                });
            }

            function actualizarMesCalendario(mes) {
                $.get('clases/Calendario.php?seleccionMes=' + mes,
                function(res) {
                    $('#calendario').html(res);
                });
            }
        </script>
        
        <div style="display: block; height: 0%;" id="fondo-emer" class="animable">
            <div id="mensa-emer" style="margin-top: -200px; opacity: 0;" class="animable">
                <img src="css/icos/close.png" id="cierra">
                <div id="interior"></div>
                <div class="clear"></div>
            </div>
        </div>
        <?
    }

    public function montar($idUsuario = null) {
            $semana = 1;
            $this->fecha = mktime(0, 0, 0, $this->mesMuestra, 1, $this->anyoMuestra);
            for ($i = 1; $i <= date('t', $this->fecha); $i++) {
                $diaSemana = date('N', strtotime(date('Y-m', $this->fecha) . '-' . $i));
                $calendario[$semana][$diaSemana] = $i;
                if ($diaSemana == 7) {
                    $semana++;
                }
            }

            $diasOcupados = Calendario_BD::diasMarcados($this->fecha, $idUsuario);
            ?>
            <div class="calendarioAgenda">
                <div id="datepickerAgenda" class="hasDatepicker">
                    <div class="datepicker-inline datepicker widget widget-content helper-clearfix corner-all" style="display: block;">
                        <div class="datepicker-header widget-header helper-clearfix corner-all">
                            <a title="&lt;Ant" class="datepicker-prev corner-all"
                               onclick="actualizarMesCalendario('<? echo $this->anyoMuestra . "-" . ($this->mesMuestra - 1) ?>')">
                                <span class="icon icon-circle-triangle-w"></span></a>
                            <a title="Sig&gt;" class="datepicker-next corner-all"
                               onclick="actualizarMesCalendario('<? echo $this->anyoMuestra . "-" . ($this->mesMuestra + 1) ?>')">
                                <span class="icon icon-circle-triangle-e"></span></a>
                            <div class="datepicker-title">
                                <span class="datepicker-month"><? echo Calendario::mesTexto($this->fecha); ?></span>
                                <span class="datepicker-year"><? echo date("Y", $this->fecha); ?></span></div>
                        </div>

                        <table class="datepicker-calendar">
                            <thead>
                                <tr>
                                    <th><span title="Lunes">Lu</span></th>
                                    <th><span title="Martes">Ma</span></th>
                                    <th><span title="Miércoles">Mi</span></th>
                                    <th><span title="Jueves">Ju</span></th>
                                    <th><span title="Viernes">Vi</span></th>
                                    <th><span title="Sábado">Sá</span></th>
                                    <th><span title="Domingo">Do</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <? foreach ($calendario as $dia) { ?>
                                    <tr>
                                        <? for ($i = 1; $i <= 7; $i++) { ?>
                                            <td>
                                                <?
                                                if (isset($dia[$i])) {
                                                    $clase = "";
                                                    $diaP = $dia[$i];
                                                    if ($diaP < 10) {
                                                        $diaP = "0" . $diaP;
                                                    }

                                                    $texto = "";

                                                    $elementos = 0;
                                                    
                                                    $color = "#ffffff";
                                                    
                                                    foreach ($diasOcupados as $diaOcup) {
                                                        if ($diaOcup->getFecha()->conFormatoSimple() == $diaP.date("-m-Y", $this->fecha)) {
                                                            if($diaOcup->getColor() != null){
                                                                $color = $diaOcup->getColor();
                                                            }
                                                            $elementos++;
                                                            if($elementos < 4){
                                                                $texto .= $diaOcup->presentar();
                                                            }else if($elementos == 4){
                                                                $texto .= "<br/>+";
                                                            }
                                                        }
                                                    }

                                                    if (date("m", $this->fecha) == date("m") &&
                                                            $dia[$i] == date("j") &&
                                                            date("Y", $this->fecha) == date("Y")) {
                                                        $clase.="state-highlight ";
                                                    }

                                                    if (date("m", $this->fecha) == $this->mes &&
                                                            $dia[$i] == $this->dia &&
                                                            date("Y", $this->fecha) == $this->anyo) {
                                                        $clase.="state-active ";
                                                    }
                                                    $elDia = $dia[$i];
                                                    if($elDia < 10){
                                                        $elDia = "0".$elDia;
                                                    }
                                                    $laFecha = date("Y", $this->fecha) . "-" . date("m", $this->fecha) . "-" . $dia[$i];
                                                    ?>
                                                    <div class="<?=$clase?>" style="background-color: <?=$color;?>">
                                                        <a onClick="actualizarFechaCalendario('<?=$laFecha?>')">
                                                            <h3><?=$dia[$i]?></h3>
                                                            <?=$texto?>
                                                        </a>
                                                    </div>
                                                <? } else { ?>
                                                    <div>&nbsp</div>
                                                <? } ?>
                                            </td>
                                        <? } ?>
                                    <tr>
                                    <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <div class="clear20"></div>
        <?
    }
    
    public static function mesTexto($fecha) {


        $devuelve = "";
        switch (date("m", $fecha)) {
            case "01":
                $devuelve = "Enero";
                break;
            case "02":
                $devuelve = "Febrero";
                break;
            case "03":
                $devuelve = "Marzo";
                break;
            case "04":
                $devuelve = "Abril";
                break;
            case "05":
                $devuelve = "Mayo";
                break;
            case "06":
                $devuelve = "Junio";
                break;
            case "07":
                $devuelve = "Julio";
                break;
            case "08":
                $devuelve = "Agosto";
                break;
            case "09":
                $devuelve = "Septiembre";
                break;
            case "10":
                $devuelve = "Octubre";
                break;
            case "11":
                $devuelve = "Noviembre";
                break;
            case "12":
                $devuelve = "Diciembre";
                break;
        }

        return $devuelve;
    }

}

class Calendario_BD {

    public static function diasMarcados($fecha, $idUsuario) {

        $bd = new BD();

        $mes = date('n', $fecha);
        $anyo = date('Y', $fecha);

        $parametros = array($mes, $anyo);
        
        if(!empty($_SESSION["filtro_grupo"]) ||
                !empty($_SESSION["filtro_curso"]) ||
                !empty($_SESSION["filtro_usuarios"])){
            $sql = "select distinct c.id_calendario, c.titulo, c.texto, c.alerta, cd.fecha, c.color, c.creador".
                " from calendario c inner join calendario_dias cd on c.id_calendario = cd.id_calendario".
                " left join calendario_usuarios cu on cu.id_calendario = c.id_calendario".
                " left join grupos_usuarios gu on gu.id_grupo = c.id_grupo".
                " where MONTH(cd.fecha) = ? and YEAR(cd.fecha) = ? and (";
            if(!empty($_SESSION["filtro_usuarios"])){
                $sql .= "cu.id_usuario in(";
                foreach (explode(",", $_SESSION["filtro_usuarios"]) as $usuario){
                    array_push($parametros, $usuario);
                    $sql .= "?,";
                }
                $sql .= "0)";
                
            }else{
                $sql .= "(cu.id_usuario != 0 or cu.id_usuario is null)";
            }
            $sql .=" and ";
            if(!empty($_SESSION["filtro_grupo"])){
                $sql .= "c.id_grupo = ?";
                array_push($parametros, $_SESSION["filtro_grupo"]);
            }else{
                $sql .= "(c.id_grupo != 0 or cu.id_usuario is null)";
            }
            $sql .=" and ";
            if(!empty($_SESSION["filtro_curso"])){
                $sql .= "c.id_curso = ?";
                array_push($parametros, $_SESSION["filtro_curso"]);
            }else{
                $sql .= "(c.id_curso != 0 or c.id_curso is null)";
            }
            $sql .=") order by cd.fecha";
            
        }else if($_SESSION["usuario"]->getNivel() == 1 && !isset($idUsuario)){
            $sql = "select c.id_calendario, c.titulo, c.texto, c.alerta, cd.fecha, c.color, c.creador".
                " from calendario c inner join calendario_dias cd on c.id_calendario = cd.id_calendario".
                " where MONTH(cd.fecha) = ? and YEAR(cd.fecha) = ? order by cd.fecha";
        }else{
            $sql = "select distinct c.id_calendario, c.titulo, c.texto, c.alerta, cd.fecha, c.color, c.creador".
                " from calendario c inner join calendario_dias cd on c.id_calendario = cd.id_calendario".
                " left join calendario_usuarios cu on cu.id_calendario = c.id_calendario".
                " left join grupos_usuarios gu on gu.id_grupo = c.id_grupo".
                " where MONTH(cd.fecha) = ? and YEAR(cd.fecha) = ? and (cu.id_usuario = ? or gu.id_usuario = ? or c.id_curso in (select u2.interesado from usuarios u2 where u2.id_usuario = ?)) order by cd.fecha";
            array_push($parametros, $idUsuario);
            array_push($parametros, $idUsuario);
            array_push($parametros, $idUsuario);
        }
        
        

        $bd->setConsulta($sql);
        
        $bd->ejecutar($parametros);

        $arrAgenda = array();

        while ($it = $bd->resultado()) {
            $agenda = new Agenda($it["id_calendario"], null, $it["titulo"], $it["texto"], $it["alerta"], new Fecha(substr($it["fecha"], 0, 10)." 00:00:00"), $it["color"]);
            $agenda->setCreador($it["creador"]);
            array_push($arrAgenda, $agenda);
        }
        return $arrAgenda;
    }
    
    public static function tareasDia($dia, $mes, $anyo, $idUsuario = null){
        $bd = new BD();

        $sql = "select c.id_calendario, c.titulo, c.texto, c.alerta, cd.fecha, u.nombre, u.apellido, u.id_usuario, c.id_curso,
 (select fecha from calendario_dias cd1 where cd1.id_calendario = c.id_calendario order by fecha limit 1) as primero,
 (select fecha from calendario_dias cd1 where cd1.id_calendario = c.id_calendario order by fecha desc limit 1) as ultimo, c.color
 from calendario c inner join calendario_dias cd on c.id_calendario = cd.id_calendario
 left join calendario_usuarios cu on cu.id_calendario = c.id_calendario
 left join usuarios u on u.id_usuario = cu.id_usuario
 where MONTH(cd.fecha) = ? and YEAR(cd.fecha) = ? and DAY(cd.fecha) = ?";
        $parametros = array($mes, $anyo, $dia);
        if(isset($idUsuario)){
            $sql .= " and (u.id_usuario = ? or ? in (select gu.id_usuario from grupos_usuarios gu where gu.id_grupo = c.id_grupo))";
            array_push($parametros, $idUsuario);
            array_push($parametros, $idUsuario);
        }
        $sql .=  " order by cd.fecha, id_calendario";

        $bd->setConsulta($sql);

        $bd->ejecutar($parametros);

        $arrAgenda = array();

        $idCalendario = 0;
        
        $agenda = new Agenda(null, null, null, null, null, null, null);
        
        while ($it = $bd->resultado()) {
            if($idCalendario != $it["id_calendario"]){
                if($idCalendario != 0){
                    $agenda->setUsuario($listaUsuarios);
                    array_push($arrAgenda, $agenda);
                }
                
                $listaUsuarios = array();
                
                $agenda = new Agenda($it["id_calendario"], null, $it["titulo"],
                        $it["texto"], $it["alerta"], new Fecha($it["fecha"]), $it["color"]);
                $agenda->setPrimerDia(new Fecha($it["primero"]));
                $agenda->setUltimoDia(new Fecha($it["ultimo"]));
                
                $idCalendario = $it["id_calendario"];
            }
            
            $usuario = new Usuario($it["id_usuario"], null, null, null, $it["nombre"],
                    $it["apellido"], null, null, null, null, null, null, null, null,
                    null, null);
            array_push($listaUsuarios, $usuario);
        }
        
        if($agenda->getIdCalendario() != null){
            $agenda->setUsuario($listaUsuarios);
            array_push($arrAgenda, $agenda);
        }
        
        return $arrAgenda;
    }
    
    public static function altaCalendario($agenda, $listaUsuarios){
        $error = false;
        
        $bd = new BD();
        $bd->setConsulta("insert into calendario (titulo, texto, alerta, color, creador, id_curso, id_grupo) values (?, ?, ?, ?, ?, ?, ?)");
        
        $parametros = array($agenda->getTitulo(), $agenda->getTexto(),
            $agenda->getAlerta(), $agenda->getColor(),
            $_SESSION["usuario"]->getIdUsuario(), $agenda->getIdCurso(),
            $agenda->getIdGrupo());
        
        $bd->ejecutar($parametros);
        
        if($bd->filasModificadas() == 0){
            $error = true;
        }
        
        $bd->setConsulta("select id_calendario from calendario where titulo = ? order by id_calendario desc limit 1");
        
        $bd->ejecutar($agenda->getTitulo());
        
        if($bd->filasModificadas() == 0){
            $error = true;
        }
        
        if($it = $bd->resultado()){
            $agenda->setIdCalendario($it["id_calendario"]);
        }
        
        $bd->setConsulta("insert into calendario_usuarios (id_calendario, id_usuario) values (?,?)");
        
        foreach ($listaUsuarios as $idUsuario){
            $bd->ejecutar(array($agenda->getIdCalendario(), $idUsuario));
        }
        
        if($bd->filasModificadas() == 0){
            $error = true;
        }
        
        
        
        $fechaActual = $agenda->getPrimerDia();
        while($fechaActual <= $agenda->getUltimoDia()){
            
            $bd->setConsulta("insert into calendario_dias (id_calendario, fecha) values (?, FROM_UNIXTIME(?))");
            $parametros = array($agenda->getIdCalendario(), $fechaActual);
            $bd->ejecutar($parametros);
            $fechaActual += (60*60*24);
        }
        
        if($bd->filasModificadas() == 0){
            $error = true;
        }
        
        return $error;
        
    }
    
    public static function borrarCalendario($idCalendario){
        $bd = new BD();
        
        $bd->setConsulta("delete from calendario_usuarios where id_calendario = ?");
        $bd->ejecutar($idCalendario);
        
        $bd->setConsulta("delete from calendario_dias where id_calendario = ?");
        $bd->ejecutar($idCalendario);
        
        $bd->setConsulta("delete from calendario where id_calendario = ?");
        $bd->ejecutar($idCalendario);
        
        if($bd->filasModificadas() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function porId($idCalendario){
        $bd = new BD();
        
        $bd->setConsulta("select c.id_calendario, c.titulo, c.texto, c.alerta, c.color,
 c.creador, c.id_grupo, c.id_curso, cu.id_usuario, (select MAX(cd.fecha) from calendario_dias cd where cd.id_calendario = c.id_calendario) as dia_fin, (select min(cd.fecha) from calendario_dias cd where cd.id_calendario = c.id_calendario) as dia_inicio from calendario c left join calendario_usuarios cu on cu.id_calendario = c.id_calendario where c.id_calendario = ?");
        $bd->ejecutar($idCalendario);
        $arrUsuarios = array();
        $agenda = null;
        while($it = $bd->resultado()){
            $agenda = new Agenda($it["id_calendario"], null, $it["titulo"], $it["texto"],
                    $it["alerta"], null, $it["color"]);
            $agenda->setCreador($it["creador"]);
            $agenda->setPrimerDia(new Fecha($it["dia_inicio"]));
            $agenda->setUltimoDia(new Fecha($it["dia_fin"]));
            $agenda->setIdCurso($it["id_curso"]);
            $agenda->setIdGrupo($it["id_grupo"]);
            
            array_push($arrUsuarios, $it["id_usuario"]);
        }
        $agenda->setUsuario($arrUsuarios);
        return $agenda;
    }
    
    public static function modificar($agenda){
        
        $correcto = false;
        
        $bd = new BD();
        $bd->setConsulta("update calendario set titulo = ?, texto = ?, alerta = ?, color = ?, id_grupo = ?, id_curso = ? where id_calendario = ?");
        
        $parametros = array($agenda->getTitulo(), $agenda->getTexto(),
            $agenda->getAlerta(), $agenda->getColor(), $agenda->getIdGrupo(),
            $agenda->getIdCurso(), $agenda->getIdCalendario());
        
        $bd->ejecutar($parametros);
        
        if($bd->filasModificadas() > 0){
            $correcto = true;
        }
        
        $bd->setConsulta("delete from calendario_usuarios where id_calendario = ?");
        $bd->ejecutar($agenda->getIdCalendario());
        
        $bd->setConsulta("insert into calendario_usuarios (id_calendario, id_usuario) values (?,?)");
        
        foreach ($agenda->getUsuario() as $idUsuario){
            $bd->ejecutar(array($agenda->getIdCalendario(), $idUsuario));
        }
        
        if($bd->filasModificadas() > 0){
            $correcto = true;
        }
        
        $bd->setConsulta("delete from calendario_dias where id_calendario = ?");
        $bd->ejecutar($agenda->getIdCalendario());
        
        $fechaActual = $agenda->getPrimerDia();
        while($fechaActual <= $agenda->getUltimoDia()){
            $bd->setConsulta("insert into calendario_dias (id_calendario, fecha) values (".$agenda->getIdCalendario().", ".date("YmdHis", $fechaActual).")");
            $bd->ejecutar();
            $fechaActual += (60*60*24);
        }
        
        if($bd->filasModificadas() > 0){
            $correcto = true;
        }
        
        return $correcto;
    }
    
    public static function listarAlertas(){
        $bd = new BD();
        $bd->setConsulta("select  c.id_calendario, c.titulo, c.texto, c.alerta, c.color,
 c.creador, c.id_curso, c.id_grupo, mf.min_fecha, cu.id_usuario,
 u.nombre, u.apellido, u.email, 2 as horas
 from calendario as c inner join v_min_fecha_cal as mf on mf.id_calendario = c.id_calendario
 inner join calendario_usuarios cu on cu.id_calendario = c.id_calendario
 inner join usuarios u on u.id_usuario = cu.id_usuario
 where YEAR(min_fecha) = year(now()) and MONTH(min_fecha) = MONTH(now()) and
 DAY(min_fecha) = DAY(now()) and (HOUR(now())+2) = HOUR(min_fecha)
 and c.alerta = 1
 union
 select  c.id_calendario, c.titulo, c.texto, c.alerta, c.color,
 c.creador, c.id_curso, c.id_grupo, mf.min_fecha, u.id_usuario,
 u.nombre, u.apellido, u.email, 2
 from calendario as c inner join v_min_fecha_cal as mf on mf.id_calendario = c.id_calendario
 left join grupos g on g.id_grupo = c.id_grupo
 left join grupos_usuarios gu on g.id_grupo = gu.id_grupo
 left join usuarios u on u.id_usuario = gu.id_usuario
 where YEAR(min_fecha) = year(now()) and MONTH(min_fecha) = MONTH(now()) and
 DAY(min_fecha) = DAY(now()) and (HOUR(now())+2) = HOUR(min_fecha)
 and u.id_usuario is not null and c.alerta = 1
 union
 select  c.id_calendario, c.titulo, c.texto, c.alerta, c.color,
 c.creador, c.id_curso, c.id_grupo, mf.min_fecha, cu.id_usuario,
 u.nombre, u.apellido, u.email, 24
 from calendario as c inner join v_min_fecha_cal as mf on mf.id_calendario = c.id_calendario
 inner join calendario_usuarios cu on cu.id_calendario = c.id_calendario
 inner join usuarios u on u.id_usuario = cu.id_usuario
 where YEAR(min_fecha) = year(now()) and MONTH(min_fecha) = MONTH(now()) and
 (DAY(min_fecha)+1) = DAY(now()) and HOUR(now()) = HOUR(min_fecha)
 and c.alerta = 1
 union
 select  c.id_calendario, c.titulo, c.texto, c.alerta, c.color,
 c.creador, c.id_curso, c.id_grupo, mf.min_fecha, u.id_usuario,
 u.nombre, u.apellido, u.email, 24
 from calendario as c inner join v_min_fecha_cal as mf on mf.id_calendario = c.id_calendario
 left join grupos g on g.id_grupo = c.id_grupo
 left join grupos_usuarios gu on g.id_grupo = gu.id_grupo
 left join usuarios u on u.id_usuario = gu.id_usuario
 where YEAR(min_fecha) = year(now()) and MONTH(min_fecha) = MONTH(now()) and
 (DAY(min_fecha)+1) = DAY(now()) and HOUR(now()) = HOUR(min_fecha)
 and u.id_usuario is not null and c.alerta = 1");
        
        $bd->ejecutar();
        
        $listaAgenda = array();
        
        while($it = $bd->resultado()){
            $agenda = new Agenda($it["id_calendario"], $it["id_usuario"], $it["titulo"],
                    $it["texto"], 1, new Fecha($it["min_fecha"]), $it["color"]);
            $agenda->setNombreUsuario($it["nombre"]);
            $agenda->setApellidoUsuario($it["apellido"]);
            $agenda->setEmailUsuario($it["email"]);
            $agenda->setHoras($it["horas"]);
            array_push($listaAgenda, $agenda);
        }
        
        return $listaAgenda;
        
    }
    

}

class Agenda {

    private $idCalendario;
    private $usuario;
    private $titulo;
    private $texto;
    private $alerta;
    private $fecha;
    private $primerDia;
    private $ultimoDia;
    private $color;
    private $creador;
    private $idCurso;
    private $idGrupo;
    private $nombreUsuario;
    private $apellidoUsuario;
    private $emailUsuario;
    private $horas;

    function __construct($idCalendario, $usuario, $titulo, $texto, $alerta, $fecha, $color) {
        $this->idCalendario = $idCalendario;
        $this->usuario = $usuario;
        $this->titulo = $titulo;
        $this->texto = $texto;
        $this->alerta = $alerta;
        $this->fecha = $fecha;
        $this->color = $color;
    }

    public function getIdCalendario() {
        return $this->idCalendario;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getAlerta() {
        return $this->alerta;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getPrimerDia() {
        return $this->primerDia;
    }

    public function getUltimoDia() {
        return $this->ultimoDia;
    }
    
    public function getColor() {
        return $this->color;
    }
    
    public function getCreador() {
        return $this->creador;
    }
    
    public function getIdCurso() {
        return $this->idCurso;
    }

    public function getIdGrupo() {
        return $this->idGrupo;
    }
    
    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function getApellidoUsuario() {
        return $this->apellidoUsuario;
    }

    public function getEmailUsuario() {
        return $this->emailUsuario;
    }
    
    public function getHoras() {
        return $this->horas;
    }

    //SET ----------------------------------------------------------------------
    public function setIdCalendario($idCalendario) {
        $this->idCalendario = $idCalendario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function setAlerta($alerta) {
        $this->alerta = $alerta;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setPrimerDia($primerDia) {
        $this->primerDia = $primerDia;
    }

    public function setUltimoDia($ultimoDia) {
        $this->ultimoDia = $ultimoDia;
    }
    
    public function setColor($color) {
        $this->color = $color;
    }
    
    public function setCreador($creador) {
        $this->creador = $creador;
    }

    public function setIdCurso($idCurso) {
        $this->idCurso = $idCurso;
    }

    public function setIdGrupo($idGrupo) {
        $this->idGrupo = $idGrupo;
    }
    
    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setApellidoUsuario($apellidoUsuario) {
        $this->apellidoUsuario = $apellidoUsuario;
    }

    public function setEmailUsuario($emailUsuario) {
        $this->emailUsuario = $emailUsuario;
    }
    
    public function setHoras($horas) {
        $this->horas = $horas;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function presentar() {
        if(mb_strlen($this->titulo) > 18){
            return "<h4>". mb_substr($this->titulo, 0, 15) ."...</h4>";
        }else{
            return "<h4>". $this->titulo ."</h4>";
        }
    }

}