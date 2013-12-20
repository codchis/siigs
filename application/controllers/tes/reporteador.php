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
			$data['jurisdicciones'] = (array)$this->ArbolSegmentacion_model->getDataKeyValue(1, 2);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_TES.'/reporteador/index', $data);
 		$this->template->render();

	}

	/**
	 * Visualiza los datos de la notificaci�n recibida
	 *
	 * @access		public
	 * @param		int 		$id 	id de notificaci�n a visualizar
	 * @return 		void
	 */
	public function view($id)
	{
		try {
			if (empty($this->Notificacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Ver detalles de la notificaci�n';
			$notification = $this->Notificacion_model->getById($id, true)[0];			
			$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
			$descripciones = $this->ArbolSegmentacion_model->getDescripcionById(explode(',',$notification->id_arr_asu), 0);
			for($i = 0; $i < count($descripciones); $i++)
				$notification->tabletas[$i] = $descripciones[$i]->descripcion;
			$data['notificacion_item'] = $notification;
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_TES.'/notificacion/view', $data);
 		$this->template->render();
	}

	/**
	 * 1) Prepara el formulario para la inserción de una notificaci�n nueva
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @return 		void
	 */
	public  function insert()
	{
		if (empty($this->Notificacion_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Crear una nueva notificaci�n';
		$this->load->model(DIR_TES.'/notificacion_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('titulo', 'Titulo', 'trim|xss_clean|required|max_length[60]');
		$this->form_validation->set_rules('contenido', 'Contenido', 'trim|xss_clean|required|max_length[300]');
		$this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'trim|required');
		$this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'trim|required');
		$this->form_validation->set_rules('id_arr_asu', 'Reportar a tabletas', 'required');
		//$arrItems = $this->grupo_model->getAll();
		//$data['items'][0] = '-- Seleccione una opción --';
// 		foreach ($arrGrupos as $grupo) 
// 		{
// 			$data['grupos'][$grupo->id] = $grupo->nombre;
// 		}
		
		if ($this->form_validation->run() === FALSE)
		{
	 		$this->template->write_view('content',DIR_TES.'/notificacion/insert', $data);
	 		$this->template->render();
		}
		else
		{
			try {
				$this->Notificacion_model->setTitulo(strtoupper($this->input->post('titulo')));
				$this->Notificacion_model->setContenido($this->input->post('contenido'));
				$this->Notificacion_model->setFechaInicio(date('Y-m-d', strtotime($this->input->post('fecha_inicio'))));
				$this->Notificacion_model->setFechaFin(date('Y-m-d', strtotime($this->input->post('fecha_fin'))));
				$this->Notificacion_model->setIdsTabletas($this->input->post('id_arr_asu'));
				$this->Notificacion_model->insert();
				$this->session->set_flashdata('msgResult', 'Registro agregado exitosamente');
				Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Notificaci�n agregada: '.strtoupper($this->input->post('titulo')));
				redirect(DIR_TES.'/notificacion','refresh');
			}
			catch (Exception $e){
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_TES.'/notificacion/insert', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * 1) Prepara el formulario para la modificación de una notificaci�n existente
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @param		int 		$id 	id de la notificaci�n a modificar
	 * @return 		void
	 */
	public function update($id)
	{
		if (empty($this->Notificacion_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Modificar notificaci�n';
 		$this->load->helper('form');
 		$this->load->library('form_validation');
		$this->form_validation->set_rules('titulo', 'Titulo', 'trim|xss_clean|required|max_length[60]');
		$this->form_validation->set_rules('contenido', 'Contenido', 'trim|xss_clean|required|max_length[300]');
		$this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'trim|required');
		$this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'trim|required');
		$this->form_validation->set_rules('id_arr_asu', 'Reportar a tabletas', 'required');

		$notification = $this->Notificacion_model->getById($id, true)[0];
		$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
		$descripciones = $this->ArbolSegmentacion_model->getDescripcionById(explode(',',$notification->id_arr_asu), 0);
		for($i = 0; $i < count($descripciones); $i++)
			$notification->tabletas[$i] = $descripciones[$i]->descripcion;
		$data['notificacion_item'] = $notification;

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_TES.'/notificacion/update', $data);
			$this->template->render();
		}
		else
		{
			try {
				$this->Notificacion_model->setId($id);
				$this->Notificacion_model->setTitulo(strtoupper($this->input->post('titulo')));
				$this->Notificacion_model->setContenido($this->input->post('contenido'));
				$this->Notificacion_model->setFechaInicio(date('Y-m-d', strtotime($this->input->post('fecha_inicio'))));
				$this->Notificacion_model->setFechaFin(date('Y-m-d', strtotime($this->input->post('fecha_fin'))));
				$this->Notificacion_model->setIdsTabletas($this->input->post('id_arr_asu'));
				$this->Notificacion_model->update();
				$this->session->set_flashdata('msgResult', 'Registro actualizado exitosamente');
				Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Notificaci�n actualizada: '.$id);
				redirect(DIR_TES.'/notificacion','refresh');
			}
			catch (Exception $e){
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_TES.'/notificacion/update', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * Solicita la eliminación de la notificaci�n recibida
	 *
	 * @access		public
	 * @param		int 		$id 	id de notificaci�n a eliminar
	 * @return 		void
	 */
	public function delete($id)
	{
		try {
			if (empty($this->Notificacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->Notificacion_model->setId($id);
			$this->Notificacion_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Notificaci�n eliminada: '.$id);
		}
		catch (Exception $e){
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
	    redirect(DIR_TES.'/notificacion','refresh');
	}
	
}