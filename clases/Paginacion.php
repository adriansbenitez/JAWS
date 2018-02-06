<?php

class Paginacion {

    private $tamanyoPaginas;
    private $totalRegistros;
    private $paginaActual;
    private $nombreAdicional;
    private $valorAdicional;

    const maxPaginasLinea = 7;

    function __construct($totalRegistros) {
        $this->tamanyoPaginas = 20;
        $this->totalRegistros = $totalRegistros;

        if (isset($_REQUEST["pag"])) {
            $this->paginaActual = $_REQUEST["pag"];
        } else {
            $this->paginaActual = 1;
        }
    }

    public function getTamanyoPaginas() {
        return $this->tamanyoPaginas;
    }

    public function getTotalPaginas() {
        $temp = round($this->totalRegistros / $this->tamanyoPaginas, 0, PHP_ROUND_HALF_DOWN);
        //$temp = round($this->totalRegistros / $this->tamanyoPaginas, 0);
        if ($this->totalRegistros / $this->tamanyoPaginas > $temp) {
            $temp++;
        }

        return $temp;
    }

    public function setNombreAdicional($nombreAdicional) {
        $this->nombreAdicional = $nombreAdicional;
    }

    public function setValorAdicional($valorAdicional) {
        $this->valorAdicional = $valorAdicional;
    }

    public function setTamanyoPaginas($tamanyoPaginas) {
        $this->tamanyoPaginas = $tamanyoPaginas;
    }

    public function minimo() {
        return (($this->paginaActual - 1) * $this->tamanyoPaginas);
    }

    public function baseAzar() {
        if (isset($_GET["base"])) {
            return $_GET["base"];
        } else {
            return time();
        }
    }

    function montarPaginas($parBase = null) {

        $totalPag = $this->getTotalPaginas();

        $j = 1;
        $x = $totalPag;
        $pi = "";
        $pf = "";
        $regn1 = "";
        $regn = "";
        if(isset($parBase)){
            $base = $parBase;
        }else{
            $base = ", " . $this->baseAzar();
        }

        if ($totalPag > 0) {
            ?><div class="pagination">
                <div style="display: table; margin: 0 auto;"><?
                    if ($totalPag <= Paginacion::maxPaginasLinea) {
                        
                    } else if ($totalPag > Paginacion::maxPaginasLinea) {
                        if ($this->paginaActual <= Paginacion::maxPaginasLinea - 2) {
                            $j = 1;
                            $x = Paginacion::maxPaginasLinea;
                            $pi = "";
                            $pf = "...";

                            if ($totalPag > Paginacion::maxPaginasLinea + 2) {
                                $regn1 = $totalPag - 1;
                                $regn = $totalPag;
                            } else {
                                $regn1 = "";
                                $regn = $totalPag;
                            }
                        } else if ($this->paginaActual > (Paginacion::maxPaginasLinea - 2)) {
                            $cadena2 = $this->rangoPagina();
                            $cadena = explode(",", $cadena2);
                            
                            $j = $cadena[0];
                            if($cadena[1] > $totalPag -1){
                                $x = $totalPag;
                            }else{
                                $x = $cadena[1];
                                if($cadena[1] > $totalPag -2){
                                    $regn1 = "";
                                }else{
                                    $regn1 = $totalPag - 1;
                                }
                                $regn = $totalPag;
                                $pf = "...";
                            }
                            
                            if ($this->ultimaPag()) {
                                $regn1 = "";
                                $regn = "";
                            }

                            $pi = "...";
                            
                        }
                    }


                    if ($this->paginaActual == 1) {
                        ?><div class="apagado">◄</div><?
                    } else {
                        ?>
                        <div class="pagina" onClick="recarga(<? echo $this->paginaActual - 1;
                        if (isset($this->nombreAdicional)) {
                            echo ", ".$this->valorAdicional;
                        }echo $base;
                        ?>)">◄</div><?
                         }

                         if ($pi == "...") {
                             ?>
                        <div class="pagina" onClick="recarga(1<?
                        if (isset($this->nombreAdicional)) {
                            echo ", ".$this->valorAdicional;
                        }echo $base;
                        ?>)">1</div>
                        <div class="apagado">...</div>
                        <?
                    }

                    for ($i = $j; $i <= $x; $i++) {
                        if ($i == $this->paginaActual) {
                            ?><div class="pagina current"><? echo $i; ?></div><?
                        } else {
                            ?><div class="pagina" onClick="recarga(<? echo $i;
                            if (isset($this->nombreAdicional)) {
                                echo ", ".$this->valorAdicional;
                            }echo $base;
                            ?>)"><? echo $i; ?></div><?
                             }
                         }

                         if ($pf == "...") {
                             ?><div class="apagado">...</div><?
                        }

                        if ($regn1 != "") {
                            ?><div class="pagina" onClick="recarga(<? echo $totalPag - 1;
                            if (isset($this->nombreAdicional)) {
                                echo ", ".$this->valorAdicional;
                            }echo $base;
                            ?>)"><? echo $totalPag - 1; ?></div><?
                        }

                        if ($regn != "") {
                            ?><div class="pagina" onClick="recarga(<? echo $totalPag;
                        if (isset($this->nombreAdicional)) {
                            echo ", ".$this->valorAdicional;
                        }echo $base;
                        ?>)"><? echo $totalPag; ?></div><?
                         }

                         if ($this->paginaActual == $totalPag) {
                             ?><div class="apagado">►</div><?
                        } else {
                            ?><div class="pagina" onClick="recarga(<? echo $this->paginaActual + 1;
                            if (isset($this->nombreAdicional)) {
                                echo ", ".$this->valorAdicional;
                            }echo $base;
                            ?>)">►</div><?
                        }
                        ?></div>
            </div><?
        }
    }

    function rangoPagina() {

        $temp1 = round(((Paginacion::maxPaginasLinea - 1) / 2), 0, PHP_ROUND_HALF_DOWN);
        //$temp1 = round((($this->tamanyoPaginas - 1) / 2), 0);
        $inicio = $this->paginaActual - $temp1;
        $fin = $this->paginaActual + $temp1;

        return $inicio . "," . $fin;
    }

    function ultimaPag() {

        $totalPaginas = $this->getTotalPaginas();
        $rang = explode(",", $this->rangoPagina());
        if ($this->paginaActual > $rang[1]+2) {
            return true;
        }
        return false;
    }

}
