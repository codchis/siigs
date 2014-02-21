<?php
/**
 * Controlador Reporteador
 *
 * @package		Librería
 * @subpackage	Helper
 * @author     	Pascual
 * @created		2013-12-10
 */

/**
 * Devuelve la fecha en cadena con el formato especificado
 *
 * @access public
 * @param  date   $objFecha Objeto fecha
 * @param  string $formato  Formato de la fecha
 * @return string Cadena de la fecha
 */
function formatFecha($objFecha, $formato="d-m-Y H:m:s") {
	/*if($objFecha instanceof DateTime)
		return $objFecha->format($formato);
	else {
		$arrayFecha = explode('-',$objFecha);
	
		if(count($arrayFecha) != 3) 
			$arrayFecha = explode('/',$objFecha);
			
		if(count($arrayFecha) != 3) 
			return NULL;
		
		$fecha = new DateTime();
		
		// formato d-m-Y
		if(strlen($arrayFecha[2]) == 4)
			$fecha->setDate($arrayFecha[2], $arrayFecha[1], $arrayFecha[0]);
		
		// formato y-m-d
		if(strlen($arrayFecha[0]) == 4)
			$fecha->setDate($arrayFecha[0], $arrayFecha[1], $arrayFecha[2]);
		
		return $fecha->format($formato);
	}*/
    
    $fecha = '';
    
    if(!empty($objFecha)) {
        $fecha = new \DateTime($objFecha);
        $fecha = $fecha->format($formato);
    }
    
    return $fecha;
}

/**
 *Crea el grid de los controles del paciente
 *se recibe el parametro $array que contien los datos del control 
 *parametro $id que es el nombre del control
 *parametro $nu que contien el id del campo oculto
 *return $grid 
 *
 */
function getArray($array,$id,$nu)
{
	$ha="50%";$hb="40%";
	$xa="99%"; $xb="80%";
	$mas=""; $script="";
	if($id=="ira"||$id=="eda"||$id=="consulta")
	{
		$ha="28%";$hb="15%";
		$xb="70%";
	}
	if($id=="vacuna")
	{
		$xa="98%"; $xb="78%"; $ha="40%"; $hb="30%";
	}
	$i=0; $grid="";
	foreach($array as $dato)
	{
		$i++; 
		$dato=(array)$dato;
		if(isset($_POST["f$id"][$i-1]))
		{
			$fecha=$_POST["f$id"][$i-1];
			if($id=="ira"||$id=="eda"||$id=="consulta")
			$y=$_POST["tratamiento_des$id"][$i-1];
			if($id=="vacuna")
			$f=$_POST["ffolio$id"][$i-1];
		}
		else 
		{
			$fecha=$dato["fecha"];
			if($id=="ira"||$id=="eda"||$id=="consulta")
			$y=$dato["id_tratamiento"];
			if($id=="vacuna")
			$f=$dato["codigo_barras"];
		}
			
		if(isset($_POST[$id][$i-1]))
			$x=$_POST[$id][$i-1];
		else
			$x=$dato["id"];
			
		$clase="row2";
		if($i%2)$clase="row1";
		$num=$i;
		if($i<10)$num="0".$i;
		if($id=="ira"||$id=="eda"||$id=="consulta")
		{
			$mas='<th width="20%" align="left"><select name="tratamiento'.$id.'[]" id="tratamiento'.$id.$num.'" style="width:'.$xa.';"></select>
					</th>
					<th width="27%" align="left"><select name="tratamiento_des'.$id.'[]" id="tratamiento_des'.$id.$num.'" style="width:'.$xa.';"></select>
					</th>';
			$script='$("#tratamiento'.$id.$num.'").load("/tes/enrolamiento/tratamiento_select/activo/1/'.$y.'/cc", function() {
				$("#tratamiento_des'.$id.$num.'").load("/tes/enrolamiento/tratamiento_select/tipo/"+encodeURIComponent($("#tratamiento'.$id.$num.'").val())+"/'.$y.'/descripcion/");
			});
					$("#tratamiento'.$id.$num.'").click(function(e) 
					{
						num=this.id.replace("/\D/g","");
						$("#tratamiento_des'.$id.$num.'").load("/tes/enrolamiento/tratamiento_select/tipo/"+encodeURIComponent(this.value)+"/0/descripcion/");
					});';
		}
		if($id=="vacuna")
		{
			$mas='<th width="20%" align="left"><input type="text" name="ffolio'.$id.'[]" id="ffolio'.$id.$num.'" style="width:87%;" value="'.$f.'">';
		}
		$grid.= '<span id="r'.$id.$num.'" ><div class="'.$clase.'" >
				<table width="100%" >
				<tr>
					<th width="10%" >'.$num.'</th>
					<th width="'.$ha.'" align="left"><select name="'.$id.'[]" id="'.$id.$num.'"  required title="requiere"  style="width:'.$xa.';"></select>					
					</th>
					<th width="'.$hb.'" align="left"><input name="f'.$id.'[]" type="text" id="f'.$id.$num.'" value="'.date("d-m-Y",strtotime($fecha)).'" style="width:'.$xb.';"></th>
					'.$mas.'
				</tr>
				</table> 
			  </div></span>
			  <script>
			  $("#'.$id.$num.'").load("/tes/enrolamiento/catalog_select/'.$id.'/'.$x.'");
			  $("#f'.$id.$num.'").datepicker(optionsFecha );
			  '.$script.'</script>';
			  
		 
	 }
	
	$grid.='<input type="hidden" id="'.$nu.'" value="'.$i.'" />';
	return $grid;
}
/**
 *Crea el grid de los controles del paciente para la opcion ver
 *se recibe el parametro $array que contien los datos del control 
 *return $grid 
 *
 */
