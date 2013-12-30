<?php

/**
 * Modelo ReglaVacuna
 * 
 * @package    TES
 * @subpackage Modelo
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
	private $id_vacuna_previa;
        
	/**
	 * @access private
	 * @var    int
	 */
	private $dia_inicio_nacido;
        
	/**
	 * @access private
	 * @var    int
	 */
	private $dia_fin_nacido;
        
	/**
	 * @access private
	 * @var    int
	 */
	private $dia_inicio_previa;
        
	/**
	 * @access private
	 * @var    int
	 */
	private $dia_fin_previa;


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

        public function getIdVacuna() {
		return $this->id_vacuna;
	}
	public function setIdVacuna($value) {
		$this->id_vacuna = $value;
	}

        public function getIdVacunaPrevia() {
		return $this->id_vacuna_previa;
	}
	public function setIdVacunaPrevia($value) {
		$this->id_vacuna_previa = $value;
	}
        
        public function getDiaInicioNacido() {
		return $this->dia_inicio_nacido;
	}
	public function setDiaInicioNacido($value) {
		$this->dia_inicio_nacido = $value;
	}
        
        public function getDiaFinNacido() {
		return $this->dia_fin_nacido;
	}
	public function setDiaFinNacido($value) {
		$this->dia_fin_nacido = $value;
	}

        public function getDiaInicioPrevia() {
		return $this->dia_inicio_previa;
	}
	public function setDiaInicioPrevia($value) {
		$this->dia_inicio_previa = $value;
	}
        
        public function getDiaFinPrevia() {
		return $this->dia_fin_previa;
	}
	public function setDiaFinPrevia($value) {
		$this->dia_fin_previa = $value;
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
	 *Devuelve todos los registros de la tabla regla_vacuna
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$query = $this->db->query("SELECT distinct a.id,b.descripcion as vacuna,a.id_vacuna_secuencial , CASE WHEN IFNULL(a.dia_inicio_aplicacion_nacido,'') = '' THEN 'Secuencial' ELSE 'Nacimiento' END AS aplicacion , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_inicio_aplicacion_secuencial else a.dia_inicio_aplicacion_nacido end as desde , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_fin_aplicacion_secuencial else a.dia_fin_aplicacion_nacido end as hasta , case when ifnull(a.id_vacuna_secuencial,'') = '' then 'Ninguna' else c.descripcion end as previa FROM cns_regla_vacuna a join cns_vacuna b on a.id_vacuna = b.id and b.activo = 1 left outer join cns_vacuna c on a.id_vacuna_secuencial = c.id and c.activo = 1");

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
	 *Devuelve la información de una regla de vacuna por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{
		$query = $this->db->query("SELECT distinct a.id,a.id_vacuna,a.id_vacuna_secuencial,b.descripcion as vacuna , CASE WHEN IFNULL(a.dia_inicio_aplicacion_nacido,'') = '' THEN 'Secuencial' ELSE 'Nacimiento' END AS aplicacion , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_inicio_aplicacion_secuencial else a.dia_inicio_aplicacion_nacido end as desde , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_fin_aplicacion_secuencial else a.dia_fin_aplicacion_nacido end as hasta , case when ifnull(a.id_vacuna_secuencial,'') = '' then 'Ninguna' else c.descripcion end as previa, a.dia_inicio_aplicacion_secuencial as desdese, a.dia_fin_aplicacion_secuencial as hastase FROM cns_regla_vacuna a join cns_vacuna b on a.id_vacuna = b.id and b.activo = 1 left outer join cns_vacuna c on a.id_vacuna_secuencial = c.id and c.activo = 1 where a.id=".$id);

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
	 *Inserta en la tabla regla_vacuna la información contenida en el objeto
	 *
	 *@access  public
	 *@return  int (Id de la inserción si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert()
	{
		$data = array(
				'id_vacuna' => $this->id_vacuna,
                                'id_vacuna_secuencial' => $this->id_vacuna_previa,
				'dia_inicio_aplicacion_nacido' => ($this->dia_inicio_nacido == 0) ? null : $this->dia_inicio_nacido,
				'dia_fin_aplicacion_nacido' => ($this->dia_fin_nacido == 0) ? null : $this->dia_fin_nacido,
                                'dia_inicio_aplicacion_secuencial' => ($this->dia_inicio_previa == 0) ? null : $this->dia_inicio_previa,
                                'dia_fin_aplicacion_secuencial' => ($this->dia_fin_previa == 0) ? null : $this->dia_fin_previa,
                                'ultima_actualizacion' => date('Y-m-d H:i:s')
		);

		$query = $this->db->insert('cns_regla_vacuna', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar la regla";
			throw new Exception(__CLASS__);
		}
		else
                {
                    $this->db->query("update cns_tabla_catalogo set fecha_actualizacion = NOW() where descripcion='cns_regla_vacuna'");
                    return $this->db->insert_id($query);
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
				'id_vacuna' => $this->id_vacuna,
                                'id_vacuna_secuencial' => $this->id_vacuna_previa,
				'dia_inicio_aplicacion_nacido' => ($this->dia_inicio_nacido == 0) ? null : $this->dia_inicio_nacido,
				'dia_fin_aplicacion_nacido' => ($this->dia_fin_nacido == 0) ? null : $this->dia_fin_nacido,
                                'dia_inicio_aplicacion_secuencial' => ($this->dia_inicio_previa == 0) ? null : $this->dia_inicio_previa,
                                'dia_fin_aplicacion_secuencial' => ($this->dia_fin_previa == 0) ? null : $this->dia_fin_previa,
                                'ultima_actualizacion' => date('Y-m-d H:i:s')
		);

		$this->db->where('id' , $this->getId());
		$query = $this->db->update('cns_regla_vacuna', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al actualizar los datos de la regla";
			throw new Exception(__CLASS__);
		}
		else
                {
                    $this->db->query("update cns_tabla_catalogo set fecha_actualizacion = NOW() where descripcion='cns_regla_vacuna'");
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

		$query = $this->db->delete('cns_regla_vacuna', array('id' => $this->getId()));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar la regla";
			throw new Exception(__CLASS__);
		}
		else
                {
                    $this->db->query("update cns_tabla_catalogo set fecha_actualizacion = NOW() where descripcion='cns_regla_vacuna'");
                    return true;
                }
	}
}