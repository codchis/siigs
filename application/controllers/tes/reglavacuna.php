<?php
/**
 * Controlador ReglaVacuna
 *
 * @author     Geovanni
 * @created    2013-12-09
 */
class ReglaVacuna extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_TES.'/ReglaVacuna_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}

	/**
	 *Acción por default del controlador, carga la lista
	 *de reglas de vacunas disponibles y una lista de opciones
	 *No recibe parámetros
	 *
	 *@return void
	 */
	public function index()
	{
		if (empty($this->ReglaVacuna_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{

			$data['title'] = 'Lista de reglas para vacunas';
			$data['reglas'] = $this->ReglaVacuna_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_TES.'/reglavacuna/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar de una regla específica, obtiene el objeto
	 *regla_vacuna por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->ReglaVacuna_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = "Detalles de la regla";
			$data['regla_item'] = $this->ReglaVacuna_model->getById($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_TES.'/reglavacuna/view', $data);
		$this->template->render();
	}

	/**
	 *Acción para preparar la insercion de nuevas reglas , realiza la validacion
	 *del formulario del lado cliente
	 *
	 *@return void
	 */
	public function insert()
	{
                if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		$error = false;
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Crear una nueva regla para vacuna';
		$this->form_validation->set_rules('aplicacion_inicio', 'Inicio aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('aplicacion_fin', 'Fin aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('id_vacuna', 'Vacuna', 'trim|xss_clean|required|is_natural_no_zero');
                $this->form_validation->set_rules('tipo_aplicacion', 'Tipo de aplicación', 'trim|xss_clean|required');

                $vacunas = $this->db->query("select id,descripcion from cns_vacuna where activo=1")->result();
                $data['vacunas'][0] = 'Elige una vacuna';
		foreach ($vacunas as $item) {
			$data['vacunas'][$item->id] = $item->descripcion;
		}
		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_TES.'/reglavacuna/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->ReglaVacuna_model->setIdVacuna($this->input->post('id_vacuna'));
				$this->ReglaVacuna_model->setIdVacunaPrevia($this->input->post('id_vacuna_previa'));
				if ($this->input->post('tipo_aplicacion') == 'nacimiento')
                                {
                                    $this->ReglaVacuna_model->setDiaInicioNacido($this->input->post('aplicacion_inicio'));
                                    $this->ReglaVacuna_model->setDiaFinNacido($this->input->post('aplicacion_fin'));
                                }
                                else
                                {
                                    $this->ReglaVacuna_model->setDiaInicioPrevia($this->input->post('aplicacion_inicio'));
                                    $this->ReglaVacuna_model->setDiaFinPrevia($this->input->post('aplicacion_fin'));                                    
                                }

				$this->ReglaVacuna_model->insert();
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_TES.'/reglavacuna/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				redirect(DIR_TES.'/reglavacuna/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualizacion de una regla ya existente,
	 *recibe un ID para obtener los valores de esa accion y mostrarlos
	 *en la vista update , realiza la validacion del formulario del lado
	 *del cliente
	 *
	 * @param  int $id
	 * @return void
	 */
	public function update($id)
	{
                if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar regla para vacuna';
		$this->form_validation->set_rules('aplicacion_inicio', 'Inicio aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('aplicacion_fin', 'Fin aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('id_vacuna', 'Vacuna', 'trim|xss_clean|required|is_natural_no_zero');
                $this->form_validation->set_rules('tipo_aplicacion', 'Tipo de aplicación', 'trim|xss_clean|required');

                $vacunas = $this->db->query("select id,descripcion from cns_vacuna where activo=1")->result();
                $data['vacunas'][0] = 'Elige una vacuna';
		foreach ($vacunas as $item) {
			$data['vacunas'][$item->id] = $item->descripcion;
		}
		if ($this->form_validation->run() === FALSE)
		{
			try
			{
				$data['regla_item'] = $this->ReglaVacuna_model->getById($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			}

			$this->template->write_view('content',DIR_TES.'/reglavacuna/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
                            $this->ReglaVacuna_model->setId($this->input->post('id'));
				$this->ReglaVacuna_model->setIdVacuna($this->input->post('id_vacuna'));
				$this->ReglaVacuna_model->setIdVacunaPrevia($this->input->post('id_vacuna_previa'));
				if ($this->input->post('tipo_aplicacion') == 'nacimiento')
                                {
                                    $this->ReglaVacuna_model->setDiaInicioNacido($this->input->post('aplicacion_inicio'));
                                    $this->ReglaVacuna_model->setDiaFinNacido($this->input->post('aplicacion_fin'));
                                }
                                else
                                {
                                    $this->ReglaVacuna_model->setDiaInicioPrevia($this->input->post('aplicacion_inicio'));
                                    $this->ReglaVacuna_model->setDiaFinPrevia($this->input->post('aplicacion_fin'));                                    
                                }

				$this->ReglaVacuna_model->update();
			}
			catch (Exception $e)
			{
				try
				{
					$data['regla_item'] = $this->ReglaVacuna_model->getById($id);
				}
				catch (Exception $e)
				{
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_TES.'/reglavacuna/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				redirect(DIR_TES.'/reglavacuna','refresh');
			}
		}
	}

	/**
	 *
	 *Acción para eliminar una regla, recibe el id de la regla a eliminar
	 *
	 * @param  int $id
	 * @return void
	 */
	public function delete($id)
	{
		try
		{
			if (empty($this->ReglaVacuna_model))
                            return false;
                        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
                        show_error('', 403, 'Acceso denegado');

			$this->load->helper('url');
			$this->ReglaVacuna_model->setId($id);
			$this->ReglaVacuna_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_TES.'/reglavacuna','refresh');
	}
}
