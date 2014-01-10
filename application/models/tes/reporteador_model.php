<?php
/**
 * Modelo Reporteador
 *
 * @package		TES
 * @subpackage	Modelo
 * @author     	Rogelio
 * @created		2013-12-20
 */
class Reporteador_model extends CI_Model {

   	/********************************************
   	 * Estas variables no pertenecen a la tabla *
   	* ******************************************/
   	
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_usr;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_log;
   	
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		if (!$this->db->conn_id)
			throw new Exception("No se pudo conectar a la base de datos");
	}

	/**
	 * Asigna el mensaje de error a visualizar: para usuario final (usr) o para bitácora (log)
	 *
	 * @access		public
	 * @param		string		$value		tipo de error a visualizar: usr o log, default: usr
	 * @return 		boolean		false 		si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}
	
	public function getCoberturaBiologicoListado($nivel, $id, $fecha)
	{
        $result = array();
		$sqlGrupoEtareo = "SELECT 	
                id, 
                descripcion
            FROM 
                asu_grupo_etareo
            ORDER BY dia_fin";
        
        $queryGrupoEtareo = $this->db->query($sqlGrupoEtareo);
        $resultGrupoEtareo = $queryGrupoEtareo->result();
        
        if (!$resultGrupoEtareo){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
        
        foreach ($resultGrupoEtareo as $grupoEtareo) {
            $objReporte = new Reporte_cobertura_biologico();
        
            $objReporte->grupo_etareo = $grupoEtareo->descripcion;
            $objReporte->pob_oficial = 2;
            $objReporte->pob_nominal = 3;
            $objReporte->concordancia = 4;
            $objReporte->bcg_tot = 5;
            $objReporte->bcg_cob = 6;
            $objReporte->hepB_tot = 7;
            $objReporte->hepB_cob = 8;
            $objReporte->penta_tot = 9;
            $objReporte->penta_cob = 10;
            $objReporte->neumo_tot = 11;
            $objReporte->neumo_cob = 12;
            $objReporte->rota_tot = 13;
            $objReporte->rota_cob = 14;
            $objReporte->srp_tot = 15;
            $objReporte->srp_cob = 16;
            $objReporte->dpt_tot = 17;
            $objReporte->dpt_cob = 18;
            $objReporte->esq_comp_tot = 19;
            $objReporte->esq_comp_oficial = 20;
            $objReporte->esq_comp_nominal = 21;

            $result[] = $objReporte;
        }
        
        return $result;
	}
	
	public function getConcentradoActividades($nivel, $id, $fecha)
	{
		$sql = "";
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
	
	public function getSeguimientoRV1RV5($nivel, $id, $fecha)
	{
		$sql = "";
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
	
	public function getCensoNominal($nivel, $id)
	{
        $result = array();
		$sqlIdsConTutor = "SELECT p.id,p.apellido_paterno,p.apellido_materno,p.nombre,p.calle_domicilio as domicilio,p.curp,p.fecha_nacimiento,p.sexo,'' AS edadEmb,
			'' AS esquema,t.apellido_paterno AS apellido_paterno_tutor,t.apellido_materno AS apellido_materno_tutor,
			t.nombre AS nombre_tutor,t.curp AS curp_tutor,t.sexo AS sexo_tutor,TIMESTAMPDIFF(DAY, fecha_nacimiento, CURDATE()) AS edad_dias
			FROM cns_persona p 
			INNER JOIN cns_persona_x_tutor pt ON p.id=pt.id_persona
			INNER JOIN cns_tutor t ON t.id=pt.id_tutor";
		switch($nivel){
			case 5:
				$sqlIdsConTutor .= " WHERE p.id_asu_um_tratante=".$id;
				break;
			case 4:
				$sqlIdsConTutor .= " WHERE p.id_asu_um_tratante IN (
									SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$id.")"; // ums por loc
				break;
			case 3:
				$sqlIdsConTutor .= " WHERE p.id_asu_um_tratante IN (
								SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
								SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$id.") )"; // locs por mpio
				break;
			case 2:
				$sqlIdsConTutor .= " WHERE p.id_asu_um_tratante IN (
							SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
							SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
							SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$id.") ) )"; // mpios por juris
				break;
			case 1:
				$sqlIdsConTutor .= " WHERE p.id_asu_um_tratante IN (
						SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
						SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
						SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
						SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$id.") ) ) )"; // juris por estado
				break;
		}
        $queryIdsConTutor = $this->db->query($sqlIdsConTutor);
        $resultIdsConTutor = $queryIdsConTutor->result();
        
        if (!$resultIdsConTutor){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
        
        foreach ($resultIdsConTutor as $IdConTutor) {
            //$IdConTutor->id;
            
        	$sqlVacunasAplicadas = "SELECT id_vacuna FROM cns_control_vacuna WHERE id_persona='".$IdConTutor->id."'";
        	$queryVacunasAplicadas = $this->db->query($sqlVacunasAplicadas);
        	$resultVacunasAplicadas = $this->object_to_array($queryVacunasAplicadas->result(), 'id_vacuna');
        	
//         	$sqlVacunasDebeTener = "SELECT id_vacuna FROM cns_regla_vacuna WHERE
// 			    (".$IdConTutor->edad_dias." >= dia_inicio_aplicacion_nacido AND
// 				".$IdConTutor->edad_dias." <= dia_fin_aplicacion_nacido) OR
// 				(dia_fin_aplicacion_nacido<=".$IdConTutor->edad_dias.")";
//         	$queryVacunasDebeTener = $this->db->query($sqlVacunasDebeTener);
//         	$resultVacunasDebeTener = $queryVacunasDebeTener->result();
        	
            // se inserta el registro del infante
        	$objReporte = new Reporte_censo_nominal();
            $objReporte->apellido_paterno = $IdConTutor->apellido_paterno;
            $objReporte->apellido_materno = $IdConTutor->apellido_materno;
            $objReporte->nombre = $IdConTutor->apellido_materno;
            $objReporte->domicilio = $IdConTutor->domicilio;
            $objReporte->curp = $IdConTutor->curp;
            $objReporte->fecha_nacimiento = $IdConTutor->fecha_nacimiento;
            $objReporte->sexo = $IdConTutor->sexo;
            $objReporte->bcg = in_array("1", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->hepaB1 = in_array("2", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->hepaB2 = in_array("3", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->hepaB3 = in_array("4", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->penta1 = in_array("5", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->penta2 = in_array("6", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->penta3 = in_array("7", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->penta4 = in_array("8", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->dptR = in_array("9", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->srp1 = in_array("19", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->srp2 = in_array("20", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->rota1 = in_array("10", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->rota2 = in_array("11", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->rota3 = in_array("12", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->neumo1 = in_array("13", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->neumo2 = in_array("14", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->neumo3 = in_array("15", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->influenza1 = in_array("16", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->influenza2 = in_array("17", $resultVacunasAplicadas) ? 'x' : '';
            $objReporte->influenzaR = in_array("18", $resultVacunasAplicadas) ? 'x' : '';
            $result[] = $objReporte;
            // se inserta el registro del tutor
            $objReporte = new Reporte_censo_nominal();
            $objReporte->apellido_paterno = $IdConTutor->apellido_paterno_tutor;
            $objReporte->apellido_materno = $IdConTutor->apellido_materno_tutor;
            $objReporte->nombre = $IdConTutor->nombre_tutor;
            $objReporte->domicilio = '';
            $objReporte->curp = $IdConTutor->curp_tutor;
            $objReporte->fecha_nacimiento = '';
            $objReporte->sexo = $IdConTutor->sexo_tutor;
            $objReporte->bcg = '';
            $objReporte->hepaB1 = '';
            $objReporte->hepaB2 = '';
            $objReporte->hepaB3 = '';
            $objReporte->penta1 = '';
            $objReporte->penta2 = '';
            $objReporte->penta3 = '';
            $objReporte->penta4 = '';
            $objReporte->dptR = '';
            $objReporte->srp1 = '';
            $objReporte->srp2 = '';
            $objReporte->rota1 = '';
            $objReporte->rota2 = '';
            $objReporte->rota3 = '';
            $objReporte->neumo1 = '';
            $objReporte->neumo2 = '';
            $objReporte->neumo3 = '';
            $objReporte->influenza1 = '';
            $objReporte->influenza2 = '';
            $objReporte->influenzaR = '';
            $result[] = $objReporte;
        }
        
        return $result;
	}
	
	public function getEsquemasIncompletos($nivel, $id)
	{
		$sql = "";
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
	
	// convierte array de objetos en array simple de un valor
	function object_to_array($data, $campo1)
	{
		$result = array();
		$i = 0;
		foreach ($data as $key => $value)
		{
			$result[$i] = $value->$campo1;
			$i++;
		}
		return $result;
	}
}
?>