<?php

require_once 'PHPMailer-master/PHPMailerAutoload.php';

class Correo {

    private $adjuntos;
    private $plantilla;
    private $remplazos;

    function __construct() {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        //$this->mail->SMTPDebug = 2;
        $this->mail->Debugoutput = 'html';
        //$this->mail->SMTPSecure = 'tls';

        $this->mail->Host = 'localhost';
        $this->mail->Port = 25;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'webmaster@inspiracle.es';
        $this->mail->Password = 'Santi27';
        $this->mail->setFrom('webmaster@inspiracle.es', 'Inspiracle');
        $this->remplazos = array();
    }

    public function setAsunto($asunto) {
        $this->mail->Subject = utf8_decode($asunto);
        $this->agregarRemplazo("asunto", $asunto);
    }

    public function setPlantilla($plantilla) {
        $this->plantilla = $plantilla;
    }

    public function agregarDestinatarios($direccion, $nombre = null) {
        //Debería ir el nombre en el parámetro 2
        if(!isset($nombre)){
            $nombre = $direccion;
        }
        $this->mail->addAddress($direccion, $nombre);
    }

    public function agregarAdjunto($nombreArchivo, $contenido) {
        $archivo = new Archivo($nombreArchivo, $contenido);
        array_push($this->adjuntos, $archivo);
    }

    public function agregarRemplazo($nombre, $valor, $cambio = true) {
        if ($cambio) {
            $total = "@" . $nombre . "@" . "|" . utf8_decode(mb_ereg_replace("\n", "<br/>", $valor));
        } else {
            $total = "@" . $nombre . "@" . "|" . mb_ereg_replace("\n", "<br/>", $valor);
        }

        array_push($this->remplazos, $total);
    }

    private function cambiar($texto) {

        foreach ($this->remplazos as $modifica) {
            $pareja = explode("|", $modifica);
            $texto = str_replace($pareja[0], $pareja[1], $texto);
        }

        return $texto;
    }

    public function enviar($noIncluir = false) {

        if(!$noIncluir){
            $this->agregarDestinatarios("webmaster@inspiracle.es");
            $this->agregarDestinatarios("doc_nachondo@hotmail.com");
        }

        $fichero_base = fopen(__DIR__ . "/" . $this->plantilla, "r");
        $resultado_lectura = fread($fichero_base, filesize(__DIR__ . "/" . $this->plantilla));

        $contenidoHTML = $this->cambiar($resultado_lectura);
        
        $this->mail->Body = $contenidoHTML;
        $this->mail->AltBody = $this->mail->Subject."\n".  strip_tags($this->contenido);
        return $this->mail->send();
    }

}

class Archivo {

    private $nombre;
    private $datos;

    function __construct($nombre, $datos) {
        $this->nombre = $nombre;
        $this->datos = $datos;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDatos() {
        return $this->datos;
    }

}
