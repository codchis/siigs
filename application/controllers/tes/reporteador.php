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
			$data['jurisdicciones'] = (array)$this->ArbolSegmentacion_model->getDataKeyValue(1, 2);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_TES.'/reporteador/index', $data);
 		$this->template->render();

	}
	
}