<? include '../trozos/session.php';
seguridad("admin");

$examen = new Examen(null, null, null, null, null);
$resultados = Examen_BD::listarExamenesRealizadosPorIdExamen($_REQUEST["id_examen"]);
foreach ($resultados as $examen){
    $user = Usuario_BD::porId($examen->getIdUsuario()); ?>
    <div class="alumno">
        <?=$examen->porcentaje()?>% - <?=$user->getApellido()?>, <?=$user->getNombre()?>
        <a href="correccionexamen.php?id_resultado=<?=$examen->getIdResultado()?>&id_usuario=<?=$examen->getIdUsuario()?>">
            <img src="css/icos/admindetalles.png" class="agrenda"/>
        </a>
        <a href="analizadorexamen.php?id_examen=<?=$examen->getIdExamen()?>&id_resultado=<?=$examen->getIdResultado()?>">
            <img src="css/icos/adminstats.png" class="agrenda"/>
        </a>
        <a href="editexamen.php?id_examen=<?=$examen->getIdExamen()?>">
            <img src="css/icos/adminedit.png" class="agrenda"/>
        </a>
    </div>
    <div class="clear"></div>
    <?
}