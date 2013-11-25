<?php
/**
 * Controlador Usuario
 *
 * @author     	Rogelio
 * @created		2013-09-25
 */
class Usuario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->helper('url');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}

	/**
	 * Ofrece el inicio de sesión 
	 *
	 * @access		public
	 * @return 		void
	 */
	public function login()
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			$data['title'] = 'Inicio de sesión';
			$this->load->helper('form');
			$this->load->helper('url');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'trim|required');
			$this->form_validation->set_rules('clave', 'Clave', 'trim|required|md5');
				
			if ($this->form_validation->run() === FALSE)
			{
				$this->template->write_view('content',DIR_SIIGS.'/usuario/login', $data);
				$this->template->render();
				return;
			}
			else
			{
				$rowUser = $this->Usuario_model->authenticate($this->input->post('nombre_usuario'), $this->input->post('clave'));
				if ($rowUser)
				{
					if (!$rowUser->activo)
						$data['msgResult'] = 'La cuenta de usuario proporcionada se encuentra inactiva.';
					else
					{
						// almacena en session las variables necesarias
						$this->session->set_userdata(USERNAME, strtoupper($rowUser->nombre_usuario));
						$this->session->set_userdata(USER_LOGGED, $rowUser->id);
						$this->session->set_userdata(GROUP_ID, strtoupper($rowUser->id_grupo));
						Bitacora_model::insert(DIR_SIIGS.'::'.__CLASS__.'::index', 'Sesion iniciada: '.strtoupper($rowUser->nombre_usuario));
						// redirige a la url de donde provino o a la predeterminada del sistema
						if (!$this->session->userdata(REDIRECT_TO))
						{
							$this->session->set_flashdata('msgResult', 'Inicio de sesión exitoso');
							redirect(DIR_SIIGS.'/usuario','refresh'); // aca se debe poner la pagina HOME
						}
						else
							redirect($this->session->userdata(REDIRECT_TO, 'refresh'));
					}
				}
				else
					$data['msgResult'] = 'Nombre de usuario o clave incorrecta.';
			}
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		$this->template->write_view('content',DIR_SIIGS.'/usuario/login', $data);
		$this->template->render();
	
	}

	/**
	 * Termina la sesión 
	 *
	 * @access		public
	 * @return 		void
	 */
	public function logout()
	{
		$this->load->helper('url');
		if ($this->session->userdata(USERNAME))
			Bitacora_model::insert(DIR_SIIGS.'::'.__CLASS__.'::index', 'Sesion finalizada: '.$this->session->userdata(USERNAME));
		// destruye la sesión y redirige al login
		$this->session->sess_destroy();
		redirect(DIR_SIIGS.'/usuario/login','refresh');
	}
	
	/**
	 * 1) Visualiza los usuarios existentes para su interacción CRUD
	 * 2) En caso de detectar un texto a buscar se filtran los usuarios existentes acorde a la búsqueda
	 *
	 * @access		public
	 * @param		int		$pag	número de página a visualizar (paginación)
	 * @return 		void
	 */
	public function index($pag = 0)
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Catálogo de Usuarios';
			$this->load->helper('form');
			$this->load->library('pagination');
			
			$data['pag'] = $pag;
			$data['msgResult'] = $this->session->flashdata('msgResult');
			
			// Configuración para el Paginador
			$configPag['base_url']   = '/'.DIR_SIIGS.'/usuario/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['uri_segment'] = '4';
			$configPag['total_rows'] = $this->Usuario_model->getNumRows($this->input->post('busqueda'));
			$configPag['per_page']   = 20;
			$this->pagination->initialize($configPag);
			if ($this->input->post('busqueda'))
				$data['users'] = $this->Usuario_model->getOnlyActives($this->input->post('busqueda'), FALSE, $configPag['per_page'], $pag);
			else 
				$data['users'] = $this->Usuario_model->getOnlyActives('', FALSE, $configPag['per_page'], $pag);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
 		$this->template->write_view('content',DIR_SIIGS.'/usuario/index', $data);
 		$this->template->render();

	}

	/**
	 * Visualiza los datos del usuario recibido
	 *
	 * @access		public
	 * @param		int 		$id 	id del usuario a visualizar
	 * @return 		void
	 */
	public function view($id)
	{
		try {
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Ver detalles de usuario';
			$data['user_item'] = $this->Usuario_model->getById($id, true);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_SIIGS.'/usuario/view', $data);
 		$this->template->render();
	}

	/**
	 * 1) Prepara el formulario para la inserción de un usuario nuevo
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @return 		void
	 */
	public  function insert()
	{
		if (empty($this->Usuario_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Crear un nuevo usuario';
		$this->load->model(DIR_SIIGS.'/grupo_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_grupo', 'Grupo', 'is_natural_no_zero');
		$this->form_validation->set_message('is_natural_no_zero', 'Debe seleccionar un grupo válido');
		$this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'trim|alpha|required|min_length[5]|max_length[15]|callback__ifUserExists');
		$this->form_validation->set_rules('clave', 'Clave', 'trim|required|min_length[5]|max_length[12]|matches[repiteclave]|md5');
		$this->form_validation->set_rules('repiteclave', 'Repetir Clave', 'trim|required');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[40]');
		$this->form_validation->set_rules('apellido_paterno', 'Apellido Paterno', 'trim|required|max_length[25]');
		$this->form_validation->set_rules('apellido_materno', 'Apellido Materno', 'trim|max_length[25]');
		$this->form_validation->set_rules('correo', 'Email', 'trim|required|valid_email|max_length[50]');
		$arrGrupos = $this->grupo_model->getAll();
		$data['grupos'][0] = '-- Seleccione una opción --';
		foreach ($arrGrupos as $grupo) 
		{
			$data['grupos'][$grupo->id] = $grupo->nombre;
		}
		
		if ($this->form_validation->run() === FALSE)
		{
	 		$this->template->write_view('content',DIR_SIIGS.'/usuario/insert', $data);
	 		$this->template->render();
		}
		else
		{
			try {
				$this->Usuario_model->setNombreUsuario(strtoupper($this->input->post('nombre_usuario')));
				$this->Usuario_model->setClave($this->input->post('clave'));
				$this->Usuario_model->setNombre($this->input->post('nombre'));
				$this->Usuario_model->setApellidoPaterno($this->input->post('apellido_paterno'));
				$this->Usuario_model->setApellidoMaterno($this->input->post('apellido_materno'));
				$this->Usuario_model->setCorreo($this->input->post('correo'));
				$this->Usuario_model->setActivo(true);
				$this->Usuario_model->setIdGrupo($this->input->post('id_grupo'));
				$this->Usuario_model->insert();
				$this->session->set_flashdata('msgResult', 'Registro agregado exitosamente');
				Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario agregado: '.strtoupper($this->input->post('nombre_usuario')));
				redirect(DIR_SIIGS.'/usuario','refresh');
			}
			catch (Exception $e){
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/usuario/insert', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * 1) Prepara el formulario para la modificación de un usuario existente
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @param		int 		$id 	id del usuario a modificar
	 * @return 		void
	 */
	public function update($id)
	{
		if (empty($this->Usuario_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Modificar usuario';
		$this->load->model(DIR_SIIGS.'/grupo_model');
 		$this->load->helper('form');
 		$this->load->library('form_validation');
 		$this->form_validation->set_rules('id_grupo', 'Grupo', 'is_natural_no_zero');
 		$this->form_validation->set_message('is_natural_no_zero', 'Debe seleccionar un grupo válido');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[40]');
		$this->form_validation->set_rules('apellido_paterno', 'Apellido Paterno', 'trim|required|max_length[25]');
		$this->form_validation->set_rules('apellido_materno', 'Apellido Materno', 'trim|max_length[25]');
		$this->form_validation->set_rules('correo', 'Email', 'trim|required|valid_email|max_length[50]');
		$arrGrupos = $this->grupo_model->getAll();
		$data['grupos'][0] = '-- Seleccione una opcion --';
		foreach ($arrGrupos as $grupo) 
		{
			$data['grupos'][$grupo->id] = $grupo->nombre;
		}
		$data['user_item'] = $this->Usuario_model->getById($id);
				
		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/usuario/update', $data);
			$this->template->render();
		}
		else
		{
			try {
				$this->Usuario_model->setId($this->input->post('id'));
				$this->Usuario_model->setNombre($this->input->post('nombre'));
				$this->Usuario_model->setApellidoPaterno($this->input->post('apellido_paterno'));
				$this->Usuario_model->setApellidoMaterno($this->input->post('apellido_materno'));
				$this->Usuario_model->setCorreo($this->input->post('correo'));
				$this->Usuario_model->setActivo($this->input->post('activo'));
				$this->Usuario_model->setIdGrupo($this->input->post('id_grupo'));
				$this->Usuario_model->update();
				$this->session->set_flashdata('msgResult', 'Registro actualizado exitosamente');
				Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario actualizado: '.$this->input->post('id'));
				redirect(DIR_SIIGS.'/usuario','refresh');
			}
			catch (Exception $e){
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/usuario/update', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * Solicita la eliminación del usuario recibido
	 *
	 * @access		public
	 * @param		int 		$id 	id del usuario a eliminar
	 * @return 		void
	 */
	public function delete($id)
	{
		try {
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->Usuario_model->setId($id);
			$this->Usuario_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario eliminado: '.$id);
		}
		catch (Exception $e){
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
	    redirect(DIR_SIIGS.'/usuario','refresh');
	}
	
	/**
	 * Callback para validar que un nombre de usuario no se duplique
	 *
	 * @access		public
	 * @param		string		$username	nombre de usuario a validar
	 * @return 		boolean					false si el nombre de usuario ya existe, true si el nombre de usuario está disponible
	 */
	public function _ifUserExists($username) 
	{
		if (empty($this->Usuario_model))
			return false;
		$is_exist = null;
		try {
			$is_exist = $this->Usuario_model->getByUsername($username);
		}
		catch(Exception $e){
		}
		if ($is_exist) 
		{
			$this->form_validation->set_message(
					'_ifUserExists', 'El nombre de usuario seleccionado ya existe, intente con otro.'
			);
			return false;
		} 
		else 
		{
			if (!$this->Usuario_model->getMsgError())
				return true;
			else{
				$this->form_validation->set_message(
						'_ifUserExists', $this->Usuario_model->getMsgError()
				);
				return false;
			}
		}
	}
}