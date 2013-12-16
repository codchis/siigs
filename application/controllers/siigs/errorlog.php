<?php

/**
 * Controlador Errorlog
 *
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Pascual
 * @created    2013-10-02
 */
class Errorlog extends CI_Controller {
    
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
     * Lista todos los registros de la tabla error, con su correspondiente paginación
     * permite hacer filtrados por Usuario, Entorno, Controlador, Acción y Fecha,
     * muestra enlaces para ver detalles de un elemento especifico
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
        
        if(!isset($this->Errorlog_model))
            return false;

        try {
            $this->load->library('pagination');
            $this->load->helper('form');
            $this->load->model( array(DIR_SIIGS.'/usuario_model', DIR_SIIGS.'/entorno_model', DIR_SIIGS.'/controlador_model', DIR_SIIGS.'/Accion_model') );

            $filtros = array();
            $data = array();

            $data['pag'] = $pag;
            $data['title'] = 'Error Log';
            
            /*** Inicia Campos para Filtros ***/
            $usuarios = $this->usuario_model->getOnlyActives();
            $data['usuarios'][0] = 'Todos';
            foreach ($usuarios as $user) {
                $data['usuarios'][$user->id] = $user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno;
            }

            $entornos = $this->entorno_model->getAll();
            $data['entornos'][0] = 'Todos';
            foreach ($entornos as $ent) {
                $data['entornos'][$ent->id] = $ent->nombre;
            }

            $data['controladores'][0] = 'Todos';

            if($this->input->post('entorno')) {
                $controladores = $this->controlador_model->getByEntorno($this->input->post('entorno'));
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

            if($this->input->post('filtrar')) {
                // Eliminar el campo hidden y el boton
                unset($_POST['filtrar'], $_POST['btnFiltrar']);
                $filtros = array_filter($this->input->post());

                if(!empty($filtros)) {
                    foreach ($filtros as $campo => $valor) {
                        switch ($campo) {
                            case 'usuario':
                                $this->Errorlog_model->addFilter('id_usuario', '=', $valor);
                                break;
                            case 'fechaIni':
                                $this->Errorlog_model->addFilter('fecha_hora', '>=', $valor);
                                break;
                            case 'fechaFin':
                                $this->Errorlog_model->addFilter('fecha_hora', '<=', $valor.' 23:59:59');
                                break;
                            case 'entorno':
                                $this->Errorlog_model->addFilter('id_entorno', '=', $valor);
                                break;
                            case 'controlador':
                                $this->Errorlog_model->addFilter('id_controlador', '=', $valor);
                                break;
                            case 'accion':
                                $this->Errorlog_model->addFilter('id_accion', '=', $valor);
                                break;
                        }
                    }
                }

                $data = array_merge($data, $filtros);
            }
            // Configuración para el Paginador
            $configPag['base_url']    = site_url().DIR_SIIGS.'/errorlog/index/';
            $configPag['first_link']  = 'Primero';
            $configPag['last_link']   = '&Uacute;ltimo';
            $configPag['uri_segment'] = 4;
            $configPag['total_rows']  = $this->Errorlog_model->getNumRows();
            $configPag['per_page']    = REGISTROS_PAGINADOR;

            $this->pagination->initialize($configPag);

            $data['registros'] = $this->Errorlog_model->getAll($configPag['per_page'], $pag);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
        
        $this->template->write_view('content',DIR_SIIGS.'/errorlog/index', $data);
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
        
        if(!isset($this->Errorlog_model))
            return false;

        try {
            $data['registro'] = $this->Errorlog_model->getById($id);
            $data['title'] = 'Datos del error';

            if( empty($data['registro']) ) {
                $data['msgResult'] = 'ERROR: El registro de error solicitado no existe';
            }
        } catch (Exception $e) { }

        $this->template->write_view('content',DIR_SIIGS.'/errorlog/view', $data);
		$this->template->render();
    }
    
}

?>
