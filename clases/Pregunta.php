<? require_once 'BD.php';
require_once 'Respuesta.php';
require_once 'Anyo.php';

class Pregunta{
    
    private $idPregunta;
    private $enunciado;
    private $anotacion;
    private $explicacion;
    private $urlVideo;
    private $imagenEnunciado;
    private $imagenExplicacion;
    private $imagenAclaracion;
    private $listaRespuestas;
    private $activo;
    private $correctas;
    private $incorrectas;
    private $total;
    private $codigo;
    private $listaAnyos;
    private $listaUnidades;
    private $idAnyo;
    private $nombreAnyo;
    
    function __construct($idPregunta, $enunciado, $anotacion, $explicacion, $urlVideo, $imagenEnunciado, $imagenExplicacion, $imagenAclaracion, $listaRespuestas) {
        $this->idPregunta = $idPregunta;
        $this->enunciado = $enunciado;
        $this->anotacion = $anotacion;
        $this->explicacion = $explicacion;
        $this->urlVideo = $urlVideo;
        $this->imagenEnunciado = $imagenEnunciado;
        $this->imagenExplicacion = $imagenExplicacion;
        $this->imagenAclaracion = $imagenAclaracion;
        $this->listaRespuestas = $listaRespuestas;
        $this->listaUnidades = array();
        $this->listaAnyos = array();
    }
    
    public function getIdPregunta() {
        return $this->idPregunta;
    }

    public function getEnunciado($numero = null) {
        if(isset($numero)){
            return mb_substr($this->enunciado, 0, $numero);
        }else{
            return $this->enunciado;
        }
    }

    public function getAnotacion() {
        return $this->anotacion;
    }

    public function getExplicacion() {
        return $this->explicacion;
    }

    public function getUrlVideo() {
        return $this->urlVideo;
    }

    public function getImagenEnunciado() {
        return $this->imagenEnunciado;
    }

    public function getImagenExplicacion() {
        return $this->imagenExplicacion;
    }

    public function getImagenAclaracion() {
        return $this->imagenAclaracion;
    }

    public function getListaRespuestas() {
        return $this->listaRespuestas;
    }
    
    public function getActivo() {
        return $this->activo;
    }

    public function getCorrectas() {
        return $this->correctas;
    }

