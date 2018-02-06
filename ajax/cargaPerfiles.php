<? include '../trozos/session.php';

 require_once '../clases/Usuario.php';

if(isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1){
    ?><ul class="listadmin"><?
    $listaUsuarios = Usuario_BD::listar($_GET["filtroUsuariosAdmin"], $_GET["filtroCursosAdmin"]);
    $usuario = new Usuario(null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null);
    foreach ($listaUsuarios as $usuario){
        $clase = "";
        switch ($usuario->getNivel()){
            case "3":
                $clase=" basico ";
            break;
            case "4":
                $clase=" tutelado ";
        }
        if($usuario->getBloqueado() == 1){
            $clase .= " bloqueado ";
        }?>
            <li class="<?=$clase?>">
                <?=$usuario->getApellido()?>, <?=$usuario->getNombre()?>
                - <?=$usuario->getNombreCurso()?>
                <br/>
                <?=$usuario->getEmail()?>
                <a href="perfilstats.php?id_usuario=<?=$usuario->getIdUsuario(); ?>">
                    <img src="css/icos/admindetalles.png" /></a>
                        <a href="funciones/controlador.php?accion=cambiarNivel&nivel=<?
                            switch ($usuario->getNivel()){
                                case "2":
                                    echo 3;
                                    break;
                                case "3":
                                    echo 4;
                                    break;
                                case "4":
                                    echo 2;
                            }?>&id_usuario=<?=$usuario->getIdUsuario()?>">
                    <img src="css/icos/tutelado.png" /></a>
                <? if($usuario->getNivel() > 2){
                    if($usuario->getMasFalladas()){ ?>
                        <a href="funciones/controlador.php?accion=cambiarMasFalladas&estado=0&id_usuario=<?= $usuario->getIdUsuario()?>"><img src="css/icos/adminexamenes_s.png"/></a>
                    <? }else{ ?>
                        <a href="funciones/controlador.php?accion=cambiarMasFalladas&estado=1&id_usuario=<?= $usuario->getIdUsuario()?>"><img src="css/icos/adminexamenes_n.png"/></a>
                    <? } 
                } ?>
                        <a href="escribirCorreo.php?id_usuario=<?=$usuario->getIdUsuario()?>"><img src="css/icos/inputmail.png"/></a>
                <? if($usuario->getBloqueado() == 0){ ?>
                    <a href="funciones/controlador.php?accion=bloquearUsuario&id_usuario=<?=$usuario->getIdUsuario(); ?>">
                        <img src="css/icos/adminblock.png" title="bloquear"/>
                    </a>
                <? }else{ ?>
                    <a href="funciones/controlador.php?accion=borrarUsuario&id_usuario=<?=$usuario->getIdUsuario();?>">
                        <img src="css/icos/admindelete.png" />
                    </a>
                    <a href="funciones/controlador.php?accion=desbloquearUsuario&id_usuario=<?=$usuario->getIdUsuario(); ?>">
                        <img src="css/icos/adminalert.png" title="desbloquear"/>
                    </a>
                <? } ?>
            </li>
    <? } ?>
    </ul>
<? }