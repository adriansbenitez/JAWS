<?php

require_once 'BD.php';
require_once 'Fecha.php';

class Examen{
    
    private $idExamen;
    private $idResultado;
    private $titulo;
    private $idAutor;
    private $nombreAutor;
    private $fecha;
    private $tiempoRestante;
    private $finalizado;
    private $puntos;
    private $acertadas;
    private $fallidas;
    private $idUsuario;
    private $nombreUsuario;
    private $tiempo;
    private $listaNombres;
    private $activo;
    private $fechaFin;
    private $listaGrupos;
    private $nombresGrupos;
    
    function __construct($idExamen, $titulo, $idAutor, $fecha, $tiempo) {
        $this->idExamen = $idExamen;
        $this->titulo = $titulo;
        $this->idAutor = $idAutor;
        $this->fecha = $fecha;
        $this->tiempo = $tiempo;
    }
    
    public function getIdExamen() {
        return $this->idExamen;
    }

    public function getIdResultado() {
        return $this->idResultado;
    }

    public function getTitulo($conteo = null) {
        if(isset($conteo)){
            return mb_substr($this->titulo, 0, $conteo);
        }else{
            return $this->titulo;
        }
    }

    public function getIdAutor() {
        return $this->idAutor;
    }

    public function getNombreAutor() {
        return $this->nombreAutor;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getTiempoRestante() {
        if($this->tiempoRestante == "NaN"){
            return 0;
        }else{
            return $this->tiempoRestante;
        }
        
    }

    public function getFinalizado() {
        return $this->finalizado;
    }

    public function getPuntos() {
        return $this->puntos;
    }

    public function getAcertadas() {
        return $this->acertadas;
    }

    public function getFallidas() {
        return $this->fallidas;
    }
    
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }
    
    public function getTiempo() {
        return $this->tiempo;
    }
    
    public function getListaNombres() {
        return $this->listaNombres;
    }
    
    public function getActivo() {
        return $this->activo;
    }
    
    public function getFechaFin() {
        return $this->fechaFin;
    }
    
    public function getListaGrupos() {
        return $this->listaGrupos;
    }
    
    public function getNombresGrupos() {
        return $this->nombresGrupos;
    }

    //SET ----------------------------------------------------------------------
    
    public function setIdExamen($idExamen) {
        $this->idExamen = $idExamen;
    }

