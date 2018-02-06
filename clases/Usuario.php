<?php
require_once 'BD.php';

/**
 * Description of Usuario
 *
 * @author Nachondo
 */ 
class Usuario {

    private $idUsuario;
    private $ip;
    private $email;
    private $nivel;
    private $nombre;
    private $apellido;
    private $telefono;
    private $idProvincia;
    private $nombreProvincia;
    private $avatar;
    private $direccion;
    private $ciudad;
    private $interesado;
    private $nombreCurso;
    private $demoUsado;
    private $universidad;
    private $clave;
    private $bloqueado;
    private $movil;
    private $masFalladas;
    
    function __construct($idUsuario, $ip, $email, $nivel, $nombre, $apellido, $telefono, $idProvincia, $nombreProvincia, $avatar, $direccion, $ciudad, $interesado, $nombreCurso, $demoUsado, $universidad) {
        $this->idUsuario = $idUsuario;
        $this->ip = $ip;
        $this->email = $email;
        $this->nivel = $nivel;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->idProvincia = $idProvincia;
        $this->nombreProvincia = $nombreProvincia;
        $this->avatar = $avatar;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->interesado = $interesado;
        $this->nombreCurso = $nombreCurso;
        $this->demoUsado = $demoUsado;
        $this->universidad = $universidad;
        $this->masFalladas = false;
    }

    
    //GET ----------------------------------------------------------------------
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function getNombre() {
        return ucwords(mb_strtolower($this->nombre, 'UTF-8'));
    }

