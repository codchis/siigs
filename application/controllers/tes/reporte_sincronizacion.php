<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controller Usuario
 *
 * @package     TES
 * @subpackage  Controlador
 * @author     	Eliecer
 * @created     2013-12-17
 */
class Reporte_sincronizacion extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		try
		{
			$this->load->helper('url');
			$this->load->helper('date');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}
	
	/**
	 *Este es el metodo por default, obtiene el listado de las perosnas
	 *se recibe el parametro $pag de tipo int que representa la paginacion
	 *
	 */
	public function index()
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			$data['title'] = 'Reporte Sincronización';
			$count=$this->Reporte_sincronizacion_model->getCount("tes_tableta");
			$array[0] = (array("atributo"=>"Total de tabletas registradas","valor"=>$count[0]["count"],"lista"=>"0"));
			$array[1] = (array("atributo"=>"Total de UM con tabletas","valor"=>"12","lista"=>"1"));
			$array[2] = (array("atributo"=>"% de UM sincronizadas","valor"=>"12","lista"=>"2"));
			$array[3] = (array("atributo"=>"Total de UM sincronizadas","valor"=>"12","lista"=>"3"));
			$array[4] = (array("atributo"=>"% de UM desincronizadas","valor"=>"12","lista"=>"4"));
			$array[5] = (array("atributo"=>"Total de UM desincronizadas","valor"=>"12","lista"=>"5"));
			$array[6] = (array("atributo"=>"Total de pacientes que no llevan su tes sincrinizada con la plataforma","valor"=>"12","lista"=>"6"));
			$array[7] = (array("atributo"=>"Total de controles no registrados en la tes","valor"=>"12","lista"=>"7"));
			$version=$this->Reporte_sincronizacion_model->get_version();
			$array[8] = (array("atributo"=>"Ultima version de la app","valor"=>$version[0]->version,"lista"=>"8"));
			$array[9] = (array("atributo"=>"Fechas de la ultima version de la app","valor"=>$version[0]->fecha_liberacion,"lista"=>"9"));
			
			$data['datos']=$array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
 		$this->template->write_view('content',DIR_TES.'/reporteador/sincronizacion', $data);
 		$this->template->render();
	}
	
	public function view($op,$title)
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			$data['title'] = $title;
			
			if($op==0)
			$array=$this->Reporte_sincronizacion_model->getListado("tes_tableta");	
			
			if($op==8||$op==9)
			$array=$this->Reporte_sincronizacion_model->getListado("tes_version");		
			
			$data['datos']=$array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
 		$this->load->view(DIR_TES.'/reporteador/reporte_view', $data);
 		
	}
}