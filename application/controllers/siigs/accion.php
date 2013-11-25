<?php
/**
 * Controlador Accion
 *
 * @author     Geovanni
 * @created    2013-09-26
 */
class Accion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
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
	 *No recibe par�metros
	 *
	 *@return void
	 */
	public function index()
	{
		if (empty($this->Accion_model))
			return false;

		try
		{

			$data['title'] = 'Lista de acciones disponibles';
			$data['acciones'] = $this->Accion_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/accion/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar de una accion específica, obtiene el objeto
	 *accion por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Accion_model))
			return false;

		try
		{
			$data['title'] = "Detalles de la acción";
			$data['accion_item'] = $this->Accion_model->getById($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/accion/view', $data);
		$this->template->render();
	}

	/**
	 *Acción para preparar la insercion de nuevas acciones , realiza la validacion
	 *del formulario del lado cliente
	 *
	 *@return void
	 */
	public function insert()
	{
		$error = false;
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Crear una nueva acción';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|alpha|max_length[30]');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('metodo', 'Método', 'trim|xss_clean|required|max_length[30]');

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
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/accion/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				redirect(DIR_SIIGS.'/accion/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualizacion de una accion ya existente,
	 *recibe un ID para obtener los valores de esa accion y mostrarlos
	 *en la vista update , realiza la validacion del formulario del lado
	 *del cliente
	 *
	 * @param  int $id
	 * @return void
	 */
	public function update($id)
	{
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar acción';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|alpha|max_length[30]');
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
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/accion/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				redirect(DIR_SIIGS.'/accion','refresh');
			}
		}
	}

	/**
	 *
	 *Acción para eliminar una accion, recibe el id de la accion a eliminar
	 *
	 * @param  int $id
	 * @return void
	 */
	public function delete($id)
	{
		try
		{
			if (empty($this->Accion_model))
				return false;

			$this->load->helper('url');
			$this->Accion_model->setId($id);
			$this->Accion_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/accion','refresh');
	}
}