    public function getApellido() {
        return ucwords(mb_strtolower($this->apellido, 'UTF-8'));
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getIdProvincia() {
        return $this->idProvincia;
    }

    public function getNombreProvincia() {
        return $this->nombreProvincia;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function getInteresado() {
        return $this->interesado;
    }

    public function getNombreCurso() {
        return $this->nombreCurso;
    }

    public function getDemoUsado() {
        return $this->demoUsado;
    }

    public function getUniversidad() {
        return $this->universidad;
    }
    
    public function getClave() {
        return $this->clave;
    }
    
    public function getBloqueado() {
        return $this->bloqueado;
    }
    
    public function getMovil() {
        return $this->movil;
    }
    
    public function getMasFalladas() {
        return $this->masFalladas;
    }

    //SET ----------------------------------------------------------------------
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setIdProvincia($idProvincia) {
        $this->idProvincia = $idProvincia;
    }

    public function setNombreProvincia($nombreProvincia) {
        $this->nombreProvincia = $nombreProvincia;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    public function setInteresado($interesado) {
        $this->interesado = $interesado;
    }

    public function setNombreCurso($nombreCurso) {
        $this->nombreCurso = $nombreCurso;
    }

    public function setDemoUsado($demoUsado) {
        $this->demoUsado = $demoUsado;
    }

    public function setUniversidad($universidad) {
        $this->universidad = $universidad;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }
    
    public function setBloqueado($bloqueado) {
        $this->bloqueado = $bloqueado;
    }

    public function setMovil($movil) {
        $this->movil = $movil;
    }
    
    public function setMasFalladas($masFalladas) {
        $this->masFalladas = $masFalladas;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function enArray(){
        return array($this->nombre, $this->apellido, $this->email, $this->nombreProvincia, $this->ciudad, $this->nombreCurso, $this->universidad, $this->nivel, $this->telefono, $this->movil);
    }
}

class Usuario_BD{
    
    private static function consultaBasica(){
        return "select u.ip, u.id_usuario, u.email, u.nivel, u.bloqueado, LTRIM(RTRIM(u.nombre)) as nombre, LTRIM(RTRIM(u.apellido)) as apellido,
 u.telefono, u.id_provincia, p.nombre nombre_provincia, u.avatar, u.direccion, u.ciudad, u.interesado, c.nombre nombre_curso, u.demo_usado,
 u.universidad, u.movil, u.mas_falladas, u.clave
 from usuarios u left join cursos c on c.id_curso = u.interesado left join provincias p on p.id_provincia = u.id_provincia";
    }
    
    private static function llenarUno($it){
        $usuario = new Usuario($it["id_usuario"], $it["ip"], $it["email"], $it["nivel"],
                    $it["nombre"], $it["apellido"], $it["telefono"], $it["id_provincia"],
                    $it["nombre_provincia"], $it["avatar"], $it["direccion"], $it["ciudad"],
                    $it["interesado"], $it["nombre_curso"], $it["demo_usado"], $it["universidad"]);

        $usuario->setBloqueado($it["bloqueado"]);
        $usuario->setMovil($it["movil"]);
        if($it["mas_falladas"]){
            $usuario->setMasFalladas(true);
        }
        return $usuario;
    }


    public static function porId($idUsuario){
        $bd = new BD();
        
        $sql = Usuario_BD::consultaBasica()." where u.id_usuario = ?";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idUsuario);
        
        if($it = $bd->resultado()){
            $usuario = Usuario_BD::llenarUno($it);
            $usuario->setClave($it["clave"]);
            return $usuario;
        }
        return null;
    }
    
    public static function porIdGrupo($idGrupo){
        $bd = new BD();
        
        $sql = Usuario_BD::consultaBasica()." where u.id_usuario = in select id_usuario from grupos_usuarios where id_grupo = ?";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idGrupo);
        
        $listaUsuarios = array();
        
        while($it = $bd->resultado()){
            $usuario = Usuario_BD::llenarUno($it);
            array_push($listaUsuarios, $usuario);
        }
        return $listaUsuarios;
    }
    
    public static function existeCorreo($email){
        $bd = new BD();
        
        $bd->setConsulta("select email from usuarios where email = ?");
	
        $bd->ejecutar($email);
        
        if($it["it"] = $bd->resultado()){
            return true;
        }
        return false;
    }
    
    public static function agregar($usuario){
        $bd = new BD();
        
        if($usuario->getTelefono() != null){
            $sql = "insert into usuarios (email, clave, nombre, apellido," .
                " fecha_creacion, bloqueado, avatar, nivel, ip, interesado, " .
                " id_provincia, ciudad, direccion, universidad, movil, telefono)" .
                " values (?, ?, ?, ?, now(), 0, ?, ?, ?, ?, ?, ?, ?, ?,".
                    $usuario->getMovil().", ".$usuario->getTelefono().")";
        }else{
            $sql = "insert into usuarios (email, clave, nombre, apellido," .
                " fecha_creacion, bloqueado, avatar, nivel, ip, interesado, " .
                " id_provincia, ciudad, direccion, universidad, movil)" .
                " values (?, ?, ?, ?, now(), 0, ?, ?, ?, ?, ?, ?, ?, ?,".$usuario->getMovil().")";
        }
        
        $parametros = array($usuario->getEmail(), $usuario->getClave(), $usuario->getNombre(),
        $usuario->getApellido(), $usuario->getAvatar(), $usuario->getNivel(),
        $usuario->getIp(), $usuario->getInteresado(), $usuario->getIdProvincia(),
        $usuario->getCiudad(), $usuario->getDireccion(),
        $usuario->getUniversidad());
        
        $bd->setConsulta($sql);
        
        
        $bd->ejecutar($parametros);
        
        if($bd->filasModificadas() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function modificar($usuario){
        
        $bd = new BD();
        
        $sql = "update usuarios set id_provincia = ?, ciudad = ?, direccion = ?,".
            " universidad = ? where id_usuario = ?";
        
        $parametros = array($usuario->getIdProvincia(), $usuario->getCiudad(),
            $usuario->getDireccion(), $usuario->getUniversidad(), $usuario->getIdUsuario());
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($parametros);
        
        if($usuario->getMovil() != null && $usuario->getMovil() != ""){
            $bd2 = new BD();
            $bd2->setConsulta("update usuarios set movil = ? where id_usuario = ?");
            $bd2->ejecutar(array($usuario->getMovil(), $usuario->getIdUsuario()));
        }
        
        if($usuario->getTelefono() != null && $usuario->getTelefono() != ""){
            $bd2 = new BD();
            $bd2->setConsulta("update usuarios set telefono = ? where id_usuario = ?");
            $bd2->ejecutar(array($usuario->getTelefono(), $usuario->getIdUsuario()));
        }
        
        if($bd->filasModificadas() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function login($email, $clave){
        $sql = Usuario_BD::consultaBasica().
                " where email = ? and clave = ? and bloqueado = 0";
        $bd = new BD();
        
        $bd->setConsulta($sql);
        
        $parametros = array($email, $clave);
        
        $bd->ejecutar($parametros);
        
        if($it = $bd->resultado()){
            $usuario = Usuario_BD::llenarUno($it);
            $usuario->setClave($clave);
            return $usuario;
        }
        return null;
    }
    
    public static function listar($nivel, $curso){
        $bd = new BD();
        
        $sql = Usuario_BD::consultaBasica();
        
        switch ($nivel){
            case "1":
                $sql .= " where u.nivel <> 0";
                break;
            case "2":
                $sql .= " where u.nivel = 2";
                break;
            case "3":
                $sql .= " where u.nivel = 3";
                break;
            case "4":
                $sql .= " where u.nivel = 4";
                break;
        }
        
        switch ($curso){
            case "0":
                $sql .= " and u.interesado <> 0";
                break;
            case "1":
                $sql .= " and u.interesado = 1";
                break;
            case "2":
                $sql .= " and u.interesado = 2";
                break;
            case "7":
                $sql .= " and u.interesado = 7";
                break;
        }
        
        $sql.=" and nivel <> 1 order by apellido, nombre";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar();
        
        $arrUsuarios = array();
        
        while($it = $bd->resultado()){
            $user = Usuario_BD::llenarUno($it);
            array_push($arrUsuarios, $user);
        }
        
        return $arrUsuarios;
    }
    
    public static function bloquear($idUsuario){
        $bd = new BD();
        
        $bd->setConsulta("update usuarios set bloqueado = 1 where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        return $bd->filasModificadas();
    }
    
    public static function desbloquear($idUsuario){
        $bd = new BD();
        
        $bd->setConsulta("update usuarios set bloqueado = 0 where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        return $bd->filasModificadas();
    }
    
    public static function borrar($idUsuario){
        $bd = new BD();
        
        $bd->setConsulta("delete from cursos_usuarios where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from usuarios_anyos where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from temas_usuarios where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from resultados_respuestas where id_resultado in (select id_resultado from resultados where id_usuario = ?)");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from resultados where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from usuarios_examenes where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from examenes_preguntas where id_examen in (select id_examen from examenes where autor = ?)");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from examenes where autor = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from periodos where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        $bd->setConsulta("delete from usuarios where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        return $bd->filasModificadas();
    }
    
    public static function cambiarNivel($idUsuario, $nivel){
        
        $bd = new BD();
        $bd->setConsulta("update usuarios set nivel = ? where id_usuario = ?");
        $parametros = array($nivel, $idUsuario);
        $bd->ejecutar($parametros);
        return $bd->filasModificadas();
    }
    
    public static function listarUsuarios(){
        $bd = new BD();
        $bd->setConsulta("select id_usuario, nombre, apellido from usuarios order by apellido");
        $bd->ejecutar();
        
        $arrUsuarios = array();
        while($it = $bd->resultado()){
            $usuario = new Usuario($it["id_usuario"], null, null, null, $it["nombre"],
                    $it["apellido"], null, null, null, null, null, null, null, null,
                    null, null);
            array_push($arrUsuarios, $usuario);
        }
        return $arrUsuarios;
    }
    
    public static function usarDemo($idUsuario){
        $bd = new BD();
        $bd->setConsulta("update usuarios set demo_usado = 1 where id_usuario = ?");
        $bd->ejecutar($idUsuario);
    }
    
    public static function cambiaTema($idUsuario, $idTema, $activo){
        $bd = new BD();
        $parametros = array($idUsuario, $idTema);
        if($activo){
            $bd->setConsulta("delete from temas_usuarios where id_usuario = ? and id_tema = ?");
        }else{
            $bd->setConsulta("insert into temas_usuarios (id_usuario, id_tema) values (?, ?)");
        }
        $bd->ejecutar($parametros);
        
        return $bd->filasModificadas();
    }
    
    public static function cambiaAnyo($idUsuario, $idAnyo, $activo){
        $bd = new BD();
        $parametros = array($idUsuario, $idAnyo);
        if($activo){
            $bd->setConsulta("delete from usuarios_anyos where id_usuario = ? and id_anyo = ?");
        }else{
            $bd->setConsulta("insert into usuarios_anyos (id_usuario, id_anyo) values (?, ?)");
        }
        $bd->ejecutar($parametros);
        
        return $bd->filasModificadas();
    }
    
    public static function listarParaCalendario(){
        $bd = new BD();
        $bd->setConsulta("select id_usuario, nombre, apellido, nivel, interesado"
                . " from usuarios where nivel in(3,4)"
                . " order by apellido, nombre");
        $bd->ejecutar();
        
        $arrUsuarios = array();
        
        while($it = $bd->resultado()){
            $usuario = new Usuario($it["id_usuario"], null, null, $it["nivel"], $it["nombre"],
                    $it["apellido"], null, null, null, null, null, null, $it["interesado"],
                    null, null, null);
            array_push($arrUsuarios, $usuario);
        }
        
        return $arrUsuarios;
    }
    
    public static function recuperaClave($email){
        $bd = new BD();
        $bd->setConsulta("select clave from usuarios where email = ?");
        $bd->ejecutar($email);
        
        if($it = $bd->resultado()){
            return $it["clave"];
        }
        return null;
    }
    
    public static function cambiaMasFalladas($idUsuario, $estado){
        $bd = new BD();
        $bd->setConsulta("update usuarios set mas_falladas = ".$estado." where id_usuario = ?");
        
        $bd->ejecutar($idUsuario);
        
        return $bd->filasModificadas();
    }
    
}