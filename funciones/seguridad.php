<? function seguridad($nivel){
    if(!isset($_SESSION["usuario"])){
        if(isset($_COOKIE["iden_user"])){
            $usuario = Usuario_BD::porId($_COOKIE["iden_user"]);
        }
        if(isset($_COOKIE["iden_user"])&& isset($_COOKIE["c_iden_user"]) && isset($usuario) && md5("azul".$_COOKIE["iden_user"].$usuario->getClave()) == $_COOKIE["c_iden_user"]){
            escribeLog("el usuario tiene la cookie creada y debe seguir conectado");
            
            $_SESSION["usuario"] = $usuario;
        }else{
            escribeLog("el usuario no tiene session abierta y es enviado a index");
            header("Location: index.php");
        }
        
    }
    
    if(isset($_SESSION["usuario"])){
        setcookie("iden_user", $_SESSION["usuario"]->getIdUsuario(), time()+(36000), "/");
        setcookie("c_iden_user", md5("azul".$_SESSION["usuario"]->getIdUsuario().$_SESSION["usuario"]->getClave()), time()+(36000), "/");
        switch ($nivel){
            case "admin":
                if($_SESSION["usuario"]->getNivel() != 1){
                    escribeLog("el usuario no tiene permisos de administrador");
                    header("Location: index.php");
                }
            break;
            
            case "nodemo":
                if($_SESSION["usuario"]->getNivel() == 2){
                    escribeLog("el usuario no tiene permisos de administrador");
                    header("Location: index.php");
                }
            break;
            
            case "usuario_falladas":
                if($_SESSION["usuario"]->getMasFalladas() == 0){
                    escribeLog("el usuario no tiene permisos para en las preguntas más falladas");
                    header("Location: listexamenes.php");
                }
            break;
        }
    }
}