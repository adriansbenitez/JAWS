<? require_once '../clases/Usuario.php';
require_once '../clases/Correo.php';
require_once '../clases/Provincia.php';
require_once '../clases/Curso.php';
require_once '../clases/Tema.php';
require_once '../clases/Pregunta.php';
require_once '../clases/Examen.php';
require_once '../clases/CreadorExamenes.php';
require_once '../clases/Categoria.php';
require_once '../clases/Calendario.php';
require_once '../clases/Grupo.php';
require_once '../clases/Periodo.php';
require_once '../recaptcha-php-1.11/recaptchalib.php';
require_once 'log.php';

if (!isset($_SESSION)) {
    session_start();
}

primeraLinea();

if (!isset($_SESSION["usuario"]) && $_REQUEST["accion"] != "login") {
    if (isset($_COOKIE["iden_user"])) {
        $usuario = Usuario_BD::porId($_COOKIE["iden_user"]);
    }

    if (isset($_COOKIE["iden_user"]) && isset($_COOKIE["c_iden_user"]) && isset($usuario) && md5("azul" . $_COOKIE["iden_user"] . $usuario->getClave()) == $_COOKIE["c_iden_user"]) {
        escribeLog("el usuario tiene la cookie creada y debe seguir conectado");
        $usuario = Usuario_BD::porId($_COOKIE["iden_user"]);
        $_SESSION["usuario"] = $usuario;
    }
}

$irA;

//die();

if (isset($_REQUEST["accion"])) {

    global $irA;

    switch ($_REQUEST["accion"]) {

        case "registrar":
            $irA = "../index.php";
            registrar();
            break;

        case "login":
            login();
            break;

        case "recupera_clave":
            recupera_clave();
            break;

        case "bloquearUsuario":
            $irA = "../perfiles.php";
            bloquearUsuario();
            break;

        case "borrarUsuario":
            $irA = "../perfiles.php";
            borrarUsuario();
            break;

        case "desbloquearUsuario":
            $irA = "../perfiles.php";
            desbloquearUsuario();
            break;

        case "logout":
            session_destroy();
            setcookie("iden_user", null, time() - 500, "/");
            setcookie("c_iden_user", null, time() - 500, "/");
            $irA = "../index.php";
            break;

        case "contacto":
            contacto();
            $irA = "../contacto.php";
            break;

        case "contratar":
            contratar();
            break;

        case "cambiarNivel":
            cambiarNivel();
            $irA = "../perfiles.php";
            break;

        case "editarCategoria":
            editarCategoria();
            break;

        case "cambiaEstadoPregunta":
            cambiaEstadoPregunta();
            break;

        case "modificaPregunta":
            modificaPregunta();
            break;

        case "eliminafoto":
            eliminafoto();
            break;

        case "activaRespuesta":
            activaRespuesta();
            break;
        
        case "desactivaRespuesta":
            desactivaRespuesta();
            break;
        
        case "eliminaRespuesta":
            eliminaRespuesta();
            break;

        case "altaPregunta":
            altaPregunta();
            $irA = "../listpreguntas.php?id_anyo=" . $_REQUEST["id_anyo"];
            break;

        case "borrarPregunta":
            borrarPregunta();
            break;

        case "cambiaEstadoExamen":
            cambiaEstadoExamen();
            break;

        case "borrarExamen":
            borrarExamen();
            break;

        case "crearExamen":
            crearExamen();
            $irA = "../listexamenes.php";
            break;

        case "crearExamenFalladas":
            crearExamenFalladas();
            $irA = "../listexamenes.php";
            break;

        case "crearExamenPersonalizado":
            crearExamenPersonalizado();
            $irA = "../listexamenes.php";
            break;

        case "rendirExamen":
            rendirExamen();
            break;

        case "altaCategoria":
            altaCategoria();
            $irA = "../listcursos.php";
            break;

        case "estadoCategoria":
            estadoCategoria();
            $irA = "../listcursos.php";
            break;

        case "borrarCategoria":
            borrarCategoria();
            $irA = "../listcursos.php";
            break;

        case "activaTema":
            activaTema();
            break;

        case "activaAnyo";
            activaAnyo();
            break;

        case "enviarTutoria":
            enviarTutoria();
            $irA = "../tutoria.php";
            break;

        case "marcar_todos_admin":
            marcar_todos_admin();
            $irA = "../listcursos.php";
            break;

        case "editar_examen":
            editar_examen();
            $irA = "../listexamenes.php";
            break;

        case "alta_calendario":
            alta_calendario();
            break;

        case "borrar_calendario":
            borrar_calendario();
            break;

        case "nuevo_grupo":
            nuevo_grupo();
            $irA = "../grupos.php";
            break;

        case "editar_grupo":
            editar_grupo();
            $irA = "../grupos.php";
            break;

        case "modificar_calendario":
            modificar_calendario();
            if ($_SESSION["usuario"]->getNivel() == 1) {
                $irA = "../calendario_general.php";
            } else {
                $irA = "../calendario.php";
            }
            break;

        case "mantener_session":
            mantener_session();
            break;

        case "enviar_alertas":
            enviar_alertas();
            die();
            break;

        case "borrarResultado":
            borrarResultado();
            break;

        case "cambiarMasFalladas":
            cambiarMasFalladas();
            break;

        case "alta_periodo":
            altaPeriodo();
            break;

        case "borrar_periodo":
            borrarPeriodo();
            break;
        
        case "borrar_grupo":
            borrar_grupo();
            break;
        
        case "crear_listado_usuarios":
            crear_listado_usuarios();
            break;
        
        case "escribirCorreo":
            escribirCorreo();
            $irA = "../perfiles.php";
            break;
        
        default:
            $_SESSION["aviso"] = "No se reconoce la acción";
    }

    if (!empty($irA)) {
        header("Location: $irA");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}

function registrar() {

    if (!Usuario_BD::existeCorreo($_REQUEST["email"])) {
        escribeLog("El correo " . $_REQUEST["email"] . " no está en la base de datos");
        $usuario = new Usuario(null, $_SERVER["REMOTE_ADDR"], $_REQUEST["email"], 2, $_REQUEST["nombre"], $_REQUEST["apellidos"], null, null, null, "default.jpg", null, null, $_REQUEST["interes"], null, 0, null);
        $usuario->setMovil(0);


        $usuario->setClave($_REQUEST["clave"]);
        $privatekey = "6LcIe_USAAAAANxubb5n7frg73LjHrOo_EywMGSr";
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_REQUEST["recaptcha_challenge_field"], $_REQUEST["recaptcha_response_field"]);
        $prueba = true;
        if ($prueba || $resp->is_valid) {
            if (Usuario_BD::agregar($usuario)) {
                escribeLog("El usuario se registra sin problemas");
            } else {
                $_SESSION["aviso"] = "Hay un problema a la hora de registrarte<br/>" .
                        "Prueba nuevamente.<br/>Si el problema persiste, por favor," .
                        " ponte en contacto con nosotros";
                escribeLog("Hay un problema al registrar al usuario", true);
            }

            $correo = new Correo();
            $correo->setAsunto("Nuevo Usuario");
            $correo->setPlantilla("plantilla_nuevo_usuario.html");
            $correo->agregarRemplazo("email", $usuario->getEmail());
            $correo->agregarRemplazo("nombre", $usuario->getNombre());
            $correo->agregarRemplazo("apellidos", $usuario->getApellido());
            $correo->agregarRemplazo("comentarios", "Usuario nuevo registrado en el inicio");
            $curso = Curso_BD::porId($_REQUEST["interes"]);
            $correo->agregarRemplazo("interes", $curso->getNombre());
            $correo->enviar();
            $_SESSION["aviso"] = "GRACIAS POR REGISTRARTE!!!!.<br> Ya puedes acceder a la demo";
        } else {
            $_SESSION["aviso"] = "Captcha no válido";
        }
    } else {
        $_SESSION["aviso"] = "El usuario ya está registrado";
    }
}

