//Este script crea un div con id 'cookie-law' (a�adir en css para pesonalizar) y carga google analytic una vez se ha aceptado
//Importante incluirlo despu�s de la carga de jquery.
/**
 * Created by robert on 25/11/13.
 */
// Creare's 'Implied Consent' EU Cookie Law Banner v:2.3
// Conceived by Robert Kent, James Bavington & Tom Foyster

var dropCookie = true;                      // false disables the Cookie, allowing you to style the banner
var cookieDuration = 365;                    // Number of days before the cookie expires, and the banner reappears
var cookieName = 'complianceCookie';        // Name of our cookie
var cookieValue = 'on';  
var _gaq = _gaq || [];                      //analytic 

function createDiv(){
    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');
    div.setAttribute('id','cookie-law');
 	
    div.innerHTML = '<p>Utilizamos cookies propias y de terceros para mejorar su experiencia de navegaci&oacute;n y realizar tareas de an&aacute;lisis.<br> Al continuar con su navegaci&oacute;n entendemos que da su consentimiento a nuestra pol&iacute;tica de cookies.<a class="close-cookie-banner" href="javascript:void(0);" onclick="createCookie(window.cookieName,window.cookieValue, window.cookieDuration);"><span>Continuar</span></a> <a href="cookies.php" rel="nofollow" title="Privacidad &amp; Politica cookies">Leer m&aacute;s</a></p>';
    // Be advised the Close Banner 'X' link requires jQuery

    // bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag
    // or
    bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag

    document.getElementsByTagName('body')[0].className+=' cookiebanner'; //Adds a class tothe <body> tag when the banner is visible


}


function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    if(window.dropCookie) {
        document.cookie = name+"="+value+expires+"; path=/";
    }
    loadAnalytic();
    hideCookieDiv();
}
function hideCookieDiv()
{
    document.getElementById('cookie-law').remove();
}
function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}
function loadAnalytic()
{
   //cambiar en funci�n del id de seguimiento
  window._gaq.push(['_setAccount', 'UA-34397631-6']);
  window._gaq.push(['_trackPageview']);

  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 
}
function loadNewAnalytic()
{                   
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                  ga('create', 'track-id-cambiar', 'dominio.com');
                  ga('send', 'pageview');
 
}

window.onload = function()
{
    if(checkCookie(window.cookieName) != window.cookieValue){
        createDiv();
    }
    else
    {
        loadAnalytic();
    }
}