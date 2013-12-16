<?php
/**
 * Controlador Permiso
 *
 * @package		SIIGS
 * @subpackage	Controlador
 * @author     	Rogelio
 * @created		2013-10-01
 */
class Permiso extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->model(DIR_SIIGS.'/Permiso_model');
		}
		catch(Exception $e){
			$this->template->write('content', $e->getMessage());
			$this->template->render();
		}
	}

	/**
	 * 1) Visualiza los entornos existentes para su selección
	 * 2) Al seleccionar un entorno se obtienen los controladores_x_accion existentes y se indica sobre cuales
	 * 	  el grupo tiene permisos asignados
	 * 3) Elimina los permisos asignados al grupo anteriormente e inserta los asignados recientemente
	 *
	 * @access		public
	 * @param		int 		$id 	id del grupo del cual se obtendrán (y actualizar si aplica) los permisos asignados
	 * @return 		void
	 */
	public function index($id)
	{
		try 
		{
			if (empty($this->Permiso_model))
				return false;
			$this->load->helper('url');
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->load->model(DIR_SIIGS.'/accion_model');
			$this->load->model(DIR_SIIGS.'/entorno_model');
			
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['title'] = 'Lista de Permisos disponibles';
			$data['actions'] = $this->accion_model->getAll();
			$arrEntornos = $this->entorno_model->getAll();
			$data['entornos'][-1] = '-- Seleccione una opción --';
			foreach ($arrEntornos as $entorno)
			{
				$data['entornos'][$entorno->id] = $entorno->nombre;
			}
			$this->form_validation->set_rules('id_entorno', 'Entorno', 'required|is_natural_no_zero');
			$this->form_validation->set_message('is_natural_no_zero', 'Debe seleccionar un entorno válido');
			$data['id_grupo'] = $id;
			if ($this->form_validation->run() === FALSE)
			{
				$this->template->write_view('content',DIR_SIIGS.'/permiso/index', $data);
				$this->template->render();
				return;
			}
			else
			{
				// se eliminan los permisos actuales
				if ($this->Permiso_model->deletePermissions($this->input->post('id_entorno'), $id))
				{
					// si hay permisos seleccionados se guardan
					if ($this->input->post('permisos'))
					{
						$i = 0;
						$data = array();
						foreach ($this->input->post('permisos') as $permiso)
						{
							$data[$i] = array(
									'id_grupo' => $id ,
									'id_controlador_accion' => $permiso
							);
							$i++;
						}
						$this->Permiso_model->insertBatch($data);
					}
					$this->session->set_flashdata('msgResult', 'Registro actualizado exitosamente');
					redirect(DIR_SIIGS.'/grupo','refresh');
				}
			}
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$this->template->write_view('content',DIR_SIIGS.'/permiso/index', $data);
			$this->template->render();
		}
	}
}