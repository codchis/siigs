<?php

/**
 * Controlador Bitacora
 *
 * @author     Pascual
 * @created    2013-09-26
 */
class Bitacora extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        if(!$this->db->conn_id) {
            $this->template->write('content', 'Error no se puede conectar a la Base de Datos');
            $this->template->render();
        }
        
        $this->load->helper('url');
    }

    /**
     * Lista todos los registros de la bitacora, con su correspondiente paginación
     * permite hacer filtrados por Usuario, Entorno, Controlador, Acción y Fecha,
     * permite eliminar un conjunto de registro o un elemento individual,
     * muestra enlaces para actualizar y ver detalles de un elemento especifico
     *
     * @access public
     * @param  int    $pag Establece el desplazamiento del primer registro a devolver
     * @return void
     */
    public function index($pag = 0)
    {
        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Bitacora_model))
            return false;

        try {
            $this->load->library('pagination');
            $this->load->helper('form');
            $this->load->model( array(DIR_SIIGS.'/Usuario_model', DIR_SIIGS.'/Entorno_model', DIR_SIIGS.'/Controlador_model', DIR_SIIGS.'/Accion_model') );

            $filtros = array();
            $data = array();

            $data['pag'] = $pag;
            $data['msgResult'] = $this->session->flashdata('msgResult');
            $data['title'] = 'Bitacora';
            
            /*** Inicia Campos para Filtros ***/
            $usuarios = $this->Usuario_model->getOnlyActives();
            $data['usuarios'][0] = 'Todos';
            foreach ($usuarios as $user) {
                $data['usuarios'][$user->id] = $user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno;
            }

            $entornos = $this->Entorno_model->getAll();
            $data['entornos'][0] = 'Todos';
            foreach ($entornos as $ent) {
                $data['entornos'][$ent->id] = $ent->nombre;
            }

            $data['controladores'][0] = 'Todos';
            
            if($this->input->post('entorno')) {
                $controladores = $this->Controlador_model->getByEntorno($this->input->post('entorno'));
                foreach ($controladores as $contr) {
                    $data['controladores'][$contr->id] = $contr->nombre;
                }
            }

            $data['acciones'][0] = 'Todos';
            $acciones = $this->Accion_model->getAll();
            foreach ($acciones as $acci) {
                $data['acciones'][$acci->id] = $acci->nombre;
            }
            /*** Fin Campos para Filtros ***/

            $registroEliminar = $this->input->post('registroEliminar');

            if( !empty($registroEliminar) ) {
                $this->Bitacora_model->delete($registroEliminar);
                $data['msgResult'] = 'Registros Eliminados exitosamente';
            }

            if($this->input->post('filtrar')) {
                // Eliminar el campo hidden y el boton
                unset($_POST['filtrar'], $_POST['btnFiltrar']);
                $filtros = array_filter($this->input->post());

                if(!empty($filtros)) {
                    foreach ($filtros as $campo => $valor) {
                        switch ($campo) {
                            case 'usuario':
                                $this->Bitacora_model->addFilter('id_usuario', '=', $valor);
                                break;
                            case 'fechaIni':
                                $this->Bitacora_model->addFilter('fecha_hora', '>=', $valor);
                                break;
                            case 'fechaFin':
                                $this->Bitacora_model->addFilter('fecha_hora', '<=', $valor.' 23:59:59');
                                break;
                            case 'entorno':
                                $this->Bitacora_model->addFilter('id_entorno', '=', $valor);
                                break;
                            case 'controlador':
                                $this->Bitacora_model->addFilter('id_controlador', '=', $valor);
                                break;
                            case 'accion':
                                $this->Bitacora_model->addFilter('id_accion', '=', $valor);
                                break;
                        }
                    }
                }

                $data = array_merge($data, $filtros);
            }
            // Configuración para el Paginador
            $configPag['base_url']    = site_url().DIR_SIIGS.'/bitacora/index/';
            $configPag['first_link']  = 'Primero';
            $configPag['last_link']   = '&Uacute;ltimo';
            $configPag['uri_segment'] = 4;
            $configPag['total_rows']  = $this->Bitacora_model->getNumRows();
            $configPag['per_page']    = REGISTROS_PAGINADOR;

            $this->pagination->initialize($configPag);

            $data['registros'] = $this->Bitacora_model->getAll($configPag['per_page'], $pag);
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/bitacora/index', $data);
		$this->template->render();
    }

    /**
     * Muestra el formulario para crear un nuevo registro en la bitacora,
     * las variables se obtienen por el metodo POST
     *
     * @access public
     * @return void
     */
    public function insert()
    {
        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Bitacora_model))
            return false;

        try {
            $this->load->helper('form');
            $this->load->model( array(DIR_SIIGS.'/Usuario_model', DIR_SIIGS.'/Entorno_model', DIR_SIIGS.'/Controlador_model', DIR_SIIGS.'/Accion_model') );

            $usuarios = $this->Usuario_model->getOnlyActives();
            $data['usuarios'][0] = 'Elegir';
            $data['title'] = 'Crear nuevo registro';
            foreach ($usuarios as $user) {
                $data['usuarios'][$user->id] = $user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno;
            }

            $entornos = $this->Entorno_model->getAll();
            $data['entornos'][0] = 'Elegir';
            foreach ($entornos as $ent) {
                $data['entornos'][$ent->id] = $ent->nombre;
            }

            $data['controladores'][0] = 'Elegir';
            
            if($this->input->post('entorno')) {
                $controladores = $this->Controlador_model->getByEntorno($this->input->post('entorno'));
                foreach ($controladores as $contr) {
                    $data['controladores'][$contr->id] = $contr->nombre;
                }
            }
            $data['acciones'][0] = 'Elegir';
            $acciones = $this->Accion_model->getAll();
            foreach ($acciones as $acci) {
                $data['acciones'][$acci->id] = $acci->nombre;
            }

            $datos = $this->input->post();
            $data['title'] = 'Crear un nuevo registro';
            $data['msgResult'] = $this->session->flashdata('msgResult');

            if(!empty($datos)) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('usuario', 'Usuario', 'is_natural_no_zero|required|callback_validateExistUsuario');
                $this->form_validation->set_rules('parametros', 'Parametros', 'trim|xss_clean|max_length[200]|required');
                $this->form_validation->set_rules('controlador', 'Controlador', 'is_natural_no_zero|required');
                $this->form_validation->set_rules('accion', 'Acción', 'is_natural_no_zero|required');

                if ($this->form_validation->run() === true) {
                    if(Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, $datos['parametros'])) {
                        $this->session->set_flashdata('msgResult', 'Registro guardado exitosamente');
                        redirect(DIR_SIIGS.'/bitacora/', 'refresh');
                        die();
                    } else {
                        $data['msgResult'] = 'Ocurrió un error al intentar guardar el registro';
                    }
                }
            }
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/bitacora/insert', $data);
		$this->template->render();
    }

    /**
     * Muestra el formulario con los datos del registro especificado por el id,
     * para actualizar sus datos
     *
     * @access public
     * @param  int    $id ID del elemento a actualizar
     * @return void
     */
    public function update($id)
    {
        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Bitacora_model))
            return false;

        try {
            $this->load->helper('form');
            $this->load->model(DIR_SIIGS.'/Usuario_model');

            $usuarios = $this->Usuario_model->getOnlyActives();
            $data['usuarios'][0] = 'Elegir';
            foreach ($usuarios as $user) {
                $data['usuarios'][$user->id] = $user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno;
            }

            $data['title'] = 'Actualizar datos del registro';
            $datos = $this->input->post();
            $data['registro'] = $this->Bitacora_model->getById($id);

            if( empty($data['registro']) )
                $data['msgResult'] = 'El registro solicitado no existe';

            if(!empty($datos)) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('usuario', 'Usuario', 'is_natural_no_zero|required|callback_validateExistUsuario');
                $this->form_validation->set_rules('parametros', 'Parametros', 'trim|xss_clean|max_length[200]|required');
                $this->form_validation->set_rules('controlador', 'Controlador', 'is_natural_no_zero|required');
                $this->form_validation->set_rules('accion', 'Acción', 'is_natural_no_zero|required');

                $this->Bitacora_model->setId_usuario($datos['usuario']);
                $this->Bitacora_model->setParametros($datos['parametros']);
                $this->Bitacora_model->setId_controlador($datos['controlador']);
                $this->Bitacora_model->setId_accion($datos['accion']);

                if ($this->form_validation->run() === true) {
                    $this->Bitacora_model->update($id);

                    $this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
                    redirect(DIR_SIIGS.'/bitacora/', 'refresh');
                    die();
                }
            }
        } catch (Exception $e) { 
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/bitacora/update', $data);
		$this->template->render();
    }

    /**
     * Muestra los datos del registro especificado por el id
     *
     * @access public
     * @param  int    $id ID del elemento a actualizar
     * @return void
     */
    public function view($id)
    {
        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Bitacora_model))
            return false;

        try {
            $data['registro'] = $this->Bitacora_model->getById($id);
            $data['title'] = 'Datos del registro';

            if( empty($data['registro']) ) {
                $data['msgResult'] = 'ERROR: El registro solicitado no existe';
            }
        } catch (Exception $e) { 
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/bitacora/view', $data);
		$this->template->render();
    }

    public function delete($id)
    {
        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Bitacora_model))
            return false;
        
        try {
            $this->Bitacora_model->delete($id);
            $this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');;
        } catch (Exception $e) {
            $this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
        }

        redirect(DIR_SIIGS.'/bitacora/', 'refresh');
        die();
    }

    /**
     * callback utilizado por las acciones create y update para validar la existencia de un usuario
     *
     * @access public
     * @param  int    $id_usuario ID del usuario
     * @return void
     */
    public function validateExistUsuario($id_usuario)
    {
        $this->load->model(DIR_SIIGS.'/Usuario_model');
        $result = $this->Usuario_model->getById($id_usuario);
        
        if( empty($result) ) {
            $this->form_validation->set_message('validateExistUsuario', 'El usuario no existe');
            return false;
        } else {
            return true;
        }
    }

}

?>
