<?php
/**
 * Controlador Servicios
 *
 * @package    TES
 * @subpakage  enrolamiento
 * @author     Eliecer
 * @created    2013-11-27
 */
class Servicios2 extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        if(!$this->db->conn_id) {
            $this->template->write('content', 'Error no se puede conectar a la Base de Datos');
            $this->template->render();
        }
		ini_set("buffering ","0");
		ob_start();
		
        $this->load->helper('url');
        $this->load->model(DIR_TES.'/Tableta_model');
        $this->load->model(DIR_TES.'/Usuario_tableta_model');
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$this->load->model(DIR_SIIGS.'/Usuario_model');
		$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
    }

    /**
     * Si la acción es 1: Valida la disponibilidad del dispositivo especificado y genera una session que se mantiene activa en toda la sincronizacion
     * Si la acción es 2: Regresa la informacion de todos los catalogos
	 * Si la acción es 3: Recibe un mensaje si es ok actualiza el estado de la tableta si es error se crea un archivo log con la descripcion
	 * Si la acción es 4: Regresa la informacion de la persona que pertenescan a la unidad medica de la tableta
	 * Si la acción es 5: Recibe la informacion que envia la tableta y la almacena en sus respectivas tablas
	 * Si la acción es 6: Regresa la informacion de los catalogos y personas que se actuailzaron o agregaron depues de la ultima sincronizacion de la tableta
     *
     * @access public
     * @param  id_accion=tipo de solicitud, id_tab=mac de la tableta, id_session=valor de la sessiuon activa,
	 * id_version= version de la tableta y datos=inofrmacion adicional puede contener un error descriptivo, datos de personas o algun otro mensaje
     * @echo void
     */
    public function Synchronization($id_accion, $id_tab = null, $id_sesion = null, $id_version = null, $datos = null)
    {
//         if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url())) {
//             show_error('', 403, 'Acceso denegado');
//             echo false;
//         }
        if(!isset($this->Tableta_model))
            echo 'No hay conexión';
        try 
		{
			$this->load->library('session');
			$id_accion=json_decode($id_accion);
			$id_tab=json_decode($id_tab);
			$id_sesion=json_decode($id_sesion);
			$this->session->set_userdata( 'sinc', "1" );
			switch($id_accion->id_accion)
			{
				case 1: // debe existir la MAC
					echo $this->is_step_1($id_tab->id_tab, $id_version);
					break;
				case 2: // debe existir el token y se regresa la info del dispositivo
					echo $this->is_step_2($id_sesion->id_sesion);
					break;
				case 3: // debe existir el token y el dato de la descripcion del proceso
					echo $this->is_step_3($id_sesion->id_sesion, $datos);
					break;
				case 4: // debe existir el token
					echo $this->is_step_4($id_sesion->id_sesion);
					break;
				case 5: // debe existir el token y el dato de la descripcion del proceso
					echo $this->ss_step_5($id_sesion->id_sesion, $datos);
					break;
				case 6: // debe existir el token
					echo $this->ss_step_6($id_sesion->id_sesion);
					break;
				default:
					$this->session->sess_destroy();
			}
        } 
		catch (Exception $e) 
		{
            Errorlog_model::save($e->getMessage(), __METHOD__);
        }
    }
	// validacion de la tableta y obtencion de la session
	public function is_step_1($id_tab,$id_version)
	{
		$tableta = $this->Tableta_model->getByMac($id_tab);
		if (count($tableta) == 1)
		{
			// debe tener usuarios asignados, el tipo de censo y la unidad médica
			if ($tableta->usuarios_asignados == 1)
			{
				if( $tableta->id_tes_estado_tableta  == 2 || $tableta->id_tes_estado_tableta  == 3 || $tableta->id_tes_estado_tableta  == 4)
				{
					if($tableta->id_tes_estado_tableta  != 4)
					{
						if( $tableta->id_tipo_censo != null )
						{
							if( $tableta->id_asu_um != null)
							{
								// se crea el token temporal
								$token = md5(date("dmYHis"));
								$this->session->set_userdata( 'session', $token );
								$this->session->set_userdata( 'mac', $id_tab );
								$this->session->set_userdata( 'fecha', $tableta->ultima_actualizacion );
								$this->session->set_userdata( 'paso', "1" );
								$this->session->set_userdata( 'id_version', $id_version );
								echo json_encode(array("id_sesion" => $this->session->userdata('session')));
								ob_flush();
							}
							else 
							{
								echo json_encode(array("id_resultado" => 'Unidad medica Nulo'));
								ob_flush();
							}
						}
						else 
						{
							echo json_encode(array("id_resultado" => 'Censo Nulo'));
							ob_flush();
						}
					}
					else
					{
						$mi_version = $this->Enrolamiento_model->get_version();
						foreach($mi_version as $dato)
						{
							echo json_encode(array("id_resultado" => 'Desactualizado', "url" => $dato->host ));
							ob_flush();
							die();
						}
					}					
				}
				else 
				{
					echo json_encode(array("id_resultado" => 'Estatus no valido'));
					ob_flush();
				}
			}
			else 
			{
				echo json_encode(array("id_resultado" => 'Tableta sin configurar'));
				ob_flush();
			}
		}
		else 
		{
			echo json_encode(array("id_resultado" => 'Tableta desconocida'));
			ob_flush();
		}
	}
	
	// generacion de informacion de catalogos
	public function is_step_2($id_sesion, $si="")
	{
		ini_set("max_execution_time", 10000000);
		
		$micadena="";
		$misesion=$this->session->userdata('session');
		$mac=$this->session->userdata('mac');
		
		$mi_version = $this->Enrolamiento_model->get_version();
		foreach($mi_version as $dato)
		{
			if($this->session->userdata('id_version')<$dato->version)
			{
				echo json_encode(array("id_resultado" => 'Desactualizado', "url" => $dato->host ));
				ob_flush();
				die();
			}
		}
		if ($id_sesion == $this->session->userdata('session')) // valida el token de entrada es el token que solicito el servicio
		{
			// se obtiene el dispositivo por token
			$tableta = $this->Tableta_model->getByMac($this->session->userdata('mac'));
			// se obtienen los usuarios asignados, el tipo de censo y la unidad médica
			if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
			{
				//************ inicio usuario ************
				
				// obtiene usuarios de las tabletas
				$cadena["id_tipo_censo"] = $tableta->id_tipo_censo;
				$cadena["id_asu_um"]     = $tableta->id_asu_um;
				
				$micadena=json_encode($cadena);
				echo substr($micadena,0,strlen($micadena)-1).",";
				$micadena="";
				ob_flush();
				unset($cadena);
				$cadena=array();
				$inusuario=array();	
				$usuariosXtableta = $this->Usuario_tableta_model->getUsuariosByTableta($tableta->id);
				if($usuariosXtableta)
				{
					foreach($usuariosXtableta as $dato)
					array_push($inusuario,$dato->id_usuario);
					
					/*$cadena= array("tes_usuario_x_tableta"=> $usuariosXtableta);
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-2).",";
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();*/
				}
				//else 
				//	$cadena= array("tes_usuario_x_tableta" => 'Error recuperando usuarios');
				
				// obtiene permisos que pertenescan al entorno
				$permisos = $this->Usuario_model->get_permiso_entorno("TES Movil");
				if($permisos)
				{
					$cadena["sis_permiso"]= $permisos;
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-2).",";
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
				}
				//else 
				//	$cadena["sis_permiso"]= 'Error recuperando sis_permiso';
					
				// obtiene grupos que pertenescan al entorno
				$grupos = $this->Usuario_model->get_grupo_entorno("TES Movil");
				if($grupos)
				{
					$cadena["sis_grupo"]= $grupos;
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-2).",";
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
				}
				//else 
				//	$cadena["sis_grupo"]= 'Error recuperando sis_grupo';
					
				// obtiene usuarios que pertenescan al entorno
				$usuarios = $this->Usuario_model->get_usuario_entorno("TES Movil",$inusuario);
				if($usuarios)
				{
					$cadena["sis_usuario"]= $usuarios;
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-2).",";
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
				}
				//else 
				//	$cadena["sis_usuario"]= 'Error recuperando sis_usuario';
				
				//************ fin usuario ************
				
				//************ inicio catalogos ************
				$catalog_relevante = $this->Enrolamiento_model->get_catalog_relevante();
				foreach($catalog_relevante as $catalog)
				{
					$array=$this->Enrolamiento_model->get_catalog($catalog->descripcion);
					if($array)
					{
						$cadena[$catalog->descripcion]= $array;
						$micadena=json_encode($cadena);
						echo substr($micadena,1,strlen($micadena)-2).",";
						$micadena="";
						ob_flush();
						unset($cadena);
						$cadena=array();
					}
					//else 
					//	$cadena[$catalog->descripcion]= 'Error recuperando '.$catalog->descripcion;
				}	
				//************ fin catalogos ************
				
				//************ inicio asu ************
				if($si=="")
				{
					$count=$this->Enrolamiento_model->get_catalog_count("asu_arbol_segmentacion");
					$mas=$count%1000;
					$contador=$count/1000;
					if($mas>0)(int)$contador++;
					if($count>0)
					$cadena["asu_arbol_segmentacion"]=array();
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-3);
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
					for($i=0;$i<$contador;$i++)
					{
						$asu=$this->Enrolamiento_model->get_catalog2("asu_arbol_segmentacion","","","","",($i*1000),(($i*1000)+1000));
						if($asu)
						{
							$micadena=json_encode($asu);
							echo substr($micadena,1,strlen($micadena)-2);
							if(($i+2)<$contador)echo ",";
							$micadena="";
							ob_flush();
						}
					}
					if($count>0)
					echo "]";
					ob_flush();
				}
				else 
				{
					$cadena["asu_arbol_segmentacion"]=array(array(
					"id" => "0",
					));
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-2);
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
				}
				//************ fin asu ************
				
				//************ inicio notificacion ************
				$asu_um = $this->ArbolSegmentacion_model->getUMParentsById($tableta->id_asu_um);
				$i=0;
				foreach($asu_um as $id)
				{
					$array=$this->Enrolamiento_model->get_catalog2("tes_notificacion", "id_arr_asu", $id);
					if($array)
					{
						echo ",";
						$cadena["tes_notificacion"]= $array;
						$micadena=json_encode($cadena);
						echo substr($micadena,1,strlen($micadena)-2);
						$micadena="";
						ob_flush();
						unset($cadena);
						$cadena=array();
					}
					//else 
					//	$cadena["tes_notificacion"]= 'Error recuperando tes_notificacion';
					$i++;
				}	
				//************ fin notificacion ************
				
				//************ inicio tes_pendientes_tarjeta ************
				$pendiente=$this->Enrolamiento_model->get_catalog2("tes_pendientes_tarjeta");
				if($pendiente)
				{
					echo ",";
					$cadena["tes_pendientes_tarjeta"]= $pendiente;
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-2);
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
				}
				//else 
				//	$cadena["tes_pendientes_tarjeta"]= 'Error recuperando tes_pendientes_tarjeta';
				//************ fin tes_pendientes_tarjeta ************
				
				// regresa el json con los datos necesarios	
				$this->session->set_userdata( 'paso', "2" );
				echo "}";
			}
			else
			{
				echo json_encode(array("id_resultado" => 'Tableta sin configurar'));
				ob_flush();
			}
		}
		else
		{
			echo json_encode(array("id_resultado" => "Error de procedimiento"));
			ob_flush();
		}
	}
	
	// procesaiento de mensajes
	public function is_step_3($id_sesion,$datos)
	{
		$fp = fopen(APPPATH."logs/sinconizacionsecuencial.txt", "a");
		fputs($fp, "JSON recibido: ".($datos)."\r\n");
		
		$datos=(array)json_decode($datos);
		$paso=$this->session->userdata('paso');
		$sinc=$this->session->userdata('sinc');
		if($datos["id_resultado"]=="error")
		{
			fputs($fp, "FECHA: ".date("d/m/Y H:i:s")." => MAC:"
			.$this->session->userdata('mac')." => VERSION:"
			.$this->session->userdata('id_version')." => PASO:"
			.$this->session->userdata('paso')." => DESCRIPCION:"
			.$datos."\r\n");
			ob_flush();
		}
		if($datos["id_resultado"]=="ok"&&$this->session->userdata('paso')=="4")
		{
			$this->actualiza_estado_tableta($id_sesion,"3",$this->session->userdata('id_version'));
			ob_flush();
		}
		if($datos["id_resultado"]=="ok"&&$this->session->userdata('paso')=="6")
		{
			$this->actualiza_estado_tableta($id_sesion,"3",$this->session->userdata('id_version'));
			ob_flush();
		}
		fclose($fp);
	}
	
	// envio de inofrmacion de personas
	public function is_step_4($id_sesion)
	{
		if ($id_sesion == $this->session->userdata('session')) // valida el token de entrada es el token que solicito el servicio
		{
			// se obtiene el dispositivo por token
			$tableta = $this->Tableta_model->getByMac($this->session->userdata('mac'));
			// se obtienen los usuarios asignados, el tipo de censo y la unidad médica
			if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
			{
				//************ inicio persona ************
				$asu_um = $this->ArbolSegmentacion_model->getUMParentsById($tableta->id_asu_um);
				$i=0;
				foreach($asu_um as $id)
				{
					$personas=$this->Enrolamiento_model->get_catalog2("cns_persona", "id_asu_um_tratante", $id);
					if($personas)
					{
						$cadena["cns_persona"]= $personas;						
						//************ inicio control catalogos X persona ************
						foreach($personas as $persona)
						{
							$catalog_relevante = $this->Enrolamiento_model->get_transaction_relevante();
							foreach($catalog_relevante as $catalog)
							{
								if($catalog->descripcion!="cns_persona"&&$catalog->descripcion!="cns_tutor")
								{
									try
									{
										$array=$this->Enrolamiento_model->get_catalog2($catalog->descripcion, "id_persona", $persona->id);
										if($array)
										{
											$cadena[$catalog->descripcion]= $array;
											if($catalog->descripcion=="cns_persona_x_tutor")
											{
												foreach($array as $dato)
												{
													$array2=$this->Enrolamiento_model->get_catalog2("cns_tutor", "id", $dato->id_tutor);
													$cadena["cns_tutor"]= $array2;
												}
											}
										}
										//else 
										//	$cadena[$catalog->descripcion]= 'Error recuperando '.$catalog->descripcion;
									}
									catch (Exception $e) {Errorlog_model::save($e->getMessage(), __METHOD__);}
								}
							}
						}
						//************ fin control catalogos X persona ************
					}
					
					$i++;
				}
				// regresa el json con los datos necesarios	
				$this->session->set_userdata( 'paso', "4" );
				echo json_encode($cadena);	
				ob_flush();
				//************ fin persona ************
			}
			else
			{
				echo json_encode(array("id_resultado" => 'Tableta sin configurar'));
				ob_flush();
			}
		}
		else
		{
			echo json_encode(array("id_resultado" => "Error de procedimiento"));
			ob_flush();
		}
	}
	
	// recibir datos de la tableta
	public function ss_step_5($id_sesion, $datos)
	{
		$bien=0;
		$fp = fopen(APPPATH."logs/sinconizacionsecuencial.txt", "a");
		fputs($fp, "JSON recibido: ".($datos)."\r\n");
		$datos=(array)json_decode($datos);
		try
		{
			fputs($fp, "Recibiendo pesonas\r\n");
			if(array_key_exists("cns_persona",$datos))
			foreach($datos["cns_persona"] as  $midato)
			{
				if($this->Enrolamiento_model->get_catalog2("cns_persona","id",$midato->id))
					$this->Enrolamiento_model->cns_update("cns_persona",$midato,$midato->id);
				else
					$this->Enrolamiento_model->cns_insert("cns_persona",$midato);
			}	
			
			fputs($fp, "Recibiendo tutor\r\n");
			if(array_key_exists("cns_tutor",$datos))
			foreach($datos["cns_tutor"] as  $midato)
			{
				if($this->Enrolamiento_model->get_catalog2("cns_tutor","id",$midato->id))
					$this->Enrolamiento_model->cns_update("cns_tutor",$midato,$midato->id);
				else
					$this->Enrolamiento_model->cns_insert("cns_tutor",$midato);
			}
		}
		catch (Exception $e) {Errorlog_model::save($e->getMessage(), __METHOD__);$bien++;}
		
		$catalog_relevante = $this->Enrolamiento_model->get_transaction_relevante();
		foreach($catalog_relevante as $catalog)
		{
			if($catalog->descripcion!="cns_persona"&&$catalog->descripcion!="cns_tutor")
			{
				try
				{
					fputs($fp, "Recibiendo $catalog->descripcion\r\n");
					if(array_key_exists($catalog->descripcion,$datos))
					foreach($datos[$catalog->descripcion] as  $midato)
					{
						if(array_key_exists("id",$midato))
						{
							$b_campo="id";
							$b_valor=$midato->id;
						}
						else if(array_key_exists("id_persona",$midato))
						{
							$b_campo="id_persona";
							$b_valor=$midato->id_persona;
							$f_campo='';
							$f_valor='';
						}
						
						if($this->Enrolamiento_model->get_catalog2($catalog->descripcion,$b_campo,$b_valor,$f_campo,$f_valor))
							$this->Enrolamiento_model->cns_update($catalog->descripcion, $b_campo,$b_valor,$f_campo,$f_valor);
						else
							$this->Enrolamiento_model->cns_insert($catalog->descripcion,$midato);
					}
					
				}
				catch (Exception $e) {Errorlog_model::save($e->getMessage(), __METHOD__);$bien++;}
			}
		}
		fclose($fp);
		if($bien==0)
		{
			$this->session->set_userdata( 'paso', "5" );
			$mi_version = $this->Enrolamiento_model->get_version();
			foreach($mi_version as $dato)
			{
				if($this->session->userdata('id_version')<$dato->version)
				{
					$this->actualiza_estado_tableta($id_sesion,"4",$this->session->userdata('id_version'));
					echo json_encode(array("id_resultado" => 'Desactualizado', "url" => $dato->host ));
					ob_flush();
					die();
				}
			}
			echo json_encode(array("id_resultado" => 'ok', "version" => $this->session->userdata('id_version')));
			ob_flush();
		}
		else
		{
			echo json_encode(array("id_resultado" => 'error'));
			ob_flush();
		}
	}
	
	// envio de datos de personas que se agregaron o actualizaron depues de la ultima sincronizacion de la tableta
	public function ss_step_6($id_sesion)
	{
		$fecha=$this->session->userdata('fecha');
		$mi_version = $this->Enrolamiento_model->get_version();
		foreach($mi_version as $dato)
		{
			if($this->session->userdata('id_version')<$dato->version)
			{
				echo json_encode(array("id_resultado" => 'Desactualizado', "url" => $dato->host ));
				ob_flush();
			}
		}
		if ($id_sesion == $this->session->userdata('session')) // valida el token de entrada es el token que solicito el servicio
		{
			// se obtiene el dispositivo por token
			$tableta = $this->Tableta_model->getByMac($this->session->userdata('mac'));
			// se obtienen los usuarios asignados, el tipo de censo y la unidad médica
			if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
			{
				
				$cadena=(array)json_decode($this->is_step_2($id_sesion,"no"));
				
				//************ inicio asu ************
				$asu=$this->Enrolamiento_model->get_catalog2("asu_arbol_segmentacion","fecha_update >=",$fecha);
				if($asu)
					$cadena["asu_arbol_segmentacion"]= $asu;
				//else 
				//	$cadena["asu_arbol_segmentacion"]= 'Error recuperando asu_arbol_segmentacion';
				//************ fin asu ************
		
				//************ inicio persona ************
				$asu_um = $this->ArbolSegmentacion_model->getUMParentsById($tableta->id_asu_um);
				$i=0;
				foreach($asu_um as $id)
				{//checar fecha en tipo de dato time stamp
					$personas=$this->Enrolamiento_model->get_catalog2("cns_persona", "id_asu_um_tratante", $id,"ultima_sincronizacion >=", $fecha);
					if($personas)
					{
						$cadena["cns_persona"]= $personas;						
						//************ inicio control catalogos X persona ************
						foreach($personas as $persona)
						{
							$catalog_relevante = $this->Enrolamiento_model->get_transaction_relevante();
							foreach($catalog_relevante as $catalog)
							{
								if($catalog->descripcion!="cns_persona"&&$catalog->descripcion!="cns_tutor")
								{
									try
									{
										$array=$this->Enrolamiento_model->get_catalog2($catalog->descripcion, "id_persona", $persona->id, 
										$catalog->columna_validar.">="	,$fecha);
										if($array)
										{
											$cadena[$catalog->descripcion]= $array;
											if($catalog->descripcion=="cns_persona_x_tutor")
											{
												foreach($array as $dato)
												{
													$array2=$this->Enrolamiento_model->get_catalog2("cns_tutor", "id", $dato->id_tutor);
													$cadena["cns_tutor"]= $array2;
												}
											}
										}
										//else 
										//	$cadena[$catalog->descripcion]= 'Error recuperando '.$catalog->descripcion;
									}
									catch (Exception $e) {Errorlog_model::save($e->getMessage(), __METHOD__);}
								}
							}
						}
						//************ fin control catalogos X persona ************
					}
					
					$i++;
				}
				// regresa el json con los datos necesarios	
				$this->session->set_userdata( 'paso', "6" );
				echo json_encode($cadena);	
				ob_flush();
				//************ fin persona ************
			}
			else
			{
				echo json_encode(array("id_resultado" => 'Tableta sin configurar'));
				ob_flush();
			}
		}
		else
		{
			echo json_encode(array("id_resultado" => "Error de procedimiento"));
			ob_flush();
		}
	}
	
	// actualiza el estado de la tableta
	public function actualiza_estado_tableta($id_sesion,$id_tes_estado_tableta="",$version="")
	{
		if ($id_sesion == $this->session->userdata('session')) // valida el token de entrada es el token que solicito el servicio
		{
			// se obtiene el dispositivo por token
			$tableta = $this->Tableta_model->getByMac($this->session->userdata('mac'));
			// se obtienen los usuarios asignados, el tipo de censo y la unidad médica
			if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
			{
				// si todo la operacion ok actualiza estado de tableta (id_tes_estado_tableta), version (version) y la fecha  (ultima_actualizacion)
				$this->Enrolamiento_model->update_status_tableta($this->session->userdata('mac'),$id_tes_estado_tableta,$version,date("Y-m-d H:i:s"));
				$this->session->unset_userdata('session');
				$this->session->unset_userdata('mac');
				$this->session->unset_userdata('fecha');
				$this->session->unset_userdata('id_version');
			}
		}
    }
		
	//// prueba tableta
	public function prueba()
	{
		$id_accion=$this->input->post('id_accion');
		$id_tab = $this->input->post('id_tab'); 
		$id_sesion = $this->input->post('id_sesion');
		$version_apk = $this->input->post('version_apk');
		$datos = $this->input->post('msg');
		if($datos=="")
		$datos = $this->input->post('datos');
		
		$this->Synchronization(
		json_encode(array("id_accion"=>$id_accion)), 
		json_encode(array("id_tab"=>$id_tab)) , 
		json_encode(array("id_sesion"=>$id_sesion)), 
		$version_apk,$datos );
	}	
	
	/*
	
	public function prueba2($id_accion,$id_tab=null,$id_sesion=null, $version=null)
	{
		 $this->Synchronization(
		 json_encode(array("id_accion"=>$id_accion)), 
		 json_encode(array("id_tab"=>$id_tab)) , 
		 json_encode(array("id_sesion"=>$id_sesion)), 
		 json_encode(array("version"=>$version)) );
	}
	
	public function pruebass2($id_accion, $id_tab = null, $id_sesion = null)
	{
		$json='{"cns_persona":[{"id":"59a6cddb690074c1f8a5019ecaeb25e8","curp":"RAEE850605HCSMSL02","nombre":"ELIECER","apellido_paterno":"RAMIREZ","apellido_materno":"ESQUINCA","sexo":"M","id_tipo_sanguineo":"1","fecha_nacimiento":"1985-06-05","id_asu_localidad_nacimiento":"810","calle_domicilio":"1 OTE SUR","numero_domicilio":"1968","colonia_domicilio":"SAN FRANCISCO","referencia_domicilio":"a lado de la precidencia","id_asu_localidad_domicilio":"810","cp_domicilio":"29000","telefono_domicilio":"9616916297","fecha_registro":"2013-12-01 00:00:00","id_asu_um_tratante":"809","celuar":"9616403231","ultima_actualizacion":"0000-00-00 00:00:00","id_nacionalidad":"1","id_operadora_celular":"1"}],"cns_persona_x_tutor":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_tutor":"43ad90cadc565fc2a030596090a654c1"}],"cns_tutor":[{"id":"43ad90cadc565fc2a030596090a654c1","curp":"raee850605hcmsl02","nombre":"ELIECER","apellido_paterno":"RAMIREZ","apellido_materno":"ESQUINCA","sexo":"M","telefono":"9616916208","celular":"9616403233","id_operadora_celular":"1"}],"cns_persona_x_alergia":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_alergia":"1"}],"cns_persona_x_afiliacion":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_afiliacion":"1"}],"cns_control_vacuna":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_vacuna":"1","fecha":"2013-12-04 00:00:00","id_asu_um":"1","codigo_barras":null},{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_vacuna":"1","fecha":"2013-12-05 00:00:00","id_asu_um":"1","codigo_barras":null}],"cns_control_ira":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_ira":"1","fecha":"2013-12-05 00:00:00","id_asu_um":"1"}],"cns_control_eda":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_eda":"1","fecha":"2013-12-05 00:00:00","id_asu_um":"1"}],"cns_control_consulta":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_consulta":"2","fecha":"2013-12-05 00:00:00","id_asu_um":"1"}],"cns_control_accion_nutricional":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","id_accion_nutricional":"1","fecha":"2013-12-05 00:00:00","id_asu_um":"1"}],"cns_control_nutricional":[{"id_persona":"59a6cddb690074c1f8a5019ecaeb25e8","peso":"5.00","altura":"1","talla":"1","fecha":"2013-12-05 00:00:00","id_asu_um":"1"}]}';
		
		$this->synchronization_sequence(
		json_encode(array("id_accion"=>$id_accion)), 
		json_encode(array("id_tab"=>$id_tab)) , 
		json_encode(array("id_sesion"=>$id_sesion)), 
		1, 
		$json);
	}*/
    
}
?>