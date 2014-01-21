<?php

class Ayuda extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($id_controlador_accion=null)
	{
        $data['contenido_ayuda'] = 'No se encontró ayuda para esta sección';
        $ruta = explode('/', str_replace('http://', '', $_SERVER['HTTP_REFERER']) );
        
        $path = $ruta[1].'::'.$ruta[2].'::'.(isset($ruta[3]) ? $ruta[3] : 'index' );
        
        $this->load->model('siigs/ControladorAccion_model');
        $idPath = $this->ControladorAccion_model->getIdByPath($path);
        
        if(!empty($idPath)) {
            $registro = $this->ControladorAccion_model->getById( $idPath );
            
            if(!empty($registro->ayuda))
                $data['contenido_ayuda'] = $registro->ayuda;
        }
        
        $this->template->write('header','',true);
		$this->template->write('menu','',true);
        $this->template->write('sala_prensa','',true);
        $this->template->write('seccion_ayuda','',true);
		$this->template->write_view('content', 'ayuda', $data);
		$this->template->render();
	}
}