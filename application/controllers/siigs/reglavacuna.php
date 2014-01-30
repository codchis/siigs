<?php
/**
 * Controlador ReglaVacuna
 *
 * @package    SIIGS
 * @subpackage Controlador
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
			$this->load->model(DIR_SIIGS.'/ReglaVacuna_model');
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
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{

			$data['title'] = 'Lista de reglas para vacunas';
			$data['reglas'] = $this->ReglaVacuna_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/reglavacuna/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar información de una regla específica, obtiene el objeto
	 *regla_vacuna por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->ReglaVacuna_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = "Detalles de la regla";
			$data['regla_item'] = $this->ReglaVacuna_model->getById($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/reglavacuna/view', $data);
                $this->template->write('menu','',true);
 		$this->template->write('sala_prensa','',true);
		$this->template->render();
	}

	/**
	 *Acción para preparar la insercion de nuevas reglas , realiza la validación
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

		$data['title'] = 'Crear una nueva regla para vacuna';
		$this->form_validation->set_rules('aplicacion_inicio', 'Inicio aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('aplicacion_fin', 'Fin aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('id_vacuna', 'Vacuna', 'trim|xss_clean|required|is_natural_no_zero');
                $this->form_validation->set_rules('id_via_vacuna', 'Vía Vacuna', 'trim|xss_clean|required|is_natural_no_zero');
                $this->form_validation->set_rules('dosis', 'Dosis', 'trim|xss_clean|decimal');
                //$this->form_validation->set_rules('tipo_aplicacion', 'Tipo de aplicación', 'trim|xss_clean|required');

                $vacunas = $this->db->query("select id,descripcion from cns_vacuna where activo=1")->result();
                $data['vacunas'][''] = 'Elige una vacuna';
		foreach ($vacunas as $item) {
			$data['vacunas'][$item->id] = $item->descripcion;
		}
                
                $vias_vacuna = $this->db->query("select id,descripcion from cns_via_vacuna where activo=1")->result();
                $data['vias_vacuna'][''] = 'Elige una vía de vacuna';
		foreach ($vias_vacuna as $item) {
			$data['vias_vacuna'][$item->id] = $item->descripcion;
		}
                
                $orden = $this->db->query("SELECT a.id FROM (SELECT 1 AS id UNION SELECT 2 AS id UNION SELECT 3 AS id UNION SELECT 4 AS id UNION SELECT 5 AS id UNION SELECT 6 AS id UNION SELECT 7 AS id
 UNION SELECT 8 AS id UNION SELECT 9 AS id UNION SELECT 10 AS id UNION SELECT 11 AS id UNION SELECT 12 AS id UNION SELECT 13 AS id UNION SELECT 14 AS id UNION SELECT 15 AS id
  UNION SELECT 16 AS id UNION SELECT 17 AS id UNION SELECT 18 AS id UNION SELECT 19 AS id UNION SELECT 20 AS id
   UNION SELECT 21 AS id UNION SELECT 22 AS id UNION SELECT 23 AS id UNION SELECT 24 AS id UNION SELECT 25 AS id UNION SELECT 26 AS id
    UNION SELECT 27 AS id UNION SELECT 28 AS id UNION SELECT 29 AS id UNION SELECT 30 AS id) AS a WHERE a.id NOT IN (SELECT orden_esq_com FROM cns_regla_vacuna WHERE orden_esq_com IS NOT NULL)")->result();
                $data['orden'][''] = 'Orden de la vacuna';
		foreach ($orden as $item) {
			$data['orden'][$item->id] = $item->id;
		}
                
                $alergias = $this->db->query("select id,descripcion from cns_alergia where activo=1")->result();
		$data['alergias'] = $alergias;
                
		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/reglavacuna/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->ReglaVacuna_model->setIdVacuna($this->input->post('id_vacuna'));
				$this->ReglaVacuna_model->setIdVacunaPrevia($this->input->post('id_vacuna_previa'));
				//if ($this->input->post('tipo_aplicacion') == 'nacimiento')
                                //{
                                    $this->ReglaVacuna_model->setDiaInicioNacido($this->input->post('aplicacion_inicio'));
                                    $this->ReglaVacuna_model->setDiaFinNacido($this->input->post('aplicacion_fin'));
                                //}
//                                else
//                                {
//                                    $this->ReglaVacuna_model->setDiaInicioPrevia($this->input->post('aplicacion_inicio'));
//                                    $this->ReglaVacuna_model->setDiaFinPrevia($this->input->post('aplicacion_fin'));                                    
//                                }
                                $this->ReglaVacuna_model->setIdViaVacuna($this->input->post('id_via_vacuna'));
                                $this->ReglaVacuna_model->setDosis($this->input->post('dosis'));
                                $this->ReglaVacuna_model->setRegion($this->input->post('region'));
                                $this->ReglaVacuna_model->setAlergias($this->input->post('alergias'));
                                $this->ReglaVacuna_model->setEsqComp($this->input->post('esq_com'));
                                
                                if ($this->input->post('esq_com') == true)
                                {
                                    $this->ReglaVacuna_model->setOrdenEsqComp($this->input->post('orden_esq_com'));
                                }

				$this->ReglaVacuna_model->insert();
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/reglavacuna/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/reglavacuna/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualización de una regla ya existente,
	 *recibe un ID para obtener los valores de esa regla y mostrarlos
	 *en la vista update , realiza la validación del formulario del lado
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
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar regla para vacuna';
		$this->form_validation->set_rules('aplicacion_inicio', 'Inicio aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('aplicacion_fin', 'Fin aplicación', 'trim|xss_clean|required|is_natural_no_zero');
		$this->form_validation->set_rules('id_vacuna', 'Vacuna', 'trim|xss_clean|required|is_natural_no_zero');
                //$this->form_validation->set_rules('tipo_aplicacion', 'Tipo de aplicación', 'trim|xss_clean|required');
                $this->form_validation->set_rules('id_via_vacuna', 'Vía Vacuna', 'trim|xss_clean|required|is_natural_no_zero');
                $this->form_validation->set_rules('dosis', 'Dosis', 'trim|xss_clean|decimal');

                $vacunas = $this->db->query("select id,descripcion from cns_vacuna where activo=1")->result();
                $data['vacunas'][''] = 'Elige una vacuna';
		foreach ($vacunas as $item) {
			$data['vacunas'][$item->id] = $item->descripcion;
                        
                
                $vias_vacuna = $this->db->query("select id,descripcion from cns_via_vacuna where activo=1")->result();
                $data['vias_vacuna'][''] = 'Elige una vía de vacuna';
		foreach ($vias_vacuna as $item) {
			$data['vias_vacuna'][$item->id] = $item->descripcion;
		}
                
                $orden = $this->db->query("SELECT a.id FROM (SELECT 1 AS id UNION SELECT 2 AS id UNION SELECT 3 AS id UNION SELECT 4 AS id UNION SELECT 5 AS id UNION SELECT 6 AS id UNION SELECT 7 AS id
 UNION SELECT 8 AS id UNION SELECT 9 AS id UNION SELECT 10 AS id UNION SELECT 11 AS id UNION SELECT 12 AS id UNION SELECT 13 AS id UNION SELECT 14 AS id UNION SELECT 15 AS id
  UNION SELECT 16 AS id UNION SELECT 17 AS id UNION SELECT 18 AS id UNION SELECT 19 AS id UNION SELECT 20 AS id
   UNION SELECT 21 AS id UNION SELECT 22 AS id UNION SELECT 23 AS id UNION SELECT 24 AS id UNION SELECT 25 AS id UNION SELECT 26 AS id
    UNION SELECT 27 AS id UNION SELECT 28 AS id UNION SELECT 29 AS id UNION SELECT 30 AS id) AS a WHERE a.id NOT IN (SELECT orden_esq_com FROM cns_regla_vacuna WHERE orden_esq_com IS NOT NULL and id <> ".$id." )")->result();
                $data['orden'][''] = 'Orden de la vacuna';
		foreach ($orden as $item) {
			$data['orden'][$item->id] = $item->id;
		}
                
                $alergias = $this->db->query("select id,descripcion from cns_alergia where activo=1")->result();
		$data['alergias'] = $alergias;
                
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
				$data['clsResult'] = 'error';
			}

			$this->template->write_view('content',DIR_SIIGS.'/reglavacuna/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
                            $this->ReglaVacuna_model->setId($this->input->post('id'));
                            $this->ReglaVacuna_model->setIdVacuna($this->input->post('id_vacuna'));
                            $this->ReglaVacuna_model->setIdVacunaPrevia($this->input->post('id_vacuna_previa'));
//				if ($this->input->post('tipo_aplicacion') == 'nacimiento')
//                                {
                                $this->ReglaVacuna_model->setDiaInicioNacido($this->input->post('aplicacion_inicio'));
                                $this->ReglaVacuna_model->setDiaFinNacido($this->input->post('aplicacion_fin'));
//                                }
//                                else
//                                {
//                                    $this->ReglaVacuna_model->setDiaInicioPrevia($this->input->post('aplicacion_inicio'));
//                                    $this->ReglaVacuna_model->setDiaFinPrevia($this->input->post('aplicacion_fin'));                                    
//                                }

                            $this->ReglaVacuna_model->setIdViaVacuna($this->input->post('id_via_vacuna'));
                            $this->ReglaVacuna_model->setDosis($this->input->post('dosis'));
                            $this->ReglaVacuna_model->setRegion($this->input->post('region'));
                            $this->ReglaVacuna_model->setAlergias($this->input->post('alergias'));
                            $this->ReglaVacuna_model->setEsqComp(($this->input->post('esq_com') == false) ? false : true);

                            if (!($this->input->post('esq_com') == false))
                            {
                                $this->ReglaVacuna_model->setOrdenEsqComp($this->input->post('orden_esq_com'));
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
					$data['clsResult'] = 'error';
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/reglavacuna/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/reglavacuna','refresh');
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
                        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
                        show_error('', 403, 'Acceso denegado');

			$this->load->helper('url');
			$this->ReglaVacuna_model->setId($id);
			$this->ReglaVacuna_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			$this->session->set_flashdata('clsResult', 'success');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
			$this->session->set_flashdata('clsResult', 'error');
		}
		redirect(DIR_SIIGS.'/reglavacuna','refresh');
	}
}
