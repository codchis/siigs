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
	
    /**
	 * Obtiene el reporte de cobertura por tipo de biologico
	 *
	 * @access  public
	 * @param   int     $nivel  Nivel del elemento del arbol ASU
     * @param   int     $id     Identificador del elemento ASU
     * @param   date    $fecha  Fecha de corte de elemento
	 * @return  void
	 */
	public function getCoberturaBiologicoListado($nivel, $id, $fecha)
	{
        $result = array();
        $idsAsu = array();
		$sqlGrupoEtareo = "SELECT * FROM asu_grupo_etareo ORDER BY dia_fin";
        
        $queryGrupoEtareo = $this->db->query($sqlGrupoEtareo);
        $resultGrupoEtareo = $queryGrupoEtareo->result();
        
        if (!$resultGrupoEtareo){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
        
        // Se obtiene datos del asu
        $queryAsu = $this->db->query('SELECT * FROM asu_arbol_segmentacion WHERE id='.$id);
        $resultAsu = $queryAsu->row();
        
        if (!$resultAsu){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "No se encuentra el Identificador de ASU";
			throw new Exception("No se encuentra el Identificador de ASU");
		}
        
        switch ($resultAsu->grado_segmentacion) {
            case 1: // Estado
                // Obtiene todos los municipios del estado
                $queryIdsAsu = $this->db->query('SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN 
                                    ( SELECT id FROM asu_arbol_segmentacion WHERE id_padre='.$id.' )');
                $resultIdsAsu = $queryIdsAsu->result();

                if (!$resultIdsAsu){
                    $this->msg_error_usr = "Servicio temporalmente no disponible.";
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                }

                foreach ($resultIdsAsu as $tempAsu) {
                    $idsAsu[] = $tempAsu->id;
                }

                break;
            case 2: // Jurisdiccion
                // Obtiene todos los municipios de la jurisdiccion
                $queryIdsAsu = $this->db->query('SELECT id FROM asu_arbol_segmentacion WHERE id_padre = '.$id);
                $resultIdsAsu = $queryIdsAsu->result();

                if (!$resultIdsAsu){
                    $this->msg_error_usr = "Servicio temporalmente no disponible.";
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                }

                foreach ($resultIdsAsu as $tempAsu) {
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

        //echo implode(',', $idsAsu);
        
        foreach ($resultGrupoEtareo as $grupoEtareo) {
            $objReporte = new Reporte_cobertura_biologico();
            // Corrige el grupo etareo
            // se toma la poblacion de menores de uno para todos grupos de meses
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
            
            $resultPob = $queryPob->row();

            if (!$resultPob) {
                $this->msg_error_usr = "Servicio temporalmente no disponible.";
                $this->msg_error_log = "No se pudo obtener los datos de la población";
                throw new Exception("No se pudo obtener los datos de la población");
            }
            
            $queryNom = $this->db->query('SELECT 
                    COUNT(id) AS nominal 
                FROM 
                    cns_persona 
                WHERE 
                    id_asu_um_tratante IN (
                        SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
                            SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN ('.implode(',', $idsAsu).')
                        )
                    ) AND 
                    TIMESTAMPDIFF(DAY, fecha_nacimiento, "'.formatFecha($fecha, 'Y-m-d').'")
                        BETWEEN '.$grupoEtareo->dia_inicio.' AND '.$grupoEtareo->dia_fin);
            
            $resultNom = $queryNom->row();
            
            // NOTA: Revisar los ID de las vacunas si cambian el catalogo cns_vacuna
            $queryBCG = $this->db->query('SELECT 
                    COUNT(cns_persona.id) AS total
                FROM 
                    cns_persona
                INNER JOIN
                    cns_control_vacuna 
                        ON cns_persona.id = cns_control_vacuna.id_persona
                WHERE 
                    id_vacuna=1 AND
                    id_asu_um_tratante IN (
                        SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
                            SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN ('.implode(',', $idsAsu).')
                        )
                    ) AND 
                    TIMESTAMPDIFF(DAY, fecha_nacimiento, "'.formatFecha($fecha, 'Y-m-d').'")
                        BETWEEN '.$grupoEtareo->dia_inicio.' AND '.$grupoEtareo->dia_fin);
            $resultBCG = $queryBCG->row();
            
            $queryHepB = $this->db->query('SELECT 
                    COUNT(cns_persona.id) AS total
                FROM 
                    cns_persona
                INNER JOIN
                    cns_control_vacuna 
                        ON cns_persona.id = cns_control_vacuna.id_persona
                WHERE 
                    cns_control_vacuna.fecha<="'.formatFecha($fecha, 'Y-m-d').'" AND
                    id_vacuna IN (2,3,4) AND
                    id_asu_um_tratante IN (
                        SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
                            SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN ('.implode(',', $idsAsu).')
                        )
                    ) AND 
                    TIMESTAMPDIFF(DAY, fecha_nacimiento, "'.formatFecha($fecha, 'Y-m-d').'")
                        BETWEEN '.$grupoEtareo->dia_inicio.' AND '.$grupoEtareo->dia_fin);
            $resultHepB = $queryHepB->row();
            
            $objReporte->grupo_etareo = $grupoEtareo->descripcion;
            $objReporte->pob_oficial = (int)$resultPob->poblacion;
            $objReporte->pob_nominal = (int)$resultNom->nominal;
            $objReporte->concordancia = $objReporte->pob_oficial ? round($objReporte->pob_nominal/$objReporte->pob_oficial, 2) : 0;
            $objReporte->bcg_tot = $resultBCG->total;
            $objReporte->bcg_cob = $objReporte->pob_oficial ? round($objReporte->bcg_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->hepB_tot = $resultHepB->total;
            $objReporte->hepB_cob = $objReporte->pob_oficial ? round($objReporte->hepB_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->penta_tot = 9;
            $objReporte->penta_cob = $objReporte->pob_oficial ? round($objReporte->penta_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->neumo_tot = 11;
            $objReporte->neumo_cob = $objReporte->pob_oficial ? round($objReporte->neumo_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->rota_tot = 13;
            $objReporte->rota_cob = $objReporte->pob_oficial ? round($objReporte->rota_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->srp_tot = 15;
            $objReporte->srp_cob = $objReporte->pob_oficial ? round($objReporte->srp_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->dpt_tot = 17;
            $objReporte->dpt_cob = $objReporte->pob_oficial ? round($objReporte->dpt_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->esq_comp_tot = 19;
            $objReporte->esq_comp_oficial = $objReporte->pob_oficial ? round($objReporte->esq_comp_tot/$objReporte->pob_oficial, 2) : 0;
            $objReporte->esq_comp_nominal = $objReporte->pob_nominal ? round($objReporte->esq_comp_tot/$objReporte->pob_nominal, 2) : 0;

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
        
		$sqlVacunasEsquemaCompleto = "SELECT v.id,v.descripcion_corta as descripcion FROM cns_regla_vacuna rv INNER JOIN cns_vacuna v ON rv.id_vacuna=v.id
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
            $objReporte->nombre = $IdConTutor->nombre;
            $objReporte->domicilio = $IdConTutor->domicilio;
            $objReporte->curp = $IdConTutor->curp;
            $objReporte->fecha_nacimiento = $IdConTutor->fecha_nacimiento;
            $objReporte->sexo = $IdConTutor->sexo;
            foreach ($resultVacunasEsquemaCompleto as $vacuna){
            	$objReporte->vacunas[$vacuna->id] = in_array($vacuna->id, $resultVacunasAplicadas) ? VACUNA_APLICADA : VACUNA_NOAPLICADA;
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
            	$objReporte->vacunas[$vacuna->id] = VACUNA_NOAPLICADA;
            }
            $result[] = $objReporte;
        }
        return $result;
	}
	
	public function getEsquemasIncompletos($nivel, $id, &$th)
	{
        $result = array();
		$sqlIdsConTutor = "SELECT p.id,p.apellido_paterno,p.apellido_materno,p.nombre,p.calle_domicilio as domicilio,p.curp,p.fecha_nacimiento,p.sexo,'' AS edadEmb,
			'' AS esquema,t.apellido_paterno AS apellido_paterno_tutor,t.apellido_materno AS apellido_materno_tutor,
			t.nombre AS nombre_tutor,t.curp AS curp_tutor,t.sexo AS sexo_tutor, TIMESTAMPDIFF(DAY, fecha_nacimiento, CURDATE()) AS edad_dias 
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
        
		$sqlVacunasEsquemaCompleto = "SELECT v.id,v.descripcion_corta as descripcion FROM cns_regla_vacuna rv INNER JOIN cns_vacuna v ON rv.id_vacuna=v.id
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

        	$sqlVacunasCorresponden = "SELECT 	id,	id_vacuna,	dia_inicio_aplicacion_nacido,	dia_fin_aplicacion_nacido 
        			FROM cns_regla_vacuna WHERE  
				    (".$IdConTutor->edad_dias." >= dia_inicio_aplicacion_nacido AND
					".$IdConTutor->edad_dias." <= dia_fin_aplicacion_nacido) OR
					(dia_fin_aplicacion_nacido<=".$IdConTutor->edad_dias.")";
        	$queryVacunasCorresponden = $this->db->query($sqlVacunasCorresponden);
        	$resultVacunasCorresponden = $this->object_to_array($queryVacunasCorresponden->result(), 'id_vacuna');
        	
//         	echo $IdConTutor->id."(".$IdConTutor->edad_dias.")<br>";
//         	var_dump($resultVacunasAplicadas);
//         	echo "<br>";
//         	var_dump($resultVacunasCorresponden);
//         	echo "<br>";
        	
        	// el infante debe aparecer si no tiene todas las vacunas que le correspondan
        	$puestas = 0;
        	foreach ($resultVacunasAplicadas as $vacunaPuesta){
        		foreach ($resultVacunasCorresponden as $vacunaCorresponde){
        			if ($vacunaPuesta == $vacunaCorresponde)
        				$puestas++;
        		}
        	}
        	if ($puestas != count($resultVacunasCorresponden)){
        		// se inserta el registro del infante
        		$objReporte = new Reporte_censo_nominal();
        		$objReporte->apellido_paterno = $IdConTutor->apellido_paterno;
        		$objReporte->apellido_materno = $IdConTutor->apellido_materno;
        		$objReporte->nombre = $IdConTutor->nombre;
        		$objReporte->domicilio = $IdConTutor->domicilio;
        		$objReporte->curp = $IdConTutor->curp;
        		$objReporte->fecha_nacimiento = $IdConTutor->fecha_nacimiento;
        		$objReporte->sexo = $IdConTutor->sexo;
        		foreach ($resultVacunasEsquemaCompleto as $vacuna){
        			$objReporte->vacunas[$vacuna->id] = in_array($vacuna->id, $resultVacunasAplicadas) ? VACUNA_APLICADA : VACUNA_NOAPLICADA;
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
        			$objReporte->vacunas[$vacuna->id] = VACUNA_NOAPLICADA;
        		}
        		$result[] = $objReporte;
        	}
        }
        return $result;
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