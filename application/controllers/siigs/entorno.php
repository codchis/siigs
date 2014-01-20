<?php

/**
 * Controlador Entorno
 * 
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Geovanni
 * @created    2013-09-26
 */
class Entorno extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try
		{
            $this->load->helper('url');
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
	 *de entornos disponibles y una lista de opciones
	 *No recibe parámetros
	 *
	 *@return void
	 */
	public function index()
	{
		if (empty($this->Entorno_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = 'Lista de Entornos disponibles';
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
			$data['entornos'] = $this->Entorno_model->getAll();
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/entorno/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar de un entorno específico, obtiene el objeto
	 *entorno por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Entorno_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = "Detalles del entorno";
			$data['entorno_item'] = $this->Entorno_model->getById($id);
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/entorno/view', $data);
                $this->template->write('menu','',true);
 		$this->template->write('sala_prensa','',true);
		$this->template->render();
	}

	/**
	 *Acción para preparar la insercion de nuevos entornos , realiza la validacion
	 *del formulario del lado cliente y del lado servidor para evitar entornos duplicados
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

		$data['title'] = 'Crear un nuevo entorno';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[15]|callback__ExistEntorno');
		$this->form_validation->set_rules('ip', 'Ip', 'trim|xss_clean|required|min_length[7]|max_length[15]|valid_ip');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('hostname', 'Hostname', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('directorio', 'Directorio', 'trim|xss_clean|required|max_length[20]');

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/entorno/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->Entorno_model->setNombre($this->input->post('nombre'));
				$this->Entorno_model->setDescripcion($this->input->post('descripcion'));
				$this->Entorno_model->setIp($this->input->post('ip'));
				$this->Entorno_model->setHostname($this->input->post('hostname'));
				$this->Entorno_model->setDirectorio($this->input->post('directorio'));

				$this->Entorno_model->insert();
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/entorno/insert', $data);
				$this->template->render();
				$error = true;
			}
			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/entorno/index','refresh');
			}
		}
	}

	/**
	 *Acción para validar que no exista previamente el entorno a insertar
	 *(Esta acción no puede ser accedida desde el navegador)
	 *
	 * @param  string $nombre_entorno Revisa si este valor ya existe como un entorno
	 * @return boolean
	 */
	public function _ExistEntorno($nombre_entorno) {

		$exist = $this->Entorno_model->getByName($nombre_entorno);

		if ($exist)
		{
			$this->form_validation->set_message(
					'_ExistEntorno', 'Este entorno ya existe en la base de datos, intente con otro nombre.'
			);
			return false;
		} else
		{
			return true;
		}
	}

	/**
	 *Acción para validar que no exista previamente el entorno a actualizar
	 *esta acción revisa si el nombre a usar ya existe en la base excepto
	 *el mismo objeto a actualizar
	 *(Esta acción no puede ser accedida desde el navegador)
	 *
	 * @param  string $nombre_entorno Revisa si este valor ya existe como un entorno
	 * @return boolean
	 */
	public function _ExistEntornoUpdate($nombre_entorno) {

		$where = 'select * from sis_entorno where nombre = "'.
				$nombre_entorno.'" and id<>"'.
				$this->input->post('id').'"';

		$exist = $this->db->query($where);

		if ($exist->num_rows() > 0)
		{
			$this->form_validation->set_message(
					'_ExistEntornoUpdate', 'Este entorno ya existe en la base de datos, intente con otro nombre.'
			);
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 *Acción para preparar la actualizacion de un entorno ya existente,
	 *recibe un ID para obtener los valores de ese entorno y mostrarlos
	 *en la vista update , realiza la validacion del formulario del lado
	 *del cliente y del servidor para evitar datos duplicados
	 *
	 * @param  int $id
	 * @return void
	 */
	public function update($id)
	{
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		//Load helpers and libraries
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar entorno';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[15]|callback__ExistEntornoUpdate');
		$this->form_validation->set_rules('ip', 'Ip', 'trim|xss_clean|required|min_length[7]|max_length[15]|valid_ip');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('hostname', 'Hostname', 'trim|xss_clean|required|max_length[100]');
		$this->form_validation->set_rules('directorio', 'Directorio', 'trim|xss_clean|required|max_length[20]');
		if ($this->form_validation->run() === FALSE)
		{
			try
			{
				$data['entorno_item'] = $this->Entorno_model->getById($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
			}

			$this->template->write_view('content',DIR_SIIGS.'/entorno/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->Entorno_model->setNombre($this->input->post('nombre'));
				$this->Entorno_model->setDescripcion($this->input->post('descripcion'));
				$this->Entorno_model->setIp($this->input->post('ip'));
				$this->Entorno_model->setHostname($this->input->post('hostname'));
				$this->Entorno_model->setDirectorio($this->input->post('directorio'));
				$this->Entorno_model->setId($this->input->post('id'));

				$this->Entorno_model->update();
			}
			catch (Exception $e)
			{
				$data['title'] = 'Modificar entorno';
				try
				{
					$data['entorno_item'] = $this->Entorno_model->getById($id);
				}
				catch (Exception $e)
				{
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
					$data['clsResult'] = 'error';
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/entorno/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/entorno','refresh');
			}
		}
	}

	/**
	 *
	 *Acción para eliminar un entorno, recibe el id del entorno a eliminar
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
			if (empty($this->Entorno_model))
				return false;

			$this->load->helper('url');
			$this->Entorno_model->setId($id);
			$this->Entorno_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			$this->session->set_flashdata('clsResult', 'success');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
			$this->session->set_flashdata('clsResult', 'error');
		}
		redirect(DIR_SIIGS.'/entorno','refresh');
	}
}
