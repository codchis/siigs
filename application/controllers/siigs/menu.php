<?php

/**
 * Controlador Menu
 *
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Pascual
 * @created    2013-10-07
 */
class Menu extends CI_Controller {
    /**
     * Guarda la instancia del objeto global CodeIgniter
     * para utilizarlo en la función estática
     *
     * @access private
     * @var    instance
     */
    private static $CI;
    
    public function __construct()
    {
        parent::__construct();

        self::$CI = &get_instance();

        $this->load->model( DIR_SIIGS.'/Menu_model' );

        if(!$this->db->conn_id) {
            $this->template->write('content', 'Error no se puede conectar a la Base de Datos');
            $this->template->render();
        }
        
        $this->load->helper('url');
    }

    /**
     * Lista todos los registros de la menu, con su correspondiente paginación
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
        
        if(!isset($this->Menu_model))
            return false;

        try {
            $this->load->library('pagination');
            $this->load->helper('form');

            $filtros = array();
            $data = array();

            $data['pag'] = $pag;
            $data['msgResult'] = $this->session->flashdata('msgResult');
            $data['title'] = 'Menu';

            $registroEliminar = $this->input->post('registroEliminar');

            if( !empty($registroEliminar) ) {
                $this->Menu_model->delete($registroEliminar);
                $data['msgResult'] = 'Registros Eliminados exitosamente';
            }

            $menus = $this->Menu_model->getAll();
            $data['menus'][0] = 'Elegir';
            foreach ($menus as $men) {
                $data['menus'][$men->id] = $men->nombre;
            }

             if($this->input->post('filtrar')) {
                // Eliminar el campo hidden y el boton
                unset($_POST['filtrar'], $_POST['btnFiltrar']);
                $filtros = array_filter($this->input->post());

                if(!empty($filtros)) {
                    foreach ($filtros as $campo => $valor) {
                        switch ($campo) {
                            case 'raiz':
                                $this->Menu_model->addFilter('id_raiz', '=', $valor);
                                break;
                        }
                    }
                }

                $data = array_merge($data, $filtros);
            }

            // Configuración para el Paginador
            $configPag['base_url']    = site_url().DIR_SIIGS.'/menu/index/';
            $configPag['first_link']  = 'Primero';
            $configPag['last_link']   = '&Uacute;ltimo';
            $configPag['uri_segment'] = 4;
            $configPag['total_rows']  = $this->Menu_model->getNumRows();
            $configPag['per_page']    = REGISTROS_PAGINADOR;

            $this->pagination->initialize($configPag);

            $data['registros'] = $this->Menu_model->getAll($configPag['per_page'], $pag);
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/menu/index', $data);
		$this->template->render();
    }

    /**
     * Muestra el formulario para crear un nuevo registro en la menu,
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
        
        if(!isset($this->Menu_model))
            return false;

        try {
            $this->load->helper('form');
            $this->load->model( array(DIR_SIIGS.'/Entorno_model', DIR_SIIGS.'/Controlador_model') );

            $menus = $this->Menu_model->getAll();
            $data['menus'][0] = 'Elegir';
            foreach ($menus as $men) {
                $data['menus'][$men->id] = $men->nombre;
            }

            $entornos = $this->Entorno_model->getAll();
            $data['entornos'][0] = 'Elegir';
            foreach ($entornos as $ent) {
                $data['entornos'][$ent->id] = $ent->nombre;
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

            $datos = $this->input->post();
            $data['title'] = 'Crear un nuevo registro';
            $data['msgResult'] = $this->session->flashdata('msgResult');

            if(!empty($datos)) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|max_length[45]|required');

                if ($this->form_validation->run() === true) {
                    if( !empty($datos['padre']) )
                        $this->Menu_model->setId_padre($datos['padre']);

                    if( !empty($datos['raiz']) )
                        $this->Menu_model->setId_raiz($datos['raiz']);

                    if( !empty($datos['controlador']) )
                        $this->Menu_model->setId_controlador($datos['controlador']);

                    if( !empty($datos['ruta']) )
                        $this->Menu_model->setRuta($datos['ruta']);

                    $this->Menu_model->setNombre($datos['nombre']);

                    $this->Menu_model->insert();
                    $this->session->set_flashdata('msgResult', 'Registro guardado exitosamente');

                    Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Registro creado: '.$this->Menu_model->getId());
                    redirect(DIR_SIIGS.'/menu/', 'refresh');
                    die();
                }
            }
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/menu/insert', $data);
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

        if(!isset($this->Menu_model))
            return false;

        try {
            $this->load->helper('form');
            $this->load->model( array(DIR_SIIGS.'/Entorno_model', DIR_SIIGS.'/Controlador_model') );

            $datos = $this->input->post();
            $data['title'] = 'Actualizar datos del registro';
            $data['registro'] = $this->Menu_model->getById($id);
            
            $menus = $this->Menu_model->getAll();
            $data['menus'][0] = 'Elegir';
            foreach ($menus as $men) {
                $data['menus'][$men->id] = $men->nombre;
            }

            $entornos = $this->Entorno_model->getAll();
            $data['entornos'][0] = 'Elegir';
            foreach ($entornos as $ent) {
                $data['entornos'][$ent->id] = $ent->nombre;
            }

            $entornos = $this->Entorno_model->getAll();
            $data['entornos'][0] = 'Elegir';
            foreach ($entornos as $ent) {
                $data['entornos'][$ent->id] = $ent->nombre;
            }

            $data['controladores'][0] = 'Elegir';
            if($data['registro']->id_entorno) {
                $controladores = $this->Controlador_model->getByEntorno($data['registro']->id_entorno);
                foreach ($controladores as $contr) {
                    $data['controladores'][$contr->id] = $contr->nombre;
                }
            }

            if(!empty($datos)) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|max_length[45]|required');
                
                if ($this->form_validation->run() === true) {
                    if( !empty($datos['padre']) )
                        $this->Menu_model->setId_padre($datos['padre']);

                    if( !empty($datos['raiz']) )
                        $this->Menu_model->setId_raiz($datos['raiz']);

                    if( !empty($datos['controlador']) )
                        $this->Menu_model->setId_controlador($datos['controlador']);

                    if( !empty($datos['ruta']) )
                        $this->Menu_model->setRuta($datos['ruta']);

                    $this->Menu_model->setNombre($datos['nombre']);

                    $this->Menu_model->update($id);

                    Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Registro actualizado: '.$id);
                    $this->session->set_flashdata('msgResult', 'Registro guardado exitosamente');
                    redirect(DIR_SIIGS.'/menu/', 'refresh');
                    die();
                }
            }
        } catch (Exception $e) {
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/menu/update', $data);
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

        if(!isset($this->Menu_model))
            return false;

        try {
            $data['registro'] = $this->Menu_model->getById($id);
            $data['title'] = 'Datos del registro';

            if( empty($data['registro']) ) {
                $data['msgResult'] = 'ERROR: El registro solicitado no existe';
            }
        } catch (Exception $e) { 
            $data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
        }

        $this->template->write_view('content',DIR_SIIGS.'/menu/view', $data);
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
        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url())) {
            show_error('', 403, 'Acceso denegado');
            return false;
        }

        if(!isset($this->Menu_model))
            return false;
        
        try {
            $this->Menu_model->delete($id);
            $this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');;
        } catch (Exception $e) {
            $this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
        }

        Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Registro eliminado: '.$id);

        redirect(DIR_SIIGS.'/menu/', 'refresh');
        die();
    }

    
}

?>