function getArrayView($array,$id="")
{
	$i=0; $grid="";
	$ha="50%";$hb="40%";
	$xa="98%"; $xb="80%";
	$mas=""; $script="";
	if($id=="ira"||$id=="eda"||$id=="consulta")
	{
		$ha="28%";$hb="15%";
		$xb="70%";
	}
	$num=0;	
	foreach($array as $dato)
	{
		$i++;
		$num++;
		if($num<10)$num="0".$num;
		$descripcion=$dato->descripcion;
		$fecha=$dato->fecha;
		$clase="row2";
		if($id%2)$clase="row1";
		
		if($id=="ira"||$id=="eda"||$id=="consulta")
		{
			$y=$dato->id_tratamiento;
			$mas='<th width="20%" align="left"><select name="tratamiento'.$id.'[]" id="tratamiento'.$id.$num.'" style="width:'.$xa.';border:0px;" disabled ></select>
					</th>
					<th width="27%" align="left"><select name="tratamiento_des'.$id.'[]" id="tratamiento_des'.$id.$num.'" style="width:'.$xa.';border:0px" disabled></select>
					</th>';
			$script='$("#tratamiento'.$id.$num.'").load("/tes/enrolamiento/tratamiento_select/activo/1/'.$y.'/cc", function() {
				$("#tratamiento_des'.$id.$num.'").load("/tes/enrolamiento/tratamiento_select/tipo/"+encodeURIComponent($("#tratamiento'.$id.$num.'").val())+"/'.$y.'/descripcion/");
			});';
		}
		$grid.= '<div class="'.$clase.'" style="height:30px">
				<table width="100%" >
				<tr>
					<th width="10%" >'.$i.'</th>
					<th width="'.$ha.'" align="left"><input style="width:'.$xa.';border:0px" value="'.$descripcion.'" title="'.$descripcion.'" readonly></th>
					<th width="'.$hb.'" align="left">'.date("d-m-Y",strtotime($fecha)).'</th>
					'.$mas.'
				</tr>
				</table> 
			  </div><script>
			  '.$script.'</script>';
	 }
	if($i==0)
	{
		$grid= '<div class="row1" style="height:30px">
				<table width="100%" >
					<tr>
						<th colspan=3 >No hay Datos</th>
					</tr>
				</table> 
			  </div>';
	}
	return $grid;
}