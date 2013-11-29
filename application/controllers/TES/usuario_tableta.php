<?php

/**
 * Controlador Usuario_tableta
 *
 * @author     Pascual
 * @created    2013-11-27
 */
class Usuario_tableta extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        if(!$this->db->conn_id) {
            $this->template->write('content', 'Error no se puede conectar a la Base de Datos');
            $this->template->render();
        }
        
        $this->load->helper('url');
        $this->load->model(DIR_TES.'/Tableta_model');
        $this->load->model(DIR_TES.'/Usuario_tableta_model');
    }

    /**
     * Lista todos los registros de usuarios correspondientes a una tableta en especifico
     * permite eliminar un conjunto de registro o un elemento individual,
     * muestra enlaces para actualizar y ver detalles de un elemento especifico
     *
     * @access public
     * @param  int    $idTableta ID de la Tableta
     * @return void
     */
    public function index($idTableta)
    {
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Tableta_model))
            return false;
        
        if(!isset($this->Usuario_tableta_model))
            return false;

        try {
            $this->load->helper(array('form', 'formatFecha'));
            $this->load->model(DIR_SIIGS.'/Grupo_model');
            
            $data = array();

            $data['msgResult'] = $this->session->flashdata('msgResult');
            $data['title'] = 'Usuarios asignados a la tableta';
            $data['tableta'] = $this->Tableta_model->getById($idTableta);
            $data['grupos'] = $this->Grupo_model->getAll();
            $data['usuarios'] = array();
        
            $registroEliminar = $this->input->post('registroEliminar');

            if( !empty($registroEliminar) ) {
                $this->Usuario_tableta_model->delete($registroEliminar, $idTableta);
                $data['msgResult'] = 'Registros Eliminados exitosamente';
            }
            
            $usuariosTableta = $this->Usuario_tableta_model->getUsuariosByTableta($idTableta);
            
            if(!empty($usuariosTableta)) {
                $this->load->model(DIR_TES.'/Usuario_model');
                
                foreach ($usuariosTableta as $usuario) {
                    $infoUsuario = $this->Usuario_model->getById($usuario->id_usuario, true);
                    $data['usuarios'][$usuario->id_usuario] = array(
                                'id'      => $usuario->id_usuario,
                                'usuario' => $infoUsuario->nombre_usuario,
                                'nombre'  => $infoUsuario->nombre.' '.$infoUsuario->apellido_paterno.' '.$infoUsuario->apellido_materno,
                                'grupo'   => $infoUsuario->Grupo
                            );
                }
            }
            
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_TES.'/usuario_tableta/index', $data);
		$this->template->render();
    }

    /**
     * Muestra el formulario para crear un nuevo registro en la tableta,
     * las variables se obtienen por el metodo POST
     *
     * @access public
     * @return void
     */
    public function insert($id_tableta)
    {
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Usuario_tableta_model))
            return false;

        try {
            $id_usuario = $this->input->post('id_usuario');
            
            if(!empty($id_usuario)) {
                $this->Usuario_tableta_model->insert($id_usuario, $id_tableta);

                $this->session->set_flashdata('msgResult', 'Registro guardado exitosamente');

                Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Registro creado: Tableta = '.$id_tableta.' - Usuario = '.$id_usuario);
                
            } else {
                $this->session->set_flashdata('msgResult', 'Error: debe proporcionar un usuario');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('msgResult', 'Error: Debe proporcionar un usuario valido');
        }
        
        redirect(DIR_TES.'/usuario_tableta/index/'.$id_tableta, 'refresh');
    }

    /**
     * Eliminar un usuario de una tableta especifica
     *
     * @access public
     * @param  int    $$id_usuario ID del usuario a eliminar
     * @param  int    $id_tableta  ID de la tableta que tiene asignada el usuario especificado
     * @return void
     */
    public function delete($id_usuario, $id_tableta)
    {
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }

        if(!isset($this->Tableta_model))
            return false;
        
        if(!isset($this->Usuario_tableta_model))
            return false;
        
        try {
            $this->Usuario_tableta_model->delete($id_usuario, $id_tableta);
            $this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
        } catch (Exception $e) {
            $this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
        }

        Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Registro eliminado: '.$id_usuario);

        redirect(DIR_TES.'/usuario_tableta/index/'.$id_tableta, 'refresh');
        die();
    }
    
}
?>