<?php

/**
 * Controlador Semana Nacional
 *
 * @package    TES
 * @subpackage Controlador
 * @author     Pascual
 * @created    2014-03-07
 */
class Semana_nacional extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        if(!$this->db->conn_id) {
            $this->template->write('content', 'Error no se puede conectar a la Base de Datos');
            $this->template->render();
        }
        
        $this->load->helper('url');
        $this->load->model(DIR_TES.'/Semana_nacional_model');
    }

    /**
     * Lista todos los registros de semanas nacional, con su correspondiente paginación
     * permite eliminar un elemento individual,
     * muestra enlaces para actualizar y ver detalles de un elemento especifico
     *
     * @access public
     * @param  int    $pag Establece el desplazamiento del primer registro a devolver
     * @return void
     */
    public function index($pag = 0)
    {
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Semana_nacional_model))
            return false;

        try {
            $this->load->library('pagination');
            $this->load->helper(array('form', 'formatFecha'));
            
            $data = array();

            $data['pag'] = $pag;
            $data['msgResult'] = $this->session->flashdata('msgResult');
            $data['clsResult'] = $this->session->flashdata('clsResult');
            $data['title'] = 'Semana Nacional';
            
            $registroEliminar = isset($_POST['registroEliminar']) ? $_POST['registroEliminar'] : null;

            if( !empty($registroEliminar) ) {
                $this->Semana_nacional_model->delete($registroEliminar);
                $data['msgResult'] = 'Registros Eliminados exitosamente';
                $data['clsResult'] = 'success';
            }
            
            // Configuración para el Paginador
            $configPag['base_url']    = site_url().DIR_TES.'/semana_nacional/index/';
            $configPag['first_link']  = 'Primero';
            $configPag['last_link']   = '&Uacute;ltimo';
            $configPag['uri_segment'] = 4;
            $configPag['total_rows']  = $this->Semana_nacional_model->getNumRows();
            $configPag['per_page']    = 20;

            $this->pagination->initialize($configPag);
            
            $data['registros'] = $this->Semana_nacional_model->getAll($configPag['per_page'], $pag);
            
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($this->Semana_nacional_model->getMsgError(), __METHOD__);
            $data['clsResult'] = 'error';
        }
        
        $this->template->write_view('content',DIR_TES.'/semana_nacional/index', $data);
		$this->template->render();
    }

    /**
     * Muestra el formulario para crear un nuevo registro en la semana_nacional,
     * las variables se obtienen por el metodo POST
     *
     * @access public
     * @return void
     */
    public function insert()
    {
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Semana_nacional_model))
            return false;

        try {
            $this->load->helper(array('form', 'formatFecha'));

            $datos = $this->input->post();
            $data['title'] = 'Crear un nuevo registro';
            $data['msgResult'] = $this->session->flashdata('msgResult');
            $data['clsResult'] = $this->session->flashdata('clsResult');

            if(!empty($datos)) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[45]|required');
                $this->form_validation->set_rules('fecha_inicio', 'Fecha de inicio', 'required');
                $this->form_validation->set_rules('fecha_fin', 'Fecha de fin', 'required');

                if ($this->form_validation->run() === true) {
                    $this->Semana_nacional_model->setDescripcion($datos['descripcion']);
                    $this->Semana_nacional_model->setFecha_inicio(formatFecha($datos['fecha_inicio'], 'Y-m-d'));
                    $this->Semana_nacional_model->setFecha_fin(formatFecha($datos['fecha_fin'], 'Y-m-d'));
                    
                    $this->Semana_nacional_model->insert();
                    
                    $this->session->set_flashdata('msgResult', 'Registro guardado exitosamente');
                    $this->session->set_flashdata('clsResult', 'success');

                    Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Registro creado: '.$this->Semana_nacional_model->getId());
                    redirect(DIR_TES.'/semana_nacional/', 'refresh');
                } else {
                    $this->session->set_flashdata('msgResult', validation_errors());
                    $this->session->set_flashdata('clsResult', 'error');
                }
            }
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($this->Semana_nacional_model->getMsgError(), __METHOD__);
            $data['clsResult'] = 'error';
        }

        $this->template->write_view('content',DIR_TES.'/semana_nacional/insert', $data);
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
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }

        if(!isset($this->Semana_nacional_model))
            return false;

        try {
            $this->load->helper(array('form', 'formatFecha'));
          
            $datos = $this->input->post();
            $data['title'] = 'Actualizar datos del registro';
            $data['registro'] = $this->Semana_nacional_model->getById($id);

            if(!empty($datos)) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[45]|required');
                $this->form_validation->set_rules('fecha_inicio', 'Fecha de inicio', 'required');
                $this->form_validation->set_rules('fecha_fin', 'Fecha de fin', 'required');
                
                if ($this->form_validation->run() === true) {
                    $this->Semana_nacional_model->setDescripcion($datos['descripcion']);
                    $this->Semana_nacional_model->setFecha_inicio(formatFecha($datos['fecha_inicio'], 'Y-m-d'));
                    $this->Semana_nacional_model->setFecha_fin(formatFecha($datos['fecha_fin'], 'Y-m-d'));

                    $this->Semana_nacional_model->update($id);

                    Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Registro actualizado: '.$id);
                    $this->session->set_flashdata('msgResult', 'Registro guardado exitosamente');
                    $this->session->set_flashdata('clsResult', 'success');
                    redirect(DIR_TES.'/semana_nacional/', 'refresh');
                    die();
                }
            }
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($this->Semana_nacional_model->getMsgError(), __METHOD__);
            $data['clsResult'] = 'error';
        }

        $this->template->write_view('content',DIR_TES.'/semana_nacional/update', $data);
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
        $usuarios = array();
        
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }

        if(!isset($this->Semana_nacional_model))
            return false;

        try {            
            $data['registro'] = $this->Semana_nacional_model->getById($id);
            $data['title'] = 'Datos del registro';
            
            $this->load->helper('formatFecha');

            if( empty($data['registro']) ) {
                $data['msgResult'] = 'ERROR: El registro solicitado no existe';
                $data['clsResult'] = 'error';
            } 
        } catch (Exception $e) { 
            $data['msgResult'] = Errorlog_model::save($this->Semana_nacional_model->getMsgError(), __METHOD__);
            $data['clsResult'] = 'error';
        }

        $this->template->write_view('content',DIR_TES.'/semana_nacional/view', $data);
        $this->template->write('menu','',true);
        $this->template->write('sala_prensa','',true);
		$this->template->render();
    }

    /**
     * Eliminar el registro especificado por el id
     *
     * @access public
     * @param  int    $id ID del elemento a eliminar
     * @return void
     */
    public function delete($id)
    {
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }
        
        if(!isset($this->Semana_nacional_model))
            return false;
        
        try {
            $this->Semana_nacional_model->delete($id);
            $this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
            $this->session->set_flashdata('clsResult', 'success');
        } catch (Exception $e) {
            $this->session->set_flashdata('msgResult', Errorlog_model::save($this->Semana_nacional_model->getMsgError(), __METHOD__));
            $this->session->set_flashdata('clsResult', 'error');
        }

        if (count($id) > 1)
        	Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Registro eliminado: '.implode(',',$id));
        else
        	Bitacora_model::insert(DIR_TES.'::'.__METHOD__, 'Registro eliminado: '.$id);
        redirect(DIR_TES.'/semana_nacional/', 'refresh');
        die();
    }
    
    /**
     * Devuelve un json con todos los registros de semanas nacional
     *
     * @access public
     * @return json
     */
    public function getAll()
    {
        $respuesta = array();
        
        if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
            $respuesta = array('error'=>true, 'msj_error'=>'Acceso denegado');
            echo json_encode($respuesta);
            die();
        }
        
        if(!isset($this->Semana_nacional_model)) {
            $respuesta = array('error'=>true, 'msj_error'=>'No se puede cargar el modelo');
            echo json_encode($respuesta);
            die();
        }

        try {
            $respuesta['registros'] = $this->Semana_nacional_model->getAll();
            
            $respuesta['error'] = false;
            
        } catch (Exception $e) {
            $respuesta = array('error'=>true, 'msj_error'=> Errorlog_model::save($this->Semana_nacional_model->getMsgError(), __METHOD__));
            echo json_encode($respuesta);
            die();
        }
        
        echo json_encode($respuesta);
    }
}
?>