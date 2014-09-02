<?php
/**
 * Controlador Objeto
 *
 * @package		Libreria
 * @subpackage	Controlador
 * @author     	Eliecer
 * @created		2013-12-10
 */ 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tree extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	/**
	 *
	 * Crea el arbol y lo muestra en la view
	 * 
	 * @param		string 		$title           Titulo de la pagina en el navegador
	 * @param		string 		$titulo          titulo o nombre a mostrar en la vista al crear el arbol
	 * @param		int 		$seleccion       tipo de seleccion 1=select. 2=multiselect. 3=multiselect Parcial (Marcan los padres del hijo seleccionado
	 * @param		string 		$tipo            tipo de control radio o check
	 * @param		boolean		$menu            si se desea mostrar el menu
	 * @param		string 		$id              id del campo oculto donde se guarda el id del elemento seleccionado
	 * @param		string 		$text            id del campo donde se muestra la descripcion del elemento seleccionado
	 * @param		string 		$idarbol         id del arbol donde comenzara la creacion
	 * @param		string 		$nivel           nivel en el que se empezara a mostrar informacion
	 * @param		string 		$omitidos        nodos que no se deben mostrar en la vista al crear el arbol
	 * @param		string 		$seleccionable   determina si un nodo se puede o no seleccionar
	 *
	 * @return 		void
	 *
	 * ejemplo en views :
	<a href="/<?php echo DIR_TES?>/Tree/tree/TES/Lugar de Nacimiento/1/radio/0/id/text/1/1/<?php echo urlencode(json_encode(array(2,3,4,5)));?>/<?php echo urlencode(json_encode(array(2)));?>" id="fba1" class="cat">Seleccionar</a>
	<input type='hidden' id='id'>
	<input type='text'   id='text'>
	Trae Estados ->Municipio y solo deja seleccionar municipios
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