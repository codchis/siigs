<?php
/**
 * Controlador Raiz
 *
 * @author     Geovanni
 * @created    2013-10-07
 */

class Raiz extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Raiz_model');
			$this->load->model(DIR_SIIGS.'/Catalogo_model');
			$this->load->model(DIR_SIIGS.'/Catalogo_x_raiz_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}

        public function pruebas()
        {
            //$this->Raiz_model->getTree(1);
            $this->Raiz_model->getChildrenFromNivel(1,1,5);
           // $this->Raiz_model->getChildrenFromId(781,true);
        }
        
	/**
	 *Acción por default del controlador, carga la lista
	 *de Raices disponibles y una lista de opciones
	 *No recibe parámetros
	 *
	 *@return void
	 */
	public function index()
	{
		if (empty($this->Raiz_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		try
		{

			$data['title'] = 'Lista de raices disponibles';
			$data['raices'] = $this->Raiz_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/raiz/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar de una raiz específica, obtiene el objeto
	 *raiz por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Raiz_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		try
		{
			$data['title'] = "Detalles de la raiz";
			$data['raiz_item'] = $this->Raiz_model->getById($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		try
		{
			$data['catalogos'] = $this->Catalogo_x_raiz_model->getByArbol($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/raiz/view', $data);
		$this->template->render();
	}

	/**
	 *Acci�n para preparar la insercion de nuevas acciones , realiza la validacion
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

		$data['title'] = 'Crear una nueva raiz';
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[45]');

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/raiz/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->Raiz_model->setDescripcion($this->input->post('descripcion'));

				$this->Raiz_model->insert();
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/raiz/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				redirect(DIR_SIIGS.'/raiz/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualizacion de una raiz ya existente,
	 *recibe un ID para obtener los valores de esa raiz y mostrarlos
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
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar raiz';
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[45]');

		if ($this->form_validation->run() === FALSE)
		{
			//obtiene el modelo
			try
			{
				$data['raiz_item'] = $this->Raiz_model->getById($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			}
			//obtiene los catalogos relacionados a la raiz
			try
			{
				$data['catalogos'] = $this->Catalogo_x_raiz_model->getByArbol($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			}
			//revisa si ya existen los registros de esta raiz en el arbol de segmentacion unica
			try
			{
				$data['existe'] = $this->Raiz_model->ExistInArbol($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			}


			$this->template->write_view('content',DIR_SIIGS.'/raiz/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->Raiz_model->setDescripcion($this->input->post('descripcion'));
				$this->Raiz_model->setId($this->input->post('id'));

				$this->Raiz_model->update();
			}
			catch (Exception $e)
			{
				try
				{
					$data['raiz_item'] = $this->Raiz_model->getById($id);
				}
				catch (Exception $e)
				{
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/raiz/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				redirect(DIR_SIIGS.'/raiz','refresh');
			}
		}
	}

	 /**
	 *
	 *Acción para crear o actualizar el ASU a partir de una raiz
	 *
	 * @param  int $id
	 * @return void
	 */
	public function createasu($id)
	{
            //if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
	    //	show_error('', 403, 'Acceso denegado');
            
		try
		{
			$catalogos = $this->Catalogo_x_raiz_model->getByArbol($id);
			
			$this->db->where("id_raiz",$id);
			$this->db->delete('asu_arbol_segmentacion');
			
			foreach ($catalogos as $item) {
				
				$iditem = $item->id;
				$tabla = $item->tabla_catalogo;
				$nivel = $item->grado_segmentacion;
				$llave = $item->nombre_columna_llave;
				$descripcion = $item->nombre_columna_descripcion;
				
				if ($nivel > 1)
				{
					$consulta = "select ".$tabla.".".$llave." as llave, ";
					$consulta .= $tabla.".".$descripcion." as descripcion, ";
					$consulta .= "asu_arbol_segmentacion.id as padre ";
					$consulta .= " from ".$tabla;
				
					$padre = $this->Catalogo_x_raiz_model->getByNivel($nivel-1);
					$relaciones = $this->Catalogo_x_raiz_model->getRelations($iditem);
					
					$consulta .= " join ".$padre->nombre." on 1=1";
					foreach ($relaciones as $relacion)
					{
						$consulta .= " and ".$tabla.".".$relacion->columna_hijo." = ".$padre->nombre.".".$relacion->columna_padre;
					}
					$consulta .= " join asu_arbol_segmentacion on grado_segmentacion=".($nivel-1);
					$consulta .= " and asu_arbol_segmentacion.id_raiz=".$id;
					$consulta .= " and asu_arbol_segmentacion.id_tabla_original=".$padre->nombre.".".$padre->llave;
					
					$filas = $this->db->query($consulta);
					$datosdump = array();
					foreach ($filas->result() as $value) {
						
						array_push($datosdump, array(
						'grado_segmentacion' => $nivel,
						'id_raiz'=> $id,
						'id_padre' => $value->padre,
						'id_tabla_original' => $value->llave,
						'orden' => '0',
						'visible' => 'true',
						'descripcion' => $value->descripcion
						));
					}
				if ($this->db->insert_batch('asu_arbol_segmentacion',$datosdump) != 1)
					{
					echo 'false';
					}
				}
				else 
				{
					$consulta = "select ".$llave." as llave, ";
					$consulta .= $descripcion." as descripcion ";
					$consulta .= " from ".$tabla;
					
					$filas = $this->db->query($consulta);
					$datosdump = array();
					foreach ($filas->result() as $key => $value) {
						
						array_push($datosdump, array(
						'grado_segmentacion' => $nivel,
						'id_raiz'=> $id,
						'id_padre' => '0',
						'id_tabla_original' => $value->llave,
						'orden' => '0',
						'visible' => 'true',
						'descripcion' => $value->descripcion
						));
					}
					if ($this->db->insert_batch('asu_arbol_segmentacion',$datosdump) != 1)
					{
					echo 'false';
					}
				}
				echo "true";
				//var_dump($consulta."<br/>");
			}
			
		}
		catch(Exception $e)
		{
			echo Errorlog_model::save($e->getMessage(), __METHOD__);
		}
	}	
	
	/**
	 *
	 *Acción para eliminar una raiz, recibe el id de la raiz a eliminar
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
			if (empty($this->Raiz_model))
				return false;

			$this->load->helper('url');
			$this->Raiz_model->setId($id);
			$this->Raiz_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/raiz','refresh');
	}
}
