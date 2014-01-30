<?php
/**
 * Controlador Raiz_x_Catalogo
 * 
 * @package    SIIGS
 * @subpackage Controlador
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
	 *Acción para visualizar información de una raiz_x_catalogo específica, obtiene el objeto
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
                        $data['clsResult']='error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/catalogo_x_raiz/view', $data);
		$this->template->render();
	}

	/**
	 *Acción para preparar la inserción de nuevas raices para catálogos , realiza la validación
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
		$this->form_validation->set_rules('columnas_descripcion', 'Descripción', 'trim|xss_clean|required');
		$this->form_validation->set_rules('grado', 'Grado de segmentación', 'trim|xss_clean|required');

		if ($this->form_validation->run() === FALSE && $id >0)
		{
			try
			{
				$data['nivel'] = $this->Catalogo_x_raiz_model->getNivel($id)->nivel;

				if ($data['nivel']>1)
				{
					$catalogo_padre = $this->Catalogo_x_raiz_model->getByNivel($id,$data['nivel']-1)->nombre;
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
                                $data['clsResult']='error';
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
				$this->Catalogo_x_raiz_model->setColumnaDescripcion($this->input->post('columnas_descripcion'));
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
                                $data['clsResult']='error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				//$this->template->write_view('content',DIR_SIIGS.'/catalogo_x_raiz/insert', $data);
				//$this->template->render();
				$error = true;
				//var_dump(Errorlog_model::save($e->getMessage(), __METHOD__));
				//die();
                                $this->session->set_flashdata('clsResult','error');
				$this->session->set_flashdata('msgResult',Errorlog_model::save($e->getMessage(), __METHOD__));
				redirect(DIR_SIIGS.'/raiz/update/'.$this->input->post('id_raiz'),'refresh');
			}

			if ($error == false)
			{
                                $this->session->set_flashdata('clsResult','success');
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				redirect(DIR_SIIGS.'/raiz/update/'.$this->input->post('id_raiz'),'refresh');
			}
		}

	}

        /**
         * Acción que sirve para revisar inconsistencias en el arbol de segmentacion
         * Recibe como parámetro el catálogo x raiz y revisa que todos los registros tengan
         * un correspondiente en el catálogo padre.
         * Solo se permite su acceso por medio de peticiones AJAX
         * 
         * @param type $id Id del catalogo_x_raiz
         * @return boolean
         */
	public function check($id)
	{
		if (!$this->input->is_ajax_request())
                show_error('', 403, 'Acceso denegado');
                
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
			//$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			echo 'false'.$data['msgResult'];
		}
	}
	
	/**
	 *
	 *Acción para eliminar un catálogo en el arbol, recibe el id del catálogo en la raiz a eliminar
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
                        $this->session->set_flashdata('clsResult','success');
			$this->session->set_flashdata('msgResult', 'Catálogo eliminado exitosamente');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
                        $this->session->set_flashdata('clsResult','error');
		}
		redirect(DIR_SIIGS.'/raiz/update/'.$id_raiz,'refresh');
	}
}
