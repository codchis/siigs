<?php

/**
 * Modelo Raiz_x_Catalogo
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2013-10-16
 */
class Catalogo_x_raiz_model extends CI_Model {

	/**
	 * @access private
	 * @var    int
	 */
	private $id;

	/**
	 * @access private
	 * @var    int
	 */
	private $id_raiz;

	/**
	 * @access private
	 * @var    int
	 */
	private $grado;

	/**
	 * @access private
	 * @var    string
	 */
	private $tabla_catalogo;

	/**
	 * @access private
	 * @var    string
	 */
	private $columna_llave;

	/**
	 * @access private
	 * @var    string
	 */
	private $columna_descripcion;

	/**
	 * @access private
	 * @var    Array
	 */
	private $relacionpadre;

	/**
	 * @access private
	 * @var    Array
	 */
	private $relacionhijo;

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

	public function getIdRaiz() {
		return $this->id_raiz;
	}

	public function setIdRaiz($value) {
		$this->id_raiz = $value;
	}

	public function getGrado() {
		return $this->grado;
	}
	public function setGrado($value) {
			$this->grado = $value;
	}

	public function getTablaCatalogo() {
		return $this->tabla_catalogo;
	}
	public function setTablaCatalogo($value) {
		$this->tabla_catalogo = $value;
	}

	public function getColumnaLLave() {
		return $this->columna_llave;
	}
	public function setColumnaLlave($value) {
		$this->columna_llave = $value;
	}

	public function getColumnaDescripcion() {
		return $this->columna_descripcion;
	}
	public function setColumnaDescripcion($value) {
		$this->columna_descripcion = $value;
	}

	public function getRelacionPadre() {
		return $this->relacionpadre;
	}
	public function setRelacionPadre($value) {
		$this->relacionpadre = $value;
	}

	public function getRelacionHijo() {
		return $this->relacionhijo;
	}
	public function setRelacionHijo($value) {
		$this->relacionhijo = $value;
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
	 *Devuelve todos los registros de la tabla raiz_x_catalogo de una raiz determinada
	 *
	 *@access  public
	 *@param   int $id 
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getByArbol($id)
	{
		$catalogos = $this->db->query('SELECT * from asu_raiz_x_catalogo where id_raiz_arbol='.$id.' order by grado_segmentacion');

		if (!$catalogos)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos";
			throw new Exception(__CLASS__);
		}
		else
		return $catalogos->result();
	}

