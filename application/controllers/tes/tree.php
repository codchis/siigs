<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tree extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	/*
	** Carga el arbol
	** parametros
	** title = <title></title>
	** titulo= titulo del arbol
	** seleccion tipo de seleccion 1=select. 2=multiselect. 3=multiselect Parcial (Marcan los padres del hijo seleccionado)
	** tipo = tipo de control radio o check
	** menu= 1,0 o TRUE,FALSE true crea un menu con trs botones marcartodos, desmarcar y alternar
	** id= id del campo en el formulario donde se desea imprimir el valor del id del arbol
	** text= id del campo donde deseamos mostrar el texto
	** idarbol= id del arbol donde queremos que se empiece a dibujar el arbol defaul=1
	** nivel= nivel en el que se desea empezar el despliegue hacia abajo
	** omitidos= niveles omitidos es decir que no se mostraran en el arbol
	** datos= valor en caso de edit
	** ejemplo en views :
	<a href="/<?php echo DIR_TES?>/Tree/tree/TES/Lugar de Nacimiento/1/radio/0/lugarcivil/lugarcivilT/1/1/<?php echo urlencode(json_encode(array(3,4,5)));?>/<?php echo urlencode(json_encode(array(4)));?>" id="fba1" class="cat">Seleccionar</a>
	
	Trae Estados ->Municipio
	*/
	public function create($title,$titulo,$seleccion,$tipo,$menu,$id,$text,$idarbol=1,$nivel=1,$omitidos=array(NULL),$seleccionable="")
	{
		$data["title"]=$title;
		$data["titulo"]=str_replace("%20"," ",$titulo);
		$data["seleccion"]=$seleccion;
		$data["tipo"]=$tipo;
		$data["menu"]=$menu;
		$data["id"]=$id;
		$data["text"]=$text;
		$data["idarbol"]=$idarbol;
		$data["nivel"]=$nivel;
		$data["omitidos"]=json_decode(urldecode($omitidos));
		
		if($seleccionable!="")
			$sel=json_decode(urldecode($seleccionable));
		else $sel="";
		$data["seleccionables"]=$sel;
		$this->load->view(DIR_TES.'/tree/tree',$data);
	}
}
?>