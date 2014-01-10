<?php
/**
 * Modelo Grupo
 *
 * @package		SIIGS
 * @subpackage	Modelo
 * @author     	Rogelio
 * @created		2013-09-25
 */
class Grupo_model extends CI_Model {
	/**
	 * @access private
	 * @var    int
	 */
	private $id;
	/**
	 * @access private
	 * @var    string
	 */
   	private $nombre;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $descripcion;
   	
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
	
	public function getNombre()
	{
	    return $this->nombre;
	}

	public function setNombre($nombre)
	{
	    $this->nombre = $nombre;
	}

	public function getDescripcion()
	{
		return $this->descripcion;
	}
	
	public function setDescripcion($descripcion)
	{
		$this->descripcion = $descripcion;
	}
	
	/**
	 * Asigna el mensaje de error a visualizar: para usuario final (usr) o para bitácora (log)
	 *
	 * @access		public
	 * @param		string		$value		tipo de error a visualizar: usr o log, default: usr
	 * @return 		boolean		false 		si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}
		
	/**
	 * Obtiene todos los grupos existentes
	 *
	 * @access public
     * @param  	int 				$offset    		Establece el desplazamiento del primer registro a devolver,
     *                        						si se define solo el valor de offset
     *                        						el valor especifica el número de registros a retornar desde el comienzo del conjunto de resultados.
     * @param  int 					$row_count 		Establece la cantidad de registros a devolver
	 * @return void|array object	false si ocurrió algún error, array object si se ejecutó correctamente
	 */
	public function getAll($keywords = '', $offset = null, $row_count = null)
	{
		if(!empty($offset) && !empty($row_count))
			$this->db->limit($offset, $row_count);
		else if (!empty($offset))
			$this->db->limit($offset);
		if (empty($keywords))
			$query = $this->db->get('sis_grupo');
		else
		{
			$this->db->select('*');
			$this->db->from('sis_grupo');
			$this->db->like('nombre', $keywords);
			$this->db->or_like('descripcion', $keywords);
			$query = $this->db->get();
		}
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return;
	}

	/**
	 * Obtiene el grupo solicitado
	 *
	 * @access 		public
	 * @param 		int			$id			id del grupo
	 * @return void|object		false si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('sis_grupo', array('id' => $id));
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
	 * Obtiene el grupo solicitado
	 *
	 * @access 		public
	 * @param 		string		$name		nombre del grupo
	 * @return void|object		false si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function getByName($name)
	{
		$query = $this->db->get_where('sis_grupo', array('nombre' => $name));			
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
	 * Obtiene el numero total de grupos
	 *
	 * @access public
	 * @param 	boolean|string		$keywords		false no hay texto a buscar|string con texto a buscar
	 * @return int
	 */
	public function getNumRows($keywords = '')
	{
		if (!$keywords)
			$query = $this->db->get('sis_grupo');
		else
		{
			$this->db->select('*');
			$this->db->from('sis_grupo');
			$this->db->like('nombre', $keywords);
			$this->db->or_like('descripcion', $keywords);
			$query = $this->db->get();
		}
		if(!$query) {
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $query->num_rows;
	}
	
	/**
	 * Inserta en la base de datos los datos del grupo (datos en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function insert()
	{
		$data = array(
			'nombre' => $this->nombre,
			'descripcion' => $this->descripcion
		);
		$result = $this->db->insert('sis_grupo', $data);
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}

	/**
	 * Actualiza en la base de datos los datos del grupo (datos en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function update()
	{
		$data = array(
				'descripcion' => $this->descripcion
		);
		$this->db->where('id' , $this->id);
		$result = $this->db->update('sis_grupo', $data);
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Elimina de la base de datos al grupo (id en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function delete()
	{
		$result = $this->db->delete('sis_grupo', array('id' => $this->getId()));
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			if (strpos($this->db->_error_message(),'Cannot delete or update a parent row') !== false) {
				$this->msg_error_usr = "No se puede eliminar debido a que ese registro contiene información vinculada.";
			}
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
}
?>