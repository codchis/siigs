<?php

/**
 * Modelo Entorno
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2013-09-26
 */
class Entorno_model extends CI_Model {

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

	/**
	 * @access private
	 * @var    string
	 */
	private $ip;

	/**
	 * @access private
	 * @var    string
	 */
	private $hostname;

	/**
	 * @access private
	 * @var    string
	 */
	private $directorio;

	/**
	 * @access private
	 * @var    string
	 */
   	private $msg_error_log;

   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_usr;

   	/***************************/
   	/*Getters and setters block*/
   	/***************************/
	public function getId() {
		return $this->id;
	}
	public function setId($value) {
		$this->id = $value;
	}

	public function getNombre() {
		return $this->nombre;
	}
	public function setNombre($value) {
			$this->nombre = $value;
	}

	public function getDescripcion() {
		return $this->descripcion;
	}
	public function setDescripcion($value) {
		$this->descripcion = $value;
	}

	public function getIp() {
		return $this->ip;
	}
	public function setIp($value) {
		$this->ip = $value;
	}

	public function getHostname() {
		return $this->hostname;
	}
	public function setHostname($value) {
		$this->hostname = $value;
	}

	public function getDirectorio() {
		return $this->directorio;
	}
	public function setDirectorio($value) {
		$this->directorio = $value;
	}
	/*******************************/
	/*Getters and setters block END*/
	/*******************************/

	/**
	 * Devuelve los mensajes de error en caso de ocurrir alguna excepción
	 * 'usr' devuelve el mensaje para la vista de usuario
	 * 'log' devuelve el mensaje para el log de errores
	 *
	 * @access  public
	 * @return  string|boolean
	 *  @param  string $value, default 'usr' (Tipo mensaje)
	 */
	public function getMsgError($value = 'usr')
	{
		if (!empty($this->msg_error_usr))
		{
			if ($value == 'usr')
				return $this->msg_error_usr;
			else if ($value == 'log')
				return $this->msg_error_log;
		}
		else
		{
			return null;
		}
	}


	/**
	 * Devuelve la información del objeto en forma de string
	 *
	 * @access public
	 * @return string
	 *
	 */

	public function getInfo()
	{
		$info = '';
		$info .= (!empty($this->id) ? $this->id : '');
	}

	public function __construct()
	{
		$this->load->database();
		 if(!$this->db->conn_id)
		 {
		 	throw new Exception("No se pudo conectar a la base de datos");
		 }
	}

	/**
	 *Devuelve todos los registros de la tabla entorno
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$query = $this->db->get('sis_entorno');

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de entornos";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}

	/**
	 *Devuelve la información de un entorno por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('sis_entorno', array('id' => $id));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información del entorno";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

	/**
	 *Devuelve la información de un entorno por su nombre
	 *
	 *@access  public
	 *@return  Object
	 *@param   string $nombre
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getByName($nombre)
	{
		$query = $this->db->get_where('sis_entorno', array('nombre' => $nombre));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información del entorno (nombre)";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}
        
        	/**
	 *Obtiene los permisos asignados al grupo
	 *
	 *@access  public
	 *@return  Object
	 *@param   string $grupo
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getPermissionsByGroup($grupo)
	{
		$query = $this->db->query("SELECT CONCAT(e.directorio,'::',c.clase,'::',a.metodo) AS modulo FROM sis_controlador_x_accion ca 
                                          INNER JOIN sis_permiso p ON ca.id=p.id_controlador_accion INNER JOIN sis_controlador c ON ca.id_controlador=c.id INNER JOIN sis_entorno e ON c.id_entorno=e.id 
                                          INNER JOIN sis_accion a ON ca.id_accion=a.id WHERE p.id_grupo=".$grupo);
		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información de los permisos por grupo (getPermisosByGrupo)";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result_array();
	}

	/**
	 *Inserta en la tabla entorno la información contenida en el objeto
	 *
	 *@access  public
	 *@return  int (Id de la inserción si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert()
	{
		$data = array(
				'nombre' => $this->nombre,
				'descripcion' => $this->descripcion,
				'ip' => $this->ip,
				'hostname' => $this->hostname,
				'directorio' => $this->directorio
		);

		$query = $this->db->insert('sis_entorno', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar el entorno";
			throw new Exception(__CLASS__);
		}
		else
			return $this->db->insert_id($query);
	}

	/**
	 *Actualiza el objeto actual en la base de datos
	 *
	 *@access  public
	 *@return  boolean (Si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function update()
	{
		$data = array(
				'nombre' => $this->nombre,
				'descripcion' => $this->descripcion,
				'ip' => $this->ip,
				'hostname' => $this->hostname,
				'directorio' => $this->directorio
		);

		$this->db->where('id' , $this->getId());
		$query = $this->db->update('sis_entorno', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al actualizar los datos del entorno";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}

	/**
	 * Elimina el registro actual de la base de datos
	 *
	 * @access public
	 * @return boolean (Si no hubo errores al eliminar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function delete()
	{
		$query = $this->db->delete('sis_entorno', array('id' => $this->getId()));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar el entorno";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}
}