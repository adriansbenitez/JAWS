<?php

require_once 'BD.php';

class Anyo{
    
    private $idAnyo;
    private $idUnidad;
    private $nombre;
    private $totalPreguntas;
    private $maxPreguntas;
    private $nombreUnidad;
    private $nombreTema;
    private $nombreCurso;
    
    function __construct($idAnyo, $nombre, $totalPreguntas) {
        $this->idAnyo = $idAnyo;
        $this->nombre = $nombre;
        $this->totalPreguntas = $totalPreguntas;
    }
    
    public function getIdAnyo() {
        return $this->idAnyo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTotalPreguntas() {
        return $this->totalPreguntas;
    }

    public function getMaxPreguntas() {
        return $this->maxPreguntas;
    }
    
    public function getNombreUnidad() {
        return $this->nombreUnidad;
    }
    
    public function getNombreTema() {
        return $this->nombreTema;
    }
    
    public function getIdUnidad() {
        return $this->idUnidad;
    }
    
    public function getNombreCurso() {
        return $this->nombreCurso;
    }

    //SET ----------------------------------------------------------------------
    public function setIdAnyo($idAnyo) {
        $this->idAnyo = $idAnyo;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setTotalPreguntas($totalPreguntas) {
        $this->totalPreguntas = $totalPreguntas;
    }

    public function setMaxPreguntas($maxPreguntas) {
        $this->maxPreguntas = $maxPreguntas;
    }
    
    public function setNombreUnidad($nombreUnidad) {
        $this->nombreUnidad = $nombreUnidad;
    }
    
    public function setNombreTema($nombreTema) {
        $this->nombreTema = $nombreTema;
    }
    
    public function setIdUnidad($idUnidad) {
        $this->idUnidad = $idUnidad;
    }
    
    public function setNombreCurso($nombreCurso) {
        $this->nombreCurso = $nombreCurso;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function peso(){
        $temp = ($this->totalPreguntas*100) / $this->maxPreguntas;
        return number_format($temp, 0);
    }

}

class Anyo_BD{
    
    public static function listaIdUnidad($idUnidad){
        $bd = new BD();
        $sql = "select a.id_anyo, a.nombre, (select COUNT(p.id_pregunta)" .
            " from anyos_preguntas ap" .
            " inner join preguntas p on p.id_pregunta = ap.id_pregunta" .
            " where ap.id_anyo = a.id_anyo) total_preguntas," .
            " (select COUNT(p.id_pregunta)" .
            " from anyos an" .
            " inner join anyos_preguntas ap on ap.id_anyo = an.id_anyo" .
            " inner join preguntas p on p.id_pregunta = ap.id_pregunta" .
            " where an.id_unidad = ?) max_preguntas" .
            " from anyos a" .
            " where a.id_unidad = ? order by nombre";
        $bd->setConsulta($sql);
        $parametros = array($idUnidad, $idUnidad);
        $bd->ejecutar($parametros);
        $arrAnyos = array();
        while($it = $bd->resultado()){
            $anyo = new Anyo($it["id_anyo"], $it["nombre"], $it["total_preguntas"]);
            $anyo->setMaxPreguntas($it["max_preguntas"]);
            array_push($arrAnyos, $anyo);
        }
        return $arrAnyos;
    }
    
    public static function modificar($anyo){
        $bd = new BD();
        $bd->setConsulta("update anyos set nombre = ? where id_anyo = ?");
        $parametros = array($anyo->getNombre(), $anyo->getIdAnyo());
        $bd->ejecutar($parametros);
        if($bd->filasModificadas() > 0){
            return true;
        }
        return false;
    }
    
    public static function porIf($anyo){
        $bd = new BD();
        $bd->setConsulta("select a.id_anyo, a.nombre, u.nombre as nombre_unidad, t.nombre as nombre_tema,c.nombre as nombre_curso" .
 " from anyos as a inner join unidades as u on u.id_unidad = a.id_unidad" .
 " inner join temas as t on t.id_tema = u.id_tema" .
 " inner join cursos as c on c.id_curso = t.id_curso" .
 " where a.id_anyo = ?");
        $parametros = array($anyo);
        $bd->ejecutar($parametros);
        if($it = $bd->resultado()){
            $anyo = new Anyo($it["id_anyo"], $it["nombre"], null);
            $anyo->setNombreTema($it["nombre_tema"]);
            $anyo->setNombreUnidad($it["nombre_unidad"]);
            $anyo->setNombreCurso($it["nombre_curso"]);
            return $anyo;
        }
        return null;
    }
    
}