function login() {

    global $irA;

    $usuario = Usuario_BD::login($_REQUEST["email"], $_REQUEST["clave"]);
    $irA = "../index.php";

    if (isset($usuario)) {
        $_SESSION["usuario"] = $usuario;

        setcookie("iden_user", $_SESSION["usuario"]->getIdUsuario(), time() + (36000), "/");
        setcookie("c_iden_user", md5("azul" . $_SESSION["usuario"]->getIdUsuario() . $_SESSION["usuario"]->getClave()), time() + (36000), "/");

        $_SESSION["aviso"] = "Bienvenido a la plataforma de Formacion Inspiracle versión 3.3.1<br>" .
                "Te recordamos que seguimos trabajando para mejorarla. Gracias";
        if ($usuario->getNivel() == 1) {
            escribeLog("Entra como administrador");
            $irA = "../perfiles.php";
        } else {
            escribeLog("Entra como usuario convencional");
            $irA = "../perfil.php";
        }
    } else {
        escribeLog("Login incorrecto");
        setcookie("iden_user", null, time() - 500, "/");
        setcookie("c_iden_user", null, time() - 500, "/");

        $_SESSION["aviso"] = "Los datos introducidos no son validos";
    }
}

function recupera_clave() {

    global $irA;

    if (isset($_SESSION["usuario"])) {
        $irA = "../perfil.php";
        return null;
    }

    $privatekey = "6LcIe_USAAAAANxubb5n7frg73LjHrOo_EywMGSr";
    $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
    if (!$resp->is_valid) {
        $irA = "../recuperacontra.php";
        $_SESSION["aviso"] = "El código captcha no es correcto";
        return null;
    }

    if (Usuario_BD::existeCorreo($_POST["email"])) {
        $clave = Usuario_BD::recuperaClave($_POST["email"]);

        $correo = new Correo();
        $correo->agregarDestinatarios($_POST["email"]);
        $correo->setAsunto("Recuperación de clave");
        $correo->setPlantilla("plantilla_recupera_clave.html");
        $correo->agregarRemplazo("email", $_POST["email"]);
        $correo->agregarRemplazo("clave", $clave);
        $correo->enviar();

        $_SESSION["aviso"] = "Su contraseña ha sido enviada a su correo electrónico";
        $irA = "../";
        return null;
    } else {
        $_SESSION["aviso"] = "El correo que indica no está registrado en nuestra base de datos";
        $irA = "../recuperacontra.php";
    }
}

function esAdmin() {
    if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
        return true;
    }
    return false;
}

function bloquearUsuario() {
    if (!esAdmin()) {
        escribeLog("Se intenta bloquear un usuario sin ser administrador", true);
        die();
    }
    if (Usuario_BD::bloquear($_GET["id_usuario"]) > 0) {
        escribeLog("el usuario ha sido bloqueado");
        $_SESSION["aviso"] = "El usuario ha sido bloqueado";
    } else {
        escribeLog("el usuario no se puede bloquear");
        $_SESSION["aviso"] = "No es posible bloquear el usuario";
    }
}

function borrarUsuario() {
    if (!esAdmin()) {
        escribeLog("Se intenta borrar un usuario sin ser administrador", true);
        die();
    }

    if (Usuario_BD::borrar($_GET["id_usuario"]) > 0) {
        escribeLog("El usuario ha sido borrado");
        $_SESSION["aviso"] = "El usuario ha sido borrado";
    } else {
        escribeLog("No se puede borrar el usuario");
        $_SESSION["aviso"] = "El usuario no ha podido ser borrado";
    }
}

function desbloquearUsuario() {
    if (!esAdmin()) {
        escribeLog("Se intenta desbloquear un usuario sin ser administrador", true);
        die();
    }

    if (Usuario_BD::desbloquear($_GET["id_usuario"]) > 0) {
        escribeLog("el usuario ha sido desbloqueado");
        $_SESSION["aviso"] = "El usuario ha sido desbloqueado";
    } else {
        escribeLog("el usuario no se puede desbloquear");
        $_SESSION["aviso"] = "No es posible desbloquear el usuario";
    }
}

function contacto() {

    $correo = new Correo();
    $correo->setPlantilla("plantilla_contacto.html");
    $correo->agregarRemplazo("email", $_REQUEST["email"]);
    $correo->agregarRemplazo("nombre", $_REQUEST["nombre"]);
    $correo->agregarRemplazo("apellidos", $_REQUEST["apellido"]);
    $correo->agregarRemplazo("dni", $_REQUEST["dni"]);
    $provincia = Provincia_BD::porId($_REQUEST["provincia"]);
    $correo->agregarRemplazo("provincia", $provincia->getNombre());
    $correo->agregarRemplazo("direccion", $_REQUEST["direccion"]);
    $correo->agregarRemplazo("ciudad", $_REQUEST["ciudad"]);
    $correo->agregarRemplazo("telefono", $_REQUEST["telefono"]);
    $correo->agregarRemplazo("movil", $_REQUEST["movil"]);
    $correo->agregarRemplazo("universidad", $_REQUEST["universidad"]);
    $correo->agregarRemplazo("comentarios", $_REQUEST["comentarios"]);
    $correo->setAsunto("Contacto desde WEB");

    if (isset($_REQUEST["chkpolitica"]) && isset($_REQUEST["email"]) && isset($_REQUEST["nombre"]) && isset($_REQUEST["apellido"]) && isset($_REQUEST["ciudad"]) && isset($_REQUEST["comentarios"]) && isset($_REQUEST["provincia"]) && $_REQUEST["provincia"] != 0) {
        $privatekey = "6LcIe_USAAAAANxubb5n7frg73LjHrOo_EywMGSr";
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
            $correo->enviar();
            $_SESSION["aviso"] = "Tu mensaje a sido enviado";
        } else {
            $_SESSION["aviso"] = "Captcha no válido";
        }
    } else {
        $_SESSION["aviso"] = "Hay un error en el envío del mensaje";
    }
}

