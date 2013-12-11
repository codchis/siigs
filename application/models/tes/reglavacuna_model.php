<?php

/**
 * Modelo ReglaVacuna
 *
 * @author     Geovanni
 * @created    2013-!2-09
 */
class ReglaVacuna_model extends CI_Model {

	/**
	 * @access private
	 * @var    int
	 */
	private $id;

	/**
	 * @access private
	 * @var    int
	 */
	private $id_vacuna;
        
	/**
	 * @access private
	 * @var    int
	 */
	private $id_vacuna;
        
	/**
	 * @access private
	 * @var    int
	 */
	private $id_vacuna;


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

	public function getMetodo() {
		return $this->metodo;
	}

	public function setMetodo($value) {
		$this->metodo = $value;
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
			return false;
		}
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
	 *Devuelve todos los registros de la tabla acciones
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$query = $this->db->query("SELECT a.id,b.descripcion as vacuna , CASE WHEN IFNULL(a.dia_inicio_aplicacion_nacido,'') = '' THEN 'Secuencial' ELSE 'Nacimiento' END AS aplicacion , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_inicio_aplicacion_secuencial else a.dia_inicio_aplicacion_nacido end as desde , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_fin_aplicacion_secuencial else a.dia_fin_aplicacion_nacido end as hasta , case when ifnull(a.id_vacuna_secuencial,'') = '' then 'Ninguna' else c.descripcion end as previa FROM cns_regla_vacuna a join cns_vacuna b on a.id_vacuna = b.id and b.activo = 1 left outer join cns_vacuna c on a.id_vacuna_secuencial = c.id and c.activo = 1");

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de regla";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}

	/**
	 *Devuelve la información de una accion por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('sis_accion', array('id' => $id));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información de la regla";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

	/**
	 *Inserta en la tabla accion, la información contenida en el objeto
	 *
	 *@access  public
	 *@return  int (Id de la inserci�n si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert()
	{
		$data = array(
				'nombre' => $this->nombre,
				'descripcion' => $this->descripcion,
				'metodo' => $this->metodo
		);

		$query = $this->db->insert('sis_accion', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar la regla";
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
				'metodo' => $this->metodo
		);

		$this->db->where('id' , $this->getId());
		$query = $this->db->update('sis_accion', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al actualizar los datos de la regla";
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

		$query = $this->db->delete('sis_accion', array('id' => $this->getId()));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar la regla";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}
}