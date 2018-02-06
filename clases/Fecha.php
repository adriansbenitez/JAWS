<?php

/**
 * Description of Fecha
 *
 * @author Nachondo
 */
class Fecha {
    
    private $fecha;
    
    function __construct($fechaTexto){
        $this->fecha = mktime(substr($fechaTexto, 11, 2), substr($fechaTexto, 14, 2), substr($fechaTexto, 17, 2), substr($fechaTexto, 5, 2),
                substr($fechaTexto, 8, 2), substr($fechaTexto, 0, 4));
    }
    
    public static function nuevaFecha($dia, $mes, $anyo, $hora){
        $miFecha = new Fecha("$anyo-$mes-$dia $hora:00:00");
        return $miFecha;
    }
    
    public static function porDatePicker($campo){
        $arrFecha = explode("/", $campo);
        $fecha = new Fecha($arrFecha[2]."-".$arrFecha[1]."-".$arrFecha[0]." 00:00:00");
        return $fecha;
    }
    
    

    //GET ----------------------------------------------------------------------
    public function getFecha() {
        return $this->fecha;
    }

    //SET ----------------------------------------------------------------------
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function conFormatoSimple($separador = "-"){
        return date("d".$separador."m".$separador."Y", $this->fecha);
    }
    
    public function conFormatoSimpleConHora(){
        return date("d-m-Y H:i:s", $this->fecha);
    }
    
    public function conFormatoAlta(){
        return date("YmdHis", $this->fecha);
    }
        
    public function conFormatoAMDConHora(){
        return date("Y-m-d H:i:s", $this->fecha);
    }
    
    public function conFormatoAMD(){
        return date("Y-m-d", $this->fecha);
    }
    
    public function conFormatoLargo(){
        return $this->diaSemana().", ".$this->dia()." de ".$this->mesTexto().
                " del ".$this->anyo();
    }
    
    public function conFormatoLargoConHora(){
        return $this->diaSemana().", ".$this->dia()." de ".$this->mesTexto().
                " del ".$this->anyo()." a las ".$this->hora().":".$this->minutos();
    }
    
    public function diaSemana(){
        $respuesta = "";
        switch (date("w", $this->fecha)){
            case 0:
                $respuesta = "Domingo";
                break;
            
            case 1:
                $respuesta = "Lunes";
                break;
            
            case 2:
                $respuesta = "Martes";
                break;
            
            case 3:
                $respuesta = "Miércoles";
                break;
            
            case 4:
                $respuesta = "Jueves";
                break;
            
            case 5:
                $respuesta = "Viernes";
                break;
            
            case 6:
                $respuesta = "Sábado";
                break;
        }
        return $respuesta;
    }
    
    public function mesTexto(){
        $respuesta = "";
        switch (date("n", $this->fecha)){
            case 1:
                $respuesta = "Enero";
                break;
            
            case 2:
                $respuesta = "Febrero";
                break;
            
            case 3:
                $respuesta = "Marzo";
                break;
            
            case 4:
                $respuesta = "Abril";
                break;
            
            case 5:
                $respuesta = "Mayo";
                break;
            
            case 6:
                $respuesta = "Junio";
                break;
            
            case 7:
                $respuesta = "Julio";
                break;
            
            case 8:
                $respuesta = "Agosto";
                break;
            
            case 9:
                $respuesta = "Septiembre";
                break;
            
            case 10:
                $respuesta = "Octubre";
                break;
            
            case 11:
                $respuesta = "Noviembre";
                break;
            
            case 12:
                $respuesta = "Diciembre";
                break;
        }
        return $respuesta;
    }
    
    public function anyo(){
        return date("Y", $this->fecha);
    }
    public function mes(){
        return date("n", $this->fecha);
    }
    
    public function dia(){
        return date("j", $this->fecha);
    }
    
    public function hora(){
        return date("G", $this->fecha);
    }
    
    public function minutos(){
        return date("i", $this->fecha);
    }
    
    public function segundos(){
        return date("s", $this->fecha);
    }
    
}
