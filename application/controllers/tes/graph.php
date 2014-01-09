<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Graph extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	// array ejemplo array(array("d1"=>"[1,2]","d2"=>"[1,2]","d3"=>"[1,2]"..."dn"=>"[b,h]"))
	// b= valor en el eje de las x h valor en el eje de las y
	// crea grafica solicitud por url array con urlencode()
	
	// crea grafica pasando un array 
	public function graph_init($title,$titulo,$array,$label,$grafica="",$nacimiento="")
	{
		if($grafica=="todos")
		$data["graficas"]='"time","basic","axis","bars","bars-h","stacked","horizontal","pie"';
		else
		{
			$cadena="";
			$grafica="_".$grafica;
			if(stripos($grafica,"time"))
				$cadena.='"time",';
				
			if(stripos($grafica,"basic"))
				$cadena.='"basic",';
				
			if(stripos($grafica,"axis"))
				$cadena.='"axis",';
				
			if(stripos($grafica,"axis"))
				$cadena.='"bar,$graficas",';
				
			if(stripos($grafica,"bars"))
				$cadena.='"bars-h",';
				
			if(stripos($grafica,"bars-h"))
				$cadena.='"stacked",';
				
			if(stripos($grafica,"stacked"))
				$cadena.='"horizontal",';
				
			if(stripos($grafica,"pie"))
				$cadena.='"pie"';
				
			
			$data["graficas"]=$cadena;
		}
		$url="/grafica/graph";
		$data["title"]=$title;
		$data["title"]=$title;
		$data["nacimiento"]=$nacimiento;
		$data["titulo"]=str_replace("%20"," ",$titulo);
		$data["array"]=json_decode(urldecode($array));
		$data["etiqueta"]=json_decode(urldecode($label));
		$this->load->view(DIR_TES.$url,$data);
	}
	public function map($lugar="Chiapas",$zoom=6,$rewrite=0,$datos="")
	{
		if($datos=="")$datos=$this->input->post('datos');
		$cadena="";
		$data["zoom"]=$zoom;
		$data["lugar"]=$lugar;
		if($datos)
		foreach($datos as $x)
		{
			$cadena.='"'.$x["localidad"].'",';
			$cadena.='"'.$x["lat"].'",';
			$cadena.='"'.$x["lon"].'",';
			$cadena.='"'.$x["descripcion"].'",';
			$cadena.='"'.$x["imagen"].'",';
			$cadena.='"'.$x["icono"].'",';
		}
		$data["array"]=$cadena;
		if($rewrite==0)
		{
			echo ".";
			$data["api"]='<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&;amp;language=es"></script>';
			$this->template->write('header','',true);
			$this->template->write('footer','',true);
			$this->template->write('menu','',true);
			$this->template->write_view('content',DIR_TES.'/grafica/map', $data);
			$this->template->render();

		}
		else
		{
			$data["api"]='';
			$this->load->view(DIR_TES.'/grafica/map', $data);
		}
	}
}
?>