<?php
/**
 * Controlador Grupo
 *
 * @author     	Rogelio
 * @created		2013-09-25
 */
class Grupo extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Grupo_model');
		}
		catch(Exception $e){
			$this->template->write('content', $e->getMessage());
			$this->template->render();
		}
	}

	/**
	 * 1) Visualiza los grupos existentes para su interacción CRUD
	 * 2) En caso de detectar un texto a buscar se filtran los grupos existentes acorde a la búsqueda
	 *
	 * @access		public
	 * @param		int		$pag	número de página a visualizar (paginación)
	 * @return 		void
	 */
	public function index($pag = 0)
	{
		try {
			if (empty($this->Grupo_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			// validar permisos (buscar dinamicamente las acciones?)
			$data['permisos'] = Usuario_model::checkCredentials(DIR_SIIGS.'::Permiso::index', current_url());
			$data['view'] = Usuario_model::checkCredentials(DIR_SIIGS.'::Grupo::view', current_url());
			$data['update'] = Usuario_model::checkCredentials(DIR_SIIGS.'::Grupo::update', current_url());
			$data['delete'] = Usuario_model::checkCredentials(DIR_SIIGS.'::Grupo::delete', current_url());
			$data['title'] = 'Catálogo de Grupos';
			$this->load->helper('form');
			$this->load->library('pagination');
				
			$data['pag'] = $pag;				
			$data['msgResult'] = $this->session->flashdata('msgResult');

			// Configuración para el Paginador
			$configPag['base_url']   = '/'.DIR_SIIGS.'/grupo/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['uri_segment'] = '4';
			$configPag['total_rows'] = $this->Grupo_model->getNumRows($this->input->post('busqueda'));
			$configPag['per_page']   = 20;
			$this->pagination->initialize($configPag);
			if ($this->input->post('busqueda'))
				$data['groups'] = $this->Grupo_model->getAll($this->input->post('busqueda'), $configPag['per_page'], $pag);
			else 
				$data['groups'] = $this->Grupo_model->getAll('', $configPag['per_page'], $pag);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_SIIGS.'/grupo/index', $data);
 		$this->template->render();
	}

	/**
	 * Visualiza los datos del grupo recibido
	 *
	 * @access		public
	 * @param		int 		$id 	id del grupo a visualizar
	 * @return 		void
	 */
	public function view($id)
	{
		try {
			if (empty($this->Grupo_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Ver detalles de grupo';
			$data['group_item'] = $this->Grupo_model->getById($id);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);		
		}
 		$this->template->write_view('content',DIR_SIIGS.'/grupo/view', $data);
 		$this->template->render();
	}
	
	/**
	 * 1) Prepara el formulario para la inserción de un grupo nuevo
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @return 		void
	 */
	public  function insert(){
		if (empty($this->Grupo_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Crear un nuevo grupo';
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|alpha_numeric|required|callback__ifGroupExists|max_length[20]');
		$this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|max_length[100]');
		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/grupo/insert', $data);
			$this->template->render();
		}
		else
		{
			try {
				$this->Grupo_model->setNombre($this->input->post('nombre'));
				$this->Grupo_model->setDescripcion($this->input->post('descripcion'));
				$this->Grupo_model->insert();
				$this->session->set_flashdata('msgResult', 'Registro agregado exitosamente');
				Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Grupo agregado: '.strtoupper($this->input->post('nombre')));
				redirect(DIR_SIIGS.'/grupo','refresh');
			}
			catch(Exception $e){
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/grupo/insert', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * 1) Prepara el formulario para la modificación de un grupo existente
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @param		int 		$id 	id del grupo a modificar
	 * @return 		void
	 */
	public function update($id)
	{
		if (empty($this->Grupo_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Modificar grupo';
 		$this->load->helper('form');
 		$this->load->library('form_validation');
		$this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|max_length[100]');
		$data['group_item'] = $this->Grupo_model->getById($id);
		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/grupo/update', $data);
			$this->template->render();
		}
		else
		{
			try {
				$this->Grupo_model->setId($id);
				$this->Grupo_model->setDescripcion($this->input->post('descripcion'));
				$this->Grupo_model->update();
				$this->session->set_flashdata('msgResult', 'Registro actualizado exitosamente');
				Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Grupo actualizado: '.$id);
				redirect(DIR_SIIGS.'/grupo','refresh');			
			}
			catch(Exception $e){
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/grupo/update', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * Solicita la eliminación del grupo recibido
	 *
	 * @access		public
	 * @param		int 		$id 	id del grupo a eliminar
	 * @return 		void
	 */
	public function delete($id)
	{
		try {
			if (empty($this->Grupo_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->Grupo_model->setId($id);
			$this->Grupo_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Grupo eliminado: '.$id);
		}
		catch(Exception $e){
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/grupo','refresh');
	}

	/**
	 * Callback para validar que un nombre de grupo no se duplique
	 *
	 * @access		public
	 * @param		string		$name		nombre del grupo a validar
	 * @return 		boolean					false si el nombre del grupo ya existe, true si el nombre del grupo está disponible
	 */
	public function _ifGroupExists($name) {
		if (empty($this->Grupo_model))
			return false;
		$is_exist = null;
		try{
			$is_exist = $this->Grupo_model->getByName($name);
		}
		catch(Exception $e){
		}
		if ($is_exist) {
			$this->form_validation->set_message(
					'_ifGroupExists', 'El nombre de grupo seleccionado ya existe, intente con otro.'
			);
			return false;
		} 
		else 
		{
			if (!$this->Grupo_model->getMsgError())
				return true;
			else{
				$this->form_validation->set_message(
						'_ifGroupExists', $this->Grupo_model->getMsgError()
				);
				return false;
			}
		}
	}
}