    public function setIdResultado($idResultado) {
        $this->idResultado = $idResultado;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setIdAutor($idAutor) {
        $this->idAutor = $idAutor;
    }

    public function setNombreAutor($nombreAutor) {
        $this->nombreAutor = $nombreAutor;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setTiempoRestante($tiempoRestante) {
        $this->tiempoRestante = $tiempoRestante;
    }

    public function setFinalizado($finalizado) {
        $this->finalizado = $finalizado;
    }

    public function setPuntos($puntos) {
        $this->puntos = $puntos;
    }

    public function setAcertadas($acertadas) {
        $this->acertadas = $acertadas;
    }

    public function setFallidas($fallidas) {
        $this->fallidas = $fallidas;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setTiempo($tiempo) {
        $this->tiempo = $tiempo;
    }
    
    public function setListaNombres($listaNombres) {
        $this->listaNombres = $listaNombres;
    }
    
    public function setActivo($activo) {
        $this->activo = $activo;
    }
    
    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }
    
    public function setListaGrupos($listaGrupos) {
        $this->listaGrupos = $listaGrupos;
    }
    
    public function setNombresGrupos($nombresGrupos) {
        $this->nombresGrupos = $nombresGrupos;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function puntuacion(){
        return ($this->acertadas*3) - $this->fallidas;
    }
    
    public function porcentaje(){
        if($this->puntos == 0){
            return "0,00";
        }
        return number_format(($this->puntuacion()/$this->puntos)*100, 2, ",", "");
    }
    
    public function blancas(){
        return ($this->puntos/3) - $this->acertadas - $this->fallidas;
    }
    
    public function totalPreguntas(){
        return $this->puntos/3;
    }
    
    public function elTiempo(){
        $tiempo = 0;
        if(!empty($this->tiempoRestante)){
            $tiempo = $this->tiempoRestante;
        }else{
            if(!empty($this->tiempo)){
                $tiempo = $this->tiempo;
            }
        }
        if($tiempo != 0){
            $horas = floor($tiempo/60);
            $minutos = $tiempo%60;
            return $horas.":".$minutos.":0";
        }
    }
    
    public function listaGruposComas(){
        $cadena = "";
        foreach ($this->listaGrupos as $idGrupo){
            if($cadena != ""){
                $cadena.= ",";
            }
            $cadena.=$idGrupo;
        }
        
        return $cadena;
    }
    
}

class Examen_BD{
    
    private static function consultaBasica(){
        return "select v.id_resultado, v.id_usuario, v.titulo, v.autor, v.id_examen, v.fecha, v.tiempo_restante, v.finalizado, v.puntos, v.acertadas, v.fallidas, u1.nombre+' '+u1.apellido as nombreAutor, u2.nombre+' '+u2.apellido as nombreUsuario, e.tiempo from v_resultados_completo v inner join usuarios u1 on u1.id_usuario = v.autor inner join usuarios u2 on u2.id_usuario = v.id_usuario inner join examenes e on e.id_examen = v.id_examen";
    }
    
    private static function llenarCompleto($it){
        $examen = new Examen($it["id_examen"], $it["titulo"], $it["autor"], $it["fecha"], $it["tiempo"]);
        $examen->setIdResultado($it["id_resultado"]);
        $examen->setIdUsuario($it["id_usuario"]);
        $examen->setTiempoRestante($it["tiempo_restante"]);
        $examen->setFinalizado($it["finalizado"]);
        $examen->setPuntos($it["puntos"]);
        $examen->setAcertadas($it["acertadas"]);
        $examen->setFallidas($it["fallidas"]);
        $examen->setNombreAutor($it["nombreAutor"]);
        $examen->setNombreUsuario($it["nombreUsuario"]);
        return $examen;
    }
    
    public static function listarExamenesRealizadosPorIdExamen($idExamen){
        
        $bd = new BD();
        
        $sql = Examen_BD::consultaBasica()." where v.id_examen = ?";
        
        $sql.=" order by v.fecha desc";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idExamen);
        
        $arrExamenes = array();
        
        while($it = $bd->resultado()){
            array_push($arrExamenes, Examen_BD::llenarCompleto($it));
        }
        return $arrExamenes;
    }
    
    public static function listarExamenesRealizados($idUsuario, $colaSql = null, $parametros = null){
        
        $bd = new BD();
        
        $sql = Examen_BD::consultaBasica()." where v.id_usuario = ?";
        
        if(isset($colaSql)){
            $sql.= $colaSql;
        }
        
        $sql.=" order by v.fecha desc";
        
        $bd->setConsulta($sql);
        
        if(isset($parametros)){
            array_unshift($parametros, $idUsuario);
            $bd->ejecutar($parametros);
        }else{
            $bd->ejecutar($idUsuario);
        }
        
        $arrExamenes = array();
        
        while($it = $bd->resultado()){
            array_push($arrExamenes, Examen_BD::llenarCompleto($it));
        }
        return $arrExamenes;
    }
    
    public static function porIdResultado($idResultado){
        
        $bd = new BD();
        
        $bd->setConsulta(Examen_BD::consultaBasica().
                " where id_resultado = ?");
        $bd->ejecutar($idResultado);
        
        if($it = $bd->resultado()){
            return Examen_BD::llenarCompleto($it);
        }
        return null;
    }
    
    public static function porId($idExamen){
        $bd = new BD();
        
        $bd->setConsulta("select e.id_examen, e.titulo, e.tiempo, e.fecha, e.fecha_fin, (select COUNT(ep.id_pregunta) from examenes_preguntas ep where e.id_examen = ep.id_examen)as total_preguntas, e.autor, u.nombre+' '+u.apellido as nombreAutor, e.activo from examenes e inner join usuarios u on u.id_usuario = e.autor where e.id_examen = ?");

        $bd->ejecutar($idExamen);
        
        if($it = $bd->resultado()){
            $fecha = new Fecha($it["fecha"]);
            
            $examen = new Examen($it["id_examen"], $it["titulo"], $it["autor"], $fecha,
                    $it["tiempo"]);
            $examen->setNombreAutor($it["nombreAutor"]);
            $examen->setPuntos($it["total_preguntas"]*3);
            $examen->setActivo($it["activo"]);
            
            if(isset($it["fecha_fin"])){
                $fechaFin = new Fecha($it["fecha_fin"]);
                $examen->setFechaFin($fechaFin);
            }
            
            $bd->setConsulta("select id_grupo from examenes_grupos where id_examen = ?");
            $bd->ejecutar($idExamen);
            $listaGrupos = array();
            while($it = $bd->resultado()){
                array_push($listaGrupos, $it["id_grupo"]);
            }
            
            $examen->setListaGrupos($listaGrupos);
            
            return $examen;
        }
        return null;
    }
    
    public static function listaExamenes($nivel, $idUsuario, $filtro, $primero, $tamanyoPagina, $pediodo = 0){
        $bd = new BD();
        
        $sql = "";
        if($nivel == 1){
            $sql = "SELECT e.id_examen, e.titulo, (select count(r.id_pregunta) from respuestas r inner join resultados_respuestas rres on r.id_respuesta = rres.id_respuesta inner join resultados res on res.id_resultado = rres.id_resultado where res.id_examen = e.id_examen and r.correcta = 1) correctas, (select count(r.id_pregunta)
 from respuestas r inner join resultados_respuestas rres on r.id_respuesta = rres.id_respuesta inner join resultados res on res.id_resultado = rres.id_resultado where res.id_examen = e.id_examen and r.correcta != 1) incorrectas, (select count(ep.id_pregunta) from examenes_preguntas ep inner join resultados res on res.id_examen = ep.id_examen where ep.id_examen = e.id_examen)*3 totales, e.fecha, e.activo, e.autor from examenes e left join usuarios_examenes ue on ue.id_examen = e.id_examen left join usuarios u on u.id_usuario = ue.id_usuario";
 if(isset($filtro)){
     $sql.=" where e.titulo like ? or u.nombre like ? or u.apellido like ?";
 }
 $sql .= " group by e.id_examen, e.titulo, e.fecha, e.activo, e.autor order by fecha desc, titulo, e.id_examen limit $primero, $tamanyoPagina";
        }else{
            $sql = "SELECT e.fecha, e.id_examen, e.titulo, (select count(r.id_pregunta) from respuestas r inner join resultados_respuestas rres on r.id_respuesta = rres.id_respuesta inner join resultados res on res.id_resultado = rres.id_resultado where res.id_examen = e.id_examen and r.correcta = 1 and res.id_usuario = ?) correctas, (select count(r.id_pregunta) from respuestas r inner join resultados_respuestas rres on r.id_respuesta = rres.id_respuesta inner join resultados res on res.id_resultado = rres.id_resultado where res.id_examen = e.id_examen and r.correcta != 1 and res.id_usuario = ?) incorrectas, (select count(ep.id_pregunta) from examenes_preguntas ep inner join resultados res on res.id_examen = ep.id_examen where ep.id_examen = e.id_examen and res.id_usuario = ?)*3 totales, e.fecha, e.activo, e.autor from examenes e left join usuarios_examenes ue on ue.id_examen = e.id_examen left join usuarios u on u.id_usuario = ue.id_usuario left join examenes_grupos eg on eg.id_examen = e.id_examen where e.activo = 1 and ((e.id_examen in (select eu.id_examen from usuarios_examenes eu where eu.id_usuario = ?)) or eg.id_grupo = (select g.id_grupo from grupos g inner join grupos_usuarios gu on gu.id_grupo = g.id_grupo where id_usuario = ?)) and (e.fecha_fin > NOW() or e.fecha_fin is null) and (e.fecha < NOW() or e.fecha is null)";
            if($pediodo > 0){
                $bd->setConsulta("select p.fecha, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha < p.fecha order by pr.fecha desc limit 1) antes, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha > p.fecha limit 1) despues from periodos p where p.id_periodo = ?");
                $bd->ejecutar(array($idUsuario, $idUsuario, $pediodo));
                $it = $bd->resultado();
                
                $fecha = str_replace("-", "", $it["fecha"]);
                $despues = str_replace("-", "", $it["despues"]);
                $antes = str_replace("-", "", $it["antes"]);
            }else if($pediodo == -1){
                $bd->setConsulta("select MAX(fecha) as fecha from periodos where id_usuario = ?");
                $bd->ejecutar($idUsuario, $pediodo);
                $it = $bd->resultado();
                $fecha = str_replace("-", "", $it["fecha"]);
            }
            $parametros = array($idUsuario, $idUsuario, $idUsuario, $idUsuario, $idUsuario);
            
            if($pediodo != 0){
                if($pediodo == -1){
                    $sql .=" and e.fecha >= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues != null){
                    $sql .=" and e.fecha >= ? and e.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $despues != null){
                    $sql .=" and e.fecha <= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues == null){
                    $sql .=" and e.fecha >= ? and e.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $antes == null){
                    $sql .=" and e.fecha <= ?";
                    array_push($parametros, $fecha);
                }
            }
            
        $sql.=" group by e.fecha, e.id_examen, e.titulo, e.activo order by fecha desc, e.id_examen desc limit $primero, $tamanyoPagina";
        }
        $bd->setConsulta($sql);
        if($nivel == 1){
            $parametros = array();
            if(isset($filtro)){
                $parametros = array('%'.$filtro.'%', '%'.$filtro.'%', '%'.$filtro.'%');
            }
            
            $bd->ejecutar($parametros);
        }else{
            $bd->ejecutar($parametros);
        }
        $arrExamenes = array();
        
        $bd2 = new BD();
        
        while($it = $bd->resultado()){
            $examen = new Examen($it["id_examen"], $it["titulo"], $it["autor"], $it["fecha"], null);
            $examen->setActivo($it["activo"]);
            $examen->setAcertadas($it["correctas"]);
            $examen->setFallidas($it["incorrectas"]);
            $examen->setPuntos($it["totales"]);
            if($nivel == 1){
                $bd2->setConsulta("select concat(u.nombre,', ', u.apellido) nombre from usuarios u inner join usuarios_examenes ue on ue.id_usuario = u.id_usuario inner join examenes e on e.id_examen = ue.id_examen where e.id_examen = ?");
                $bd2->ejecutar($examen->getIdExamen());
                $arrNombres = array();
                while($it2 = $bd2->resultado()){
                    array_push($arrNombres, $it2["nombre"]);
                }
                $examen->setListaNombres($arrNombres);
                
                $bd2->setConsulta("select eg.id_grupo, g.nombre from grupos g inner join examenes_grupos eg on eg.id_grupo = g.id_grupo where eg.id_examen = ?");
                $bd2->ejecutar($examen->getIdExamen());
                $arrNombresGrupos = array();
                while ($it2 = $bd2->resultado()){
                    array_push($arrNombresGrupos, $it2["nombre"]);
                }
                $examen->setNombresGrupos($arrNombresGrupos);
            }
            
            array_push($arrExamenes, $examen);
        }
        return $arrExamenes;
    }
    
    public static function contarExamenes($nivel, $idUsuario, $filtro, $pediodo = 0){
        $bd = new BD();
        if($nivel == 1){
            if(isset($filtro)){
                $bd->setConsulta("select count(e.id_examen) total from examenes e where e.titulo like ? or e.id_examen in (select ue.id_examen from usuarios_examenes ue inner join usuarios u on u.id_usuario = ue.id_examen where u.nombre like ? or u.apellido like ?)");
                $parametros = array("%".$filtro."%", "%".$filtro."%", "%".$filtro."%");
                $bd->ejecutar($parametros);
            }else{
                $bd->setConsulta("select COUNT(id_examen) total from examenes");
                $bd->ejecutar();
            }
            
        }else{
            $parametros = array($idUsuario, $idUsuario);
            
            $fecha = null;
            $antes = null;
            $despues = null;
            
            if($pediodo > 0){
                $bd->setConsulta("select p.fecha, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha < p.fecha order by pr.fecha desc limit 1) antes, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha > p.fecha limit 1) despues from periodos p where p.id_periodo = ?");
                $bd->ejecutar(array($idUsuario, $idUsuario, $pediodo));
                $it = $bd->resultado();
                
                $fecha = str_replace("-", "", $it["fecha"]);
                $despues = str_replace("-", "", $it["despues"]);
                $antes = str_replace("-", "", $it["antes"]);
            }else if($pediodo == -1){
                $bd->setConsulta("select MAX(fecha) as fecha from periodos where id_usuario = ?");
                $bd->ejecutar($idUsuario, $pediodo);
                $it = $bd->resultado();
                $fecha = str_replace("-", "", $it["fecha"]);
            }
            
            
            $sql = "select COUNT(e.id_examen) total from usuarios_examenes ue right join examenes e on e.id_examen = ue.id_examen left join examenes_grupos eg on eg.id_examen = e.id_examen where (ue.id_usuario = ? or eg.id_grupo = (select g.id_grupo from grupos g inner join grupos_usuarios gu on gu.id_grupo = g.id_grupo where id_usuario = ?)) and (e.fecha_fin > NOW() or e.fecha_fin is null) and (e.fecha < NOW() or e.fecha is null)";
            if($pediodo != 0){
                if($pediodo == -1){
                    $sql .=" and e.fecha >= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues != null){
                    $sql .=" and e.fecha >= ? and e.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $despues != null){
                    $sql .=" and e.fecha <= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues == null){
                    $sql .=" and e.fecha >= ? and e.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $antes == null){
                    $sql .=" and e.fecha <= ?";
                    array_push($parametros, $fecha);
                }
            }
            
            $bd->setConsulta($sql);
            $bd->ejecutar($parametros);
        }
        if($it = $bd->resultado()){
            return $it["total"];
        }
        return 0;
    }
    
    public static function cambiarEstado($examen){
        $bd = new BD();
        $bd->setConsulta("update examenes set activo = ? where id_examen = ?");
        $parametros = array($examen->getActivo(), $examen->getIdExamen());
        $bd->ejecutar($parametros);
        return $bd->filasModificadas();
    }
    
    public static function borrarExamen($idExamen){
        $bd = new BD();
        $bd->setConsulta("delete from resultados_respuestas
 where id_resultado in (select id_resultado from resultados where id_examen = ?)");
        $bd->ejecutar($idExamen);
        
        $bd->setConsulta("delete from resultados_respuestas_dudas
 where id_resultado in (select id_resultado from resultados where id_examen = ?)");
        $bd->ejecutar($idExamen);
        
        $bd->setConsulta("delete from resultados where id_examen = ?");
        $bd->ejecutar($idExamen);
        
        $bd->setConsulta("delete from examenes_preguntas where id_examen = ?");
        $bd->ejecutar($idExamen);
        
        $bd->setConsulta("delete from usuarios_examenes where id_examen = ?");
        $bd->ejecutar($idExamen);
        
        $bd->setConsulta("delete from examenes where id_examen = ?");
        $bd->ejecutar($idExamen);
        
        return $bd->filasModificadas();
    }
        
    public static function examenARendir($idUsuario, $idExamen){
        
        $bd = new BD();
        
	$sql = "select e.id_examen, e.titulo, e.tiempo,
             (select COUNT(ep.id_pregunta) from examenes_preguntas ep where e.id_examen = ep.id_examen)
             total_preguntas, (select r.tiempo_restante from resultados r where r.id_examen = e.id_examen
             and r.finalizado = 0 and r.id_usuario = ? limit 1) tiempo_restante, (select r.id_resultado from resultados r where r.id_examen = e.id_examen and r.finalizado = 0 and r.id_usuario = ? limit 1) id_resultado,
             (select count(r.id_resultado) from resultados r where r.id_examen = e.id_examen and r.finalizado = 0
             and r.id_usuario = ?) sin_terminar from examenes e where e.id_examen = ?";
	$bd->setConsulta($sql);
        $parametros = array($idUsuario, $idUsuario, $idUsuario, $idExamen);
        $bd->ejecutar($parametros);
        
        if($it = $bd->resultado()){
            $examen = new Examen($it["id_examen"], $it["titulo"], null, null, $it["tiempo"]);
            $examen->setTiempoRestante($it["tiempo_restante"]);
            if(isset($it["id_resultado"])){
                $examen->setIdResultado($it["id_resultado"]);
            }else{
                $examen->setIdResultado(0);
            }
            $examen->setFinalizado($it["sin_terminar"]);
            $examen->setPuntos($it["total_preguntas"]*3);
        }
        
        return $examen;
    }

    public static function borrarRespuestas($idResultado){
        
        $bd = new BD();
        
        $bd->setConsulta("delete from resultados_respuestas where id_resultado = ?");
        $bd->ejecutar($idResultado);
    }

    public static function modificar($examen){
        $bd = new BD();
        
        $bd->setConsulta("update resultados set fecha = now(), tiempo_restante = ?, finalizado = ? where id_resultado = ?");
        $parametros = array($examen->getTiempoRestante(), $examen->getFinalizado(), $examen->getIdResultado());
        $bd->ejecutar($parametros);
        return $bd->filasModificadas();
    }

    public static function alta($idUsuario, $examen){
        $bd = new BD();
        
        $bd->setConsulta("insert into resultados (id_usuario, id_examen, fecha, tiempo_restante, finalizado) values (?, ?, now(), ?, ?)");
        $parametros = array($idUsuario, $examen->getIdExamen(), $examen->getTiempoRestante(), $examen->getFinalizado());
        $bd->ejecutar($parametros);
    }
    
    public static function ultimoUsuario($idUsuario){
        
        $bd = new BD();
        
        $bd->setConsulta("select id_resultado, fecha from resultados r where r.id_usuario = ? order by fecha desc limit 1");
        $bd->ejecutar($idUsuario);
        
        if($it = $bd->resultado()){
            return $it["id_resultado"];
        }
        return 0;
    }
    
    public static function altaRespuestas($idResultado, $listaRespuestas){
        $bd = new BD();
        
        $bd->setConsulta("insert into resultados_respuestas (id_resultado, id_respuesta)".
            " values(?, ?)");
        $parametros = array($idResultado, 0);
        foreach ($listaRespuestas as $idRespuesta){
            $parametros[1] = $idRespuesta;
            $bd->ejecutar($parametros);
        }
    }
    
    public static function altaPosibles($idResultado, $listaPosibles){
        $bd = new BD();
        
        $bd->setConsulta("insert into resultados_respuestas_dudas (id_resultado, id_respuesta)".
            " values(?, ?)");
        $parametros = array($idResultado, 0);
        foreach ($listaPosibles as $idRespuesta){
            $parametros[1] = $idRespuesta;
            $bd->ejecutar($parametros);
        }
    }
    
    public static function actualizar($examen, $listaAlumnos){
        $bd = new BD();
        
        $mofif = 0;
        
        if($examen->getFechaFin() != null){
            $fechaFin = ", fecha_fin = ".
            $examen->getFechaFin()->conFormatoAlta();
        }
        
        $bd->setConsulta("update examenes set tiempo=?, titulo=?, fecha = ".
            $examen->getFecha()->conFormatoAlta()." where id_examen = ?");
        
        $parametros = array($examen->getTiempo(), $examen->getTitulo(), $examen->getIdExamen());
        $bd->ejecutar($parametros);
        $mofif += $bd->filasModificadas();
        
        $bd->setConsulta("delete from examenes_grupos where id_examen = ?");
        $bd->ejecutar($examen->getIdExamen());
        
        $bd->setConsulta("insert into examenes_grupos (id_examen, id_grupo) values (?, ?)");
        foreach ($examen->getListaGrupos() as $idGrupo){
            $bd->ejecutar(array($examen->getIdExamen(), $idGrupo));
            $mofif += $bd->filasModificadas();
        }
        
        if($examen->getFechaFin() != null){
            $bd->setConsulta("update examenes set fecha_fin = ".
            $examen->getFechaFin()->conFormatoAlta()." where id_examen = ?");
        
            $bd->ejecutar($examen->getIdExamen());
            $mofif += $bd->filasModificadas();
        }
        
        $bd->setConsulta("delete from usuarios_examenes where id_examen = ?");
        $bd->ejecutar($examen->getIdExamen());
        
        $bd->setConsulta("insert into usuarios_examenes (id_examen, id_usuario) values (?, ?)");
        
        foreach ($listaAlumnos as $idUsuario){
            if(!empty($idUsuario)){
                $parametros = array($examen->getIdExamen(), $idUsuario);
                $bd->ejecutar($parametros);
                $mofif += $bd->filasModificadas();
            }
        }
        
        if($mofif > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function borrarResultado($idResultado){
        $bd = new BD();
        
        $bd->setConsulta("delete from resultados_respuestas where id_resultado = ?");
        $bd->ejecutar($idResultado);
        
        $bd->setConsulta("delete from resultados where id_resultado = ?");
        $bd->ejecutar($idResultado);
        
        if($bd->filasModificadas() > 0){
            return true;
        }
        return false;
    }

    public static function eliminarPreguntas($idExamen){
        $bd = new BD();
        $bd->setConsulta("delete from examenes_preguntas where id_examen = ?");
        $bd->ejecutar($idExamen);

        if($bd->filasModificadas() > 0){
            return true;
        }
        return false;
    }
}