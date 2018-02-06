<?php

require_once 'PHPExcel.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class HojaExel {
    
    private $obj;
    
    function __construct() {
        PHPExcel_Settings::setLocale("es_es");
        $this->obj = new PHPExcel();
        
        $this->obj->getProperties()->setTitle("LISTADO_". date("d-m-Y"));
        $this->obj->getProperties()->setDescription("Listado generado automáticamente desde la plataforma Inspiracle");
        $this->obj->getActiveSheet()->setTitle("Listado");
    }
    
    public function escribirCelda($horizonal, $vertical, $texto){
        $this->obj->getActiveSheet()->setCellValueByColumnAndRow($horizonal, $vertical, $texto);
    }
    
    public function escribirArray($horizonal, $vertical, $array){
        $suma = 0;
        foreach($array as $ele){
            $this->obj->getActiveSheet()->setCellValueByColumnAndRow($horizonal+$suma, $vertical, $ele);
            $suma++;
        }
        
    }
    
    public function devolverArchivo(){
        $escritor = new PHPExcel_Writer_Excel2007($this->obj);
        
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="LISTADO_'. date("d-m-Y").'"');

        $escritor->save('php://output');
        die();
    }
}
