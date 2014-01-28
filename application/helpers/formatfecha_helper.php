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
	$i=0; $grid="";
	foreach($array as $dato)
	{
		$i++; 
		$dato=(array)$dato;
		if(isset($_POST["f$id"][$i-1]))
			$fecha=$_POST["f$id"][$i-1];
		else 
			$fecha=$dato["fecha"];
		if(isset($_POST[$id][$i-1]))
			$x=$_POST[$id][$i-1];
		else
			$x=$dato["id"];
		$clase="row2";
		if($i%2)$clase="row1";
		$num=$i;
		if($i<10)$num="0".$i;
		$grid.= '<span id="r'.$id.$num.'" ><div class="'.$clase.'" >
				<table width="100%" >
				<tr>
					<th width="10%" >'.$num.'</th>
					<th width="50%" align="left"><select name="'.$id.'[]" id="'.$id.$num.'"  required title="requiere"  style="width:95%;"></select>
					<script>$("#'.$id.$num.'").load("/tes/enrolamiento/catalog_select/'.$id.'/'.$x.'");</script>
					</th>
					<th width="40%" align="left"><input name="f'.$id.'[]" type="text" id="f'.$id.$num.'" value="'.date("Y-m-d",strtotime($fecha)).'"></th>
				</tr>
				</table> 
			  </div></span>';
			  
		 
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
function getArrayView($array)
{
	$id=0; $grid="";
	foreach($array as $dato)
	{
		$id++;
		$descripcion=$dato->descripcion;
		$fecha=$dato->fecha;
		$clase="row2";
		if($id%2)$clase="row1";
	
		$grid.= '<div class="'.$clase.'" style="height:30px">
				<table width="100%" >
				<tr>
					<th width="10%" >'.$id.'</th>
					<th width="50%" align="left">'.$descripcion.'</th>
					<th width="40%" align="left">'.$fecha.'</th>
				</tr>
				</table> 
			  </div>';
		 
	 }
	if($id==0)
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