function contratar() {

    global $irA;
    
    $usuario = new Usuario(null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

    if (isset($_SESSION["usuario"])) {
        
        $usuario = $_SESSION["usuario"];
        
        if (!empty($_REQUEST["movil"])) {
            $usuario->setMovil($_REQUEST["movil"]);
        } else {
            $usuario->setMovil(null);
        }

        if (!empty($_REQUEST["movil"])) {
            $usuario->setTelefono($_REQUEST["telefono"]);
        } else {
            $usuario->setTelefono(null);
        }
        
        $usuario->setUniversidad($_REQUEST["universidad"]);
        
        $usuario->setIdProvincia($_REQUEST["provincia"]);
        
        $usuario->setCiudad($_REQUEST["ciudad"]);
        
        $usuario->setDireccion($_REQUEST["direccion"]);

        if (Usuario_BD::modificar($usuario)) {
            escribeLog("Se agrega un nuevo usuario y contrata el curso");
            $correo = new Correo();

            $correo->setPlantilla("plantilla_nuevo_usuario_completo.html");
            $correo->setAsunto("Nuevo usuario y contratación de servicios");

            $correo->agregarRemplazo("email", $usuario->getEmail());
            $correo->agregarRemplazo("nombre", $usuario->getNombre());
            $correo->agregarRemplazo("apellidos", $usuario->getApellido());

            $res = "";
            switch ($_REQUEST["interes"]) {
                case "1":
                    $res = "Curso BIR";
                    break;
                case "2":
                    $res = "Curso QIR";
                    break;
                case "7":
                    $res = "Curso FIR";
                    break;
            }
            $correo->agregarRemplazo("interes", $res);

            $provincia = Provincia_BD::porId($_REQUEST["provincia"]);
            $correo->agregarRemplazo("provincia", $provincia->getNombre());

            $correo->agregarRemplazo("ciudad", $usuario->getCiudad());
            $correo->agregarRemplazo("direccion", $usuario->getDireccion());
            $correo->agregarRemplazo("telefono", $usuario->getTelefono());
            $correo->agregarRemplazo("movil", $usuario->getMovil());
            $correo->agregarRemplazo("universidad", $usuario->getUniversidad());
            $correo->agregarRemplazo("comentarios", $_REQUEST["comentarios"]);

            $correo->enviar();

            $_SESSION["aviso"] = "Su solicitud se ha enviado correctamente<br/>Gracias" .
                    " por ponerse en contacto con nosotros.<br> En breve atenderemos" .
                    " su petición.<br/><br/>Ya puedes acceder a la plataforma y probarla" .
                    " con una demostración.";
            $irA = "../index.php";
        } else {
            $_SESSION["aviso"] = "No ha sido posible registrarle en nuestro sitio web." .
                    " Por favor contacta con nosotros";
            $irA = "";
            escribeLog("Ocurre un error al registrar al usuario", true);
        }
    } else {
        if (Usuario_BD::existeCorreo($_REQUEST["email"])) {
            $irA = "../tarifas.php";
            $_SESSION["aviso"] = "El correo facilitado ya se encuentra en nuestra base" .
                    " de datos, por lo que ya estás registrado en nuestro sitio, por" .
                    " cualquier problema ponte en contacto con nosotros.";
        } else {
            $usuario = new Usuario(null, $_SERVER["REMOTE_ADDR"], $_REQUEST["email"], 2, $_REQUEST["nombre"], $_REQUEST["apellidos"], null, $_REQUEST["provincia"], null, "default.jpg", $_REQUEST["direccion"], $_REQUEST["ciudad"], $_REQUEST["interes"], null, 0, $_REQUEST["universidad"]);
            $usuario->setClave($_REQUEST["clave"]);

            if (!empty($_REQUEST["movil"])) {
                $usuario->setMovil($_REQUEST["movil"]);
            } else {
                $usuario->setMovil(null);
            }

            if (!empty($_REQUEST["movil"])) {
                $usuario->setTelefono($_REQUEST["telefono"]);
            } else {
                $usuario->setTelefono(null);
            }

            if (Usuario_BD::agregar($usuario)) {
                escribeLog("Se agrega un nuevo usuario y contrata el curso");
                $correo = new Correo();

                $correo->setPlantilla("plantilla_nuevo_usuario_completo.html");
                $correo->setAsunto("Nuevo usuario y contratación de servicios");

                $correo->agregarRemplazo("email", $_REQUEST["email"]);
                $correo->agregarRemplazo("nombre", $_REQUEST["nombre"]);
                $correo->agregarRemplazo("apellidos", $_REQUEST["apellidos"]);

                $res = "";
                switch ($_REQUEST["interes"]) {
                    case "1":
                        $res = "Curso BIR";
                        break;
                    case "2":
                        $res = "Curso QIR";
                        break;
                    case "7":
                        $res = "Curso FIR";
                        break;
                }
                $correo->agregarRemplazo("interes", $res);

                $provincia = Provincia_BD::porId($_REQUEST["provincia"]);
                $correo->agregarRemplazo("provincia", $provincia->getNombre());

                $correo->agregarRemplazo("ciudad", $_REQUEST["ciudad"]);
                $correo->agregarRemplazo("direccion", $_REQUEST["direccion"]);
                $correo->agregarRemplazo("telefono", $_REQUEST["telefono"]);
                $correo->agregarRemplazo("movil", $_REQUEST["movil"]);
                $correo->agregarRemplazo("universidad", $_REQUEST["universidad"]);
                $correo->agregarRemplazo("tiempo", $_REQUEST["tiempo_curso"]);
                $correo->agregarRemplazo("manual", $_REQUEST["manual"]);
                $correo->agregarRemplazo("comentarios", $_REQUEST["comentarios"]);

                $correo->enviar();

                $_SESSION["aviso"] = "Su solicitud se ha enviado correctamente<br/>Gracias" .
                        " por ponerse en contacto con nosotros.<br> En breve atenderemos" .
                        " su petición.<br/><br/>Ya puedes acceder a la plataforma y probarla" .
                        " con una demostración.";
                $irA = "../index.php";
            } else {
                $_SESSION["aviso"] = "No ha sido posible registrarle en nuestro sitio web." .
                        " Por favor contacta con nosotros";
                $irA = "";
                escribeLog("Ocurre un error al registrar al usuario", true);
            }
        }
    }
}

function cambiarNivel() {
    if (!esAdmin()) {
        die();
    }
    if (Usuario_BD::cambiarNivel($_GET["id_usuario"], $_GET["nivel"])) {
        $_SESSION["aviso"] = "Nivel modificado";
        escribeLog("Se modifica el nivel a " . $_GET["nivel"]);
    } else {
        $_SESSION["aviso"] = "No es posble modificar el nivel";
        escribeLog("No se puede modificar el nivel");
    }
}

function editarCategoria() {
    if (!esAdmin()) {
        die();
    }

    switch ($_REQUEST["nivel"]) {
        case 1:
            $curso = new Curso($_REQUEST["elid"], $_REQUEST["nombre"], 0);
            $curso->setPrecio($_REQUEST["precio"]);
            if (isset($_REQUEST["cuarto"])) {
                $curso->setCuarto(1);
            } else {
                $curso->setCuarto(0);
            }
            if (Curso_BD::modificar($curso)) {
                $_SESSION["aviso"] = "El curso ha sido modificado";
            } else {
                $_SESSION["aviso"] = "No ha sido posible modificar el curso";
            }
            break;

        case 2:
            $tema = new Tema($_REQUEST["elid"], $_REQUEST["nombre"], 0, 0);
            $tema->setPrecio($_REQUEST["precio"]);
            if (Tema_BD::modificar($tema)) {
                $_SESSION["aviso"] = "La asignatura ha sido modificado";
            } else {
                $_SESSION["aviso"] = "No ha sido posible modificar la asignatura";
            }
            break;

        case 3:
            $unidad = new Unidad($_REQUEST["elid"], $_REQUEST["nombre"], 0, 0);
            if (Unidad_BD::modificar($unidad)) {
                $_SESSION["aviso"] = "La unidad temática ha sido modificado";
            } else {
                $_SESSION["aviso"] = "No ha sido posible modificar la unidad temática";
            }
            break;

        case 4:
            $anyo = new Anyo($_REQUEST["elid"], $_REQUEST["nombre"], 0, 0);
            if (Anyo_BD::modificar($anyo)) {
                $_SESSION["aviso"] = "El año ha sido modificado";
            } else {
                $_SESSION["aviso"] = "No ha sido posible modificar la unidad temática";
            }
            break;
    }
}

function cambiaEstadoPregunta() {
    if (!esAdmin()) {
        die();
    }

    if (isset($_GET["id_pregunta"])) {
        $pregunta = Pregunta_BD::porId($_GET["id_pregunta"]);
        if (Pregunta_BD::cambiaEstado($pregunta->getIdPregunta(), $pregunta->getActivo())) {
            $_SESSION["aviso"] = "Se ha cambiado el estado de pregunta";
        } else {
            $_SESSION["aviso"] = "No ha sido posible cambiar el estado";
        }
    }
}

function modificaPregunta() {
    if (!esAdmin()) {
        die();
    }

    $anotacion = null;
    if (!empty($_REQUEST["anotacion"])) {
        $anotacion = $_REQUEST["anotacion"];
    }
    $imagenAnotacion = null;
    if (!empty($_FILES["imagen_anotacion"])) {
        $imagenAnotacion = $_FILES["imagen_anotacion"]["tmp_name"];
    }
    $explicacion = null;
    if (!empty($_REQUEST["explicacion"])) {
        $explicacion = $_REQUEST["explicacion"];
    }
    $imagenExplicacion = null;
    if (!empty($_FILES["imagen_explicacion"])) {
        $imagenExplicacion = $_FILES["imagen_explicacion"]["tmp_name"];
    }
    $urlVideo = null;
    if (!empty($_REQUEST["video"])) {
        $urlVideo = $_REQUEST["video"];
    }
    $imagenEnunciado = null;
    if (!empty($_FILES["imagen_enunciado"])) {
        $imagenEnunciado = $_FILES["imagen_enunciado"]["tmp_name"];
    }

    $pregunta = new Pregunta($_REQUEST["id_pregunta"], $_REQUEST["enunciado"], $anotacion, $explicacion, $urlVideo, $imagenEnunciado, $imagenExplicacion, $imagenAnotacion, null);
    $pregunta->setCodigo($_REQUEST["codigo"]);
    $listaAnyos = array();
    for ($i = 1; $i <= $_REQUEST["ultimoTemario"]; $i++) {
        if (isset($_REQUEST["anyo_" . $i])) {
            $anyo = new Anyo($_REQUEST["anyo_" . $i], null, null, null);
            array_push($listaAnyos, $anyo);
        }
    }

    $pregunta->setListaAnyos($listaAnyos);

    $listaRespuestas = array();
    for ($i = 1; $i <= $_REQUEST["hidcontrespuestas"]; $i++) {
        if (isset($_REQUEST["respuesta_" . $i])) {
            $correcta = 0;
            if (isset($_REQUEST["chkrespuesta_" . $i])) {
                $correcta = 1;
            }
            $idRespuesta = null;
            if (isset($_REQUEST["id_respuesta_" . $i])) {
                $idRespuesta = $_REQUEST["id_respuesta_" . $i];
            }

            $imagen = null;
            if (!empty($_FILES["imagen_respuesta_" . $i]["name"])) {
                $imagen = $_FILES["imagen_respuesta_" . $i]["tmp_name"];
            }

            $archivo = null;
            if (!empty($_REQUEST["archivo_respuesta_" . $i])) {
                $archivo = $_REQUEST["archivo_respuesta_" . $i];
            }

            $respuesta = new Respuesta($idRespuesta, $_REQUEST["id_pregunta"], $_REQUEST["respuesta_" . $i], $correcta, null, $imagen);
            $respuesta->setArchivo($archivo);
            array_push($listaRespuestas, $respuesta);
        }
    }

    if (!empty($_REQUEST["id_unidad"])) {
        $listaUnidades = explode(",", $_REQUEST["id_unidad"]);
        $pregunta->setListaUnidades($listaUnidades);
    }

    $pregunta->setListaRespuestas($listaRespuestas);

    Pregunta_BD::modificaPregunta($pregunta);
    $_SESSION["aviso"] = "Pregunta modificada";
}

function eliminafoto() {
    if (!esAdmin()) {
        die();
    }

    switch ($_GET["campo"]) {
        case "1":
            Pregunta_BD::eliminarImagenEnunciado($_GET["id_pregunta"]);
            break;
        case "2":
            Pregunta_BD::eliminarImagenAnotacion($_GET["id_pregunta"]);
            break;
        case "3":
            Pregunta_BD::eliminarImagenExplicacion($_GET["id_pregunta"]);
            break;
        case "4":
            Respuesta_BD::eliminarImagen($_GET["id_respuesta"]);
            break;
    }
    $_SESSION["aviso"] = "Imagen eliminada";
    unlink("../upload/pregunta/" . $_GET["imagen"]);
}

function activaRespuesta(){
    if (!esAdmin()) {
        die();
    }

    if (Respuesta_BD::activaRespuesta($_GET["id_respuesta"]) > 0) {
        $_SESSION["aviso"] = "Respuesta activada";
    }
}


function desactivaRespuesta(){
    if (!esAdmin()) {
        die();
    }

    if (Respuesta_BD::desactivaRespuesta($_GET["id_respuesta"]) > 0) {
        $_SESSION["aviso"] = "Respuesta desactivada";
    }
}

function eliminaRespuesta() {
    if (!esAdmin()) {
        die();
    }

    if (Respuesta_BD::eliminaRespuesta($_GET["id_respuesta"]) > 0) {
        $_SESSION["aviso"] = "Respuesta eliminada";
    }
}

function altaPregunta() {
    if (!esAdmin()) {
        die();
    }

    $anotacion = null;
    if (!empty($_REQUEST["anotacion"])) {
        $anotacion = $_REQUEST["anotacion"];
    }
    $imagenAnotacion = null;
    if (!empty($_FILES["imagen_anotacion"])) {
        $imagenAnotacion = $_FILES["imagen_anotacion"]["tmp_name"];
    }
    $explicacion = null;
    if (!empty($_REQUEST["explicacion"])) {
        $explicacion = $_REQUEST["explicacion"];
    }
    $imagenExplicacion = null;
    if (!empty($_FILES["imagen_explicacion"])) {
        $imagenExplicacion = $_FILES["imagen_explicacion"]["tmp_name"];
    }
    $urlVideo = null;
    if (!empty($_REQUEST["video"])) {
        $urlVideo = $_REQUEST["video"];
    }
    $imagenEnunciado = null;
    if (!empty($_FILES["imagen_enunciado"])) {
        $imagenEnunciado = $_FILES["imagen_enunciado"]["tmp_name"];
    }

    $pregunta = new Pregunta(null, $_REQUEST["enunciado"], $anotacion, $explicacion, $urlVideo, $imagenEnunciado, $imagenExplicacion, $imagenAnotacion, null);
    $pregunta->setCodigo($_REQUEST["codigo"]);

    if (isset($_REQUEST["id_unidad"])) {
        $listaUnidades = explode(",", $_REQUEST["id_unidad"]);
        $pregunta->setListaUnidades($listaUnidades);
    }

    $listaAnyos = array();
    for ($i = 1; $i <= $_REQUEST["ultimoTemario"]; $i++) {
        if (isset($_REQUEST["anyo_" . $i])) {
            $anyo = new Anyo($_REQUEST["anyo_" . $i], null, null, null);
            array_push($listaAnyos, $anyo);
        }
    }
    $pregunta->setListaAnyos($listaAnyos);

    $listaRespuestas = array();
    for ($i = 1; $i <= $_REQUEST["hidcontrespuestas"]; $i++) {
        if (isset($_REQUEST["respuesta_" . $i])) {
            $correcta = 0;
            if (isset($_REQUEST["chkrespuesta_" . $i])) {
                $correcta = 1;
            }
            $idRespuesta = null;

            $imagen = null;
            if (!empty($_FILES["imagen_respuesta_" . $i]["name"])) {
                $imagen = $_FILES["imagen_respuesta_" . $i]["tmp_name"];
            }

            $respuesta = new Respuesta(null, $_REQUEST["id_pregunta"], $_REQUEST["respuesta_" . $i], $correcta, null, $imagen);
            array_push($listaRespuestas, $respuesta);
        }
    }
    $pregunta->setListaRespuestas($listaRespuestas);

    Pregunta_BD::alta($pregunta);
    $_SESSION["aviso"] = "Nueva pregunta disponible";
}

function borrarPregunta() {
    if (!esAdmin()) {
        die();
    }

    Pregunta_BD::eliminarImagenEnunciado($_GET["id_pregunta"]);
    Pregunta_BD::eliminarImagenAnotacion($_GET["id_pregunta"]);
    Pregunta_BD::eliminarImagenExplicacion($_GET["id_pregunta"]);
    $pregunta = Pregunta_BD::porIdCompleta($_GET["id_pregunta"]);

    $respuesta = new Respuesta(null, null, null, null, null, null);
    foreach ($pregunta->getListaRespuestas() as $respuesta) {
        Respuesta_BD::eliminarImagen($respuesta->getIdRespuesta());
        Respuesta_BD::eliminaRespuesta($respuesta->getIdRespuesta());
    }

    if (Pregunta_BD::eliminarPregunta($pregunta->getIdPregunta())) {
        $_SESSION["aviso"] = "Pregunta eliminada";
    } else {
        $_SESSION["aviso"] = "No se ha podido eliminar la pregunta";
    }
}

function cambiaEstadoExamen() {
    if (!esAdmin()) {
        die();
    }

    $examen = Examen_BD::porId($_GET["id_examen"]);
    
    if($examen->getActivo() == 1){
        $examen->setActivo(0);
    }else{
        $examen->setActivo(1);
    }
    

    if (Examen_BD::cambiarEstado($examen)) {
        if ($examen->getActivo()) {
            $_SESSION["aviso"] = "El examen " . $examen->getTitulo() . " ha sido activado";
        } else {
            $_SESSION["aviso"] = "El examen " . $examen->getTitulo() . " ha sido desactivado";
        }
    } else {
        $_SESSION["aviso"] = "No se ha podido cambiar el estado del examen";
    }
}

function borrarExamen() {
    if (!esAdmin()) {
        $examen = Examen_BD::porId($_GET["id_examen"]);
        if($examen->getIdAutor() != $_SESSION["usuario"]->getIdUsuario()){
            die();    
        }
    }

    if (Examen_BD::borrarExamen($_GET["id_examen"])) {
        return $_SESSION["aviso"] = "El examen ha sido borrado";
    } else {
        return $_SESSION["aviso"] = "No ha sido posible borrar el examen";
    }
}

function crearExamen() {
    if (!isset($_SESSION["usuario"])) {
        die();
    }

    if ($_SESSION["usuario"]->getNivel() == 2) {
        if ($_SESSION["usuario"]->getDemoUsado() == 1) {
            $_SESSION["aviso"] = "Has llegado la tope de exámenes que puedes generar con la plataforma en modo demo";
            $irA = "bloques.php";
            return 0;
        }
    }

    $creadorExamen = new CreadorExamenes();

    if (!empty($_REQUEST["cursos"])) {
        $creadorExamen = CreadorExamenes_BD::totalesPreguntasCurso($_SESSION["usuario"], $_REQUEST["cursos"]);
    } else if (!empty($_REQUEST["temas"])) {
        $creadorExamen = CreadorExamenes_BD::totalesPreguntasTema($_SESSION["usuario"], $_REQUEST["temas"]);
    } else if (!empty($_REQUEST["unidades"])) {
        $creadorExamen = CreadorExamenes_BD::totalesPreguntasUnidades($_SESSION["usuario"], $_REQUEST["unidades"]);
    } else if (!empty($_REQUEST["anyos"])) {
        $creadorExamen = CreadorExamenes_BD::totalesPreguntasAnyos($_REQUEST["anyos"]);
    }
    
    if (!empty($_REQUEST["id_grupo"])) {
        $listaGrupos = explode(",", $_REQUEST["id_grupo"]);
        $creadorExamen->setListaGrupos($listaGrupos);
    }

    $creadorExamen->setTotalPreguntas($_REQUEST["numpreguntas"]);
    $creadorExamen->setTitulo($_REQUEST["titulo"]);
    
    if (!empty($_REQUEST["tiempo"])) {
        $creadorExamen->setTiempo($_REQUEST["tiempo"]);
    } else {
        $creadorExamen->setTiempo(0);
    }

    $_SESSION["usuario"]->setDemoUsado(1);
    Usuario_BD::usarDemo($_SESSION["usuario"]->getIdUsuario());
    
    $creadorExamen->repartePesos();
    
    $creadorExamen->sorteaPreguntas();
    
    if (!empty($_REQUEST["fecha"])) {
        $creadorExamen->setFecha(Fecha::porDatePicker($_REQUEST["fecha"]));
    }

    if (!empty($_REQUEST["fecha_fin"])) {
        $creadorExamen->setFechaFin(Fecha::porDatePicker($_REQUEST["fecha_fin"]));
    }

    if (CreadorExamenes_BD::crear($creadorExamen)) {
        if (count($creadorExamen->getListaPreguntas()) == $creadorExamen->getTotalPreguntas()) {
            $_SESSION["aviso"] = "El examen se ha creado con éxito";
        } else {
            $_SESSION["aviso"] = "El examen se ha creado con éxito.<br/>Debido a las preguntas repetidas entre las unidades" .
                    " temáticas seleccionadas, su examen solo dispone de " . count($creadorExamen->getListaPreguntas()) . " preguntas";
        }
    } else {
        $_SESSION["aviso"] = "No ha sido posible crear el examen";
        escribeLog("No ha sido posible crear el examen", true);
    }
    
}

function crearExamenFalladas() {
    if (!isset($_SESSION["usuario"])) {
        die();
    }

    $creadorExamen = new CreadorExamenes();

    if (!empty($_REQUEST["cursos"])) {
        $creadorExamen = CreadorExamenes_BD::preguntasCursoFalladas($_SESSION["usuario"], $_REQUEST["cursos"]);
    } else if (!empty($_REQUEST["temas"])) {
        $creadorExamen = CreadorExamenes_BD::preguntasTemasFalladas($_SESSION["usuario"], $_REQUEST["temas"]);
    } else if (!empty($_REQUEST["unidades"])) {
        $creadorExamen = CreadorExamenes_BD::preguntasUnidadesFalladas($_SESSION["usuario"], $_REQUEST["unidades"]);
    }

    $creadorExamen->setTotalPreguntas($_REQUEST["numpreguntas"]);
    $creadorExamen->setTitulo($_REQUEST["titulo"]);
    $creadorExamen->setTiempo($_REQUEST["tiempo"]);

    $creadorExamen->sorteaPreguntasSimple();

    if (CreadorExamenes_BD::crear($creadorExamen)) {
        if (count($creadorExamen->getListaPreguntas()) == $creadorExamen->getTotalPreguntas()) {
            $_SESSION["aviso"] = "El examen se ha creado con éxito";
        } else {
            $_SESSION["aviso"] = "El examen se ha creado con éxito.<br/>Debido a las preguntas repetidas entre las unidades" .
                    " temáticas seleccionadas, su examen solo dispone de " . count($creadorExamen->getListaPreguntas()) . " preguntas";
        }
    } else {
        $_SESSION["aviso"] = "No ha sido posible crear el examen";
        escribeLog("No ha sido posible crear el examen", true);
    }
}

function crearExamenPersonalizado() {
    if (!esAdmin()) {
        die();
    }

    $creadorExamen = new CreadorExamenes();

    $creadorExamen->setTitulo($_REQUEST["titulo"]);
    $creadorExamen->setTiempo($_REQUEST["tiempo"]);
    $arrPreguntas = explode(",", $_REQUEST["listaPreguntas"]);
    $creadorExamen->setListaPreguntas($arrPreguntas);
    
    if (!empty($_REQUEST["fecha"])) {
        $creadorExamen->setFecha(Fecha::porDatePicker($_REQUEST["fecha"]));
    }

    if (!empty($_REQUEST["fecha_fin"])) {
        $creadorExamen->setFechaFin(Fecha::porDatePicker($_REQUEST["fecha_fin"]));
    }
    
    if (!empty($_REQUEST["id_grupo"])) {
        $listaGrupos = explode(",", $_REQUEST["id_grupo"]);
        $creadorExamen->setListaGrupos($listaGrupos);
    }

    CreadorExamenes_BD::crear($creadorExamen);
    $_SESSION["aviso"] = "El examen se ha creado con éxito";
}

function rendirExamen() {
    if (!isset($_SESSION["usuario"])) {
        die();
    }

    global $irA;

    $examen = new Examen($_REQUEST["id_examen"], null, null, null, null);
    if (is_numeric($_REQUEST["tiempo"])) {
        $examen->setTiempoRestante($_REQUEST["tiempo"]);
    } else {
        $examen->setTiempoRestante(0);
    }


    $examen->setIdResultado($_REQUEST["id_resultado"]);
    $examen->setFinalizado($_REQUEST["finalizado"]);

    $listaRespuestas = array();
    $listaPosibles= array();
    foreach ($_REQUEST as $clave => $valor) {
        if (preg_match('/^opt(\d)+$/', $clave)) {
            if ($valor != 0) {
                array_push($listaRespuestas, $valor);
            }
        }
        
        if (preg_match('/^pos_(\d)+$/', $clave)) {
            array_push($listaPosibles, str_replace("pos_", "", $clave));
        }
    }
    if ($examen->getIdResultado() != 0) {
        if (Examen_BD::modificar($examen)) {
            Examen_BD::borrarRespuestas($examen->getIdResultado());
        }
    } else {
        Examen_BD::alta($_SESSION["usuario"]->getIdUsuario(), $examen);
        $examen->setIdResultado(Examen_BD::ultimoUsuario($_SESSION["usuario"]->getIdUsuario()));
    }

    Examen_BD::altaRespuestas($examen->getIdResultado(), $listaRespuestas);
    Examen_BD::altaPosibles($examen->getIdResultado(), $listaPosibles);
    if ($examen->getFinalizado()) {
        $irA = "../correccionexamen.php";
        $_SESSION["id_resultado"] = $examen->getIdResultado();
    } else {
        $irA = "../examenesrealizados.php";
    }
}

function altaCategoria() {
    if (!esAdmin()) {
        die();
    }

    $categoria = new Categoria($_REQUEST["elid"], $_REQUEST["nombre"], $_REQUEST["codigo"], 1, 0);
    if (isset($_REQUEST["precio"])) {
        $categoria->setPrecio($_REQUEST["precio"]);
    }
    $categoria->setNivel($_REQUEST["nivel"]);
    if ($categoria->getNivel() == 1) {
        if (isset($_REQUEST["cuarto"])) {
            $categoria->setCuarto(1);
        } else {
            $categoria->setCuarto(0);
        }
    }

    Categoria_BD::alta($categoria);
}

function estadoCategoria() {
    if (!esAdmin()) {
        die();
    }

    $cat = new Categoria(null, null, null, null, null, null);
    if (isset($_GET["id_curso"])) {
        $cat = Categoria_BD::porId($_GET["id_curso"], 1);
    }
    if (isset($_GET["id_tema"])) {
        $cat = Categoria_BD::porId($_GET["id_tema"], 2);
    }
    if (isset($_GET["id_unidad"])) {
        $cat = Categoria_BD::porId($_GET["id_unidad"], 3);
    }
    if (isset($_GET["id_anyo"])) {
        $cat = Categoria_BD::porId($_GET["id_anyo"], 4);
    }

    Categoria_BD::modificarEstado($cat);
}

function borrarCategoria() {
    if (!esAdmin()) {
        die();
    }

    $cat = new Categoria(null, null, null, null, null, null);
    if (isset($_GET["id_curso"])) {
        $cat = Categoria_BD::porId($_GET["id_curso"], 1);
    }
    if (isset($_GET["id_tema"])) {
        $cat = Categoria_BD::porId($_GET["id_tema"], 2);
    }
    if (isset($_GET["id_unidad"])) {
        $cat = Categoria_BD::porId($_GET["id_unidad"], 3);
    }
    if (isset($_GET["id_anyo"])) {
        $cat = Categoria_BD::porId($_GET["id_anyo"], 4);
    }

    Categoria_BD::borrar($cat);
}

function activaTema() {
    if (!esAdmin()) {
        die();
    }

    echo Usuario_BD::cambiaTema($_GET["id_usuario"], $_GET["id_tema"], $_GET["activado"]);
    die();
}

function activaAnyo() {
    if (!esAdmin()) {
        die();
    }

    echo Usuario_BD::cambiaAnyo($_GET["id_usuario"], $_GET["id_anyo"], $_GET["activado"]);
    die();
}

function enviarTutoria() {
    if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]->getNivel() < 3) {
        die();
    }

    $usuario = $_SESSION["usuario"];

    $correo = new Correo();
    $correo->setPlantilla("plantilla_dudas.html");
    $correo->setAsunto("Consulta del alumno " . $usuario->getApellido() . " " . $usuario->getNombre());
    $correo->agregarDestinatarios("tutoria@inspiracle.es");
    $correo->agregarDestinatarios("doc_nachondo@hotmail.com");

    $correo->agregarRemplazo("email", $usuario->getEmail());
    $correo->agregarRemplazo("nombre", $usuario->getNombre());
    $correo->agregarRemplazo("apellido", $usuario->getApellido());
    $correo->agregarRemplazo("telefono", $usuario->getTelefono());

    $correo->agregarRemplazo("tema", $_REQUEST["asignatura"]);
    $correo->agregarRemplazo("dudaContenidos", $_REQUEST["dudacontenidos"]);
    $correo->agregarRemplazo("dudaExamen", $_REQUEST["dudaexamenes"]);
    $correo->agregarRemplazo("dudaAlumno", $_REQUEST["dudas"]);

    $correo->enviar(true);
    
    $_SESSION["aviso"] = "Hemos recibido tu pregunta<br/>Contestaremos lo antes posible.";
}