	/**
	 *Devuelve el nivel siguiente para la tabla raiz_x_catalogo de un arbol determinado
	 *
	 *@access  public
	 *@param   int $id
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getNivel($id)
	{
		$nivel = $this->db->query('SELECT ifnull(max(grado_segmentacion),0) +1 as nivel from asu_raiz_x_catalogo where id_raiz_arbol='.$id);

		if (!$nivel)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos";
			throw new Exception(__CLASS__);
		}
		else
			return $nivel->row();
	}

	/**
	 *Devuelve el catalogo padre de un elemento raiz_x_catalogo
	 *
	 *@access  public
	 *@param   int $idarbol
         *@param   int $nivel
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getByNivel($idarbol,$nivel)
	{
		$nivel = $this->db->query('SELECT tabla_catalogo as nombre,nombre_columna_llave as llave from asu_raiz_x_catalogo where grado_segmentacion='.$nivel." and id_raiz_arbol=".$idarbol);

		if (!$nivel)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos";
			throw new Exception(__CLASS__);
		}
		else
			return $nivel->row();
	}

	/**
	 *Devuelve la información de un catalogo x accion por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{

		$query = $this->db->query('select * from asu_raiz_x_catalogo where id='.$id);
		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

		/**
	 *Devuelve las relaciones de una raiz x catalogo
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getRelations($id)
	{

		$query = $this->db->query('select * from asu_relacion_catalogo where id_raiz_x_catalogo='.$id);
		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener las relaciones entre los catálogos";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}
	
	/**
	 *Revisa inconsistencias en los datos de un catalogo x raiz con
	 *respecto a su catalogo padre
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function check($id)
	{

		$cathijo = $this->getById($id);
		if (!$cathijo || count($cathijo) == 0)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos x raiz";
			throw new Exception(__CLASS__);
		}
		else
		{
			$catpadre = $this->db->query('select * from asu_raiz_x_catalogo where id_raiz_arbol='.$cathijo->id_raiz_arbol.' and grado_segmentacion='.($cathijo->grado_segmentacion-1));
			if (!$catpadre || $catpadre->num_rows() == 0)
			{
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos x raiz";
				throw new Exception(__CLASS__);
			}
			else
			{
				$relaciones = $this->getRelations($id);
				if (!$relaciones  || count($relaciones) == 0)
				{
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos x raiz";
					throw new Exception(__CLASS__);
				}
				else
				{
					$consulta = "select * from ".$cathijo->tabla_catalogo.' where 1=1 ';
					$consulta .= ' and '.$cathijo->tabla_catalogo.'.'.$cathijo->nombre_columna_llave.' not in (select '.$cathijo->tabla_catalogo.'.'.$cathijo->nombre_columna_llave.' from '.$cathijo->tabla_catalogo.' join '.$catpadre->row()->tabla_catalogo.' on 1=1 ';
					foreach ($relaciones as $relacion)
					{
						//$consulta .= ' and '.$relacion->columna_hijo.' not in (select distinct '.$relacion->columna_padre.' from '.$catpadre->row()->tabla_catalogo.')';
						$consulta .= ' and '.$cathijo->tabla_catalogo.'.'.$relacion->columna_hijo.'='.$catpadre->row()->tabla_catalogo.'.'.$relacion->columna_padre.' ';
					}
					$consulta .= " )";
					//var_dump($consulta);
					$errores = $this->db->query($consulta);
					if (!$errores)
					{
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos x raiz";
						throw new Exception(__CLASS__);
					}
					else
						return $errores->result();
				}
			}
		}
	}

	/**
	 *Inserta en la base datos la información del objeto actual
	 *
	 *@access  public
	 *@return  int (Id de la inserción si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert()
	{
		$datos = array(
		'grado_segmentacion' => $this->grado,
		'tabla_catalogo' => $this->tabla_catalogo,
		'nombre_columna_llave' => $this->columna_llave,
		'nombre_columna_descripcion' => $this->columna_descripcion,
		'id_raiz_arbol' => $this->id_raiz,
		);

		$query = $this->db->insert('asu_raiz_x_catalogo',$datos);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar el catalogo x raiz";
			throw new Exception(__CLASS__);
		}
		else
		{
			$id = $this->db->insert_id();
			$datos = array();

			if (count($this->relacionpadre) > 0)
			{
				for($i=0;$i<count($this->relacionpadre);$i++)
				{
					array_push($datos, array(
						'id_raiz_x_catalogo'=>$id,
						'columna_padre'=> $this->relacionpadre[$i],
						'columna_hijo' => $this->relacionhijo[$i]
					));
				}

				$query = $this->db->insert_batch('asu_relacion_catalogo',$datos);

				if (!$query)
				{
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					$this->msg_error_usr = "Ocurrió un error al insertar el catalogo x raiz";
					throw new Exception(__CLASS__);
				}
				else
					return true;
			}
			else
				return true;
		}
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
			$this->msg_error_usr = "Ocurrió un error al actualizar los datos del catálogo";
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
                $query_relaciones = $this->db->delete('asu_relacion_catalogo', array('id_raiz_x_catalogo' => $this->getId()));
		$query = $this->db->delete('asu_raiz_x_catalogo', array('id' => $this->getId()));

		if (!$query && !$query_relaciones)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar la raiz x catalogo";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}
}