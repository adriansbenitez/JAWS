<?php

require_once 'BD.php';
require_once 'Usuario.php';

/**
 * Description of Curso
 *
 * @author Nachondo
 */
class Curso {

    private $idCurso;
    private $nombre;
    private $totalPreguntas;
    private $precio;
    private $cuarto;

    function __construct($idCurso, $nombre, $totalPreguntas) {
        $this->idCurso = $idCurso;
        $this->nombre = $nombre;
        $this->totalPreguntas = $totalPreguntas;
    }

    public function getIdCurso() {
        return $this->idCurso;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTotalPreguntas() {
        return $this->totalPreguntas;
    }

    public function getPrecio() {
        return $this->precio;
    }
    
    public function getCuarto() {
        return $this->cuarto;
    }

    //SET ----------------------------------------------------------------------
    public function setIdCurso($idCurso) {
        $this->idCurso = $idCurso;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setTotalPreguntas($totalPreguntas) {
        $this->totalPreguntas = $totalPreguntas;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function setCuarto($cuarto) {
        $this->cuarto = $cuarto;
    }

}

class Curso_BD {

    private static function consultaBasica() {
        return "select c.id_curso, c.nombre, c.precio, (select COUNT(p.id_pregunta) from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where c.id_curso = t.id_curso) total_preguntas from cursos c";
    }
    
    public static function porId($idCurso) {
        $bd = new BD();

        $bd->setConsulta(Curso_BD::consultaBasica() . " where id_curso = ?");

        $bd->ejecutar($idCurso);

        if($it = $bd->resultado()) {
            $curso = new Curso($it["id_curso"], $it["nombre"], $it["total_preguntas"]);
            $curso->setPrecio($it["precio"]);
            return $curso;
        }
        return null;
    }

    public static function listar() {
        $bd = new BD();

        $bd->setConsulta(Curso_BD::consultaBasica() . " order by nombre");

        $bd->ejecutar();

        $arrCursos = array();

        while ($it = $bd->resultado()) {
            $curso = new Curso($it["id_curso"], $it["nombre"], $it["total_preguntas"]);
            array_push($arrCursos, $curso);
        }
        return $arrCursos;
    }
    
    public static function listarCursosConUsuarios() {
        $bd = new BD();

        $bd->setConsulta(Curso_BD::consultaBasica() . " where id_curso in (select interesado from usuarios) order by nombre");

        $bd->ejecutar();

        $arrCursos = array();

        while ($it = $bd->resultado()) {
            $curso = new Curso($it["id_curso"], $it["nombre"], $it["total_preguntas"]);
            array_push($arrCursos, $curso);
        }
        return $arrCursos;
    }

    public static function modificar($curso) {
        $bd = new BD();
        $bd->setConsulta("update cursos set nombre = ?, precio = ?, cuarto = ? where id_curso = ?");
        $parametros = array($curso->getNombre(), $curso->getPrecio(), $curso->getCuarto(),$curso->getIdCurso());
        $bd->ejecutar($parametros);
        if ($bd->filasModificadas() > 0) {
            return true;
        }
        return false;
    }

    public static function paraExamen($usuario) {
        $bd = new BD();

        switch ($usuario->getNivel()) {
            case "1":
                $bd->setConsulta("select p.id_curso, p.nombreCurso, COUNT(id_pregunta) total_preguntas" .
                        " from v_preguntas_por_entidad p" .
                        " group by p.id_curso, p.nombreCurso order by nombreCurso");
                $bd->ejecutar();
                break;
            case "2":
                $bd->setConsulta("select p.id_curso, p.nombreCurso, COUNT(id_pregunta) total_preguntas" .
                        " from v_preguntas_por_entidad p" .
                        " where p.id_curso = ?" .
                        " group by p.id_curso, p.nombreCurso order by nombreCurso");
                $bd->ejecutar($usuario->getInteresado());
                break;
            default:
                $bd->setConsulta("select p.id_curso, p.nombreCurso, COUNT(id_pregunta) total_preguntas" .
                        " from v_preguntas_por_entidad p" .
                        " where p.id_tema in (select id_tema from temas_usuarios where id_usuario = ?)" .
                        " and p.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?)" .
                        " group by p.id_curso, p.nombreCurso order by nombreCurso");
                $bd->ejecutar(array($usuario->getIdUsuario(), $usuario->getIdUsuario()));
                break;
        }
        $arrCursos = array();
        while($it = $bd->resultado()){
            $curso = new Curso($it["id_curso"], $it["nombreCurso"], $it["total_preguntas"]);
            array_push($arrCursos, $curso);
        }
        return $arrCursos;
    }
    
    public static function paraExamenFalladas($usuario){
        $bd = new BD();

        $bd->setConsulta("select count(todo.id_pregunta) total_preguntas, c.id_curso, c.nombre from (select DISTINCT r.id_usuario, ep.id_pregunta,(select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta inner join resultados r2 on r2.id_resultado = rr.id_resultado where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario) as correctas,(select count(ep2.id_pregunta) from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario) as total, (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1) as id_anyo from resultados r inner join examenes e on e.id_examen = r.id_examen inner join examenes_preguntas ep on ep.id_examen = e.id_examen where r.id_usuario = ? group by r.id_usuario, ep.id_pregunta, id_anyo) as todo inner join anyos an on an.id_anyo = todo.id_anyo inner join unidades uni on uni.id_unidad = an.id_unidad inner join temas t on t.id_tema = uni.id_tema inner join cursos c on c.id_curso = t.id_curso where todo.total * $factorMasFalladas > todo.correctas and c.id_curso = ? GROUP by c.id_curso, c.nombre UNION select count(todo.id_pregunta) total_preguntas, c.id_curso, c.nombre from (select DISTINCT r.id_usuario, ep.id_pregunta,(select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta inner join resultados r2 on r2.id_resultado = rr.id_resultado where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario) as correctas,(select count(ep2.id_pregunta) from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario) as total, (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1) as id_anyo from resultados r inner join examenes e on e.id_examen = r.id_examen inner join examenes_preguntas ep on ep.id_examen = e.id_examen where r.id_usuario = ? group by r.id_usuario, ep.id_pregunta, id_anyo) as todo inner join preguntas_virtual pv on pv.id_pregunta = todo.id_pregunta inner JOIN unidades uni on uni.id_unidad = pv.id_unidad inner join temas t on t.id_tema = uni.id_tema inner join cursos c on c.id_curso = t.id_curso where todo.total * $factorMasFalladas > todo.correctas and c.id_curso = ? GROUP by c.id_curso, c.nombre");      
        $bd->ejecutar(array($usuario->getIdUsuario(), $usuario->getInteresado(),$usuario->getIdUsuario(), $usuario->getInteresado()));
        
        $arrCursos = array();
        $curso = new Curso(null, null, null, null);
        while($it = $bd->resultado()){
            if($curso->getIdCurso() == $it["id_curso"]){
                $curso->setTotalPreguntas($curso->getTotalPreguntas()+$it["total_preguntas"]);
            }else{
                if($curso->getIdCurso() != null){
                    array_push($arrCursos, $curso);
                }
                $curso = new Curso($it["id_curso"], $it["nombre"], $it["total_preguntas"]);
            }
        }
        array_push($arrCursos, $curso);
        return $arrCursos;
    }
    
    public static function bloquesDisponibles($idUsuario){
        
        $bd = new BD();
        $bd->setConsulta("select distinct c.id_curso, c.nombre, c.precio from cursos c where c.id_curso not in (select t.id_curso from temas_usuarios tu inner join temas t on t.id_tema = tu.id_tema inner join cursos c on c.id_curso = t.id_curso where tu.id_usuario = ?) and c.precio != 0");
        $bd->ejecutar($idUsuario);
        $arrCursos = array();
        while($it = $bd->resultado()){
            $curso = new Curso($it["id_curso"], $it["nombre"], 0);
            $curso->setPrecio($it["precio"]);
            array_push($arrCursos, $curso);
        }
        return $arrCursos;
    }
    
    public static function bloquesContratados($idUsuario){
        
        $bd = new BD();
        $bd->setConsulta("select distinct c.id_curso, c.nombre, c.precio from temas_usuarios tu inner join temas t on t.id_tema = tu.id_tema inner join cursos c on c.id_curso = t.id_curso where tu.id_usuario = ? and c.precio != 0");
        $bd->ejecutar($idUsuario);
        $arrCursos = array();
        while($it = $bd->resultado()){
            $curso = new Curso($it["id_curso"], $it["nombre"], 0);
            $curso->setPrecio($it["precio"]);
            array_push($arrCursos, $curso);
        }
        return $arrCursos;
    }
    
    public static function marcar_entidades($idCurso, $activo){
        $cuenta = 0;
        $bd = new BD();
        $bd->setConsulta("update temas set activo = ? where id_curso = ?");
        $bd->ejecutar(array($activo, $idCurso));
        $cuenta+=$bd->filasModificadas();
        
        $bd->setConsulta("update unidades set activo = ? where id_tema in (select id_tema from temas where id_curso = ?)");
        $bd->ejecutar(array($activo, $idCurso));
        $cuenta+=$bd->filasModificadas();
        
        $bd->setConsulta("update anyos set activo = ? where id_unidad in (select u.id_unidad from unidades u inner join temas t on t.id_tema = u.id_tema where t.id_curso = ?)");
        $bd->ejecutar(array($activo, $idCurso));
        $cuenta+=$bd->filasModificadas();
        
        return $cuenta;
    }
    
    public static function listarParaCalendario(){
        $bd = new BD();
        $bd->setConsulta("select id_curso, nombre from cursos where precio > 0");
        
        $bd->ejecutar();
        $listaCursos = array();
        while($it = $bd->resultado()){
            $curso = new Curso($it["id_curso"], $it["nombre"], null);
            array_push($listaCursos, $curso);
        }
        return $listaCursos;
    }

}