function marcar_todos_admin() {
    if (!esAdmin()) {
        die();
    }

    switch ($_REQUEST["nivel"]) {
        case 1:
            $modificados = Curso_BD::marcar_entidades($_REQUEST["id_curso"], $_REQUEST["activo"]);
            $_SESSION["aviso"] = "Se modifica el estado de $modificados entidades";
            break;

        case 2:
            $modificados = Tema_BD::marcar_entidades($_REQUEST["id_tema"], $_REQUEST["activo"]);
            $_SESSION["aviso"] = "Se modifica el estado de $modificados entidades";
            break;

        case 3:
            $modificados = Unidad_BD::marcar_entidades($_REQUEST["id_unidad"], $_REQUEST["activo"]);
            $_SESSION["aviso"] = "Se modifica el estado de $modificados entidades";
            break;
    }
}

function editar_examen() {
    if (!esAdmin()) {
        die();
    }
    $examen = new Examen($_REQUEST["id_examen"], $_REQUEST["titulo"], null, null, $_REQUEST["tiempo"]);
    $listaAlumnos = explode(",", $_REQUEST["listaAlumnos"]);
    
    if (!empty($_REQUEST["id_grupo"])) {
        $listaGrupos = explode(",", $_REQUEST["id_grupo"]);
        $examen->setListaGrupos($listaGrupos);
    }

    $arrFecha = explode("/", $_REQUEST["fecha"]);

    $examen->setFecha(new Fecha($arrFecha[2] . "-" . $arrFecha[1] . "-" . $arrFecha[0] . " 00-00-00"));

    if (!empty($_REQUEST["fecha_fin"])) {
        $arrFechaFin = explode("/", $_REQUEST["fecha_fin"]);
        $examen->setFechaFin(new Fecha($arrFechaFin[2] . "-" . $arrFechaFin[1] . "-" . $arrFechaFin[0] . " 00-00-00"));
    }

    Examen_BD::eliminarPreguntas($examen->getIdExamen());

    $listaPreg = explode(",", $_REQUEST["listaPreguntas"]);
    foreach ($listaPreg as $preg){
        Pregunta_BD::asociar($examen->getIdExamen(), $preg);
    }

    if(Examen_BD::actualizar($examen, $listaAlumnos)){
        $_SESSION["aviso"] = "El examen ".$_REQUEST["titulo"]." ha sido modificado";
    }else{
        $_SESSION["aviso"] = "No ha sido posible modificar el examen ".$_REQUEST["titulo"];
    }
}

