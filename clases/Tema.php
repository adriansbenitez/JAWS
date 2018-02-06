<?php

require_once 'BD.php';

/**
 * Description of Tema
 *
 * @author Nachondo
 */
class Tema {
    
    private $idTema;
    private $nombre;
    private $totalPreguntas;
    private $maxPreguntas;
    private $precio;
    private $cantidadPreguntas;
    
    function __construct($idTema, $nombre, $totalPreguntas) {
        $this->idTema = $idTema;
        $this->nombre = $nombre;
        $this->totalPreguntas = $totalPreguntas;
    }
    
    public function getIdTema() {
        return $this->idTema;
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
    
    public function getPrecio() {
        return $this->precio;
    }
    
    public function getCantidadPreguntas() {
        return $this->cantidadPreguntas;
    }

    //SET ----------------------------------------------------------------------
    public function setIdTema($idTema) {
        $this->idTema = $idTema;
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
    
    public function setPrecio($precio) {
        $this->precio = $precio;
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

class Tema_BD{
    
    public static function porId($idTema){
        $bd = new BD();
        $bd->setConsulta("select t.id_tema, t.nombre, " .
            " (select COUNT(p.id_pregunta)" .
            " from unidades u" .
            " inner join anyos a on a.id_unidad = u.id_unidad" .
            " inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo" .
            " inner join preguntas p on p.id_pregunta = ap.id_pregunta" .
            " where u.id_tema = t.id_tema) total_preguntas, t.precio" .
            " from temas t" .
            " where t.id_tema = ?");
        $bd->ejecutar($idTema);
        
        if($it = $bd->resultado()){
            $tema = new Tema($idTema, $it["nombre"], $it["total_preguntas"]);
            $tema->setPrecio($it["precio"]);
            
            return $tema;
        }
        
        return null;
    }
    
    public static function listaIdCurso($idCurso){
        
        $bd = new BD();
        $sql = "select t.id_tema, t.nombre," .
            " (select COUNT(p.id_pregunta)" .
            " from unidades u" .
            " inner join anyos a on a.id_unidad = u.id_unidad" .
            " inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo" .
            " inner join preguntas p on p.id_pregunta = ap.id_pregunta" .
            " where u.id_tema = t.id_tema) total_preguntas," .
            " (select COUNT(p.id_pregunta)" .
            " from temas te inner join unidades u on u.id_tema = te.id_tema" .
            " inner join anyos a on a.id_unidad = u.id_unidad" .
            " inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo" .
            " inner join preguntas p on p.id_pregunta = ap.id_pregunta" .
            " where te.id_curso = ?) max_preguntas" .
            " from temas t" .
            " where t.id_curso = ? order by nombre";
        $bd->setConsulta($sql);
        $parametros = array($idCurso, $idCurso);
        $bd->ejecutar($parametros);
        $arrTemas = array();
        while($it = $bd->resultado()){
            $tema = new Tema($it["id_tema"], $it["nombre"], $it["total_preguntas"]);
            $tema->setMaxPreguntas($it["max_preguntas"]);
            array_push($arrTemas, $tema);
        }
        return $arrTemas;
    }
    
    public static function modificar($tema){
        $bd = new BD();
        $bd->setConsulta("update temas set nombre = ?, precio = ? where id_tema = ?");
        $parametros = array($tema->getNombre(), $tema->getPrecio(), $tema->getIdTema());
        $bd->ejecutar($parametros);
        if($bd->filasModificadas() > 0){
            return true;
        }
        return false;
    }
    
    public static function listaPorAcceso($idUsuario){
        $bd = new BD();
        
        $bd->setConsulta("select t.id_tema, t.nombre, null as total_preguntas, null as max_preguntas from temas t inner join temas_usuarios tu on tu.id_tema = t.id_tema where tu.id_usuario = ? order by nombre");
        $bd->ejecutar($idUsuario);
        $arrTemas = array();
        while($it = $bd->resultado()){
            $tema = new Tema($it["id_tema"], $it["nombre"], $it["total_preguntas"]);
            $tema->setMaxPreguntas($it["max_preguntas"]);
            array_push($arrTemas, $tema);
        }
        return $arrTemas;
    }    
}