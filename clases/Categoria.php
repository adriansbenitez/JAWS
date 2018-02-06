<?php

require_once 'BD.php';

class Categoria{
    
    private $id;
    private $nombre;
    private $nombreCompleto;
    private $precio;
    private $nivel;
    private $cuarto;
    
    function __construct($id, $nombre, $nombreCompleto, $precio) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nombreCompleto = $nombreCompleto;
        $this->precio = $precio;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    public function getPrecio() {
        return number_format($this->precio, 2, ".", "'");
    }
    
    public function getNivel() {
        return $this->nivel;
    }
    
    public function getCuarto() {
        return $this->cuarto;
    }

    //SET ----------------------------------------------------------------------

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setNombreCompleto($nombreCompleto) {
        $this->nombreCompleto = $nombreCompleto;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }
    
    public function setCuarto($cuarto) {
        $this->cuarto = $cuarto;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function nombreCategoria(){
        switch ($this->nivel){
            case 1:
                return "Curso";
            case 2:
                return "Asignatura";
            case 3:
                return "Unidad temática";
            case 4:
                return "Año (nivel 4)";
        }
    }
   
}

class Categoria_BD{
    
    public static function porId($idCat, $tipo){
        $bd = new BD();
        
        $sql = "";
        
        switch ($tipo){
            case 1:
                $sql = "select id_curso elid, nombre, nombre nombreCompleto, precio, cuarto from cursos" .
                    " where id_curso = ?";
                break;
            
            case 2:
                $sql = "select t.id_tema elid, t.nombre, concat(t.nombre,' (',c.nombre,')') nombreCompleto, t.precio" .
                    " from temas t inner join cursos c on c.id_curso = t.id_curso" .
                    " where t.id_tema = ?";
                break;
            
            case 3:
                $sql = "select u.id_unidad elid, u.nombre nombre, concat(u.nombre,' (',t.nombre,')') nombreCompleto, null precio" .
                    " from unidades u inner join temas t on t.id_tema = u.id_tema" .
                    " where u.id_unidad = ?";
                break;
            
            case 4:
                $sql = "select a.id_anyo elid, a.nombre, concat(a.nombre,' (',u.nombre,')') nombreCompleto, null precio" .
                    " from anyos a inner join unidades u on a.id_unidad = u.id_unidad" .
                    " where a.id_anyo = ?";
                break;
        }
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idCat);
        
        if($it = $bd->resultado()){
            $categoria = new Categoria($it["elid"], $it["nombre"], $it["nombreCompleto"], $it["precio"]);
            if($tipo == 1){
                $categoria->setCuarto($it["cuarto"]);
            }
            $categoria->setNivel($tipo);
            return $categoria;
        }
        return null;
    }
    
    public static function alta($categoria){
        $bd = new BD();
        $parametros = array();
        switch ($categoria->getNivel()){
            case 1:
                $bd->setConsulta("insert into cursos(nombre, precio, codigo, cuarto) values (?, ?, ?, ?)");
                array_push($parametros, $categoria->getNombre());
                array_push($parametros, $categoria->getPrecio());
                array_push($parametros, $categoria->getNombreCompleto());
                array_push($parametros, $categoria->getCuarto());
                break;
            case 2:
                $bd->setConsulta("insert into temas(id_curso, nombre, precio, codigo)".
                    " values (?, ?, ?, ?)");
                array_push($parametros, $categoria->getId());
                array_push($parametros, $categoria->getNombre());
                array_push($parametros, $categoria->getPrecio());
                array_push($parametros, $categoria->getNombreCompleto());
                break;
            case 3:
                $bd->setConsulta("insert into unidades (id_tema, nombre) values (?, ?)");
                array_push($parametros, $categoria->getId());
                array_push($parametros, $categoria->getNombre());
                break;
            case 4:
                $bd->setConsulta("insert into anyos (id_unidad, nombre) values (?, ?)");
                array_push($parametros, $categoria->getId());
                array_push($parametros, $categoria->getNombre());
                break;
        }
        $bd->ejecutar($parametros);
        if($bd->filasModificadas() > 0){
            $_SESSION["aviso"] = "Se agregó una nueva entidad";
        }else{
            $_SESSION["aviso"] = "No ha sido posible agregar la entidad";
        }
    }
    
