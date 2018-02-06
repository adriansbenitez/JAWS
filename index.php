<? include 'trozos/session.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/registro.js?ver=1.1"></script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div id="carouselcont">
                <div id="carousel">
                    <div class="oneByOne_item" id="slider1">
                        <img src="images/slider/slide1/bg.jpg" class="carouselbg"  />
                        <img src="images/slider/slide1/ipad.png" id="ipad"  />
                        <img src="images/slider/slide1/mac.png" id="mac"/>
                        <div class="titleslide">Desde tu Smartphone – ordenador – tablet ,<br />
                            365 días al año 24 horas día, en cualquier lugar.</div>
                    </div>
                    <div class="oneByOne_item" id="slider2">
                        <img src="images/slider/slide2/bg.jpg" class="carouselbg"  />
                        <img src="images/slider/slide2/brain.png" id="brain" />
                        <img src="images/slider/slide2/inspiracle.png" id="inspiracle" />
                        <div class="titleslide">Ya puedes acceder al mejor sistema de entrenamiento de  exámenes BIR – QIR.</div>
                    </div>
                    <div class="oneByOne_item" id="slider3">
                        <img src="images/slider/slide3/bg.jpg" class="carouselbg"  />
                        <img src="images/slider/slide3/home.png" id="home" />
                        <img src="images/slider/slide3/clock.png" id="clock" />
                        <img src="images/slider/slide3/stats.png" id="stats" />
                        <div class="titleslide">Regístrate gratuitamente y pruébalo, te encantará.</div>
                    </div>
                </div>
                <div id="frmregistrar">
                    <h3>Regístrate <span>GRATIS</span></h3>
                    <input class="requerido" type="text" name="nombre" placeholder="Nombre"/>
                    <input class="requerido" type="text" name="apellidos" placeholder="Apellidos"/>
                    <input class="requerido" type="text" name="email" placeholder="Email"/>
                    <input class="requerido" type="password" name="clave" placeholder="Clave"/>
                    <select class="requerido" name="interes">
                        <option value="0">¿Qué curso te interesa?</option>
                        <option value="1">Curso BIR</option>
                        <option value="2">Curso QIR</option>
                        <?/*<option value="7">Curso FIR</option>*/?>
                    </select>
                    <input type="button" value="REGISTRARSE" class="transition03"/>
                    <a href="#ventana" class="emergente" id="llamarRegistro"></a>
                </div>
            </div>
            <div id="ventana" class="white-popup-block mfp-hide">
                <button class="mfp-close cerrarVentana" type="button">×</button>
                <form method="post" action="funciones/controlador.php" id="confirmaRegistro">
                    <input type="hidden" name="accion" value="registrar"/>

                    <input type="hidden" name="nombre"/>
                    <input type="hidden" name="apellidos"/>
                    <input type="hidden" name="email"/>
                    <input type="hidden" name="clave"/>
                    <input type="hidden" name="interes"/>

                    <div style="display: table; margin: 0 auto;">
                    <? require 'recaptcha-php-1.11/recaptchalib.php';
                    $publickey = "6LcIe_USAAAAALa4StYjP4is4Gdb7r2yCibbfNwb"; // you got this from the signup page
                    echo recaptcha_get_html($publickey); ?>
                    </div>
                </form>
                <div style="display: table; margin: 20px auto;">
                    <input type="submit" onclick="registrar()" id="botonregistrar" value="CONFIRMAR"/>
                </div>
            </div>


            <div id="columsslogans">
                <div class="transition03 scale1_1">
                    <h2>En cualquier<br /> lugar</h2>
                    <img src="images/landingslogan/3columnshome.png" onclick="javascript:cerrarsession()" />
                    <p>En casa, en el metro, en el tren, en el bus, en  cualquier lugar.<br />
                        Si dispones de conexión a Internet puedes prepararte para superar el examen de Interno Residente.</p>
                </div>
                <div class="transition03 scale1_1">
                    <h2>24 horas<br /> 365 días</h2>
                    <img src="images/landingslogan/3columnsclock.png" />
                    <p>Test BIR-QIR configurables.<br /> El examen en tu ordenador, Smartphone, tablet.<br />
                        Todas las preguntas de los 12 últimos años.<br /> Miles de preguntas añadidas por Inspiracle.</p>
                </div>
                <div class="transition03 scale1_1">
                    <h2>Seguimiento de resultados</h2>
                    <img src="images/landingslogan/3columnsstats.png" />
                    <p>Nº de contestaciones correctas.<br /> Contabiliza y recuerda las contestaciones incorrectas.<br />
                        Compara resultados con la media de los obtenidos por los usuarios del sistema.</p>
                </div>
            </div>

            <div class="spacer"></div>

            <div id="columnsopinions">
                <div id="opinions">
                    <div class="contopinion">
                        <img src="images/landingopinions/client.png" class="transition1" />
                        <div class="opinion transition03">
                            <h2>Hola, soy Ana Vidal,<br>Residente QIR Radiofarmacia (3º Año)</h2>
                            <blockquote>
                                <span></span>
                                Como antigua alumna quisiera felicitaros por vuestra trayectoria, siempre estaré agradecida por la gran labor académica, pero sobre todo el trato personal que me habéis brindado. Ya que la prueba selectiva para Internos Residentes es muy dura, tanto física como mentalmente, gracias a vuestra labor he podido superar con éxito esa dura prueba. Muchas gracias!
                            </blockquote>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="contopinion">
                        <img src="images/landingopinions/director.png" class="transition1" />
                        <div class="opinion transition03">
                            <h2>Hola, soy Iliana Perdomo, <br />Directora de Inspiracle.</h2>
                            <blockquote><span></span>La  importancia de un entrenamiento metodológicamente optimizado para superar con éxito la prueba selectiva para Internos Residentes es un hecho que muchos ya habéis constatado. Hemos volcado toda nuestra experiencia docente en  Inspiracle Trainig.  <a href="">Probad la demo gratuitamente.</a> </blockquote>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>