function alta_calendario() {
    $color = null;
    if (isset($_REQUEST["color"])) {
        $color = $_REQUEST["color"];
    }
    $alerta = 0;
    if (isset($_REQUEST["alerta"])) {
        $alerta = 1;
    }
    $agenda = new Agenda(null, null, $_REQUEST["titulo"], $_REQUEST["texto"], $alerta, null, $color);

    $fechaPrimerArray = explode("/", $_REQUEST["fecha_inicio"]);
    $fechaUltimoArray = explode("/", $_REQUEST["fecha_fin"]);

    $agenda->setPrimerDia(mktime($_REQUEST["hora"], 0, 0, $fechaPrimerArray[1], $fechaPrimerArray[0], $fechaPrimerArray[2]));

    $agenda->setUltimoDia(mktime($_REQUEST["hora"], 0, 0, $fechaUltimoArray[1], $fechaUltimoArray[0], $fechaUltimoArray[2]));

    if (!empty($_REQUEST["lista_usuarios"])) {
        $arrayUsuarios = explode(",", $_REQUEST["lista_usuarios"]);
    } else {
        $arrayUsuarios = array($_SESSION["usuario"]->getIdUsuario());
    }

    if (!empty($_REQUEST["id_curso"])) {
        $agenda->setIdCurso($_REQUEST["id_curso"]);
    }

    if (!empty($_REQUEST["id_grupo"])) {
        $agenda->setIdGrupo($_REQUEST["id_grupo"]);
    }

    if (!Calendario_BD::altaCalendario($agenda, $arrayUsuarios)) {
        $_SESSION["aviso"] = "Alta de agenda realizada correctamente";
    } else {
        $_SESSION["aviso"] = "Ha ocurrido un error al realizar el alta";
    }
}

