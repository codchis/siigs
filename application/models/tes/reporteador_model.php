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
	
	public function getCensoNominal($nivel, $id, &$th)
	{
        $result = array();
		$sqlIdsConTutor = "SELECT p.id,p.apellido_paterno,p.apellido_materno,p.nombre,p.calle_domicilio as domicilio,p.curp,p.fecha_nacimiento,p.sexo,'' AS edadEmb,
			'' AS esquema,t.apellido_paterno AS apellido_paterno_tutor,t.apellido_materno AS apellido_materno_tutor,
			t.nombre AS nombre_tutor,t.curp AS curp_tutor,t.sexo AS sexo_tutor
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
        
		$sqlVacunasEsquemaCompleto = "SELECT v.id,v.descripcion FROM cns_regla_vacuna rv INNER JOIN cns_vacuna v ON rv.id_vacuna=v.id
											WHERE rv.esq_com=1 ORDER BY rv.orden_esq_com";
		$queryVacunasEsquemaCompleto = $this->db->query($sqlVacunasEsquemaCompleto);
		$resultVacunasEsquemaCompleto = $queryVacunasEsquemaCompleto->result();
		$th = '<tr><th>Apellido Paterno</th><th>Apellido Materno</th><th>Nombre</th>
						<th>Domicilio</th><th>CURP</th><th>Fecha Nac</th><th>Sexo</th>';
		foreach($resultVacunasEsquemaCompleto as $vacuna){
			$th.= '<th>'.$vacuna->descripcion.'</th>';
		}
		$th.='</tr>';
		
        foreach ($resultIdsConTutor as $IdConTutor) {
        	$sqlVacunasAplicadas = "SELECT id_vacuna FROM cns_control_vacuna WHERE id_persona='".$IdConTutor->id."'";
        	$queryVacunasAplicadas = $this->db->query($sqlVacunasAplicadas);
        	$resultVacunasAplicadas = $this->object_to_array($queryVacunasAplicadas->result(), 'id_vacuna');
        	
            // se inserta el registro del infante
        	$objReporte = new Reporte_censo_nominal();
            $objReporte->apellido_paterno = $IdConTutor->apellido_paterno;
            $objReporte->apellido_materno = $IdConTutor->apellido_materno;
            $objReporte->nombre = $IdConTutor->apellido_materno;
            $objReporte->domicilio = $IdConTutor->domicilio;
            $objReporte->curp = $IdConTutor->curp;
            $objReporte->fecha_nacimiento = $IdConTutor->fecha_nacimiento;
            $objReporte->sexo = $IdConTutor->sexo;
            foreach ($resultVacunasEsquemaCompleto as $vacuna){
            	$objReporte->vacunas[$vacuna->id] = in_array($vacuna->id, $resultVacunasAplicadas) ? 'x' : '';
            }
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
            foreach ($resultVacunasEsquemaCompleto as $vacuna){
            	$objReporte->vacunas[$vacuna->id] = '';
            }
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