<?php

/**
 * Modelo Catalogo
 *
 * @author     Geovanni
 * @created    2013-10-07
 */
class CatalogoCsv_model extends CI_Model {

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
		$catalogos = $this->db->query('SELECT table_name as nombre FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = "'.$this->db->database.'" AND table_name in ('.CATALOGOSCSV.')');
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
         * Accion para activar o desactivar registros de catalogos como el de EDA, IRA y Consultas
         * @param type $id el id del registro en el catalogo
         * @param type $catalogo nombre del catalogo donde se realizara la operacion
         * @param type $valor agregar o eliminar el registro del catalogo
         * @return boolean como el resultado de la operación
         * @throws Exception Si ocurre algun error al consultar y modificar la base de datos
         */
        
        public function activaEnCatalogo($id,$catalogo,$valor)
        {   

            $consulta = "update ".$catalogo." set activo = ".(($valor == true) ? 1 : 0)." where id = '".$id."'";
            $datos = $this->db->query($consulta);

            if (!$datos)
            {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al activar el elemento en el catalogo".$catalogo;
                    throw new Exception(__CLASS__);
            }
            else
            {
                $this->db->query("update cns_tabla_catalogo set fecha_actualizacion = NOW() where descripcion='".$catalogo."'");
                return true;
            }
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
		
		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al revisar la llave primaria de los catalogos";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}
}