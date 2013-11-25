<?php

/**
 * Modelo ControladorAccion
 *
 * @author     Geovanni
 * @created    2013-09-26
 */
class ControladorAccion_model extends CI_Model {

	/**
	 * @access private
	 * @var    int
	 */
	private $id;

	/**
	 * @access private
	 * @var    int
	 */
	private $id_controlador;

	/**
	 * @access private
	 * @var    int
	 */
	private $id_accion;

	/**
	 * @access private
	 * @var    boolean
	 */
	private $activo;

	/**
	 * @access private
	 * @var    array
	 */
	private $acciones;

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
	 *Devuelve el Id de una accion por controlador de una accion y controlador determinados
	 *
	 *@access  public
	 *@return  int
	 *@param   int $controlador (Id del controlador)
	 *@param   int $accion      (Id de la accion)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getId($controlador , $accion)
	{
		$query = $this->db->get_where('sis_controlador_x_accion', array('id_accion' => $accion , 'id_controlador' => $controlador));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de acciones";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row()->id;
	}

	/**
	 *Devuelve la información de una accion por controlador de acuerdo a su Id
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{

		$query = $this->db->get_where('sis_controlador_x_accion', array('id' => $id));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de acciones";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

	/**
	 *Devuelve el id de una accion por controlador
	 *de acuerdo al path
	 *
	 *@access  public
	 *@return  int
	 *@param   string $path
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getIdByPath($path)
	{

		$arreglo = explode('::', $path);

		if (count($arreglo) != 3)
		{
 			$this->msg_error_log = "(". __METHOD__.") => : el formato de la cadena es incorrecta";
 			$this->msg_error_usr = "El formato de la cadena no es correcto";
 			throw new Exception(__CLASS__);
		}

		$query = $this->db->query("SELECT c.id FROM sis_entorno a join sis_controlador b on a.id = b.id_entorno join sis_controlador_x_accion c on b.id = c.id_controlador join sis_accion d on c.id_accion = d.id where a.directorio = '".$arreglo[0]."' and b.clase = '".$arreglo[1]."' and d.metodo = '".$arreglo[2]."'");

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de acciones";
			throw new Exception(__CLASS__);
		}
		else
		{
			if ($query->num_rows() == 0)
				return '0';
			else
				return $query->row()->id;
		}
	}
}