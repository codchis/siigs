<?php
/**
 * Controlador Reporteador
 *
 * @package		TES
 * @subpackage	Controlador
 * @author     	Rogelio
 * @created		2013-12-20
 */
class Reporteador extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->helper('url');
			$this->load->model(DIR_TES.'/Reporteador_model');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}

	/**
	 * Visualiza los reportes existentes
	 *
	 * @access		public
	 * @return 		void
	 */
	public function index()
	{
		try{
			if (empty($this->Reporteador_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Reportes';
			$this->load->helper('form');
			$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
			
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
			$data['estados'] = (array)$this->ArbolSegmentacion_model->getDataKeyValue(1, 1);

			$array[0] = (array("atributo"=>"Cobertura por Tipo de Biológico","valor"=>"","lista"=>"0"));
			$array[1] = (array("atributo"=>"Concentrado de Actividades","valor"=>"","lista"=>"1"));
			$array[2] = (array("atributo"=>"Seguimiento RV-1 y RV-5 a menores de 1 año","valor"=>"","lista"=>"2"));
			$array[3] = (array("atributo"=>"Censo Nominal","valor"=>"","lista"=>"3"));
			$array[4] = (array("atributo"=>"Esquemas Incompletos","valor"=>"","lista"=>"4"));
			$data['datos']=$array;
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}
 		$this->template->write_view('content',DIR_TES.'/reporteador/index', $data);
 		$this->template->render();

	}
	
	public function view($op, $title, $nivel, $id, $fecha = '')
	{
		try{
			if (empty($this->Reporteador_model))
				return false;
            
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
            
            $array=array();
			$data['title'] = $title;
            $data['datos'] = $array;

			if($op==0) {
                $this->load->model(DIR_TES.'/Reporte_cobertura_biologico');
				$array = $this->Reporteador_model->getCoberturaBiologicoListado($nivel, $id, $fecha);
                $data['headTable'] = '';
            }
			if($op==1)
				$array=$this->Reporteador_model->getConcentradoActividades($nivel, $id, $fecha);
			if($op==2)
				$array=$this->Reporteador_model->getSeguimientoRV1RV5($nivel, $id, $fecha);
			if($op==3){
				$this->load->model(DIR_TES.'/Reporte_censo_nominal');
				$array = $this->Reporteador_model->getCensoNominal($nivel, $id, $fecha);
				$data['headTable'] = '<tr><th>Apellido Paterno</th><th>Apellido Materno</th><th>Nombre</th>
						<th>Domicilio</th><th>CURP</th><th>Fecha Nac</th><th>Sexo</th><th>Edad</th><th>Esquema</th>
						<th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th>
						<th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th>
						<th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th>
						<th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th>
						<th>BGC</th><th>BGC</th><th>BGC</th><th>BGC</th></tr>';
			}
			if($op==4)
				$array=$this->Reporteador_model->getEsquemasIncompletos($nivel, $id);
				
			$data['datos'] = $array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['infoclass'] = 'error';
		}
		
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
		$this->template->write_view('content',DIR_TES.'/reporteador/reporte_view', $data);
		$this->template->render();
	}
}