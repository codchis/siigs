<?php
/**
 * Controlador Notificaci髇
 *
 * @author     	Rogelio
 * @created		2013-11-26
 */
class Notificacion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->helper('url');
			$this->load->model(DIR_TES.'/Notificacion_model');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}

	/**
	 * 1) Visualiza las notificaciones existentes para su interacci贸n CRUD
	 * 2) En caso de detectar un texto a buscar se filtran las notificaciones existentes acorde a la b煤squeda
	 *
	 * @access		public
	 * @param		int		$pag	n煤mero de p谩gina a visualizar (paginaci贸n)
	 * @return 		void
	 */
	public function index($pag = 0)
	{
		try{
			if (empty($this->Notificacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Cat谩logo de Notificaciones';
			$this->load->helper('form');
			$this->load->library('pagination');
			
			$data['pag'] = $pag;
			$data['msgResult'] = $this->session->flashdata('msgResult');
			
			if($this->input->post('filtrar')) {
				// Eliminar el campo hidden y el boton
				unset($_POST['filtrar'], $_POST['btnFiltrar']);
				$filtros = array_filter($this->input->post());

				if(!empty($filtros)) {
					foreach ($filtros as $campo => $valor) {
						switch ($campo) {
							case 'fechaIni':
								$this->Notificacion_model->addFilter('fecha_inicio', '>=', $valor);
								break;
							case 'fechaFin':
								$this->Notificacion_model->addFilter('fecha_inicio', '<=', $valor.' 23:59:59');
								break;
						}
					}
				}
			
				$data = array_merge($data, $filtros);
			}
				
			// Configuraci贸n para el Paginador
			$configPag['base_url']   = '/'.DIR_TES.'/notificacion/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['uri_segment'] = '4';
			$configPag['total_rows'] = $this->Notificacion_model->getNumRows($this->input->post('busqueda'));
			$configPag['per_page']   = 20;
			$this->pagination->initialize($configPag);
			$data['notifications'] = $this->Notificacion_model->getAll($this->input->post('busqueda'), $configPag['per_page'], $pag);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_TES.'/notificacion/index', $data);
 		$this->template->render();

	}

	/**
	 * Visualiza los datos de la notificaci髇 recibida
	 *
	 * @access		public
	 * @param		int 		$id 	id de notificaci髇 a visualizar
	 * @return 		void
	 */
	public function view($id)
	{
		try {
			if (empty($this->Notificacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Ver detalles de la notificaci髇';
			$notifications = $this->Notificacion_model->getById($id, true);
			$data['notificacion_item'] = $notifications[0];
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_TES.'/notificacion/view', $data);
 		$this->template->render();
	}

	/**
	 * 1) Prepara el formulario para la inserci贸n de una notificaci髇 nueva
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
		$data['title'] = 'Crear una nueva notificaci髇';
		$this->load->model(DIR_TES.'/notificacion_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|max_length[60]');
		$this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'trim|required');
		$this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'trim|required');
		$this->form_validation->set_rules('id_arr_asu', 'Reportar a tabletas', 'required');
		//$arrItems = $this->grupo_model->getAll();
		//$data['items'][0] = '-- Seleccione una opci贸n --';
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
				$this->Notificacion_model->setFechaInicio($this->input->post('fecha_inicio'));
				$this->Notificacion_model->setFechaFin($this->input->post('fecha_fin'));
				$this->Notificacion_model->setIdsTabletas($this->input->post('id_arr_asu'));
				$this->Notificacion_model->insert();
				$this->session->set_flashdata('msgResult', 'Registro agregado exitosamente');
				Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Notificaci髇 agregada: '.strtoupper($this->input->post('titulo')));
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
	 * 1) Prepara el formulario para la modificaci贸n de una notificaci髇 existente
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @param		int 		$id 	id de la notificaci髇 a modificar
	 * @return 		void
	 */
	public function update($id)
	{
		if (empty($this->Notificacion_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Modificar notificaci髇';
 		$this->load->helper('form');
 		$this->load->library('form_validation');
		$this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|max_length[60]');
		$this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'trim|required');
		$this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'trim|required');
		$this->form_validation->set_rules('id_arr_asu', 'Reportar a tabletas', 'required');
 		
		$data['notificacion_item'] = $this->Notificacion_model->getById($id)[0];
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
				$this->Notificacion_model->setFechaInicio($this->input->post('fecha_inicio'));
				$this->Notificacion_model->setFechaFin($this->input->post('fecha_fin'));
				$this->Notificacion_model->setIdsTabletas($this->input->post('id_arr_asu'));
				$this->Notificacion_model->update();
				$this->session->set_flashdata('msgResult', 'Registro actualizado exitosamente');
				Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Notificaci髇 actualizada: '.$id);
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
	 * Solicita la eliminaci贸n de la notificaci髇 recibida
	 *
	 * @access		public
	 * @param		int 		$id 	id de notificaci髇 a eliminar
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
			Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Notificaci髇 eliminada: '.$id);
		}
		catch (Exception $e){
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
	    redirect(DIR_TES.'/notificacion','refresh');
	}
	
}