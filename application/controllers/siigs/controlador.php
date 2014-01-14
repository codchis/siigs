<?php
/**
 * Controlador Controlador
 * 
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Geovanni
 * @created    2013-09-26
 */
class Controlador extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Controlador_model');
			$this->load->model(DIR_SIIGS.'/Entorno_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}
	/**
	 *Acción por default del controlador, carga la lista
	 *de controladores disponibles y una lista de opciones
	 *Recibe un parametro en caso de filtrado por entornos
	 *
	 *@param int $id (Id del entorno)
	 *@return void
	 */
	public function index($pag = 0)
	{
		if (empty($this->Controlador_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$this->load->library('pagination');
			$this->load->helper('form');

			$id = $this->input->post('id_entorno') ? $this->input->post('id_entorno') : 0;

			//Configuracion para la paginacion
			$configPag['base_url']   ='/'. DIR_SIIGS.'/controlador/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['total_rows'] = $this->Controlador_model->getNumRows($id);
			$configPag['uri_segment'] = '4';
			$configPag['per_page']   = REGISTROS_PAGINADOR;

			$this->pagination->initialize($configPag);

            if (!$this->input->is_ajax_request())
			{
                $this->Controlador_model->setOffset($pag);
                $this->Controlador_model->setRows($configPag['per_page']);
			}

			$data['title'] = 'Lista de Controladores disponibles' .
					(($id) ? ' ('.$this->Entorno_model->getById($id)->nombre.')' : '' );
			$data['msgResult'] = $this->session->flashdata('msgResult');
                        $data['clsResult'] = $this->session->flashdata('clsResult');
			$data['id_entorno'] = ($id) ? $id : 0 ;
			$data['pag'] = $pag;

			if ($id == 0)
			{
				$data['controladores'] = $this->Controlador_model->getAll();
			}
			else
				$data['controladores'] = $this->Controlador_model->getByEntorno($id);

			if ($this->input->is_ajax_request())
			{
				echo json_encode($data["controladores"]);
				die();
			}

			$entornos = $this->Entorno_model->getAll();
			$data['entornos'][0] = 'Todos';
			foreach ($entornos as $item) {
				$data['entornos'][$item->id] = $item->nombre;
			}
		}
		catch (Exception $e)
		{
                        $data['clsResult'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		//Dibujar la vista en el navegador
		$this->template->write_view('content',DIR_SIIGS.'/controlador/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar de un controlador específico, obtiene el objeto
	 *controlador por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Controlador_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = "Detalles del controlador";
			$data['controlador_item'] = $this->Controlador_model->getById($id);
		}
		catch(Exception $e)
		{
                        $data['clsResult'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/controlador/view', $data);
		$this->template->render();
	}

	/**
	 *Acción para preparar la insercion de nuevos controladores , realiza la validacion
	 *del formulario del lado cliente
	 *
	 *@return void
	 */
	public function insert($id = FALSE)
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		$error = false;
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Crear un nuevo controlador';
		$this->form_validation->set_rules('id_entorno', 'Entorno', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[40]');
		$this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('clase', 'Clase', 'trim|xss_clean|required|max_length[30]');

		$data['id_entorno'] = ($id != FALSE) ? $id : $this->input->post('id_entorno');
		$entornos = $this->Entorno_model->getAll();
		$data['entornos'][''] = 'Elige un entorno';
		foreach ($entornos as $item) {
			$data['entornos'][$item->id] = $item->nombre;
		}

		if ($this->form_validation->run() === FALSE)
		{

			$this->template->write_view('content',DIR_SIIGS.'/controlador/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->Controlador_model->setNombre($this->input->post('nombre'));
				$this->Controlador_model->setDescripcion($this->input->post('descripcion'));
				$this->Controlador_model->setClase($this->input->post('clase'));
				$this->Controlador_model->setIdEntorno($this->input->post('id_entorno'));

				$this->Controlador_model->insert();
			}
			catch (Exception $e)
			{
                                $data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/controlador/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
                                $this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/controlador/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualizacion de un controlador ya existente,
	 *recibe un ID para obtener los valores de ese controlador y mostrarlos
	 *en la vista update , realiza la validacion del formulario del lado
	 *del cliente
	 *
	 * @param  int $id
	 * @return void
	 */
	public function update($id)
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		$this->load->helper('form');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar controlador';
                $this->form_validation->set_rules('id_entorno', 'Entorno', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[40]');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('clase', 'Clase', 'trim|xss_clean|required|max_length[30]');

		if ($this->form_validation->run() === FALSE)
		{
			try
			{
				$data["controlador_item"] = $this->Controlador_model->getById($id);
                                
                                $entornos = $this->Entorno_model->getAll();
                                $data['entornos'][''] = 'Elige un entorno';
                                foreach ($entornos as $item) {
                                        $data['entornos'][$item->id] = $item->nombre;
                                        }
                        }
                        catch (Exception $e)
                        {
                                $data['clsResult'] = 'error';
                                $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
                        }

			$this->template->write_view('content',DIR_SIIGS.'/controlador/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->Controlador_model->setNombre($this->input->post('nombre'));
				$this->Controlador_model->setDescripcion($this->input->post('descripcion'));
				$this->Controlador_model->setClase($this->input->post('clase'));
                                $this->Controlador_model->setIdEntorno($this->input->post('id_entorno'));
				$this->Controlador_model->setId($this->input->post('id'));

				$this->Controlador_model->update();
			}
			catch (Exception $e)
			{
				$data['title'] = 'Modificar controlador';
				try
				{
					$data['controlador_item'] = $this->Controlador_model->getById($id);
                                        
                                        $entornos = $this->Entorno_model->getAll();
                                        $data['entornos'][0] = 'Elige un entorno';
                                        foreach ($entornos as $item) {
                                                $data['entornos'][$item->id] = $item->nombre;
                                                }
				}
				catch (Exception $e)
				{
                                        $data['clsResult'] = 'error';
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				}

                                $data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/controlador/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
                                $this->session->set_flashdata('clsResult', 'success');
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				redirect(DIR_SIIGS.'/controlador','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualizacion de acciones asignadas a un controlador,
	 *recibe un ID para obtener las acciones asignadas a ese controlador y mostrarlos
	 *en la vista update
	 *
	 * @param  int $id
	 * @return void
	 */
	public function accion($id)
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Acciones asignadas al controlador';
		$data['id_controlador'] = $id;
		$this->form_validation->set_rules('acciones', 'Acciones', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			try
			{
				$data["controlador_acciones"] = $this->Controlador_model->getAcciones($id);
			}
			catch (Exception $e)
			{
                                $data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __CLASS__.'::update');
			}

			$this->template->write_view('content',DIR_SIIGS.'/controlador/accion', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');
				$error = false;

                                $this->session->set_flashdata('clsResult', 'success');
				$this->session->set_flashdata('msgResult', 'Se modificaron correctamente las acciones permitidas al controlador');

				$this->Controlador_model->setAccion($this->input->post('acciones'));
				$this->Controlador_model->setId($this->input->post('id'));
				$this->Controlador_model->accionesUpdate();
			}
			catch (Exception $e)
			{
                            	$data['msgResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __CLASS__.'::update');
				$error = true;
			}

			if ($error == true)
			{
				$this->template->write_view('content',DIR_SIIGS.'/controlador/accion', $data);
				$this->template->render();
			}
			else
				redirect(DIR_SIIGS.'/controlador','refresh');
		}
	}

	/**
	 *
	 *Acción para eliminar un controlador, recibe el id del controlador a eliminar
	 *
	 * @param  int $id
	 * @return void
	 */
	public function delete($id)
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		try
		{
			if (empty($this->Controlador_model))
				return false;

			$this->load->helper('url');
			$this->Controlador_model->setId($id);
			$this->Controlador_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
                        $this->session->set_flashdata('clsResult', 'success');
		}
		catch(Exception $e)
		{
                        $this->session->set_flashdata('clsResult', 'error');
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/controlador','refresh');
	}


	/**
	 *Acción para servir un array de objetos con los permisos asignados a
	 *un entorno y grupo determinados, esta accion solo es accedida por peticiones
	 *AJAX y devuelve un objeto JSON
         *Solo se permite su acceso por medio de peticiones AJAX
	 *
	 * @param  int $entorno
	 * @param  int $grupo
	 * @return Object JSON
	 */
	public function getGroupPermissions($entorno, $grupo)
	{
		try {
		if ($this->input->is_ajax_request())
		{
			$data['permisos'] = $this->Controlador_model->getPermisos($entorno , $grupo);
			echo json_encode($data['permisos']);
			exit;
		}
		else echo 'Acceso denegado';
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
}
