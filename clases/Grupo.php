<?php

require_once 'BD.php';

/**
 * Description of Grupo
 *
 * @author aula7
 */
class Grupo {
    private $idGrupo;
    private $nombre;
    private $listaAlumnos;
    private $totalUsuarios;
    
    function __construct($idGrupo, $nombre, $listaAlumnos) {
        $this->idGrupo = $idGrupo;
        $this->nombre = $nombre;
        $this->listaAlumnos = $listaAlumnos;
    }
    
    public function getIdGrupo() {
        return $this->idGrupo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getListaAlumnos() {
        return $this->listaAlumnos;
    }
    
    public function getTotalUsuarios() {
        return $this->totalUsuarios;
    }

    //SET ----------------------------------------------------------------------
    public function setIdGrupo($idGrupo) {
        $this->idGrupo = $idGrupo;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setListaAlumnos($listaAlumnos) {
        $this->listaAlumnos = $listaAlumnos;
    }
    
    public function setTotalUsuarios($totalUsuarios) {
        $this->totalUsuarios = $totalUsuarios;
    }

}

class Grupo_BD{
    
    public static function alta($grupo){
        $bd = new BD();
        $bd->setConsulta("insert into grupos (nombre) values(?)");
        
        $bd->ejecutar($grupo->getNombre());
        
        $bd->setConsulta("select id_grupo from grupos where nombre = ?");
        $bd->ejecutar($grupo->getNombre());
        
        if($it = $bd->resultado()){
            $grupo->setIdGrupo($it["id_grupo"]);
        }
        
        $bd->setConsulta("insert into grupos_usuarios (id_grupo, id_usuario) values(?, ?)");
        foreach ($grupo->getListaAlumnos() as $idUsuario){
            $bd->ejecutar(array($grupo->getIdGrupo(), $idUsuario));
        }
        
        if($bd->filasModificadas() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function listar(){
        $bd = new BD();
        $bd->setConsulta("select g.id_grupo, g.nombre, COUNT(gu.id_usuario) as total_usuarios
 from grupos g inner join grupos_usuarios gu on gu.id_grupo = g.id_grupo
 group by g.id_grupo, g.nombre");
        
        $bd->ejecutar();
        
        $arrGrupos = array();
        
        while($it = $bd->resultado()){
            $grupo = new Grupo($it["id_grupo"], $it["nombre"], null);
            $grupo->setTotalUsuarios($it["total_usuarios"]);
            array_push($arrGrupos, $grupo);
        }
        return $arrGrupos;
    }
    
    public static function porId($idGrupo){
        $bd = new BD();
        
        $bd->setConsulta("select g.id_grupo, g.nombre, gu.id_usuario
 from grupos g inner join grupos_usuarios gu on gu.id_grupo = g.id_grupo
 where g.id_grupo = ?");
        $bd->ejecutar($idGrupo);
        
        $arrUsuarios = array();
        $grupo = null;
        while($it = $bd->resultado()){
            if(!isset($grupo)){
                $grupo = new Grupo($it["id_grupo"], $it["nombre"], null);
            }
            array_push($arrUsuarios, $it["id_usuario"]);
        }
        $grupo->setListaAlumnos($arrUsuarios);
        return $grupo;
    }
    
    public static function modificar($grupo){
        
        $bd = new BD();
        $bd->setConsulta("update grupos set nombre = ? where id_grupo = ?");
        
        $bd->ejecutar(array($grupo->getNombre(), $grupo->getIdGrupo()));
        
        $bd->setConsulta("delete from grupos_usuarios where id_grupo = ?");
        $bd->ejecutar($grupo->getIdGrupo());
        
        $bd->setConsulta("insert into grupos_usuarios (id_grupo, id_usuario) values(?, ?)");
        foreach ($grupo->getListaAlumnos() as $idUsuario){
            $bd->ejecutar(array($grupo->getIdGrupo(), $idUsuario));
        }
        
        if($bd->filasModificadas() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function borrar($idGupo){
        
        $bd = new BD();
        
        $bd->setConsulta("delete from grupos_usuarios where id_grupo = ?");
        $bd->ejecutar($idGupo);
        
        $total = "Se eliminaron ".$bd->filasModificadas()." usuarios del grupo<br/>";
        
        $bd->setConsulta("delete from examenes_grupos where id_grupo = ?");
        $bd->ejecutar($idGupo);
        
        $total .= $bd->filasModificadas()." exámenes ya no están asociados al grupo<br/>";
        
        $bd->setConsulta("delete from calendario where id_grupo = ?");
        $bd->ejecutar($idGupo);
        
        $total .= $bd->filasModificadas()." Entradas en el calendario fueron eliminadas<br/>";
        
        $bd->setConsulta("delete from grupos where id_grupo = ?");
        $bd->ejecutar($idGupo);
        
        return $total;
    }
}