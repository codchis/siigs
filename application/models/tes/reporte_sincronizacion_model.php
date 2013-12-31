<?php
/**
 * Modelo Usuario
 *
 * @package     TES
 * @subpackage  Modelo
 * @author     	Eliecer
 * @created     2013-12-17
 */
class Reporte_sincronizacion_model extends CI_Model 
{
	/**
	 * Guarda la instancia del objeto global CodeIgniter
	 * para utilizarlo en la función estática
	 *
	 * @access private
	 * @var    instance
	 */
	private static $CI;
	
	

	public function getListado($sql)
	{

		$query = $this->db->query($sql); //echo $this->db->last_query();
	
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return;
	}
	
	public function getCount($tabla,$sentecia="")
	{
		if($sentecia!="")
			$query=$this->db->query($sentecia);
		else
			$query=$this->db->get($tabla);
		return $query->num_rows();  
	}
	
	public function get_version()
	{
		$this->db->select('host,fecha_liberacion');
		$this->db->select_max('version');
		$this->db->from('tes_version');
		$query = $this->db->get(); //echo $this->db->last_query();
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return null;
	}
	
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}
}