function borrar_calendario() {
    if (Calendario_BD::borrarCalendario($_REQUEST["id_calendario"])) {
        $_SESSION["aviso"] = "Entrada borrada correctamente";
    } else {
        $_SESSION["aviso"] = "Ocurrió un error al borrar la entrada";
    }
}

function nuevo_grupo() {
    if (!esAdmin()) {
        die();
    }

    $grupo = new Grupo(null, $_REQUEST["nombre"], explode(",", $_REQUEST["lista_usuarios"]));

    if (Grupo_BD::alta($grupo)) {
        $_SESSION["aviso"] = "El grupo se creó con éxito";
    } else {
        $_SESSION["aviso"] = "No ha sido posible crear el grupo";
    }
}

function editar_grupo() {
    if (!esAdmin()) {
        die();
    }

    $grupo = new Grupo($_REQUEST["id_grupo"], $_REQUEST["nombre"], explode(",", $_REQUEST["lista_usuarios"]));

    if (Grupo_BD::modificar($grupo)) {
        $_SESSION["aviso"] = "El grupo se modificó con éxito";
    } else {
        $_SESSION["aviso"] = "No ha sido posible modificar el grupo";
    }
}

function modificar_calendario() {
    $agenda = Calendario_BD::porId($_REQUEST["id_calendario"]);

    if (isset($_REQUEST["lista_usuarios"])) {
        if (!empty($_REQUEST["lista_usuarios"])) {
            $agenda->setUsuario(explode(",", $_REQUEST["lista_usuarios"]));
        } else {
            $agenda->setUsuario(array());
        }

        if (!empty($_REQUEST["id_curso"])) {
            $agenda->setIdCurso($_REQUEST["id_curso"]);
        } else {
            $agenda->setIdCurso(null);
        }

        if (!empty($_REQUEST["id_grupo"])) {
            $agenda->setIdGrupo($_REQUEST["id_grupo"]);
        } else {
            $agenda->setIdGrupo(null);
        }

        $agenda->setColor($_REQUEST["color"]);
    }

    $fechaPrimerArray = explode("/", $_REQUEST["fecha_inicio"]);
    $fechaUltimoArray = explode("/", $_REQUEST["fecha_fin"]);

    $agenda->setPrimerDia(mktime($_REQUEST["hora"], 0, 0, $fechaPrimerArray[1], $fechaPrimerArray[0], $fechaPrimerArray[2]));

    $agenda->setUltimoDia(mktime($_REQUEST["hora"], 0, 0, $fechaUltimoArray[1], $fechaUltimoArray[0], $fechaUltimoArray[2]));

    if (isset($_REQUEST["alerta"])) {
        $agenda->setAlerta(1);
    } else {
        $agenda->setAlerta(0);
    }

    $agenda->setTexto($_REQUEST["texto"]);
    $agenda->setTitulo($_REQUEST["titulo"]);

    if (Calendario_BD::modificar($agenda)) {
        $_SESSION["aviso"] = "Modificación realizada";
    } else {
        $_SESSION["aviso"] = "No ha sido posible modificar";
    }
}

