<?php
/**
 * Modelo Permiso
 *
 * @author     Rogelio
 * @created    2013-10-01
 */
class Permiso_model extends CI_Model {
	/**
	 * @access private
	 * @var    int
	 */
	private $id;
    /**
     * @access private
     * @var    int
     */
	private $id_grupo;
	/**
	 * @access private
	 * @var    datetime
	 */
   	private $fecha;
   	/**
   	 * @access private
   	 * @var    int
   	 */
   	private $id_controlador_accion;
   	
   	/********************************************
   	 * Estas variables no pertenecen a la tabla *
   	* ******************************************/
   	
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_usr;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_log;
   	
	public function __construct()
	{
		$this->load->database();
		if (!$this->db->conn_id)
			throw new Exception("No se pudo conectar a la base de datos");
	}

	public function getId()
	{
	    return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}
	
	public function getIdGrupo()
	{
	    return $this->id_grupo;
	}

	public function setIdGrupo($id_grupo)
	{
	    $this->id_grupo = $id_grupo;
	}

	public function getFecha()
	{
		return $this->fecha;
	}
	
	public function setFecha($fecha)
	{
		$this->fecha = $fecha;
	}

	public function getIdControladorAccion()
	{
		return $this->id_controlador_accion;
	}
	
	public function setIdControladorAccion($id_controlador_accion)
	{
		$this->id_controlador_accion = $id_controlador_accion;
	}
	
	/**
	 * Asigna el mensaje de error a visualizar: para usuario final (usr) o para bitácora (log)
	 *
	 * @access public
	 * @param $value tipo de error a visualizar: usr o log, default: usr
	 * @return boolean false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}

	/**
	 * Obtiene el permiso solicitado
	 *
	 * @access 		public
	 * @param 		int			$id		id del controlador_x_accion 
	 * @return void|object				false si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function getPermission($id)
	{
		// obtiene permiso por controlador_x_accion e id_grupo
		$query = $this->db->get_where('permiso', array('id_controlador_accion' => $id, 'id_grupo' => $this->session->userdata(GROUP_ID)));
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return;
	}
	
	/**
	 * Inserta en la base de datos el arreglo de permisos recibido 
	 *
	 * @access		public
	 * @param 		array object	$data 	array con los permisos a insertar
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function insertBatch($data)
	{
		// insert_batch hace inserciones en bloques de 100
		// y siempre retorna true =(
		$result = $this->db->insert_batch('permiso', $data); 
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}

	/**
	 * Elimina de la base de datos los permisos del entorno y grupo recibidos
	 *
	 * @access		public
	 * @param 		int		$entorno		id de entorno
	 * @param 		int		$grupo 			id del grupo
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function deletePermissions($entorno, $grupo)
	{
		$this->db->select('permiso.id');
		$this->db->from('permiso');
		$this->db->join('controlador_x_accion', 'controlador_x_accion.id = permiso.id_controlador_accion');
		$this->db->join('controlador', 'controlador.id = controlador_x_accion.id_controlador');
		$this->db->where('permiso.id_grupo', $grupo);
		$this->db->where('controlador.id_entorno', $entorno);
		$query = $this->db->get();
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
		{
			$i = 0;
			$aBorrar = array();
			foreach ($query->result() as $row)
			{
				$aBorrar[$i] = $row->id;
				$i++;
			}
			if (count($aBorrar) > 0)
			{
				$this->db->where_in('id', $aBorrar);
				$result = $this->db->delete('permiso');
				if (!$result){
					$this->msg_error_usr = "Servicio temporalmente no disponible.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception(__CLASS__);
				}
				else 	
					return true;
			}
			else
				return true;
		}
		return false;
	}
	
}
?>