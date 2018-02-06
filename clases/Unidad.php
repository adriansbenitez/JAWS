<?php

require_once 'BD.php';

class Unidad{
    
    private $idUnidad;
    private $nombre;
    private $activo;
    private $totalPreguntas;
    private $maxPreguntas;
    private $idTema;
    private $cantidadPreguntas;
    
    function __construct($idUnidad, $nombre, $activo, $totalPreguntas) {
        $this->idUnidad = $idUnidad;
        $this->nombre = $nombre;
        $this->activo = $activo;
        $this->totalPreguntas = $totalPreguntas;
    }
    
    public function getIdUnidad() {
        return $this->idUnidad;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function getTotalPreguntas() {
        return $this->totalPreguntas;
    }

    public function getMaxPreguntas() {
        return $this->maxPreguntas;
    }
    
    public function getIdTema() {
        return $this->idTema;
    }
    
    public function getCantidadPreguntas() {
        return $this->cantidadPreguntas;
    }

    //SET ----------------------------------------------------------------------
    public function setIdUnidad($idUnidad) {
        $this->idUnidad = $idUnidad;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }

    public function setTotalPreguntas($totalPreguntas) {
        $this->totalPreguntas = $totalPreguntas;
    }

    public function setMaxPreguntas($maxPreguntas) {
        $this->maxPreguntas = $maxPreguntas;
    }
    
    public function setIdTema($idTema) {
        $this->idTema = $idTema;
    }
    
    public function setCantidadPreguntas($cantidadPreguntas) {
        $this->cantidadPreguntas = $cantidadPreguntas;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function peso(){
        $temp = ($this->totalPreguntas*100) / $this->maxPreguntas;
        return number_format($temp, 0);
    }

}

class Unidad_BD{
    
    public static function porId($idUnidad){
        $bd = new BD();
        $sql = "select u.id_unidad, u.nombre, u.activo, (select COUNT(p.id_pregunta) from anyos a inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where u.id_unidad = a.id_unidad) total_preguntas from unidades u where u.id_unidad = ?";
        $bd->setConsulta($sql);
        $parametros = array($idUnidad);
        $bd->ejecutar($parametros);
        
        if($it = $bd->resultado()){
            $unidad = new Unidad($it["id_unidad"], $it["nombre"], $it["activo"],
                    $it["total_preguntas"]);
            return $unidad;
        }
        return null;
    }
    
    public static function listaIdTema($idTema){
        $bd = new BD();
        $sql = "select u.id_unidad, u.nombre, u.activo, (select COUNT(p.id_pregunta) from anyos a inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where u.id_unidad = a.id_unidad) total_preguntas, (select COUNT(p.id_pregunta) from unidades un inner join anyos a on a.id_unidad = un.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where un.id_tema = ?) max_preguntas from unidades u where u.id_tema = ? order by nombre";
        $bd->setConsulta($sql);
        $parametros = array($idTema, $idTema);
        $bd->ejecutar($parametros);
        $arrUnidades = array();
        while($it = $bd->resultado()){
            $unidad = new Unidad($it["id_unidad"], $it["nombre"], $it["activo"],
                    $it["total_preguntas"]);
            $unidad->setMaxPreguntas($it["max_preguntas"]);
            array_push($arrUnidades, $unidad);
        }
        return $arrUnidades;
    }
    
    public static function modificar($unidad){
        $bd = new BD();
        $bd->setConsulta("update unidades set nombre = ? where id_unidad = ?");
        $parametros = array($unidad->getNombre(), $unidad->getIdUnidad());
        $bd->ejecutar($parametros);
        if($bd->filasModificadas() > 0){
            return true;
        }
        return false;
    }
    
    public static function marcar_entidades($idUnidad, $activo){
        $bd = new BD();
        $bd->setConsulta("update anyos set activo = ? where id_unidad = ?");
        $bd->ejecutar(array($activo, $idUnidad));
        
        return $bd->filasModificadas();
    }
    
    public static function porVirtual($idPregunta){
        $bd = new BD();
        $bd->setConsulta("select concat(c.nombre, '/', t.nombre, '/', u.nombre) as nombre, u.activo, u.id_unidad from unidades u inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso inner join preguntas_virtual pv on pv.id_unidad = u.id_unidad where pv.id_pregunta = ?");
        $bd->ejecutar($idPregunta);
        if($it = $bd->resultado()){
            return new Unidad($it["id_unidad"], $it["nombre"], $it["activo"], null);
        }
        return null;
    }
    
}