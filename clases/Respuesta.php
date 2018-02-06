<?php

require_once 'BD.php';

/**
 * Description of Respuesta
 *
 * @author Nachondo
 */

class Respuesta {
    
    private $idRespuesta;
    private $idPregunta;
    private $texto;
    private $correcta;
    private $marcada;
    private $imagen;
    private $activo;
    private $contestada;
    private $archivo;
    
    private $posible;
    
    
    function __construct($idRespuesta, $idPregunta, $texto, $correcta, $marcada, $imagen) {
        $this->idRespuesta = $idRespuesta;
        $this->idPregunta = $idPregunta;
        $this->texto = $texto;
        $this->correcta = $correcta;
        $this->marcada = $marcada;
        $this->imagen = $imagen;
        $this->posible = false;
    }
    
    
    public function getIdRespuesta() {
        return $this->idRespuesta;
    }

    public function getIdPregunta() {
        return $this->idPregunta;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getCorrecta() {
        return $this->correcta;
    }

    public function getMarcada() {
        return $this->marcada;
    }

    public function getImagen() {
        return $this->imagen;
    }
    
    public function getActivo() {
        return $this->activo;
    }
    
    public function getContestada() {
        return $this->contestada;
    }
    
    public function getArchivo() {
        return $this->archivo;
    }
    
    function getPosible() {
        return $this->posible;
    }

    //SET ----------------------------------------------------------------------
    public function setIdRespuesta($idRespuesta) {
        $this->idRespuesta = $idRespuesta;
    }

    public function setIdPregunta($idPregunta) {
        $this->idPregunta = $idPregunta;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function setCorrecta($correcta) {
        $this->correcta = $correcta;
    }

    public function setMarcada($marcada) {
        $this->marcada = $marcada;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }
    
    public function setActivo($activo) {
        $this->activo = $activo;
    }
    
    public function setContestada($contestada) {
        $this->contestada = $contestada;
    }
    
    public function setArchivo($archivo) {
        $this->archivo = $archivo;
    }
    
    function setPosible($posible) {
        $this->posible = $posible;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function imprimeTexto(){
        return str_replace("@i", '<img src="upload/pregunta/'.$this->imagen.'?ver='. time().'"/>',
            $this->texto);
    }
}

class Respuesta_BD{
    
    public static function modificaRespuesta($respuesta){
        $bd = new BD();
        $sql = "update respuestas set texto = ?, correcta = ?, imagen = ? where id_respuesta = ?";
        if($respuesta->getImagen() != null){
            move_uploaded_file($respuesta->getImagen(), "../upload/pregunta/R".$respuesta->getIdRespuesta().".png");
            $respuesta->setImagen("R".$respuesta->getIdRespuesta().".png");
        }
        if($respuesta->getArchivo() != null){
            $respuesta->setImagen($respuesta->getArchivo());
        }
        $parametros = array($respuesta->getTexto(), $respuesta->getCorrecta(),
            $respuesta->getImagen(), $respuesta->getIdRespuesta());
        
        $bd->setConsulta($sql);
        $bd->ejecutar($parametros);
    }
    
    public static function altaRespuesta($respuesta){
        $bd = new BD();
        $sql = "insert into respuestas (id_pregunta, texto, correcta, imagen) values(?, ?, ?, ?)";
        $bd->setConsulta($sql);
        if($respuesta->getImagen() != null){
            move_uploaded_file($respuesta->getImagen(), "../upload/pregunta/R".$respuesta->getIdRespuesta().".png");
            $respuesta->setImagen("R".$respuesta->getIdRespuesta().".png");
        }
        $parametros = array($respuesta->getIdPregunta(), $respuesta->getTexto(),
            $respuesta->getCorrecta(), $respuesta->getImagen());
        $bd->ejecutar($parametros);
    }
    
    public static function eliminarImagen($idRespuesta){
        $bd = new BD();
        $sql = "update respuestas set imagen = null where id_respuesta = ?";
        $bd->setConsulta($sql);
        $bd->ejecutar($idRespuesta);
    }
    
    public static function activaRespuesta($idRespuesta){
        $bd = new BD();
        
        $bd->setConsulta("update respuestas set activo = 1 where id_respuesta = ?");
        $bd->ejecutar($idRespuesta);

        return $bd->filasModificadas();
    }
    
    public static function desactivaRespuesta($idRespuesta){
        $bd = new BD();
        
        $bd->setConsulta("update respuestas set activo = 0 where id_respuesta = ?");
        $bd->ejecutar($idRespuesta);

        return $bd->filasModificadas();
    }


    public static function eliminaRespuesta($idRespuesta){
        $bd = new BD();
        
        $bd->setConsulta("delete from resultados_respuestas where id_respuesta = ?");
        $bd->ejecutar($idRespuesta);

        $bd->setConsulta("delete from respuestas where id_respuesta = ?");
        $bd->ejecutar($idRespuesta);
        
        return $bd->filasModificadas();
    }
    
}