    public function getIncorrectas() {
        return $this->incorrectas;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getCodigo() {
        return $this->codigo;
    }
    
    public function getListaAnyos() {
        return $this->listaAnyos;
    }
    
    public function getListaUnidades() {
        return $this->listaUnidades;
    }
    
    function getIdAnyo() {
        return $this->idAnyo;
    }

    function getNombreAnyo() {
        return $this->nombreAnyo;
    }

    //SET ----------------------------------------------------------------------
    public function setIdPregunta($idPregunta) {
        $this->idPregunta = $idPregunta;
    }

    public function setEnunciado($enunciado) {
        $this->enunciado = $enunciado;
    }

    public function setAnotacion($anotacion) {
        $this->anotacion = $anotacion;
    }

    public function setExplicacion($explicacion) {
        $this->explicacion = $explicacion;
    }

    public function setUrlVideo($urlVideo) {
        $this->urlVideo = $urlVideo;
    }

    public function setImagenEnunciado($imagenEnunciado) {
        $this->imagenEnunciado = $imagenEnunciado;
    }

    public function setImagenExplicacion($imagenExplicacion) {
        $this->imagenExplicacion = $imagenExplicacion;
    }

    public function setImagenAclaracion($imagenAclaracion) {
        $this->imagenAclaracion = $imagenAclaracion;
    }

    public function setListaRespuestas($listaRespuestas) {
        $this->listaRespuestas = $listaRespuestas;
    }
    
    public function setActivo($activo) {
        $this->activo = $activo;
    }

    public function setCorrectas($correctas) {
        $this->correctas = $correctas;
    }

    public function setIncorrectas($incorrectas) {
        $this->incorrectas = $incorrectas;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setListaAnyos($listaAnyos) {
        $this->listaAnyos = $listaAnyos;
    }
    
    public function setListaUnidades($listaUnidades) {
        $this->listaUnidades = $listaUnidades;
    }
    
    function setIdAnyo($idAnyo) {
        $this->idAnyo = $idAnyo;
    }

    function setNombreAnyo($nombreAnyo) {
        $this->nombreAnyo = $nombreAnyo;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function clase(){
        $respuesta = new Respuesta(null, null, null, null, null, null);
        foreach ($this->listaRespuestas as $respuesta){
            if($respuesta->getMarcada() == null){
                return "nsnc";
            }else if($respuesta->getIdRespuesta() == $respuesta->getMarcada()
                    && $respuesta->getCorrecta()){
                return "ok";
            }
        }
        return "ko";
    }
    
    public function imprimeAnotacion(){
        return str_replace("@i", '<img src="upload/pregunta/'.$this->imagenAclaracion.'?ver='. time().'"/>',
            $this->anotacion);
    }
    
    public function imprimeEnunciado(){
        return str_replace("@i", '<img src="upload/pregunta/'.$this->imagenEnunciado.'?ver='. time().'"/>',
            $this->enunciado);
    }
    
    public function imprimeExplicacion(){
        return str_replace("@i", '<img src="upload/pregunta/'.$this->imagenExplicacion.'?ver='. time().'"/>',
            $this->explicacion);
    }
    
    public function getBlancas(){
        return $this->total - $this->correctas - $this->incorrectas;
    }
    
    public function porcentaje(){
        if($this->total > 0){
            return number_format((($this->correctas)*100)/$this->total, 2, ".", "'");
        }
        return "0.00";
    }
    
    public function porcentajeConFormato(){
        $primero = "00";
        $segundo = "00";
        if($this->total > 0){
            $ele = explode(".", number_format((($this->correctas)*100)/$this->total, 2, ".", "'"));
            $primero = $ele[0];
            $segundo = $ele[1];
        }
        return "<span class=\"porcien\">$primero<span>,$segundo%</span></span>";
    }
}

class Pregunta_BD{
    
    private static function llenarPregunta($it){
        $pregunta = new Pregunta($it["id_pregunta"], $it["enunciado"], null,
                null, null, null, null, null, null);
        $pregunta->setActivo($it["activo"]);
        $pregunta->setCorrectas($it["correctas"]);
        $pregunta->setIncorrectas($it["incorrectas"]);
        $pregunta->setTotal($it["total"]);
        return $pregunta;
    }

    public static function porResultado($idResultado, $idUsuario){
        
        $bd = new BD();
        
        $sql = "select p.id_pregunta, p.enunciado, p.anotacion, p.explicacion, p.url_video, p.codigo, p.imagen_enunciado, p.imagen_explicacion, p.imagen_aclaracion, r.id_respuesta, r.texto, r.correcta, (select rere.id_respuesta from resultados_respuestas rere inner join respuestas r2 on r2.id_respuesta = rere.id_respuesta where r2.id_pregunta = p.id_pregunta and rere.id_resultado = ?) marcada, r.imagen, (select 1 from resultados_respuestas_dudas pos where pos.id_resultado = rs.id_resultado and r.id_respuesta = pos.id_respuesta limit 1) posible from preguntas p inner join respuestas r on r.id_pregunta = p.id_pregunta inner join examenes_preguntas ep on ep.id_pregunta = p.id_pregunta inner join resultados rs on rs.id_examen = ep.id_examen where rs.id_resultado = ? and rs.id_usuario = ? order by p.codigo";
        
        $bd->setConsulta($sql);
        
        $parametros = array($idResultado, $idResultado, $idUsuario);
        $bd->ejecutar($parametros);
        
        $arrPreguntas = array();
        $pregunta = new Pregunta(0, null, null, null, null, null, null, null, null);
        while($it = $bd->resultado()){
            if($it["id_pregunta"] != $pregunta->getIdPregunta()){
                if($pregunta->getIdPregunta() != 0){
                    array_push($arrPreguntas, $pregunta);
                }
                $pregunta = new Pregunta($it["id_pregunta"], $it["enunciado"], $it["anotacion"],
                        $it["explicacion"], $it["url_video"], $it["imagen_enunciado"],
                        $it["imagen_explicacion"], $it["imagen_aclaracion"], array());
                $pregunta->setCodigo($it["codigo"]);
            }
            $respuesta = new Respuesta($it["id_respuesta"], $it["id_pregunta"], $it["texto"],
                    $it["correcta"], $it["marcada"], $it["imagen"]);
            if($it["posible"] == 1){
                $respuesta->setPosible(true);
            }
            $arrayAgrega = $pregunta->getListaRespuestas();
            array_push($arrayAgrega, $respuesta);
            $pregunta->setListaRespuestas($arrayAgrega);
        }
        array_push($arrPreguntas, $pregunta);
        return $arrPreguntas;
    }
    
    public static function porAnyo($idAnyo){
        $bd = new BD();
        
        $sql = "select p.id_pregunta, p.enunciado, p.activo, p.codigo, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap1 on ap1.id_pregunta = re.id_pregunta where ap1.id_anyo = ap.id_anyo and ap1.id_pregunta = p.id_pregunta and re.correcta = 1) correctas, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap1 on ap1.id_pregunta = re.id_pregunta where ap1.id_anyo = ap.id_anyo and ap1.id_pregunta = p.id_pregunta and re.correcta != 1) incorrectas, (select count(*) from examenes_preguntas ep inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap1 on ap1.id_pregunta = ep.id_pregunta where ap1.id_anyo = ap.id_anyo and ap1.id_pregunta = p.id_pregunta) total from preguntas p inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta where ap.id_anyo = ? order by codigo";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idAnyo);
        
        $arrPreguntas = array();
        
        while($it = $bd->resultado()){
            $pregunta = Pregunta_BD::llenarPregunta($it);
            $pregunta->setCodigo($it["codigo"]);
            array_push($arrPreguntas, $pregunta);
        }
        return $arrPreguntas;
    }
    
    public static function porId($idPregunta){
        $bd = new BD();
        
        $sql = "select p.id_pregunta, p.enunciado, p.activo, p.codigo, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta where ap.id_pregunta = p.id_pregunta and re.correcta = 1) correctas, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta where ap.id_pregunta = p.id_pregunta and re.correcta != 1) incorrectas, (select count(*) from examenes_preguntas ep inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta where ap.id_pregunta = p.id_pregunta) total from preguntas p where p.id_pregunta = ?";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idPregunta);
        
        if($it = $bd->resultado()){
            $pregunta = Pregunta_BD::llenarPregunta($it);
            return $pregunta;
        }
        return null;
    }
    
    public static function cambiaEstado($idPregunta, $activo){
        $bd = new BD();
        
        $bd->setConsulta("update preguntas set activo = ? where id_pregunta = ?");
        
        if($activo == 1){
            $activo = 0;
        }else{
            $activo = 1;
        }
        
        $parametros = array($activo, $idPregunta);
        
        $bd->ejecutar($parametros);
        return $bd->filasModificadas();
    }
    
    public static function porIdCompleta($idPregunta){
        $bd = new BD();
        
        $sql = "select p.id_pregunta, p.enunciado, p.activo, p.url_video, p.anotacion, p.explicacion, a.id_anyo, a.nombre nombreAnyo, u.id_unidad, u.nombre nombreUnidad, t.id_tema, t.nombre nombreTema, p.codigo, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta where ap.id_pregunta = p.id_pregunta and re.correcta = 1) correctas, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta where ap.id_pregunta = p.id_pregunta and re.correcta != 1) incorrectas, (select count(*) from examenes_preguntas ep inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta where ap.id_pregunta = p.id_pregunta) total, p.imagen_enunciado, p.imagen_explicacion, p.imagen_aclaracion, c.id_curso, c.nombre nombreCurso from preguntas p left join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta left join anyos a on a.id_anyo = ap.id_anyo left join unidades u on u.id_unidad = a.id_unidad left join temas t on t.id_tema = u.id_tema left join cursos c on c.id_curso = t.id_curso where p.id_pregunta = ?";
        
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idPregunta);
        $pregunta = new Pregunta(null, null, null, null, null, null, null, null, null);
        $listaRespuestas = array();
        $listaAnyos = array();
        while($it = $bd->resultado()){
            if($pregunta->getIdPregunta() == null){
                $pregunta = new Pregunta($it["id_pregunta"], $it["enunciado"], $it["anotacion"],
                    $it["explicacion"], $it["url_video"], $it["imagen_enunciado"],
                    $it["imagen_explicacion"], $it["imagen_aclaracion"], null);
                            $pregunta->setCorrectas($it["correctas"]);
                $pregunta->setIncorrectas($it["incorrectas"]);
                $pregunta->setTotal($it["total"]);
                $pregunta->setCodigo($it["codigo"]);
            }
            $anyo = new Anyo($it["id_anyo"], $it["nombreAnyo"], 0, 0);
            $anyo->setNombreUnidad($it["nombreUnidad"]);
            $anyo->setNombreTema($it["nombreCurso"]." / ".$it["nombreTema"]);
            
            array_push($listaAnyos, $anyo);
        }
        $pregunta->setListaAnyos($listaAnyos);
        
        $sql = "select id_unidad from preguntas_virtual where id_pregunta = ?";
        $bd->setConsulta($sql);
        $bd->ejecutar($idPregunta);
        $listaUnidades = array();
        
        while($it = $bd->resultado()){
            array_push($listaUnidades, $it["id_unidad"]);
        }
        $pregunta->setListaUnidades($listaUnidades);
        
        $sql = "select r.id_respuesta, r.id_pregunta, r.texto, r.correcta, r.activo, COUNT(re.id_respuesta) contestada, r.imagen from respuestas r left join resultados_respuestas re on re.id_respuesta = r.id_respuesta where id_pregunta = ? group by r.id_respuesta, r.id_pregunta, r.texto, r.correcta, r.activo, r.imagen order by id_respuesta";
        $bd->setConsulta($sql);
        $bd->ejecutar($idPregunta);
        while($it = $bd->resultado()){
            $respuesta = new Respuesta($it["id_respuesta"], $it["id_pregunta"], $it["texto"],
                    $it["correcta"], null, $it["imagen"]);
            $respuesta->setActivo($it["activo"]);
            $respuesta->setContestada($it["contestada"]);
            
            array_push($listaRespuestas, $respuesta);
        }
        $pregunta->setListaRespuestas($listaRespuestas);
        
        return $pregunta;
    }
    
