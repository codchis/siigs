<?php

/**
 * Modelo ReglaVacuna
 * 
 * @package    SIIGS
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
	 * @var    int
	 */
	private $id_via_vacuna;

        /**
	 * @access private
	 * @var    float
	 */
	private $dosis;
        
	/**
	 * @access private
	 * @var    string
	 */
	private $region;
        
	/**
	 * @access private
	 * @var    bool
	 */
	private $esq_com;

	/**
	 * @access private
	 * @var    int
	 */
	private $orden_esq_comp;
        
	/**
	 * @access private
	 * @var    array(int)
	 */
	private $alergias;
        
	/**
	 * @access private
	 * @var    boolean
	 */
	private $forzar_aplicacion;
        
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
        
        public function getIdViaVacuna() {
		return $this->id_via_vacuna;
	}
	public function setIdViaVacuna($value) {
		$this->id_via_vacuna = $value;
	}
        
        public function getDosis() {
		return $this->dosis;
	}
	public function setDosis($value) {
		$this->dosis = $value;
	}
        
        public function getRegion() {
		return $this->region;
	}
	public function setRegion($value) {
		$this->region = $value;
	}
        
        public function getEsqComp() {
		return $this->esq_com;
	}
	public function setEsqComp($value) {
		$this->esq_com = $value;
	}
        
        public function getOrdenEsqComp() {
		return $this->orden_esq_comp;
	}
	public function setOrdenEsqComp($value) {
		$this->orden_esq_comp = $value;
	}
        
        public function getAlergias() {
		return $this->alergias;
	}
	public function setAlergias($value) {
		$this->alergias = $value;
	}
        
        public function getForzarAplicacion() {
		return $this->forzar_aplicacion;
	}
	public function setForzarAplicacion($value) {
		$this->forzar_aplicacion = $value;
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
		$query = $this->db->query("SELECT distinct a.id,b.descripcion as vacuna,a.id_vacuna_secuencial , CASE WHEN IFNULL(a.dia_inicio_aplicacion_nacido,'') = '' THEN 'Secuencial' ELSE 'Nacimiento' END AS aplicacion , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_inicio_aplicacion_secuencial else a.dia_inicio_aplicacion_nacido end as desde , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_fin_aplicacion_secuencial else a.dia_fin_aplicacion_nacido end as hasta , case when ifnull(a.id_vacuna_secuencial,'') = '' then 'Ninguna' else c.descripcion end as previa, case when ifnull(a.id_via_vacuna,'') = '' then '' else d.descripcion end as via_vacuna, a.dosis as dosis, a.region as region, a.esq_com, a.orden_esq_com, a.alergias FROM cns_regla_vacuna a join cns_vacuna b on a.id_vacuna = b.id and b.activo = 1 left outer join cns_vacuna c on a.id_vacuna_secuencial = c.id and c.activo = 1 left outer join cns_via_vacuna d on a.id_via_vacuna = d.id");
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
		$query = $this->db->query("SELECT distinct a.id,a.id_vacuna,a.id_via_vacuna,a.id_vacuna_secuencial,b.descripcion as vacuna , CASE WHEN IFNULL(a.dia_inicio_aplicacion_nacido,'') = '' THEN 'Secuencial' ELSE 'Nacimiento' END AS aplicacion , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_inicio_aplicacion_secuencial else a.dia_inicio_aplicacion_nacido end as desde , case when ifnull(a.dia_inicio_aplicacion_nacido,'') = '' then a.dia_fin_aplicacion_secuencial else a.dia_fin_aplicacion_nacido end as hasta , case when ifnull(a.id_vacuna_secuencial,'') = '' then 'Ninguna' else c.descripcion end as previa, a.dia_inicio_aplicacion_secuencial as desdese, a.dia_fin_aplicacion_secuencial as hastase, case when ifnull(a.id_via_vacuna,'') = '' then '' else d.descripcion end as via_vacuna, a.dosis as dosis, a.region as region, a.esq_com, a.orden_esq_com, a.alergias as id_alergias,a.forzar_aplicacion FROM cns_regla_vacuna a join cns_vacuna b on a.id_vacuna = b.id and b.activo = 1 left outer join cns_vacuna c on a.id_vacuna_secuencial = c.id and c.activo = 1 left outer join cns_via_vacuna d on a.id_via_vacuna = d.id where a.id=".$id);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información de la regla";
			throw new Exception(__CLASS__);
		}
		else
                {
		$info = $query->row();
                //var_dump($info);
                $descalergias = '';
                if(!empty($info->id_alergias))
                if (count($info->id_alergias)>0)
                {
                $infoalergias = $this->db->query("select * from cns_alergia where id in (".$info->id_alergias.")");                
                if ($infoalergias)
                if (count($infoalergias->result())>0)
                {
                    foreach($infoalergias->result() as $infoalergia)
                    $descalergias.= $infoalergia->descripcion.", ";
                    $descalergias = substr($descalergias, 0, count($descalergias)-3);
                }
                }
                $info->alergias = $descalergias;
                return $info;
                }
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
				'dia_inicio_aplicacion_nacido' => ($this->dia_inicio_nacido == 0) ? 0 : $this->dia_inicio_nacido,
				'dia_fin_aplicacion_nacido' => ($this->dia_fin_nacido == 0) ? 0 : $this->dia_fin_nacido,
                                'dia_inicio_aplicacion_secuencial' => ($this->dia_inicio_previa == 0) ? null : $this->dia_inicio_previa,
                                'dia_fin_aplicacion_secuencial' => ($this->dia_fin_previa == 0) ? null : $this->dia_fin_previa,
                                'id_via_vacuna' => $this->id_via_vacuna,
                                'dosis' => $this->dosis,
                                'region' => $this->region,
                                'alergias' => (!empty($this->alergias)) ? $this->alergias : null,
                                'esq_com' => $this->esq_com,
                                'forzar_aplicacion' => $this->forzar_aplicacion,
                                'ultima_actualizacion' => date('Y-m-d H:i:s')
		);
                
                $data['orden_esq_com'] = ($this->esq_com) ? $this->orden_esq_comp : null;

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
				'dia_inicio_aplicacion_nacido' => ($this->dia_inicio_nacido == 0) ? 0 : $this->dia_inicio_nacido,
				'dia_fin_aplicacion_nacido' => ($this->dia_fin_nacido == 0) ? 0 : $this->dia_fin_nacido,
                                'dia_inicio_aplicacion_secuencial' => ($this->dia_inicio_previa == 0) ? null : $this->dia_inicio_previa,
                                'dia_fin_aplicacion_secuencial' => ($this->dia_fin_previa == 0) ? null : $this->dia_fin_previa,
                                'id_via_vacuna' => $this->id_via_vacuna,
                                'dosis' => $this->dosis,
                                'region' => $this->region,
                                'alergias' => (!empty($this->alergias)) ? $this->alergias : null,
                                'esq_com' => $this->esq_com,
                                'forzar_aplicacion' => $this->forzar_aplicacion,
                                'ultima_actualizacion' => date('Y-m-d H:i:s')
		);

                $data['orden_esq_com'] = ($this->esq_com) ? $this->orden_esq_comp : null;
                
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