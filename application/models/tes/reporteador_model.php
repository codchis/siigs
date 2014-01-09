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
        
        // Se obtiene datos del asu
        $queryAsu = $this->db->query('SELECT * FROM asu_arbol_segmentacion WHERE id='.$id);
        $resultAsu = $queryAsu->result();
        
        echo 'nivel: '.$nivel.', id: '.$id.', fecha: '.$fecha;
        
        foreach ($resultGrupoEtareo as $grupoEtareo) {
            $idsAsu = array();
            $objReporte = new Reporte_cobertura_biologico();
            
            switch ($resultAsu->grado_segmentacion) {
                case 1: // Estado
                    // Obtiene todos los municipios del estado
                    $queryIdsAsu = 'SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
                                        SELECT id FROM asu_arbol_segmentacion WHERE id_padre='.$id.' 
                                    )';
                    $resultIdsAsu = $queryIdsAsu->result();
                    
                    if (!$resultIdsAsu){
                        $this->msg_error_usr = "Servicio temporalmente no disponible.";
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                    }
                    
                    foreach ($idsAsu as $tempAsu) {
                        $idsAsu[] = $tempAsu->id;
                    }
                    
                    break;
                case 2: // Jurisdiccion
                    // Obtiene todos los municipios de la jurisdiccion
                    $queryIdsAsu = 'SELECT id FROM asu_arbol_segmentacion WHERE id_padre = '.$id;
                    $resultIdsAsu = $queryIdsAsu->result();
                    
                    if (!$resultIdsAsu){
                        $this->msg_error_usr = "Servicio temporalmente no disponible.";
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                    }
                    
                    foreach ($idsAsu as $tempAsu) {
                        $idsAsu[] = $tempAsu->id;
                    }
                    
                    break;
                case 3: // Municipio
                    $idsAsu = array($id);
                    break;
                default:
                    $this->msg_error_usr = "El grado de segmentación especifico no es valido para este reporte";
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception("El grado de segmentación especifico no es valido para este reporte");
                    break;
            }
            
            // Corrige el grupo etareo
            // se toma la poblacion de menores de uno para todos los meses
            $idGrupoEtareo = $grupoEtareo->id;
            
            if($idGrupoEtareo>=10 && $idGrupoEtareo<=21) {
                $idGrupoEtareo = 1;
            }
            
            $queryPob = $this->db->query('SELECT 	
                    SUM(poblacion) AS poblacion
                FROM 
                    asu_poblacion
                WHERE 
                    id_asu IN ('.implode(',', $idsAsu).') AND 
                    id_grupo_etareo = '.$idGrupoEtareo.' AND
                    ano = 2013');
            $resultPob = $queryPob->result();

            if (!$resultPob){
                $this->msg_error_usr = "Servicio temporalmente no disponible.";
                $this->msg_error_log = "No se pudo obtener los datos de la población";
                throw new Exception("No se pudo obtener los datos de la población");
            }
            
        
            $objReporte->grupo_etareo = $grupoEtareo->descripcion;
            $objReporte->pob_oficial = $resultPob->poblacion;
            $objReporte->pob_nominal = 3;
            $objReporte->concordancia = 4;
            $objReporte->bcg_tot = 5;
            $objReporte->bcg_cob = round($objReporte->bcg_tot/$objReporte->pob_oficial, 2);
            $objReporte->hepB_tot = 7;
            $objReporte->hepB_cob = round($objReporte->hepB_tot/$objReporte->pob_oficial, 2);
            $objReporte->penta_tot = 9;
            $objReporte->penta_cob = round($objReporte->penta_tot/$objReporte->pob_oficial, 2);
            $objReporte->neumo_tot = 11;
            $objReporte->neumo_cob = round($objReporte->neumo_tot/$objReporte->pob_oficial, 2);
            $objReporte->rota_tot = 13;
            $objReporte->rota_cob = round($objReporte->rota_tot/$objReporte->pob_oficial, 2);
            $objReporte->srp_tot = 15;
            $objReporte->srp_cob = round($objReporte->srp_tot/$objReporte->pob_oficial, 2);
            $objReporte->dpt_tot = 17;
            $objReporte->dpt_cob = round($objReporte->dpt_tot/$objReporte->pob_oficial, 2);
            $objReporte->esq_comp_tot = 19;
            $objReporte->esq_comp_oficial = round($objReporte->esq_comp_tot/$objReporte->pob_oficial, 2);
            $objReporte->esq_comp_nominal = round($objReporte->esq_comp_tot/$objReporte->pob_nominal, 2);

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
        	$resultVacunasAplicadas = $queryVacunasAplicadas->result();

        	$sqlVacunasDebeTener = "SELECT id_vacuna FROM cns_regla_vacuna WHERE
			    (".$IdConTutor->edad_dias." >= dia_inicio_aplicacion_nacido AND
				".$IdConTutor->edad_dias." <= dia_fin_aplicacion_nacido) OR
				(dia_fin_aplicacion_nacido<=".$IdConTutor->edad_dias.")";
        	$queryVacunasDebeTener = $this->db->query($sqlVacunasDebeTener);
        	$resultVacunasDebeTener = $queryVacunasDebeTener->result();
        	
            // se inserta el registro del infante
        	$objReporte = new Reporte_censo_nominal();
            $objReporte->apellido_paterno = $IdConTutor->apellido_paterno;
            $objReporte->apellido_materno = $IdConTutor->apellido_materno;
            $objReporte->nombre = $IdConTutor->apellido_materno;
            $objReporte->domicilio = $IdConTutor->domicilio;
            $objReporte->curp = $IdConTutor->curp;
            $objReporte->fecha_nacimiento = $IdConTutor->fecha_nacimiento;
            $objReporte->sexo = $IdConTutor->sexo;
            $objReporte->bcg = 'x';
            $objReporte->hepaB1 = 'x';
            $objReporte->hepaB2 = 'x';
            $objReporte->hepaB3 = 'x';
            $objReporte->penta1 = 'x';
            $objReporte->penta2 = 'x';
            $objReporte->penta3 = 'x';
            $objReporte->penta4 = '-';
            $objReporte->dpt1 = '';
            $objReporte->dpt2 = '';
            $objReporte->dpt3 = '-';
            $objReporte->srp1 = 'x';
            $objReporte->srp2 = '';
            $objReporte->rota1 = '|';
            $objReporte->rota2 = '|';
            $objReporte->rota3 = '';
            $objReporte->neumo1 = 'T';
            $objReporte->neumo2 = 'T';
            $objReporte->neumo3 = 'T';
            $objReporte->influenza1 = 'x';
            $objReporte->influenza2 = '-';
            $objReporte->influenzaR = '-';
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
            $objReporte->dpt1 = '';
            $objReporte->dpt2 = '';
            $objReporte->dpt3 = '';
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
	
}
?>