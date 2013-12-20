<?php

/**
 * Modelo Catalogo
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2013-10-07
 */
class Catalogo_model extends CI_Model {

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
	private $campos;

	/**
	 * @access private
	 * @var    string
	 */
	private $llave;

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

	public function getCampos() {
		return $this->campos;
	}
	public function setCampos($value) {
		$this->campos = $value;
	}

	public function getLLave() {
		return $this->llave;
	}
	public function setLlave($value) {
		$this->llave = $value;
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
	 *Devuelve una lista con los catalogos existentes en la DB
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$catalogos = $this->db->query('SELECT table_name as nombre FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = "'.$this->db->database.'" AND table_name LIKE "cat_%"');
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
	 *Devuelve los datos de un catalogo pasado como parametro
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAllData($nombrecat)
	{
		$catalogos = $this->db->query('select * from '.$nombrecat);

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
	 *Devuelve la informacion de un catalogo por su nombre
	 *
	 *@access  public
	 *@return  Object
	 *@param   string $nombre (Nombre del catalogo)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getByName($nombre)
	{
		$result = array(
			'nombre' => $nombre
		);
		$tblcampos = $this->db->query('describe '.$nombre);
		if (!$tblcampos)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de los catálogos";
			throw new Exception(__CLASS__);
		}
		else
		{
			$campos = '';
			$llave = '';
			foreach ($tblcampos->result() as $campo)
			{
				if ($campo->Key == '')
					$campos .= $campo->Field.'|'.$campo->Type.'|'.$campo->Null.'|NO||';
				else
					$llave .= $campo->Field.'|'.$campo->Type.'|'.$campo->Null.'|YES||';
			}
			$result['campos'] = substr($campos, 0,count($campos)-3);;

			$result['llave'] = substr($llave, 0,count($llave)-3);
		}

			return (object)$result;
	}
	
	/**
	 *Revisa en la base de datos por registros duplicados en los campos pasados por parametro
	 *
	 * @access public
	 *@param   string $campo (varios campos delimitados por | )
	 * @return boolean (Si no hubo errores al eliminar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function checkPk($campo)
	{
		$campo = urldecode($campo);
		$campos = explode('|',$campo);
		$querycampos  = "";
			
		foreach($campos as $c)
			$querycampos .= $c.', ';
		
		$consulta = "select ".$querycampos." count(*) from tmp_catalogo group by ".substr($querycampos,0,strlen($querycampos)-2)." having count(*) > 1";
		$query = $this->db->query($consulta);
		
		//var_dump($consulta);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar el catálogo";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}
        
	/**
	 *Revisa en la base de datos por registros que no coincidan con el tipo de dato
         * pasado como parametro en el campo indicado
	 *
	 * @access public
	 *@param   string $campo (varios campos delimitados por | )
	 * @return boolean (Si no hubo errores al eliminar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function checkTypeData($campo,$type)
	{
            $consulta = '';
            switch ($type)
            {
                case 'int':
                    $consulta = "SELECT * FROM tmp_catalogo WHERE not ".$campo." REGEXP '^-?[0-9]+$'";
                case 'decimal':
                    $consulta = "SELECT * FROM tmp_catalogo WHERE not ".$campo." REGEXP '^[0-9]+(\.[0-9])?$'";
            }
            if ($consulta=='')
                echo 'true';
            else
            {
                $query = $this->db->query($consulta);
                if (!$query)
                {
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        $this->msg_error_usr = "Ocurrió un error al obtener tipos de datos de la colmna en tmp_catalogo";
                        //throw new Exception(__CLASS__);
                        echo 'false';
                }
                else
                {
                    if ($query->num_rows()>0)
                        echo 'false';
                    else
                        echo 'true';
                }
            }
	}

	/**
	 *Inserta en la base datos el catálogo y obtiene los datos
	 *de la tabla temporal
	 *
	 *@access  public
	 *@param string $create (la consulta para crear el catalogo)
	 *@param string $select (la consulta para extraer datos de la tabla tmp_catalogo)
	 *@return  boolean (si la insercion es correcta)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert($create,$select)
	{

		$query = $this->db->query($create);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar el catalogo";
			throw new Exception(__CLASS__);
		}
		else
		{
			$query = $this->db->query($select);

			if (!$query)
			{
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				$this->msg_error_usr = "Ocurrió un error al insertar el catálogo";
				throw new Exception(__CLASS__);
			}
			else
				return true;
		}
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

		$query = $this->db->query('drop table '.$this->nombre);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar el catálogo";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}
}