function enviar_alertas() {

    $listaAlertas = Calendario_BD::listarAlertas();
    $alerta = new Agenda(null, null, null, null, null, null, null);

    foreach ($listaAlertas as $alerta) {
        $correo = new Correo();
        $correo->setPlantilla("plantilla_recordatorio.html");

        $correo->agregarDestinatarios($alerta->getEmailUsuario());
        $correo->setAsunto("Recordatorio: " . $alerta->getTitulo());

        $correo->agregarRemplazo("fecha", $alerta->getFecha()->conFormatoLargoConHora());
        $correo->agregarRemplazo("ocurre", $alerta->getHoras());
        $correo->agregarRemplazo("titulo", $alerta->getTitulo());
        $correo->agregarRemplazo("texto", $alerta->getTexto());
        $correo->agregarRemplazo("nombre", $alerta->getNombreUsuario() . " " . $alerta->getApellidoUsuario());
        $correo->enviar();
    }
}

function borrarResultado() {
    if (!esAdmin()) {
        die();
    }

    if (Examen_BD::borrarResultado($_REQUEST["id_resultado"])) {
        $_SESSION["aviso"] = "El resultado se ha eliminado";
    } else {
        $_SESSION["aviso"] = "No se pudo eliminar el resultado";
        escribeLog("No se pudo eliminar el resultado", true);
    }
}

