<? include 'trozos/session.php';
 require_once 'clases/Curso.php';
 seguridad("usuario");

if(isset($_GET["id_curso"])){
    $curso = Curso_BD::porId($_GET["id_curso"]);
}
header("HTTP/1.1 301 Moved Permanently");
header("Location: http://training.inspiracle.com/contratar-curso.php"); 