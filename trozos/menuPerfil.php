<? function menuPerfil($usuario){ ?>
    <div>
        <img id="imgperfil" src="upload/avatar/<? echo $usuario->getAvatar(); ?>" />
        <div id="infousuario">
        <ul>
            <li>&rarr; <? echo $usuario->getNombre(); ?></li>
            <li>&rarr; <? echo $usuario->getApellido();?></li>
            <li>&rarr; <? echo $usuario->getEmail();?></li>
            <li>&rarr; <? echo $usuario->getNombreProvincia(); ?></li>
            <li>&rarr; <? echo $usuario->getUniversidad();?></li>
            <li>&rarr; <? echo $usuario->getNombreCurso(); ?></li>
        </ul>
    </div>
    <? $agregado = "";
        if($_SESSION["usuario"]->getNivel() == 1){
            $agregado = "?id_usuario=".$usuario->getIdUsuario();
    } ?>
    <? if($usuario->getNivel() != 2){ ?>
        <a href="materias.php<? echo $agregado; ?>" class="botonclaro">Mis Cursos</a>
        <a href="calendario.php<? echo $agregado; ?>" class="botonclaro">Calendario</a>
    <? } ?>
    <a href="examenesrealizados.php<? echo $agregado; ?>" class="botonclaro">Exámenes Realizados</a>
    <a href="perfilstats.php<? echo $agregado; ?>" class="botonclaro">Estadísticas</a>
    <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
        <a href="periodos.php<? echo $agregado; ?>" class="botonclaro">Períodos</a>
    <? } ?>
</div>
<? }