function cambiarMasFalladas() {
    if (!esAdmin()) {
        die();
    }

    if (Usuario_BD::cambiaMasFalladas($_REQUEST["id_usuario"], $_REQUEST["estado"])) {
        $_SESSION["aviso"] = "Cambio realizado";
    } else {
        $_SESSION["aviso"] = "No se pudo hacer el cambio";
    }
}

function altaPeriodo() {
    if (!esAdmin()) {
        die();
    }

    $fecha = Fecha::porDatePicker($_REQUEST["fecha"]);

    $periodo = new Periodo(null, $_REQUEST["titulo"], $fecha, $_REQUEST["id_usuario"]);

    if (Periodo_BD::alta($periodo) > 0) {
        $_SESSION["aviso"] = "El período se dió de alta con éxito";
    } else {
        $_SESSION["aviso"] = "No se ha podido dar de alta el período";
    }
}

function borrarPeriodo() {
    if (!esAdmin()) {
        die();
    }

    if (Periodo_BD::borrar($_REQUEST["id_periodo"])) {
        $_SESSION["aviso"] = "El período se borró con éxito";
    } else {
        $_SESSION["aviso"] = "No se ha podido borrar el período";
    }
}

function borrar_grupo(){
    if (!esAdmin()) {
        die();
    }

    $_SESSION["aviso"] = Grupo_BD::borrar($_REQUEST["id_grupo"]);
    die();
}

function crear_listado_usuarios(){
    if (!esAdmin()) {
        die();
    }
    
    require_once '../clases/HojaExcel.php';
    
    $listaUsuarios = Usuario_BD::listar($_GET["filtroUsuariosAdmin"], $_GET["filtroCursosAdmin"]);
    
    $usuario = new Usuario(null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    
    $hoja = new HojaExel();
    
    $hoja->escribirArray(1, 2, array("NOMBRE", "APELLIDO", "EMAIL", "PROVINCIA", "CIUDAD", "CURSO", "NIVEL", "FIJO", "MOVIL"));
    
    $actual = 0;
    foreach ($listaUsuarios as $usuario){
        $hoja->escribirArray(1, 4+$actual, $usuario->enArray());
        $actual++;
    }
    
    $hoja->devolverArchivo();
}

function escribirCorreo(){
    if (!esAdmin()) {
        die();
    }
    
    if(isset($_REQUEST["id_usuario"])){
        $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
        
        $correo = new Correo();
        $correo->setPlantilla("plantilla_correo_basico.html");
        $correo->setAsunto($_REQUEST["asunto"]);
        $correo->agregarDestinatarios($usuario->getEmail(), $usuario->getNombre());
        $correo->agregarRemplazo("texto", $_REQUEST["texto"]);
        $correo->enviar();
    }
    
    if(isset($_REQUEST["id_grupo"])){
        $listaUsuario = Usuario_BD::porIdGrupo($_REQUEST["id_grupo"]);
        
        $correo = new Correo();
        $correo->setPlantilla("plantilla_correo_basico.html");
        $correo->setAsunto($_REQUEST["asunto"]);
        $correo->agregarRemplazo("texto", $_REQUEST["texto"]);
        
        foreach($listaUsuario as $usuario){
            $correo->agregarDestinatarios($usuario->getEmail(), $usuario->getNombre());
        }
        
        $correo->enviar();
    }
    
    
    $_SESSION["aviso"] = "Correo enviado";
}