<?php

/**
 * Modelo Raiz
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2013-10-07
 */
class Raiz_model extends CI_Model {

	/**
	 * @access private
	 * @var    int
	 */
	private $id;

	/**
	 * @access private
	 * @var    string
	 */
	private $descripcion;

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

	public function getDescripcion() {
		return $this->descripcion;
	}

	public function setDescripcion($value) {
		$this->descripcion = $value;
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
	 *Devuelve todos los registros de la tabla raiz
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$query = $this->db->query("select a.*, sum(case when ifnull(b.id,'') = '' then 0 else 1 end) as catalogos from asu_raiz a left outer join asu_raiz_x_catalogo b on a.id = b.id_raiz_arbol group by a.id");

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de raices";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}
	
	/**
	 *Revisa si la raiz pasada como parametro existe en el ASU
	 *
	 *@access  public
	 *@param   int $id
	 *@return  Boolean
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function ExistInArbol($id)
	{
		$query = $this->db->query('select * from asu_arbol_segmentacion where id_raiz='.$id);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de raices";
			throw new Exception(__CLASS__);
		}
		else
			return ($query->num_rows() == 0 ? false : true);
	}

	/**
	 *Devuelve la información de una raiz por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('asu_raiz', array('id' => $id));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información de la raiz";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

	/**
	 *Inserta en la tabla raiz, la información contenida en el objeto
	 *
	 *@access  public
	 *@return  int (Id de la inserción si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert()
	{
		$data = array(
				'descripcion' => $this->descripcion
		);

		$query = $this->db->insert('asu_raiz', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar la raiz";
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
				'descripcion' => $this->descripcion
		);

		$this->db->where('id' , $this->getId());
		$query = $this->db->update('asu_raiz', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al actualizar los datos de la raiz";
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

		$query = $this->db->delete('asu_raiz', array('id' => $this->getId()));
                $asu = $this->db->delete('asu_arbol_segmentacion', array('id_raiz' => $this->getId()));

		if (!$query || !$asu)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar la raiz";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}
}