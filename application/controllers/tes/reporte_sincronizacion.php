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
			
			$ttr=$this->Reporte_sincronizacion_model->getCount("tes_tableta");
			$array[0] = (array("atributo"=>"Total de tabletas registradas","valor"=>$ttr,"lista"=>"0"));
			
			$ttsa=$this->Reporte_sincronizacion_model->getCount("", "select * from tes_tableta where id_tes_estado_tableta in(1)");
			$array[1] = (array("atributo"=>"Total de tabletas sin asignar","valor"=>$ttsa,"lista"=>"1"));
			
			$td=$this->Reporte_sincronizacion_model->getCount("", "select * from tes_tableta where id_tes_estado_tableta in(4)");
			$array[2] = (array("atributo"=>"Porcentaje de tabletas desactualizadas","valor"=>number_format(($td*100)/$ttr,"2"),"lista"=>"2"));
			
			$tir=$this->Reporte_sincronizacion_model->getCount("", "select * from tes_tableta where id_tes_estado_tableta in(5,6)");
			$array[3] = (array("atributo"=>"Porcentaje de tabletas inactivas o en reparación","valor"=>number_format(($tir*100)/$ttr,"2"),"lista"=>"3"));
			
			$tut=$this->Reporte_sincronizacion_model->getCount("","select distinct(id_asu_um) from tes_tableta");
			$array[4] = (array("atributo"=>"Total de UM con tabletas","valor"=>$tut,"lista"=>"4"));
			
			$us=$this->Reporte_sincronizacion_model->getCount("","select * from tes_tableta where id_tes_estado_tableta in(2,3)");
			$array[5] = (array("atributo"=>"Porcentaje de tabletas sincronizadas","valor"=>number_format(($us*100)/$ttr,"2"),"lista"=>"5"));
			
			$array[6] = (array("atributo"=>"Total de tabletas sincronizadas","valor"=>$us,"lista"=>"6"));
			
			$array[7] = (array("atributo"=>"Porcentaje de tabletas desincronizadas","valor"=>number_format(100-(($us*100)/$ttr),"2"),"lista"=>"7"));
			
			$ttd=$this->Reporte_sincronizacion_model->getCount("","select * from tes_tableta where id_tes_estado_tableta not in(2,3)");
			$array[8] = (array("atributo"=>"Total de tabletas desincronizadas","valor"=>$ttd,"lista"=>"8"));
			
			$array[9] = (array("atributo"=>"Total de pacientes que no llevan su tes sincrinizada con la plataforma","valor"=>"12","lista"=>"9"));
			$array[10] = (array("atributo"=>"Total de controles no registrados en la tes","valor"=>"12","lista"=>"10"));
			
			$version=$this->Reporte_sincronizacion_model->get_version();
			$array[11] = (array("atributo"=>"Ultima version de la app","valor"=>$version[0]->version,"lista"=>"11"));
			$array[12] = (array("atributo"=>"Fechas de la ultima version de la app","valor"=>$version[0]->fecha_liberacion,"lista"=>"12"));
			
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
			$array=array();
			$campos="t.id as No,mac as Mac, tv.version+' -> '+tv.descripcion as Version, et.descripcion as Estado, tc.descripcion as 'Tipo Censo', asu.descripcion as 'Unidad Medica'";
			$join = "left join tes_version tv on tv.id= t.id_version
					 left join sis_estado_tableta et on et.id=t.id_tes_estado_tableta
					 left join tes_tipo_censo tc on tc.id=t.id_tipo_censo
					 left join asu_arbol_segmentacion asu on asu.id=t.id_asu_um";
			if($op==0)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join");
			
			if($op==1)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN ('1')");	
			
			if($op==2)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN ('1')");	
			
			if($op==3)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN (5,6)");
			
			if($op==4)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE t.id_asu_um!=''");
			
			if($op==5||$op==6)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN (3,2)");
			
			if($op==7)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta NOT IN (3,2)");	
			
			if($op==11)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT * FROM tes_version");
			
			if($op==12)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT * FROM tes_version order by fecha_liberacion DESC");		
			
			$data['datos']=$array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
		$this->template->write_view('content',DIR_TES.'/reporteador/reporte_view', $data);
 		$this->template->render();
	}
}