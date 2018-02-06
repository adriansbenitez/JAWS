<? include 'trozos/session.php';
include 'trozos/menuPerfil.php';
seguridad("usuario");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
    </head>
    <body>
    <? include 'trozos/encabezado.php'; ?>
        <div id="content">

            <div class="contplataforma">
                <h1>Inicio <img src="css/icos/icoperfil.png" /></h1>
                <div class="colun2-3 perfilcolumn">
                    <? $usuario = $_SESSION["usuario"];
                    if(isset($_REQUEST["id_usuario"]) && isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1){
                        $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
                    } ?>
                    <? menuPerfil($usuario); ?>
                    <div>
                        <div class="texto">                
                            <p>Te agradecemos la confianza depositada en Inspiracle Training.</p>
                            <p>La versión  que te ofrecemos en estos momento es la versión 3.3.1 para que puedas explorar las potencialidades y posibilidades de la plataforma. Esperamos que su uso os aporte un entrenamiento que ayude a mejorar los resultados  justo antes de la meta final.</p>
                            <p>Recordad que es una versión beta previa a la definitiva, así que<strong> agradeceremos enormemente cualquier comentario</strong> que podáis hacer que conduzca a una mejora de la versión final.</p>
                            <p>Vuestros comentarios han detectado un problema técnico en la generación de exámenes para todo el Curso BIR, que consiste en la repetición excesiva de preguntas de  asignaturas como Fisiología. El departamento ha trabajado para solucionarlo y ya hemos conseguido mejoras. Al final del proceso las preguntas de las asignaturas y temas saldrán en los test generados  en proporción acorde a su importancia (peso histórico) en los exámenes de Oposiciones de los últimos años.</p>
                            <p>Pero, ¿qué permite Inspiracle Training?</p>
                            <p>Puedes personalizar de test a la medida de tus intereses y necesidades.  Puedes seleccionar preguntas de un tema concreto, de una  materia, o combinar preguntas temas seleccionados de diferentes  materias. </p>
                            <p>Por una parte evitas contestar a preguntas de temas que no has preparado, y por otra, te sirve como una medida fiable de tu grado de preparación que tienes en cada momento.</p>
                            <p>Permite realizar los exámenes  oficiales de los últimos años. Con la ventaja de que obtienes aleatoriamente dentro de los test generados las preguntas presentes en los  exámenes oficiales. </p>
                            <p>Inspiracle Training  al generar los test, pondera la importancia (peso) de las materias y/o temas que poseen los mismos en los exámenes oficiales. Permitiendo repasar  en mayor medida aquellos contenidos más preguntados en el histórico de los exámenes oficiales.  </p>
                            <p>El sistema recuerda las preguntas que no han sido acertadas y hace hincapié en ellas en los próximos test. Permitiéndote  comprobar que las dificultades se han superado.</p>
                            <p>Podrás generar test personalizados con preguntas semejantes a aquellos que te han resultado más complejas.</p>
                            <p>Como ves es una  plataforma versátil que permite la preparación personalizada que se adapta a cualquier forma de estudio.</p>
                            <p>Desde ahora mismo puedes iniciar tu entrenamiento con Inspiracle Training.</p>
                            <p>¡Comienza a entrenar tu futuro!</p>
                            <p>Lee o si lo prefieres descarga  el breve tutorial, que os guiará en como poder generar los test  de preparación de exámenes.</p>
                        </div>
                        <h2>Guia de Inspiracle formacion</h2>             
                        <br /><br />
                        <p><a href="images/perfil/GuiaPlataforma.pdf" target="_blank" class="botonclaro">Descargar Guia</a></p>

                        <p class="texto">
                            1 -	Debes de introducir la dirección URL: <a href="http://training.inspiracle.es">http://training.inspiracle.es</a> en tu navegador, o acceder a <a href="www.inspiracle.es">www.inspiracle.es</a> y hacer click en Inspiracle Training.
                        </p>
                        <p class="texto">
                            2 -	En la barra superior de la pantalla que devuelve hay que escribir las claves de usuario y contraseña:
                            <br /><br />
                            <img src="images/perfil/001.jpg" />
                            <br /><br />
                            Previamente debes de estar registrado como usuario en “Registrate” opción a la que tienes acceso, igualmente, desde la pantalla anterior.
                            <br /><br />
                            Al entrar a la plataforma sale un mensaje de bienvenida.
                            <br /><br />
                            <img src="images/perfil/002.jpg" />
                        </p>
                        <p class="texto">
                            3 -	Al cerrar esta ventana de bienvenida entrareis en la pantalla con vuestro Perfil:
                            <br /><br />
                            <img src="images/perfil/003.jpg" />
                            <br /><br />
                            En la parte superior (indicado por la flecha azul) existe la barra MENU: PERFIL/EXÁMENES/BLOQUES.
                        </p>

                        <p class="texto">
                            4 -	Al dar clic en el MENU “EXÁMENES” se nos abre la siguiente ventana (sección de EXÁMENES) donde tendremos el listado de todos los exámenes* que hayamos generado con el sistema.
                            <br />
                            *Nota: en caso de ser  el primer test  aparecerá en blanco.
                            <br /><br />
                            <img src="images/perfil/004.jpg" />
                            <br /><br />
                            En esta ventana (pantalla) podremos generar un nuevo examen.
                            <br /><br />
                            A través de un ejemplo explicaremos como generar un nuevo que nombraremos “BIR 2012”.
                        </p>
                        <p class="texto">
                            5 -	Hacer clic en el botón negro “+ Crear Exámen”. El sistema cambia de pantalla apareciendo la mostrada en la siguiente imagen.
                            <br /><br />
                            <img src="images/perfil/005.jpg" />
                            <br /><br />
                            En esta ventana (pantalla) podremos generar un nuevo examen.
                            <br /><br />
                            A través de un ejemplo explicaremos como generar un nuevo que nombraremos “BIR 2012”.
                        </p>
                        <p class="texto">
                            6-	Rellenar los campos que nos permitirán identificar el examen que vamos a generar.
                            <br /><br />
                            Nombre de examen: “BIR 2012”.
                            <br /><br />
                            Tiempo Máximo: el que necesitemos en función del número de preguntas. 
                            <br /><br />
                            En este caso: 100 minutos.
                            <br /><br />
                            Número de preguntas: 100.
                            <br /><br />
                            Seleccionar dirigido al PUBLICO (podrá verlo cualquier usuario) - PRIVADO (solo tendrá acceso y visualización el propio usuario que lo genera).
                        </p>
                        <p class="texto">
                            7 -	Escoger la o las materias de las cuáles queremos generar el examen.  Puede ser de todo el curso BIR, o de una asignatura o varias asignaturas, o  por bloques dentro de las asignaturas. Se debe marcar la casilla a la derecha. 
                            <br /><br />
                            Para escoger todo el curso BIR, marcamos su casilla, o en su lugar, si queremos escoger una asignatura en particular, desplegamos el curso BIR (dando clic) en el signo (+) a su izquierda. Ver imagen. 
                            <br /><br />
                            En este caso al desplegar, he marcado las asignaturas: Biología Molecular, Bioquímica y Fisiología, que son las que mayor peso tienen en el histórico de exámenes.
                            <br /><br />
                            <img src="images/perfil/007.jpg" />
                            <br /><br />
                        </p>                
                        <p class="texto">
                            8 -	Hago clic en el botón negro “Crear Examen” situado al final de la tabla de asignaturas, extremo inferior derecho de la pantalla. y se me genera el examen, cuyo tiempo de espera, es variable en función del número de asignaturas y número de preguntas.
                            <br /><br />
                            <img src="images/perfil/008a.jpg" />
                            <br /><br />
                            Al terminar de generarlo, se nos abre la siguiente ventana, y al cerrarla, por defecto la plataforma, me mantiene en el mismo MENU “Crear Exámenes”.
                            <br /><br />
                            <img src="images/perfil/008b.jpg" />
                            <br /><br />
                        </p>
                        <p class="texto">
                            9-	Para poder realizar el examen generado, hay que ir a la barra MENU “EXÁMENES” y visualizarlos donde indica la flecha.
                            <br /><br />
                            <img src="images/perfil/009.jpg" />
                            <br /><br />                
                        </p> 
                        <p class="texto">
                            10-	Abre la  pantalla dando clic en el MENU “Exámenes”, donde se indica todo el listado de los exámenes que hemos generado. Al final del listado se puede visualizar el último examen creado “BIR 2012”
                            <br /><br />
                            <img src="images/perfil/010.jpg" />
                            <br /><br />                
                        </p>
                        <p class="texto">
                            11 - Para poder responder a las preguntas del examen generado debemos dar clic encima de la lupa situada a la derecha del examen “BIR 2012”. Automáticamente se listas las preguntas del examen con las opciones de respuesta y el cronómetro a la derecha de la pantalla.
                            <br /><br />
                            <img src="images/perfil/011.jpg" />
                            <br /><br /> 
                            Tienes cinco opciones para contestar la pregunta, y la opción de dejarla en blanco para simular la situación real de contestación de preguntas test en el examen oficial.  
                        </p>
                        <p class="texto">
                            12 - Al dar finalizar examen la plataforma nos dará los resultados usando el sistema de puntuación que se utiliza en la oposición 3 puntos (respuesta correcta); -1 punto (fallo); 0 puntos (respuesta en blanco).
                            *Nota: Mismo baremo de puntuación que se aplica en el Examen de Oposición Internos Residentes.
                            <br /><br />
                            <img src="images/perfil/012.jpg" />
                            <br /><br /> 
                            Debajo de la corrección aparecen las preguntas tal y como las contestamos para que podamos revisar el examen y ver cuáles han sido nuestros  resultados pregunta a pregunta.
                            <br /><br />
                            Una vez terminado el examen definitivamente puedo ir a la BARRA MENU “Exámenes” y puedo generar otros exámenes con contenidos diferentes.
                        </p>
                        <a href="images/perfil/GuiaPlataforma.pdf" target="_blank" class="botonclaro">Descargar Guia</a>
                    </div>

                </div>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
