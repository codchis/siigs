<?php
/**
 * Controlador Servicios
 *
 * @author     Rogelio
 * @created    2013-11-27
 */
class Servicios extends CI_Controller {
    
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
     * Si la acción es 1: Valida la disponibilidad del dispositivo especificado 
     * Si la acción es 2: Regresa los valores requeridos para sincronización de la tableta cuando el token es correcto
     *
     * @access public
     * @param  int    $pag Establece el desplazamiento del primer registro a devolver
     * @return void
     */
    public function synchronize($id_accion, $id_tab = null, $id_sesion = null)
    {
//         if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
//             show_error('', 403, 'Acceso denegado');
//             return false;
//         }
        if(!isset($this->Tableta_model))
            return 'No hay conexión';
// 		echo 'id_accion '.$id_accion.'<br>';
// 		echo 'id_tab '.$id_tab.'<br>';
// 		echo 'id_sesion '.$id_sesion.'<br>';
        try {
			switch($id_accion)
			{
				case 1: // debe existir la MAC
					$tableta = $this->Tableta_model->getByMac($id_tab);
					if (count($tableta) == 1)
					{
						// debe tener usuarios asignados, el tipo de censo y la unidad médica
						if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
						{
							// se crea el token temporal
							$token = md5($id_tab);
						}
						else 
							return 'Tableta sin configurar';
					}
					else 
						return 'Tableta desconocida';
					break;
				case 2: // debe existir el token y se regresa la info del dispositivo
					if ($id_sesion == 'ddasdas') // ver mecanismo validador de tokens
					{
						// se obtiene el dispositivo por token
						$tableta = $this->Tableta_model->getByMac('123456789');
						// se obtienen los usuarios asignados, el tipo de censo y la unidad médica
						if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
						{
							$cadena = "{ \"response\": {";
							$cadena .= "\"usuarios\": [";
							$usuarios = $this->Usuario_tableta_model->getUsuariosByTableta(2);
							foreach ($usuarios as $usuario){
								$cadena .= "{ \"id_usuario\": \"".$usuario->id_usuario."\", \"nombre_usuario\": \"".$usuario->id_usuario."\" }";
							}
							$cadena .= "]},";
							$cadena .= "\"id_tipo_censo\": \"".$tableta->id_tipo_censo."\",";
							$cadena .= "\"id_asu_um\": \"".$tableta->id_asu_um."\" }";
							echo $cadena;
						}
						else
							return 'Tableta sin configurar';
					}
					else
						return 'Error de procedimiento';
					break;
			}
        } catch (Exception $e) {
            Errorlog_model::save($e->getMessage(), __METHOD__);
        }
		if ($id_accion == 1)
			echo $token;
		else if ($id_accion == 2)
			return '';
		else
			return 'Error de procedimiento';
    }

    public function receive()
    {
    	echo $_POST["param"].' recibido';
    }
    
}
?>