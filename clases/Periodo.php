<?php

require_once 'BD.php';
require_once 'Fecha.php';

/**
 * Clase que define los períodos de formación, útil para las estadisticas.
 *
 * @author Nachondo
 */
class Periodo {
    
    private $idPeriodo;
    private $titulo;
    private $fecha;
    private $idUsuario;
    
    function __construct($idPeriodo, $titulo, $fecha, $idUsuario) {
        $this->idPeriodo = $idPeriodo;
        $this->titulo = $titulo;
        $this->fecha = $fecha;
        $this->idUsuario = $idUsuario;
    }
    
    public function getIdPeriodo() {
        return $this->idPeriodo;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

}

class Periodo_BD{
    
    public static function cargar($idUsuario){
        $bd = new BD();
        $bd->setConsulta("select id_periodo, titulo, fecha from periodos where id_usuario = ? order by fecha");
        
        $bd->ejecutar($idUsuario);
        
        $arrListaPeriodos = array();
        while($it = $bd->resultado()){
            $fecha = new Fecha($it["fecha"]);
            $periodo = new Periodo($it["id_periodo"], $it["titulo"], $fecha, $idUsuario);
            array_push($arrListaPeriodos, $periodo);
        }
        
        return $arrListaPeriodos;
    }
    
    
    public static function alta($periodo){
        $bd = new BD();
        
        $bd->setConsulta("insert into periodos (titulo, fecha, id_usuario) values (?,"
                . $periodo->getFecha()->conFormatoAlta().", ?)");
        $parametros = array($periodo->getTitulo(), $periodo->getIdUsuario());
        $bd->ejecutar($parametros);
        
        return $bd->filasModificadas();
    }
    
    public static function borrar($idPeriodo){
        $bd = new BD();
        
        $bd->setConsulta("delete from periodos where id_periodo = ?");
        $bd->ejecutar($idPeriodo);
        
        return $bd->filasModificadas();
    }
}