    public static function modificaPregunta($pregunta){
        $bd = new BD();
        
        $sql = "update preguntas set anotacion = ?, explicacion = ?, url_video = ?, codigo = ?, enunciado = ? where id_pregunta = ?";
        
        $parametros = array($pregunta->getAnotacion(), $pregunta->getExplicacion(), $pregunta->getUrlVideo(),
        $pregunta->getCodigo(), $pregunta->getEnunciado(), $pregunta->getIdPregunta());
        
        $bd->setConsulta($sql);
        $bd->ejecutar($parametros);
        
        if($pregunta->getImagenEnunciado() != null){
            $sql = "update preguntas set imagen_enunciado = ? where id_pregunta = ?";
            $nombreImagen = "P".$pregunta->getIdPregunta().".png";
            $parametros = array($nombreImagen, $pregunta->getIdPregunta());
            move_uploaded_file($pregunta->getImagenEnunciado(), "../upload/pregunta/".$nombreImagen);
            $bd->setConsulta($sql);
            $bd->ejecutar($parametros);
        }
        
        if($pregunta->getImagenAclaracion() != null){
            $sql = "update preguntas set imagen_aclaracion = ? where id_pregunta = ?";
            $nombreImagen = "A".$pregunta->getIdPregunta().".png";
            $parametros = array($nombreImagen, $pregunta->getIdPregunta());
            move_uploaded_file($pregunta->getImagenAclaracion(), "../upload/pregunta/".$nombreImagen);
            $bd->setConsulta($sql);
            $bd->ejecutar($parametros);
        }
        
        if($pregunta->getImagenExplicacion() != null){
            $sql = "update preguntas set imagen_explicacion = ? where id_pregunta = ?";
            $nombreImagen = "E".$pregunta->getIdPregunta().".png";
            $parametros = array($nombreImagen, $pregunta->getIdPregunta());
            move_uploaded_file($pregunta->getImagenExplicacion(), "../upload/pregunta/".$nombreImagen);
            $bd->setConsulta($sql);
            $bd->ejecutar($parametros);
        }
        
        $sql = "delete from anyos_preguntas where id_pregunta = ?";
        $bd->setConsulta($sql);
        $bd->ejecutar($pregunta->getIdPregunta());
        
        $sql = "insert into anyos_preguntas (id_anyo, id_pregunta) values (?,?)";
        $bd->setConsulta($sql);
        $anyo = new Anyo(null, null, null, null);
        foreach ($pregunta->getListaAnyos() as $anyo){
            $parametros = array($anyo->getIdAnyo(), $pregunta->getIdPregunta());
            $bd->ejecutar($parametros);
        }
        
        $sql = "delete from preguntas_virtual where id_pregunta = ?";
        $bd->setConsulta($sql);
        $bd->ejecutar($pregunta->getIdPregunta());
        
        $sql = "insert into preguntas_virtual (id_unidad, id_pregunta) values (?,?)";
        $bd->setConsulta($sql);
        
        escribeLogTest("tenemos: ".count($pregunta->getListaUnidades()));
        
        foreach ($pregunta->getListaUnidades() as $unidad){
            
            $parametros = array($unidad, $pregunta->getIdPregunta());
            $bd->ejecutar($parametros);
        }
        
        $respuesta = new Respuesta(null, null, null, null, null, null);
        foreach ($pregunta->getListaRespuestas() as $respuesta){
            if($respuesta->getIdRespuesta() != null){
                Respuesta_BD::modificaRespuesta($respuesta);
            }else{
                Respuesta_BD::altaRespuesta($respuesta);
            }
        }
    }
    
    
    public static function eliminarImagenEnunciado($idPregunta){
        $bd = new BD();
        $bd->setConsulta("update preguntas set imagen_enunciado = null where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
    }
    
    public static function eliminarImagenAnotacion($idPregunta){
        $bd = new BD();
        $bd->setConsulta("update preguntas set imagen_aclaracion = null where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
    }
    
    public static function eliminarImagenExplicacion($idPregunta){
        $bd = new BD();
        $bd->setConsulta("update preguntas set imagen_explicacion = null where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
    }
    
    public static function alta($pregunta){
        $bd = new BD();
        
        if($pregunta->getImagenEnunciado() != null){
            $nombreImagen = "P".$pregunta->getIdPregunta().".png";
            move_uploaded_file($pregunta->getImagenEnunciado(), "../upload/pregunta/".$nombreImagen);
            $pregunta->setImagenEnunciado($nombreImagen);
        }
        
        if($pregunta->getImagenAclaracion() != null){
            $nombreImagen = "A".$pregunta->getIdPregunta().".png";
            move_uploaded_file($pregunta->getImagenAclaracion(), "../upload/pregunta/".$nombreImagen);
            $pregunta->setImagenAclaracion($nombreImagen);
        }
        
        if($pregunta->getImagenExplicacion() != null){
            $nombreImagen = "E".$pregunta->getIdPregunta().".png";
            move_uploaded_file($pregunta->getImagenExplicacion(), "../upload/pregunta/".$nombreImagen);
            $pregunta->setImagenExplicacion($nombreImagen);
        }
        
        $sql = "insert into preguntas (anotacion, explicacion, url_video, codigo, enunciado, imagen_enunciado, imagen_aclaracion, imagen_explicacion) values(?, ?, ?, ?, ?, ?, ?, ?)";
        $parametros = array($pregunta->getAnotacion(), $pregunta->getExplicacion(), $pregunta->getUrlVideo(),
        $pregunta->getCodigo(), $pregunta->getEnunciado(), $pregunta->getImagenEnunciado(),
        $pregunta->getImagenAclaracion(), $pregunta->getImagenExplicacion());
        
        $bd->setConsulta($sql);
        $bd->ejecutar($parametros);
        
        $bd->setConsulta("select id_pregunta from preguntas order by id_pregunta desc limit 1");
        $bd->ejecutar();
        if($it = $bd->resultado()){
            $pregunta->setIdPregunta($it["id_pregunta"]);
        }
        
        $sql = "insert into anyos_preguntas (id_anyo, id_pregunta) values (?,?)";
        $bd->setConsulta($sql);
        $anyo = new Anyo(null, null, null, null);
        foreach ($pregunta->getListaAnyos() as $anyo){
            $parametros = array($anyo->getIdAnyo(), $pregunta->getIdPregunta());
            $bd->ejecutar($parametros);
        }
        
        $sql = "insert into preguntas_virtual (id_pregunta, id_unidad) values (?,?)";
        $bd->setConsulta($sql);
        foreach ($pregunta->getListaUnidades() as $unidad){
            if(!empty($unidad)){
                $parametros = array($pregunta->getIdPregunta(), $unidad);
                $bd->ejecutar($parametros);
            }
        }
        
        $respuesta = new Respuesta(null, null, null, null, null, null);
        foreach ($pregunta->getListaRespuestas() as $respuesta){
            $respuesta->setIdPregunta($pregunta->getIdPregunta());
            if($respuesta->getIdRespuesta() != null){
                Respuesta_BD::modificaRespuesta($respuesta);
            }else{
                Respuesta_BD::altaRespuesta($respuesta);
            }
        }
    }
    
    public static function eliminarPregunta($idPregunta){
        $bd = new BD();
        $bd->setConsulta("delete from examenes_preguntas where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
        
        $bd->setConsulta("delete from anyos_preguntas where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
        
        $bd->setConsulta("delete from preguntas_virtual where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
        
        $bd->setConsulta("delete from preguntas where id_pregunta = ?");
        $bd->ejecutar($idPregunta);
        return $bd->filasModificadas();
    }
    
    public static function contarPreguntasPerdidas(){
        $bd = new BD();
        $bd->setConsulta("select COUNT(id_pregunta) total_registros from preguntas p1 where p1.id_pregunta not in(select distinct p.id_pregunta from preguntas p inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso)");
        $bd->ejecutar();
        if($it = $bd->resultado()){
            return $it["total_registros"];
        }
    }
    
    public static function listarPerdidas($registroInicio, $tamanyoPaginas){
        $bd = new BD();
        $bd->setConsulta("SELECT p1.enunciado, p1.activo, p1.id_pregunta, p1.codigo, null correctas, null incorrectas, null total from preguntas p1 where p1.id_pregunta not in(select distinct p.id_pregunta from preguntas p inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso) limit $registroInicio, $tamanyoPaginas");
        $bd->ejecutar();
        $arrPreguntas = array();
        while($it = $bd->resultado()){
            $pregunta = Pregunta_BD::llenarPregunta($it);
            $pregunta->setCodigo($it["codigo"]);
            array_push($arrPreguntas, $pregunta);
        }
        return $arrPreguntas;
    }
    
    public static function resultadoBuscador($campo1, $campo2, $campo3, $preguntas){
        $bd = new BD();
        
        $listaPreg = explode(",", $preguntas);
        $lis = "";
        $primero = true;
        foreach ($listaPreg as $pre){
            if($primero){
                $primero = false;
            }else{
                $lis.=",";
            }
            $p = explode("@", $pre);
            $lis.=$p[0];
        }
        
        $bd->setConsulta("select p.id_pregunta, p.activo, p.enunciado, p.codigo, concat(c.nombre, '/', t.nombre, '/', u.nombre, '/', a.nombre) as nombre_anyo, a.id_anyo from preguntas p inner join respuestas r on r.id_pregunta = p.id_pregunta inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso where UPPER(p.codigo) like ? and UPPER(p.enunciado) like ? and UPPER(r.texto) like ? and p.id_pregunta not in ($lis) group by p.id_pregunta, p.enunciado, p.activo, p.codigo, nombre_anyo, id_anyo");

        $parametros = array(strtoupper('%'.$campo1.'%'), strtoupper('%'.$campo2.'%'),
            strtoupper('%'.$campo3.'%'));
        $bd->ejecutar($parametros);
        
        $arrPreguntas = array();
        $pregunta = new Pregunta(0, null, null, null, null, null, null, null, null);
        
        while($it = $bd->resultado()){
            if($pregunta->getIdPregunta() != $it["id_pregunta"]){
                if($pregunta->getIdPregunta() != 0){
                    array_push($arrPreguntas, $pregunta);
                }
                $pregunta = new Pregunta($it["id_pregunta"], $it["enunciado"], null, null, null, null, null, null, null);
                $pregunta->setCodigo($it["codigo"]);
                $pregunta->setActivo($it["activo"]);
            }
            $arrAnyo = $pregunta->getListaAnyos();
            array_push($arrAnyo, $it["id_anyo"]."@".$it["nombre_anyo"]);
            $pregunta->setListaAnyos($arrAnyo);
        }
        
        if($pregunta->getIdPregunta() != 0){
            array_push($arrPreguntas, $pregunta);
        }
        
        return $arrPreguntas;
    }
    
    public static function preguntasAzar($idExamen){
        $bd = new BD();
        
        $sql = "select rand(now()) azar, p.id_pregunta, p.enunciado, p.imagen_enunciado, url_video from preguntas p inner join examenes_preguntas ep on ep.id_pregunta = p.id_pregunta where ep.id_examen = ? and p.activo = 1 order by azar";
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idExamen);
        $arrPreguntas = array();
        while($it = $bd->resultado()){
            $pregunta = new Pregunta($it["id_pregunta"], $it["enunciado"], null,
                null, $it["url_video"], $it["imagen_enunciado"], null, null, null);
            array_push($arrPreguntas, $pregunta);
        }
        return $arrPreguntas;
    }
    
    public static function preguntasPorExamen($idExamen){
        $bd = new BD();
        
        $sql = "select p.id_pregunta, p.enunciado, p.codigo, a.id_anyo, concat(c.nombre, '/', t.nombre, '/', u.nombre, '/', a.nombre) as nombre_anyo from preguntas p inner join examenes_preguntas ep on ep.id_pregunta = p.id_pregunta inner join anyos a on ep.id_anyo = a.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso where ep.id_examen = ? and p.activo = 1 order by p.codigo";
        $bd->setConsulta($sql);
        
        $bd->ejecutar($idExamen);
        $arrPreguntas = array();
        while($it = $bd->resultado()){
            $pregunta = new Pregunta($it["id_pregunta"], $it["enunciado"], null,
                null, null, null, null, null, null);
            $pregunta->setCodigo($it["codigo"]);
            $pregunta->setIdAnyo($it["id_anyo"]);
            $pregunta->setNombreAnyo($it["nombre_anyo"]);
            array_push($arrPreguntas, $pregunta);
        }
        return $arrPreguntas;
    }
    
    public static function asociar($idExamen, $preg){
        $ele = explode("@", $preg);
        $bd = new BD();
        $bd->setConsulta("select count(id_pregunta) as total_preg from examenes_preguntas where id_examen = ? and id_pregunta = ?");
        $bd->ejecutar(array($idExamen, $ele[0]));
        
        if($it = $bd->resultado()){
            if($it["total_preg"] == 0){
                $bd->setConsulta("insert into examenes_preguntas (id_examen, id_pregunta, id_anyo) values(?, ?, ?)");
                $bd->ejecutar(array($idExamen, $ele[0], $ele[1]));
            }else{
                $bd->setConsulta("update examenes_preguntas set id_anyo = ? where id_examen = ? and id_pregunta = ?");
                $bd->ejecutar(array($ele[1], $idExamen, $ele[0]));
            }
        }
    }
}