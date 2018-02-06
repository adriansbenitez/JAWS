<? include 'trozos/session.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
            #conttarifas ul > li > div > span{
                float: none;
                position: static;
                font-size: 15px;
                padding: 0;
            }
        </style>
    </head>
    <body>
<? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div id="conttarifas">
                <h1>Tarifas</h1>
                <div class="transition03 first">
                    <ul class="tarifa">
                        <li><h2>Demo</h2></li>
                        <li><span>Generar Exámenes</span>1 Test</li>
                        <li><span>Resultado de los test</span>1 Test</li>
                        <li><span>Test Personalizado</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Estadísticas</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Preguntas Aleatorias</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Preguntas Falladas</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Manual Completo</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Calendario de Estudio</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Tutoria Personalizada</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <?/*
                        <li><span>Manual Inspiracle</span>0,00 <strong>€</strong></li>
                        <li><span>Manual Inspiracle</span>0,00 <strong>€</strong></li>
                        <li><span>Acceso al Curso</span>0,00 <strong>€</strong></li>
                        <li><span>Cuota mensual</span>0,00 <strong>€</strong></li>*/?>
                        <li><a href="index.php" class="transition03">REGISTRATE</a><div class="clear"></div></li>
                    </ul>
                </div>
                <div class="transition03 second selected">
                    <ul class="tarifa ">
                        <li><h2>Personal Training</h2></li>
                        <li><span>Generar Exámenes</span>Ilimitado</li>
                        <li><span>Resultado de los test</span>Ilimitado</li>
                        <li><span>Test Personalizado</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Estadísticas</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Preguntas Aleatorias</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Preguntas Falladas</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Manual Completo</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Calendario de Estudio</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <li><span>Tutoria Personalizada</span><img src="css/icos/cross.png" />&nbsp;</li>
                        <?/*<li><span>Manual Inspiracle Curso BIR</span>400,00 <strong>€ (optativo)</strong></li>
                        <li><span>Manual Inspiracle Curso QIR</span>325,00 <strong>€ (optativo)</strong></li>
                        <li><span>Manual Inspiracle Curso FIR</span>400,00 <strong>€ (optativo)</strong></li>
                        <li><span>Acceso al Curso</span>300,00 <strong>€</strong></li>
                        <li><span>Cuota mensual</span>0,00 <strong>€</strong></li>*/?>
                        <li>
                            <div>Personal Training + tyutoria = 990€ - <span style="color:#009900">25%</span> = 792 €</div>
                            <div>Personal Training = 699€ - <span style="color:#009900">40%</span> = 449 €</div>
                            <div class="clear"></div>
                            <a href="contratar-curso.php?tarifa=1" class="transition03">CONTRATAR</a>
                            <div class="clear"></div>
                        </li>
                    </ul>
                </div>
                <div class="transition03 last">
                    <ul class="tarifa">
                        <li><h2>Training Premium</h2></li>
                        <li><span>Generar Exámenes</span>Ilimitado</li>
                        <li><span>Resultado de los test</span>Ilimitado</li>
                        <li><span>Test Personalizado</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Estadísticas</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Preguntas Aleatorias</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Preguntas Falladas</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Manual Completo</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Calendario de Estudio</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <li><span>Tutoria Personalizada</span><img src="css/icos/tick.png" />&nbsp;</li>
                        <?/*
                        <li><span>Manual Inspiracle Curso BIR</span>400,00 <strong>€ (obligatorio)</strong></li>
                        <li><span>Manual Inspiracle Curso QIR</span>325,00 <strong>€ (obligatorio)</strong></li>
                        <li><span>Manual Inspiracle Curso FIR</span>400,00 <strong>€ (obligatorio)</strong></li>
                        <li><span>Acceso al Curso</span>300,00 <strong>€</strong></li>
                        <li><span>Cuota mensual</span>0,00 <strong>€</strong></li>
                         * 
                         */?>
                        <li>
                            <div>Curso PREMIUM: 8 meses<br/> 1395€ - <span style="color:#009900">40%</span> = 996 €</div>
                            <div class="clear"></div>
                            <a href="contratar-curso.php?tarifa=2" class="transition03">CONTRATAR</a>
                            <div class="clear"></div>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
                <p>
                    <div class="aviso">El pago se realizará a través de transferencia bancaria. Los permisos se activarán en cuanto se confirme
                        el pago inicial por el acceso a la plataforma. En el caso de la demora de los pagos de las restantes cuotas, se cerrará
                        el acceso del usuario a la plataforma pudiendo perder los datos guardados.</div>
                </p>
                <p>
                    <div class="aviso"><h2 style="text-align: center;">Uso de la plataforma por 300€ hasta el 1 de Febrero de 2017</h2></div>
                </p>

                <div class="twocolumns">
                    <div>
                        <h2>Inspiracle  Personal Training</h2>
                        <p>¿Me interesa  Inspiracle Personal Training?</p>
                        <div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-pause-on-hover="true" data-cycle-speed="200">
                            <p><span>1</span>Si te interesa disponer de un completo sistema de entrenamiento para la preparación de los exámenes de acceso para  Interno Residentes, BIR y  QIR.</p>
                            <p><span>2</span>Si quieres que ese sistema sea flexible y te permita usarlo en cualquier momento y lugar.</p>
                            <p><span>3</span>Si quiere poder configurar los test a la medida de tus necesidades: por temas, materias, o exámenes de los últimos años, y así poder entrenar aquellas partes que consideres que necesitas mejorar. </p>
                            <p><span>4</span>Si necesitas poder hacer test de un tema, de una asignatura o del conjunto de varias asignaturas, el sistema tiene en cuenta el peso (%) histórico de dichas asignaturas en el examen oficial.</p>
                            <p><span>5</span>Si quieres poder ser tú quien decida qué tipo de test hacer en cada momento y el número de preguntas en cada test.</p>
                            <p><span>6</span>Si quieres que el sistema te permita hacer aleatorias las preguntas presentes en exámenes oficiales.</p>
                            <p><span>7</span>Si  quieres que aquellas preguntas que no has acertado sean recordadas por el sistema y se haga hincapié en ellas en próximos test.</p>
                            <p><span>8</span>Si quieres hacer test con preguntas semejantes a aquellos test que han resultado más complejos, generando test similares.</p>
                            <p><span>9</span>Si quieres todo esto y más, no dudes en inscribirte en nuestra web y <a href=""><strong>acceder a una demo gratuita</strong></a>.</p>
                            <p>Comienza ya a  preparar tu futuro con Inspiracle Personal Training</p>
                        </div>
                    </div>

                    <div>
                        <h2>Inspiracle Training Premium</h2>
                        <p>¿Por qué decidirte por Inspiracle Training Premium?</p>
                        <div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-pause-on-hover="true" data-cycle-speed="200">
                            <p><span>1</span>Porque mantiene  todas las ventajas de Inspiracle Personal Training.</p>
                            <p><span>2</span>Te ofrece, además, la asistencia de profesores especialistas en cada una de las materias  dándote acceso a una tutoría especializada. Son los autores de los Manuales Inspiracle para Internos Residentes, que se venden en la <a href="tienda Inspiracle.es" target="_blank"><strong>tienda virtual</strong></a>.</p>
                            <p><span>3</span>Porque los tutores supervisarán los test y te propondrán test a medida para superar las dificultades que detecten, haciendo especial referencia a las preguntas no correctamente contestadas.</p>
                            <p><span>4</span>Nuestros tutores especializados te harán una propuesta de calendario de estudio con fechas para realizar las evaluaciones, lo que te permitirá llegar en las mejores condiciones al examen oficial.</p>
                            <p><span>5</span>Podrás comparar tus resultados con la media de los resultados de los usuarios que están utilizando Inspiracle Trainig.</p>
                            <p><span>6</span>Tienes a tu alcance  una tutoría que te ayuda a preparar el examen  con las mayores garantías de éxito, no lo dudes, matricúlate como alumno de <a href=""><strong>Inspiracle Training Premium</strong></a>.</p>
                            <p><span>7</span>Comienza a preparar tu futuro</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
<? include 'trozos/pie.php'; ?>
    </body>
</html>