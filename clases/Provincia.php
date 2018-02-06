<?php

require_once 'BD.php';

class Provincia{
    
    private $idProvincia;
    private $nombre;
    
    
    function __construct($idProvincia, $nombre) {
        $this->idProvincia = $idProvincia;
        $this->nombre = $nombre;
    }
    
    public function getIdProvincia() {
        return $this->idProvincia;
    }

    public function getNombre() {
        return $this->nombre;
    }

    //SET ----------------------------------------------------------------------
    
    public function setIdProvincia($idProvincia) {
        $this->idProvincia = $idProvincia;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    //PERSONALIZADOS -----------------------------------------------------------
    public static function montarSelect($id_provincia = 0, $clase = ""){ ?>
        <select name="provincia" class="<? echo $clase ?>">
            <option value="0">--Selecciona tu provincia--</option>
            <? $listaProv = Provincia_BD::listar();
            foreach ($listaProv as $provincia){ ?>
                <option value="<? echo $provincia->getidProvincia();
                    ?>" <? if($id_provincia == $provincia->getIdProvincia()){echo "selected ";}
                    ?>><? echo $provincia->getNombre(); ?></option>
            <? } ?>
        </select>
    <? }

}

class Provincia_BD{
    
    public static function listar(){
        $bd = new BD();
        
        $bd->setConsulta("select id_provincia, nombre from provincias");
        
        $bd->ejecutar();
        
        $arrProvincias = array();
        
        while($it = $bd->resultado()){
            $provincia = new Provincia($it["id_provincia"], $it["nombre"]);
            array_push($arrProvincias, $provincia);
        }
        return $arrProvincias;
    }
    
    
    public static function porId($idProvincia){
        $bd = new BD();
        
        $bd->setConsulta("select id_provincia, nombre from provincias where id_provincia = ?");
        
        $bd->ejecutar($idProvincia);
        
        if($it = $bd->resultado()){
            $provincia = new Provincia($it["id_provincia"], $it["nombre"]);
            return $provincia;
        }
        return null;
    }
    
}