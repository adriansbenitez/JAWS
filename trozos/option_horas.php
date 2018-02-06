<? if(isset($agenda)){
    $hora = $agenda->getPrimerDia()->hora();
    echo "<h1>$hora</h1>";
}else{
    $hora = "00";
} ?>
<option value="00"<? if("00" == $hora){echo ' selected="selected"';} ?>>00 hs</option>
<option value="01"<? if("01" == $hora){echo ' selected="selected"';} ?>>01 hs</option>
<option value="02"<? if("02" == $hora){echo ' selected="selected"';} ?>>02 hs</option>
<option value="03"<? if("03" == $hora){echo ' selected="selected"';} ?>>03 hs</option>
<option value="04"<? if("04" == $hora){echo ' selected="selected"';} ?>>04 hs</option>
<option value="05"<? if("05" == $hora){echo ' selected="selected"';} ?>>05 hs</option>
<option value="06"<? if("06" == $hora){echo ' selected="selected"';} ?>>06 hs</option>
<option value="07"<? if("07" == $hora){echo ' selected="selected"';} ?>>07 hs</option>
<option value="08"<? if("08" == $hora){echo ' selected="selected"';} ?>>08 hs</option>
<option value="09"<? if("09" == $hora){echo ' selected="selected"';} ?>>09 hs</option>
<option value="10"<? if("10" == $hora){echo ' selected="selected"';} ?>>10 hs</option>
<option value="11"<? if("11" == $hora){echo ' selected="selected"';} ?>>11 hs</option>
<option value="12"<? if("12" == $hora){echo ' selected="selected"';} ?>>12 hs</option>
<option value="13"<? if("13" == $hora){echo ' selected="selected"';} ?>>13 hs</option>
<option value="14"<? if("14" == $hora){echo ' selected="selected"';} ?>>14 hs</option>
<option value="15"<? if("15" == $hora){echo ' selected="selected"';} ?>>15 hs</option>
<option value="16"<? if("16" == $hora){echo ' selected="selected"';} ?>>16 hs</option>
<option value="17"<? if("17" == $hora){echo ' selected="selected"';} ?>>17 hs</option>
<option value="18"<? if("18" == $hora){echo ' selected="selected"';} ?>>18 hs</option>
<option value="19"<? if("19" == $hora){echo ' selected="selected"';} ?>>19 hs</option>
<option value="20"<? if("20" == $hora){echo ' selected="selected"';} ?>>20 hs</option>
<option value="21"<? if("21" == $hora){echo ' selected="selected"';} ?>>21 hs</option>
<option value="22"<? if("22" == $hora){echo ' selected="selected"';} ?>>22 hs</option>
<option value="23"<? if("23" == $hora){echo ' selected="selected"';} ?>>23 hs</option>