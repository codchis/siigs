<?php

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