<?php
/**
 * Controlador Raiz_x_Catalogo
 *
 * @author     Geovanni
 * @created    2013-10-16
 */
class Catalogo_x_raiz extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Catalogo_x_raiz_model');
			$this->load->model(DIR_SIIGS.'/Catalogo_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}

	/**
	 *Acción para visualizar de una raiz_x_catalogo específica, obtiene el objeto
	 *raiz_x_catalogo por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Catalogo_x_raiz_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{
			$data['title'] = "Detalles del raiz x catálogo";
			$data['catalogo_item'] = $this->Catalogo_x_raiz_model->getById($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/catalogo_x_raiz/view', $data);
		$this->template->render();
	}

	/**
	 *Acción para preparar la insercion de nuevas raices para catálogos , realiza la validacion
	 *del formulario del lado cliente
	 *
	 *@return void
	 */
	public function insert($id = 0)
	{
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		$error = false;
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Agregar un nuevo catálogo al arbol';
		$this->form_validation->set_rules('tabla_catalogo', 'Catálogo', 'trim|xss_clean|required|');
		$this->form_validation->set_rules('columna_llave', 'Columna llave', 'trim|xss_clean|required');
		$this->form_validation->set_rules('columna_descripcion', 'Columna descripcion', 'trim|xss_clean|required');
		$this->form_validation->set_rules('grado', 'Grado de segmentación', 'trim|xss_clean|required');

		if ($this->form_validation->run() === FALSE && $id >0)
		{
			try
			{
				$data['nivel'] = $this->Catalogo_x_raiz_model->getNivel($id)->nivel;

				if ($data['nivel']>1)
				{
					$catalogo_padre = $this->Catalogo_x_raiz_model->getByNivel($data['nivel']-1)->nombre;
					$data['catalogo_padre'] = $catalogo_padre;
				}
				else
					$data['catalogo_padre'] = '';

				$catalogos = $this->Catalogo_model->getAll();
				$data['catalogos'][""] = 'Todos';
				foreach ($catalogos as $item) {
					$data['catalogos'][$item->nombre] = $item->nombre;
				}

				$data['id_raiz'] = $id;
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			}

			$this->template->write_view('content',DIR_SIIGS.'/catalogo_x_raiz/insert',$data);
			$this->template->render();
		}
		else if ($this->form_validation->run() === TRUE)
		{
			try
			{
				//var_dump($this->input->post());
				$this->load->helper('url');

				$this->Catalogo_x_raiz_model->setGrado($this->input->post('grado'));
				$this->Catalogo_x_raiz_model->setTablaCatalogo($this->input->post('tabla_catalogo'));
				$this->Catalogo_x_raiz_model->setColumnaLlave($this->input->post('columna_llave'));
				$this->Catalogo_x_raiz_model->setColumnaDescripcion($this->input->post('columna_descripcion'));
				$this->Catalogo_x_raiz_model->setIdRaiz($this->input->post('id_raiz'));

				$relaciones = $this->input->post('relaciones');

				$relacionpadre = array();
				$relacionhijo = array();
				for ($i = 1;$i<=$relaciones;$i++)
				{
					array_push($relacionpadre, $this->input->post('relacionpadre'.$i));
					array_push($relacionhijo, $this->input->post('relacionhijo'.$i));
				}
				//var_dump($relacionpadre);
				//var_dump($relacionhijo);

				$this->Catalogo_x_raiz_model->setRelacionPadre($relacionpadre);
				$this->Catalogo_x_raiz_model->setRelacionHijo($relacionhijo);


				//die();
				$this->Catalogo_x_raiz_model->insert();
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				//$this->template->write_view('content',DIR_SIIGS.'/catalogo_x_raiz/insert', $data);
				//$this->template->render();
				$error = true;
				var_dump(Errorlog_model::save($e->getMessage(), __METHOD__));
				die();
				$this->session->set_flashdata(Errorlog_model::save($e->getMessage(), __METHOD__));
				redirect(DIR_SIIGS.'/raiz/update/'.$this->input->post('id_raiz'),'refresh');
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				redirect(DIR_SIIGS.'/raiz/update/'.$this->input->post('id_raiz'),'refresh');
			}
		}

	}

	public function check($id)
	{
		try
		{
			$this->load->helper('form');
			if ($this->input->is_ajax_request())
			{
				$nivel = $this->Catalogo_x_raiz_model->getById($id);

				if (empty($nivel))
				{
					echo json_encode(array('false'));
					return;
				}

				if ($nivel->grado_segmentacion == 1)
				{
					echo json_encode(array('true'));
					return;
				}
				else
				{
					$inconsistencias = $this->Catalogo_x_raiz_model->check($id);
					if (count($inconsistencias) == 0)
					{
					echo json_encode(array('true'));
						return;
					}
					else
					{
						echo json_encode($inconsistencias);
					}
				}
			}
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			echo 'false'.$data['msgResult'];
		}
	}

	public function update($id)
	{
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
            
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar catálogo';
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
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
            
		try
		{
			if (empty($this->Catalogo_x_raiz_model))
				return false;
				
			$id_raiz = 	$this->Catalogo_x_raiz_model->getById($id)->id_raiz_arbol;
			//var_dump($id_raiz);
			//die();
			$this->load->helper('url');
			$this->Catalogo_x_raiz_model->setId($id);
			$this->Catalogo_x_raiz_model->delete();
			$this->session->set_flashdata('msgResult', 'Catálogo eliminado exitosamente');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/raiz/update/'.$id_raiz,'refresh');
	}
}
