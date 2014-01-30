<?php
/**
 * Controlador Accion
 * 
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Geovanni
 * @created    2013-09-26
 */
class Accion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Accion_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}

	/**
	 *Acción por default del controlador, carga la lista
	 *de acciones disponibles y una lista de opciones
	 *@param int $pag Numero de registro para el paginador
	 *
	 *@return void
	 */
	public function index($pag = 0)
	{
		if (empty($this->Accion_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
                $this->load->library('pagination');
                $this->load->helper('form');

                //Configuracion para la paginacion
                $configPag['base_url']   ='/'. DIR_SIIGS.'/accion/index/';
                $configPag['first_link'] = 'Primero';
                $configPag['last_link']  = '&Uacute;ltimo';
                $configPag['total_rows'] = $this->Accion_model->getNumRows();
                $configPag['uri_segment'] = '4';
                $configPag['per_page']   = 20;

                $this->pagination->initialize($configPag);
                $this->Accion_model->setOffset($pag);
                $this->Accion_model->setRows($configPag['per_page']); 
                
		try
		{

			$data['title'] = 'Lista de acciones disponibles';
			$data['acciones'] = $this->Accion_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
                        $data['clsResult'] = $this->session->flashdata('clsResult');
                        $data['pag'] = $pag;
		}
		catch (Exception $e)
		{
                        $data['clsResult'] = "error";
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/accion/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar información de una acción específica, obtiene el objeto
	 *acción por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Accion_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = "Detalles de la acción";
			$data['accion_item'] = $this->Accion_model->getById($id);
		}
		catch (Exception $e)
		{
                        $data['clsResult'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/accion/view', $data);
                $this->template->write('menu','',true);
 		$this->template->write('sala_prensa','',true);
		$this->template->render();
	}

	/**
	 *Acción para preparar la inserción de nuevas acciones , realiza la validación
	 *del formulario del lado cliente
	 *
	 *@return void
	 */
	public function insert()
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		$error = false;
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Crear una nueva acción';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[30]');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('metodo', 'Método', 'trim|xss_clean|required|max_length[30]');
                //$this->form_validation->set_message('alpha','Los campos Nombre y Método solo pueden contener caracteres del alfabeto');

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/accion/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->Accion_model->setNombre($this->input->post('nombre'));
				$this->Accion_model->setDescripcion($this->input->post('descripcion'));
				$this->Accion_model->setMetodo($this->input->post('metodo'));

				$this->Accion_model->insert();
			}
			catch (Exception $e)
			{
                                $data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/accion/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
                                $this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/accion/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualización de una acción ya existente,
	 *recibe un ID para obtener los valores de esa acción y mostrarlos
	 *en la vista update , realiza la validación del formulario del lado
	 *del cliente
	 *
	 * @param  int $id Este parámetro no puede ser nulo
	 * @return void
	 */
	public function update($id)
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar acción';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[30]');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('metodo', 'Método', 'trim|xss_clean|required|max_length[30]');

		if ($this->form_validation->run() === FALSE)
		{
			try
			{
				$data['accion_item'] = $this->Accion_model->getById($id);
			}
			catch (Exception $e)
			{
                            	$data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			}

			$this->template->write_view('content',DIR_SIIGS.'/accion/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->Accion_model->setNombre($this->input->post('nombre'));
				$this->Accion_model->setDescripcion($this->input->post('descripcion'));
				$this->Accion_model->setMetodo($this->input->post('metodo'));
				$this->Accion_model->setId($this->input->post('id'));

				$this->Accion_model->update();
			}
			catch (Exception $e)
			{
				try
				{
					$data['accion_item'] = $this->Accion_model->getById($id);
				}
				catch (Exception $e)
				{
                                        $data['clsResult'] = 'error';
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				}
                                $data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/accion/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
                            $this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
                            $this->session->set_flashdata('clsResult', 'success');
                            redirect(DIR_SIIGS.'/accion','refresh');
			}
		}
	}

	/**
	 *
	 *Acción para eliminar una acción, recibe el id de la acción a eliminar
	 *
	 * @param  int $id Este parámetro no puede ser nulo
	 * @return void
	 */
	public function delete($id)
	{
		try
		{
			if (empty($this->Accion_model))
                            return false;
                        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
                        show_error('', 403, 'Acceso denegado');

			$this->load->helper('url');
			$this->Accion_model->setId($id);
			$this->Accion_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
                        $this->session->set_flashdata('clsResult', 'success');
		}
		catch(Exception $e)
		{
                        $this->session->set_flashdata('clsResult', 'error');
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/accion','refresh');
	}
}
