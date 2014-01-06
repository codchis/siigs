<?php

/**
 * Modelo Controlador
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2013-09-26
 */
class Controlador_model extends CI_Model {

	/**
	 * @access private
	 * @var    int
	 */
	private $id;

	/**
	 * @access private
	 * @var    int
	 */
	private $id_entorno;

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
	private $clase;

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
   	 * @access private
   	 * @var    array
   	 */
	private $acciones;

	/**
	 * @access private
	 * @var    int
	 */
	private $offset;

	/**
	 * @access private
	 * @var    int
	 */
	private $rows;

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

	public function getIdEntorno() {
		return $this->id_entorno;
	}

	public function setIdEntorno($value) {
		$this->id_entorno = $value;
	}

	public function getAccion() {
		return $this->acciones;
	}

	public function setAccion($value) {
		$this->acciones = $value;
	}

	public function getClase() {
		return $this->clase;
	}

	public function setClase($value) {
		$this->clase = $value;
	}

	public function setOffset($value) {
		$this->offset = $value;
	}
	public function setRows($value) {
		$this->rows = $value;
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

	public function __construct()
	{
		$this->load->database();
		if(!$this->db->conn_id)
		{
			throw new Exception("No se pudo conectar a la base de datos");
		}
	}

	/**
	 *Devuelve todos los registros de la tabla controlador
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$string = 'select a.id as id_entorno,a.nombre as entorno, b.* from sis_entorno a join sis_controlador b on a.id = b.id_entorno order by a.nombre ,b.nombre';

		if ((!empty($this->offset) || $this->offset == 0) && !empty($this->rows))
		$string .= ' limit '.$this->offset. ','.$this->rows;
		$query = $this->db->query($string);

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
	 *Devuelve el numero de registros
	 *
	 *@access  public
	 *@return  int
	 *@param   int $entorno , default 0 (Id del entorno)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getNumRows($entorno = 0)
	{
		$query = $this->db->query('select count(*) as num from sis_entorno a join sis_controlador b on a.id = b.id_entorno ' . (($entorno == 0) ? '' :' where b.id_entorno = '.$entorno));
		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de entornos";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row()->num;
	}

	/**
	 *Devuelve la información de un controlador por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('sis_controlador', array('id' => $id));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de controladores";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

	/**
	 *Devuelve todos los controladores que pertenecen a un entorno por su Id
	 *
	 *@access  public
	 *@return  ArrayObject
	 *@param   int $id ID (Id del entorno)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getByEntorno($id)
	{
		$string = 'select a.id as id_entorno,a.nombre as entorno, b.* from sis_entorno a join sis_controlador b on a.id = b.id_entorno where b.id_entorno = '.$id . ' order by a.nombre ,b.nombre';

		if ((!empty($this->offset) || $this->offset == 0) && !empty($this->rows))
		$string .= ' limit '.$this->offset. ','.$this->rows;

		$query = $this->db->query($string);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos del controlador";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}

	/**
	 *Devuelve todas las acciones asignadas al controlador por su Id
	 *
	 *@access  public
	 *@return  ArrayObject
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAcciones($id)
	{
		$query = $this->db->query("select a.id as id, a.nombre as accion , case when b.id_accion is null or b.activo = FALSE then false else true end as activo from sis_accion a left join sis_controlador_x_accion b on a.id = b.id_accion and b.id_controlador =".$id);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener las acciones asignadas al controlador";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}

	/**
	 *Devuelve los permisos asignados a un grupo sobre un entorno determinado
	 *(Mapea la información de las acciones asignadas a un controlador y los une con
	 *los permisos de un grupo sobre esas acciones)
	 *
	 *@access  public
	 *@return  ArrayObject
	 *@param   int $entorno (Id del entorno)
	 *@param   int $grupo   (Id del grupo)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getPermisos($entorno , $grupo)
	{
		$query = $this->db->query("select a.id_entorno,d.id, a.nombre as controlador, b.nombre as accion, case when isnull(c.id) or c.activo = 0 or isnull(c.activo) then 0 else c.id end as id , case when isnull(e.id) then 0 else 1 end as activo from sis_controlador a cross join sis_accion b left join sis_controlador_x_accion c on c.id_accion = b.id and c.id_controlador = a.id left join (sis_grupo d join sis_permiso e on d.id = e.id_grupo and d.id = ".$grupo." ) on e.id_controlador_accion = c.id where a.id_entorno = ".$entorno." order by a.nombre,b.nombre");

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los permisos del controlador";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}

	/**
	 *Inserta en la tabla controlador, la información contenida en el objeto
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
				'id_entorno' => $this->id_entorno,
				'clase' => $this->clase
		);

		$query = $this->db->insert('sis_controlador', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar el controlador";
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
				'clase' => $this->clase,
                                'id_entorno' => $this->id_entorno
		);

		$this->db->where('id' , $this->getId());
		$query = $this->db->update('sis_controlador', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al actualizar el controlador";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}

	/**
	 *Actualiza las acciones asignadas a un controlador
	 *
	 *@access  public
	 *@return  boolean (Si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function accionesUpdate()
	{

		$this->db->query('delete from sis_permiso where id_controlador_accion in (select id from sis_controlador_x_accion where id_controlador = '. $this->getId() . ' and activo = 1 and id_accion not in ('.implode(",", $this->acciones).'))');
		$this->db->query('update sis_controlador_x_accion set activo = 0 where id_controlador = '. $this->getId());

		foreach ($this->acciones as $accion)
		{
			$item = array(
					'id_controlador' => $this->getId(),
					'id_accion' => $accion
			);

			$exists = $this->db->get_where("sis_controlador_x_accion",$item);

			if (!$exists)
			{
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				$this->msg_error_usr = "Ocurrió un error al actualizar las acciones del controlador (obtener acciones)";
				throw new Exception(__CLASS__);
			}

			if ($exists->num_rows() > 0)
			{
				$this->db->where($item);
				$query = $this->db->update('sis_controlador_x_accion', array('activo' => '1'));

				if (!$query && $remove)
				{
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					$this->msg_error_usr = "Ocurrió un error al actualizar las acciones del controlador (actualizar accion)";
					throw new Exception(__CLASS__);
				}
			}
			else
			{
				$item['activo'] = '1';
				$query = $this->db->insert('sis_controlador_x_accion', $item);

				if (!$query)
				{
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					$this->msg_error_usr = "Ocurrió un error al actualizar las acciones del controlador (insertar accion)";
					throw new Exception(__CLASS__);
				}
			}
		}
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
		$query = $this->db->delete('sis_controlador', array('id' => $this->getId()));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar el controlador";
			throw new Exception(__CLASS__);
		}
		else
			return true;


	}
}