    public static function modificarEstado($categoria){
        $bd = new BD();
        $parametros = array();
        switch ($categoria->getNivel()){
            case 1:
                $bd->setConsulta("update cursos set activo = ? where id_curso = ?");
                array_push($parametros, !$categoria->getActivo());
                array_push($parametros, $categoria->getId());
                break;
            case 2:
                $bd->setConsulta("update temas set activo = ? where id_tema = ?");
                array_push($parametros, !$categoria->getActivo());
                array_push($parametros, $categoria->getId());
                break;
            case 3:
                $bd->setConsulta("update unidades set activo = ? where id_unidad = ?");
                array_push($parametros, !$categoria->getActivo());
                array_push($parametros, $categoria->getId());
                break;
            case 4:
                $bd->setConsulta("update anyos set activo = ? where id_anyo = ?");
                array_push($parametros, !$categoria->getActivo());
                array_push($parametros, $categoria->getId());
                break;
        }
        $bd->ejecutar($parametros);
        if($bd->filasModificadas() > 0){
            $_SESSION["aviso"] = "Se modificó el estado entidad";
        }else{
            $_SESSION["aviso"] = "No ha sido posible modificar el estado";
        }
        
    }
    
    public static function borrar($categoria){
        $bd = new BD();
        $perdidas = 0;
        switch ($categoria->getNivel()){
            case 1:
                $bd->setConsulta("select count(a.id_anyo) total from anyos a inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso where c.id_curso = ?");
                
                $bd->ejecutar($categoria->getId());
                if($it = $bd->resultado()){
                    if($it["total"] > 0){
                        $bd->setConsulta("delete from anyos_preguntas" .
                            " where id_anyo in (select a.id_anyo from anyos a inner join unidades u on u.id_unidad = a.id_unidad" .
                            " inner join temas t on t.id_tema = u.id_tema" .
                            " inner join cursos c on c.id_curso = t.id_curso" .
                            " where c.id_curso = ?)");
                        $bd->ejecutar($categoria->getId());
                        $perdidas = $bd->filasModificadas();
                        
                        $bd->setConsulta("delete from anyos" .
                            " where id_anyo in (select a.id_anyo from anyos a inner join unidades u on u.id_unidad = a.id_unidad" .
                            " inner join temas t on t.id_tema = u.id_tema" .
                            " inner join cursos c on c.id_curso = t.id_curso" .
                            " where c.id_curso = ?)");
                        $bd->ejecutar($categoria->getId());
                    }
                }
                
                $bd->setConsulta("select count(u.id_unidad) total from unidades u" .
                    " inner join temas t on t.id_tema = u.id_tema" .
                    " inner join cursos c on c.id_curso = t.id_curso" .
                    " where c.id_curso = ?");
                $bd->ejecutar($categoria->getId());
                if($it = $bd->resultado()){
                    if($it["total"] > 0){
                        $bd->setConsulta("delete from unidades" .
                            " where id_unidad in (select u.id_unidad from unidades u" .
                            " inner join temas t on t.id_tema = u.id_tema" .
                            " inner join cursos c on c.id_curso = t.id_curso" .
                            " where c.id_curso = ?)");
                        $bd->ejecutar($categoria->getId());
                    }
                }
                
                $bd->setConsulta("select count(t.id_tema) total from temas t" .
                    " inner join cursos c on c.id_curso = t.id_curso" .
                    " where c.id_curso = ?");
                $bd->ejecutar($categoria->getId());
                if($it = $bd->resultado()){
                    if($it["total"] > 0){
                        $bd->setConsulta("delete from temas" .
                            " where id_tema in (select t.id_tema from temas t" .
                            " inner join cursos c on c.id_curso = t.id_curso" .
                            " where c.id_curso = ?)");
                        $bd->ejecutar($categoria->getId());
                    }
                }
                
                $bd->setConsulta("delete from cursos" .
                    " where id_curso = ?");
                $bd->ejecutar($categoria->getId());
                
                break;
            case 2:
                $bd->setConsulta("select count(a.id_anyo) total from anyos a inner join unidades u on u.id_unidad = a.id_unidad" .
                    " inner join temas t on t.id_tema = u.id_tema" .
                    " where t.id_tema = ?");
                $bd->ejecutar($categoria->getId());
                if($it = $bd->resultado()){
                    if($it["total"] > 0){
                        $bd->setConsulta("delete from anyos_preguntas" .
                            " where id_anyo in (select a.id_anyo from anyos a inner join unidades u on u.id_unidad = a.id_unidad" .
                            " inner join temas t on t.id_tema = u.id_tema" .
                            " where t.id_tema = ?)");
                        $bd->ejecutar($categoria->getId());
                        $perdidas = $bd->filasModificadas();

                        $bd->setConsulta("delete from anyos" .
                            " where id_anyo in (select a.id_anyo from anyos a inner join unidades u on u.id_unidad = a.id_unidad" .
                            " inner join temas t on t.id_tema = u.id_tema" .
                            " where t.id_tema = ?)");
                        $bd->ejecutar($categoria->getId());
                    }
                }
                
                $bd->setConsulta("select count(u.id_unidad) total from unidades u" .
                    " inner join temas t on t.id_tema = u.id_tema" .
                    " where t.id_tema = ?");
                $bd->ejecutar($categoria->getId());
                if($it = $bd->resultado()){
                    if($it["total"] > 0){
                        $bd->setConsulta("delete from unidades" .
                            " where id_unidad in (select u.id_unidad from unidades u" .
                            " inner join temas t on t.id_tema = u.id_tema" .
                            " where t.id_tema = ?)");
                        $bd->ejecutar($categoria->getId());
                    }
                }
                
                $bd->setConsulta("delete from temas where id_tema = ?");
                $bd->ejecutar($categoria->getId());
                break;
            
            case 3:
                $bd->setConsulta("select count(a.id_anyo) total from anyos a inner join" .
                    " unidades u on u.id_unidad = a.id_unidad" .
                    " where u.id_unidad = ?");
                $bd->ejecutar($categoria->getId());
                if($it = $bd->resultado()){
                    if($it["total"] > 0){
                        $bd->setConsulta("delete from anyos_preguntas" .
                            " where id_anyo in (select a.id_anyo from anyos a inner join" .
                            " unidades u on u.id_unidad = a.id_unidad" .
                            " where u.id_unidad = ?)");
                        $bd->ejecutar($categoria->getId());
                        $perdidas = $bd->filasModificadas();

                        $bd->setConsulta("delete from anyos" .
                            " where id_anyo in (select a.id_anyo from anyos a inner join" .
                            " unidades u on u.id_unidad = a.id_unidad" .
                            " where u.id_unidad = ?)");
                        $bd->ejecutar($categoria->getId());
                    }
                }
                
                $bd->setConsulta("delete from unidades where id_unidad = ?");
                $bd->ejecutar($categoria->getId());
                break;
            
            case 4:
                $bd->setConsulta("delete from anyos_preguntas" .
                    " where id_anyo = ?");
                $bd->ejecutar($categoria->getId());
                $perdidas = $bd->filasModificadas();
                
                $bd->setConsulta("delete from anyos" .
                    " where id_anyo = ?");
                $bd->ejecutar($categoria->getId());                
                break;
                
        }
        if($bd->filasModificadas() > 0){
            $_SESSION["aviso"] = "La entidad se ha borrado. $perdidas preguntas quedan sin asociar";
        }else{
            $_SESSION["aviso"] = "No se ha podido borrar la entidad";
        }
    }
}