<?php
/**
 * Controlador Servicios
 *
 * @package    TES
 * @subpakage  Controlador
 * @author     Eliecer
 * @created    2013-11-27
 */
class Servicios extends CI_Controller {
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
		$this->load->model(DIR_SIIGS.'/ReglaVacuna_model');
		$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
    }
	/**
	 * @access public
	 *
	 * Metodo principal al que se le hacen las peticiones y es el que se encarga de distribuir la informacion
	 * recibe parametros por POST
	 * @param		int 		$id_accion     Representa el tipo de accion que se ejecutara
	 * @param		int 		$id_tab        Representa a la MAC de la tableta
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 * @param		int 		$version_apk   Representa la version de la apk instalada en la tableta
	 * @param		json 		$datos         Representa el json que contiene el contenido para la comunicacion tableta - servidor
	 *
	 * @return 		void
	 */
	public function Synchronization()
	{
		$id_accion=$this->input->post('id_accion');
		$id_tab = $this->input->post('id_tab'); 
		$id_sesion = $this->input->post('id_sesion');
		$version_apk = $this->input->post('version_apk');
		$datos = $this->input->post('msg');
		if($datos=="")
		$datos = $this->input->post('datos');
		
		$this->is_step_0(
		json_encode(array("id_accion"=>$id_accion)), 
		json_encode(array("id_tab"=>$id_tab)) , 
		json_encode(array("id_sesion"=>$id_sesion)), 
		$version_apk,$datos );
	}	

    /**
	 * @access public
	 *
	 * Paso 0 se procesa las peticiones segun la accion:
     * Si la acción es 1: Valida la disponibilidad del dispositivo especificado y genera una session que se mantiene activa en toda la sincronizacion
     * Si la acción es 2: Regresa la informacion de todos los catalogos
	 * Si la acción es 3: Recibe un mensaje si es ok actualiza el estado de la tableta si es error se crea un archivo log con la descripcion
	 * Si la acción es 4: Regresa la informacion de la persona que pertenescan a la unidad medica de la tableta
	 * Si la acción es 5: Recibe la informacion que envia la tableta y la almacena en sus respectivas tablas
	 * Si la acción es 6: Regresa la informacion de los catalogos y personas que se actuailzaron o agregaron depues de la ultima sincronizacion de la tableta
     *
	 * @param		int 		$id_accion     Representa el tipo de accion que se ejecutara
	 * @param		int 		$id_tab        Representa a la MAC de la tableta
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 * @param		int 		$version_apk   Representa la version de la apk instalada en la tableta
	 * @param		json 		$datos         Representa el json que contiene el contenido para la comunicacion tableta - servidor
	 *
	 * @return 		void
     * 
     */
    public function is_step_0($id_accion, $id_tab = null, $id_sesion = null, $id_version = null, $datos = null)
    {
        if(!isset($this->Tableta_model))
            echo json_encode(array("id_resultado" => 'No hay conexión')); 
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
					$this->is_step_1($id_tab->id_tab, $id_version);
					break;
				case 2: // debe existir el token y se regresa la info del dispositivo
					$this->is_step_2($id_sesion->id_sesion);
					break;
				case 3: // debe existir el token y el dato de la descripcion del proceso
					$this->is_step_3($id_sesion->id_sesion, $datos);
					break;
				case 4: // debe existir el token
					$this->is_step_4($id_sesion->id_sesion);
					break;
				case 5: // debe existir el token y el dato de la descripcion del proceso
					$this->ss_step_5($id_sesion->id_sesion, $datos);
					break;
				case 6: // debe existir el token
					$this->ss_step_6($id_sesion->id_sesion);
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
	/**
	 * @access public
	 *
	 * valida que la tableta este asignada a una unidad medica y que tenga un status valido para la sincronizacion
	 * recibe parametros por POST
	 * @param		int 		$id_tab        Representa a la MAC de la tableta
	 * @param		int 		$version_apk   Representa la version de la apk instalada en la tableta
	 *
	 * @return 		session
	 */
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
								$this->session->set_userdata( 'dias_extras', $tableta->periodo_esq_inc );
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
	
	/**
	 * @access public
	 *
	 * Valida que la session este activa, genera los catalogos a enviar en la sincronizacion por primera vez
	 * 
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 * @param		int 		$si            Bandera que especifica si este paso es llamado por otro paso
	 * @param		json 		$sf            Bandera que especifica el comportamiento del armado del json
	 *
	 * @return 		echo
	 */
	public function is_step_2($id_sesion, $si="", $sf="")
	{
		ini_set("max_execution_time", 999999999);
		
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
				$inusuario=array(0);	
				$usuariosXtableta = $this->Usuario_tableta_model->getUsuariosByTableta($tableta->id);
				
				if($usuariosXtableta)
				{
					foreach($usuariosXtableta as $dato)	
					array_push($inusuario,$dato->id_usuario);
					
				}
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
				if(sizeof($inusuario)==0)array_push($inusuario,0);
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
				
				//************ fin usuario ************

				//************ inicio asu ************
				if($si=="")
				{
					$count=$this->Enrolamiento_model->get_catalog_count("asu_arbol_segmentacion","id_raiz","1");
					$mas=$count%8000;
					$contador=$count/8000;
					if($mas>0)(int)$contador++;
					if($count>0)
					$cadena["asu_arbol_segmentacion"]=array();
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-3);
					$micadena="";
					ob_flush();
					unset($cadena);
					$cadena=array();
					$fecha=$this->session->userdata('fecha');
					for($i=0;$i<$contador;$i++)
					{
						if($sf=="")
							$asu=$this->Enrolamiento_model->get_catalog2("asu_arbol_segmentacion","id_raiz","1","","",($i*8000),8000);
						else
							$asu=$this->Enrolamiento_model->get_catalog2("asu_arbol_segmentacion","fecha_update >=",$fecha,"id_raiz","1",($i*8000),8000);
						
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
				
				$i=0;$array=array();$tem="";
				if($asu_um)
				foreach($asu_um as $id)
				{
					$result=$this->Enrolamiento_model->get_notificacion($id);
					if($result)
					{
						if($tem!=$result[0]->id)
						{
							if($i==0)
								$array=$result;
							else
								array_push($array,$result[0]);
							$i++;
						}
						$tem=$result[0]->id;
					}
					//else 
					//	$cadena["tes_notificacion"]= 'Error recuperando tes_notificacion';
					
				}
				if(count($array)>0)
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
				//************ fin notificacion ************
				
				//************ inicio catalogos ************
				if($sf=="")
				$this->catalogos_relevantes();
				//************ fin catalogos ************
				
				//************ inicio tes_pendientes_tarjeta ************
				$pendiente=$this->Enrolamiento_model->get_catalog2("tes_pendientes_tarjeta","resuelto","0");
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
				//************ fin tes_pendientes_tarjeta ************
				
				// regresa el json con los datos necesarios	
				$this->session->set_userdata( 'paso', "2" );
				if($sf=="")
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
	
	/**
	 * @access public
	 *
	 * Guarda en la base de datos el estado de la sincronizacion
	 * 
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 * @param		json 		$datos         Representa el json que contiene el contenido para la comunicacion tableta - servidor
	 *
	 * @return 		void
	 */
	public function is_step_3($id_sesion,$datos)
	{
		$fp = fopen(APPPATH."logs/sinconizacionsecuencial.txt", "a");
		fputs($fp, "FECHA: ".date("d/m/Y H:i:s")." => MAC:"
			.$this->session->userdata('mac')." => VERSION:"
			.$this->session->userdata('id_version')." => PASO:"
			.$this->session->userdata('paso')." JSON recibido: ".($datos)."\r\n");
		
		$datos=(array)json_decode($datos);
		$paso=$this->session->userdata('paso');
		$sinc=$this->session->userdata('sinc');
		if($datos["id_resultado"]=="error")
		{
			fputs($fp, "ERROR: ".$datos["descripcion"]."\r\n");
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
	/**
	 * @access public
	 *
	 * Prepara la informacion de perssonas con su catalogos transaccionales de cada una
	 * 
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 *
	 * @return 		echo
	 */
	public function is_step_4($id_sesion)
	{
		ini_set("max_execution_time", 999999999);
		$tableta = $this->Tableta_model->getByMac($this->session->userdata('mac'));
		if($tableta->id_tipo_censo==1)
			ini_set("memory_limit","300M");
		if($tableta->id_tipo_censo==2)
			ini_set("memory_limit","200M");
		if($tableta->id_tipo_censo==3)
			ini_set("memory_limit","100M");
		if ($id_sesion == $this->session->userdata('session')) // valida el token de entrada es el token que solicito el servicio
		{
			$cadena="";
			// se obtiene el dispositivo por token
			$tableta = $this->Tableta_model->getByMac($this->session->userdata('mac'));
			// se obtienen los usuarios asignados, el tipo de censo y la unidad médica
			if ($tableta->usuarios_asignados == 1 && $tableta->id_tipo_censo != null && $tableta->id_asu_um != null)
			{
				//************ inicio persona ************
				$asu_um = $this->ArbolSegmentacion_model->getUMParentsById($tableta->id_asu_um);
				if($tableta->id_tipo_censo!=5)
				{
					$asu_um = array_reverse($asu_um);
					$asu_um = $this->ArbolSegmentacion_model->getCluesFromId($asu_um[$tableta->id_tipo_censo-1]);
				}
				else
					$asu_um['children'][0]=array("key"=>$tableta->id_asu_um);
				$i=0; 
				$miasu=array();
				foreach($asu_um["children"] as $id)
				{
					$miasu[]=$id["key"];
				}
				$personas=$this->Enrolamiento_model->get_cns_persona($miasu);
				
				if($personas)
				{
					$cadena["cns_persona"]= $personas;	
					$micadena=json_encode($cadena);
					echo "{".substr($micadena,1,strlen($micadena)-2);
					$micadena="";	
					ob_flush();	
					unset($cadena);
					$cadena=array();				
					//************ inicio control catalogos X persona ************
					$regla_vacuna=array();
					$mipersona=array();
					foreach($personas as $persona)
					{
						$vacunas=$array=$this->Enrolamiento_model->get_catalog2("cns_control_vacuna", "id_persona", $persona->id);
						array_push($regla_vacuna,$this->esquema_incompleto($persona->id,$persona->fecha_nacimiento,$vacunas));
						$mipersona[]=$persona->id;
					}
						
					$catalog_relevante = $this->Enrolamiento_model->get_transaction_relevante();
					foreach($catalog_relevante as $catalog)
					{
						if($catalog->descripcion!="cns_persona"&&$catalog->descripcion!="cns_tutor")
						{
							try
							{
								
								if($catalog->descripcion=="cns_persona_x_tutor")
								{
									$array=$this->Enrolamiento_model->get_cns_cat_persona($catalog->descripcion, $mipersona);
									$mitutor=array();
									foreach($array as $dato)
									{
										$mitutor[]=$dato->id_tutor;
									}
									$array2=$this->Enrolamiento_model->get_persona_x_tutor($mitutor);
									
									echo ",";
									$cadena["cns_tutor"]= $array2;	
									$micadena=json_encode($cadena);
									echo substr($micadena,1,strlen($micadena)-2);
									$micadena="";	
									ob_flush();	
									unset($cadena);
									$cadena=array();	
								}
								$count=$this->Enrolamiento_model->get_cns_cat_persona_count($catalog->descripcion, $mipersona);
								$mas=$count%15000;
								$contador=$count/15000;
								if($mas>0)(int)$contador++;
								if($count>0)
								{
									$cadena[$catalog->descripcion]=array();
									$micadena=json_encode($cadena);
									echo ",".substr($micadena,1,strlen($micadena)-3);
									$micadena="";
									ob_flush();
								}
								unset($cadena);
								$cadena=array();
								$array=array();
								for($i=0;$i<$contador;$i++)
								{ 
									$array=$this->Enrolamiento_model->get_cns_cat_persona($catalog->descripcion, $mipersona, ($i*15000),15000);
									if($array)
									{
										$micadena=json_encode($array);
										if($i>0)echo ",";
										echo substr($micadena,1,strlen($micadena)-2);
										
										$micadena="";
										ob_flush();									
									}
								}
								if($count>0)
								{
									echo "]";
									ob_flush();
								}								
							}
							catch (Exception $e) {Errorlog_model::save($e->getMessage(), __METHOD__);}
						}
					}
						
					
					$rv=array();
					for($x=0;$x<count($regla_vacuna);$x++)
						for($y=0;$y<count($regla_vacuna[$x]);$y++)
						$rv[]=array("id_persona"=>$regla_vacuna[$x][$y]["id_persona"],
								  "id_vacuna"=> $regla_vacuna[$x][$y]["id_vacuna"],
								  "prioridad"=> $regla_vacuna[$x][$y]["prioridad"]);
					$cadena["esquema_incompleto"]=$rv;
					//************ fin control catalogos X persona ************
				}
				else
				{
					echo json_encode(array("cns_persona" => array()));
				}
				$array=$this->Reporte_sincronizacion_model->getListado("SELECT id FROM cns_persona WHERE activo=0");
				if($array)
				{
					$data=array();
					foreach($array as $x)
					{
						$data[]=$x->id;
					}
					echo ",";
					$micadena["persona_x_borrar"]=$data;
					$micadena=json_encode($micadena);
					echo substr($micadena,1,strlen($micadena)-2);
					$micadena="";	
					ob_flush();	
					unset($data);
				}				
				
				// regresa el json con los datos necesarios	
				$this->session->set_userdata( 'paso', "4" );
				if($cadena!="")
				{
					echo ",";
					$micadena=json_encode($cadena);
					echo substr($micadena,1,strlen($micadena)-1);
					$micadena="";	
					ob_flush();	
					unset($cadena);
					$cadena=array();
				}
				
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
	/**
	 * @access public
	 *
	 * Recibe los datos que genero la tableta para ser almacenados en la base de datos del servidor
	 * 
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 * @param		json 		$datos         Representa el json que contiene el contenido para la comunicacion tableta - servidor
	 *
	 * @return 		echo
	 */
	public function ss_step_5($id_sesion, $datos)
	{
		header('Content-Type: text/html; charset=UTF-8');
		$bien=0;
		$datos=(array)json_decode($datos);
		try
		{
			if(array_key_exists("cns_persona",$datos))
			foreach($datos["cns_persona"] as  $midato)
			{
				if($this->Enrolamiento_model->get_catalog2("cns_persona","id",$midato->id))
					$this->Enrolamiento_model->cns_update("cns_persona",$midato,$midato->id);
				else
					$this->Enrolamiento_model->cns_insert("cns_persona",$midato);
			}	
			if(array_key_exists("cns_visita",$datos))
			foreach($datos["cns_visita"] as  $visita)
			{
				$this->Enrolamiento_model->cns_insert("cns_visita",$visita);
				
				if($visita->id_estado_visita==1||$visita->id_estado_visita==4)
					$this->Enrolamiento_model->cns_update_visita($visita->id_persona);
	
				if($visita->id_estado_visita!=1&&$visita->id_estado_visita!=4&&$visita->id_estado_visita!=3)
					$this->Enrolamiento_model->cns_update("cns_persona",array("contador_visitas" => '0'),$visita->id_persona);
					
				$contador=$this->Enrolamiento_model->get_catalog2("cns_persona","id",$visita->id_persona);

				if($visita->id_estado_visita==3||$contador[0]->contador_visitas==3)
					$this->Enrolamiento_model->cns_update("cns_persona",array("activo" => '0','ultima_actualizacion' => date("Y-m-d H:i:s")),$visita->id_persona);
			}	
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
					if(array_key_exists($catalog->descripcion,$datos))
					foreach($datos[$catalog->descripcion] as  $midato)
					{
						$f_valor="";
						if(array_key_exists("id",$midato))
						{
							$b_campo="id";
							$b_valor=$midato->id;
						}
						else if(array_key_exists("id_persona",$midato))
						{
							$b_campo="id_persona";
							$b_valor=$midato->id_persona;
							if($catalog->descripcion=="cns_antiguo_domicilio")
							{
								$f_campo='fecha_cambio';
								$f_valor=$midato->fecha_cambio;
							}
							if($catalog->descripcion=="cns_persona_x_alergia")
							{
								$f_campo='ultima_actualizacion';
								$f_valor=$midato->ultima_actualizacion;
							}
							else
								$f_campo='fecha';
							if($f_valor=="")	
							$f_valor=$midato->fecha;
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
		if($bien==0)
		{
			if(array_key_exists("tes_pendientes_tarjeta",$datos))
			foreach($datos["tes_pendientes_tarjeta"] as  $midato)
			{
				if($this->Enrolamiento_model->get_catalog2("tes_pendientes_tarjeta","fecha",$midato->fecha,"id_persona",$midato->id_persona))
					$this->Enrolamiento_model->cns_update("tes_pendientes_tarjeta",$midato,$midato->fecha,"id_persona",$midato->id_persona);
				else
					$this->Enrolamiento_model->cns_insert("tes_pendientes_tarjeta",$midato);
			}
			$this->Enrolamiento_model->tes_pendientes_tarjeta_delete();	
			
			if(array_key_exists("sis_bitacora",$datos))
			foreach($datos["sis_bitacora"] as  $midato)
			{
				$this->Enrolamiento_model->cns_insert("sis_bitacora",$midato);
			}	
			if(array_key_exists("sis_error",$datos))
			foreach($datos["sis_error"] as  $midato)
			{
				$this->Enrolamiento_model->cns_insert("sis_error",$midato);
			}	
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
	/**
	 * @access public
	 *
	 * prepara los datos para la sincronizacion secuencia, envia unicamente aquellos datos modificados despues de la ultima sincronizacion de la tableta
	 * 
	 * @param		int 		$id_session    Representa la session activa para la peticion
	 *
	 * @return 		void
	 */
	public function ss_step_6($id_sesion)
	{
		ini_set("max_execution_time", 999999999);
		ini_set("memory_limit","200M");
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
				//************ inicio asu ************
				$this->is_step_2($id_sesion,"","si");
				//************ fin asu ************
				
				//************ inicio catalogos ************
				$this->catalogos_relevantes("si");
				//************ fin catalogos ************
				
				//************ inicio persona ************
				$asu_um = $this->ArbolSegmentacion_model->getUMParentsById($tableta->id_asu_um);
				if($tableta->id_tipo_censo!=5)
				{
					$asu_um = array_reverse($asu_um);
					$asu_um = $this->ArbolSegmentacion_model->getCluesFromId($asu_um[$tableta->id_tipo_censo-1]);
				}
				else
					$asu_um['children'][0]=array("key"=>$tableta->id_asu_um);
				$i=0; $xy=0; $cadena=array();
				
				$miasu=array();
				foreach($asu_um["children"] as $id)
				{
					$miasu[]=$id["key"];
				}
				$personas=$this->Enrolamiento_model->get_cns_persona($miasu,$fecha);
				
				if($personas)
				{
					$cadena["cns_persona"]= $personas;	
					$micadena=json_encode($cadena);
					echo ",".substr($micadena,1,strlen($micadena)-2);
					$micadena="";	
					ob_flush();	
					unset($cadena);
					$cadena=array();				
					//************ inicio control catalogos X persona ************
					$mipersona=array();
					foreach($personas as $persona)
					{
						$mipersona[]=$persona->id;
					}
					//************ inicio control catalogos X persona ************
					$catalog_relevante = $this->Enrolamiento_model->get_transaction_relevante();
					foreach($catalog_relevante as $catalog)
					{
						if($catalog->descripcion!="cns_persona"&&$catalog->descripcion!="cns_tutor")
						{
							try
							{
								$array=$this->Enrolamiento_model->get_cns_cat_persona($catalog->descripcion, $mipersona);
								if($array)
								{
									echo ",";
									$cadena[$catalog->descripcion]= $array;	
									$micadena=json_encode($cadena);
									echo substr($micadena,1,strlen($micadena)-2);
									$micadena="";	
									ob_flush();	
									unset($cadena);
									$cadena=array();

									if($catalog->descripcion=="cns_persona_x_tutor")
									{
										$mitutor=array();
										foreach($array as $dato)
										{
											$mitutor[]=$dato->id_tutor;
										}
										$array2=$this->Enrolamiento_model->get_persona_x_tutor($mitutor);
										
										echo ",";
										$cadena["cns_tutor"]= $array2;	
										$micadena=json_encode($cadena);
										echo substr($micadena,1,strlen($micadena)-2);
										$micadena="";	
										ob_flush();	
										unset($cadena);
										$cadena=array();	
									}
								}
							}
							catch (Exception $e) {Errorlog_model::save($e->getMessage(), __METHOD__);}
						}
					}
					
					$xy++;
				}
				//************ fin control catalogos X persona ************
					
				echo ",";
				$micadena=json_encode($cadena);
				echo substr($micadena,1,strlen($micadena)-2);
				$micadena="";
				ob_flush();
				unset($cadena);
				$cadena=array();
					
				$regla_vacuna=array();
				$personas=$this->Enrolamiento_model->get_cns_persona($miasu);										
				if($personas)
				{
					$regla_vacuna=array();
					foreach($personas as $persona)
					{
						$vacunas=$array=$this->Enrolamiento_model->get_catalog2("cns_control_vacuna", "id_persona", $persona->id);
						array_push($regla_vacuna,$this->esquema_incompleto($persona->id,$persona->fecha_nacimiento,$vacunas));
					}
				}
				
				$rv=array();
				for($x=0;$x<count($regla_vacuna);$x++)
					for($y=0;$y<count($regla_vacuna[$x]);$y++)
					$rv[]=array("id_persona"=>$regla_vacuna[$x][$y]["id_persona"],
							  "id_vacuna"=> $regla_vacuna[$x][$y]["id_vacuna"],
							  "prioridad"=> $regla_vacuna[$x][$y]["prioridad"]);
				$cadena["esquema_incompleto"]=$rv;
				
				$micadena=json_encode($cadena);
				echo (substr($micadena,1,strlen($micadena)-2));
				$micadena="";
				ob_flush();
				unset($cadena);
				$cadena=array();
				
				$array=$this->Reporte_sincronizacion_model->getListado("SELECT id FROM cns_persona WHERE activo=0");
				if($array)
				{
					$data=array();
					foreach($array as $x)
					{
						$data[]=$x->id;
					}
					echo ",";
					$micadena["persona_x_borrar"]=$data;
					$micadena=json_encode($micadena);
					echo substr($micadena,1,strlen($micadena)-2);
					$micadena="";	
					ob_flush();	
					unset($data);
				}
				// regresa el json con los datos necesarios	
				$this->session->set_userdata( 'paso', "6" );
				
				echo "}";
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
	/**
	 * @access public
	 *
	 * Actualiza el estatus de la tableta
	 * 
	 * @param		int 		$id_session            Representa la session activa para la peticion
	 * @param		json 		$id_tes_estado_tableta Representa el status que tomara la tableta
	 * @param		int 		$version               Representa la version de la apk instalada en la tableta
	 *
	 * @return 		echo
	 */
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
	/**
	 * @access public
	 *
	 * Genera los esquemas incompletos de las personas que correspondan a la unidad medica de la tableta
	 * 
	 * @param		int 		$id_persona            Representa la persona a la que se le calculara su esquema
	 * @param		string 		$fecha                 Representa la fecha de nacimiento de la persona
	 * @param		array 		$vacunas               Representa las vacunas aplicadas a la persona
	 *
	 * @return 		echo
	 */
	public function esquema_incompleto($id_persona,$fecha,$vacunas)
	{//agregar dias a la fecha si periodo de colchon ver tabla tableta agregar bit de prioridad 1 ya le toca 0 periodo de ventana "prioridad"=>1 ó 0
		$cadena= array();
		$regla=$this->ReglaVacuna_model->getAll(); 
		
		$fecha     = date("Y-m-d",strtotime($fecha));
		$datetime1 = date_create($fecha);
		$datetime2 = date_create(date("Y-m-d"));
		$interval  = date_diff($datetime1, $datetime2);
		$dias      = $interval->format('%a');
		$dias_extra= $dias+$this->session->userdata('dias_extras');
		$mas=0;
		if($dias>365&&$dias<1461)$mas=365;
		if($dias>1461)$mas=1461;
		
		foreach($regla as $r)
		{
			$x=0;
			if($vacunas!=""&&$r->esq_com=="1")
			{
				foreach($vacunas as $v)
				{
					if($r->id==$v->id_vacuna)
					{
						$x++;
					}
				}
				if($x==0)
				{
					if($r->hasta>$mas)
					{
						if(($dias>=($r->desde)&&$dias<=($r->hasta)||$dias>($r->hasta)))
							array_push($cadena,array("id_persona" => $id_persona,"id_vacuna" => $r->id, "prioridad"=>1));
						if(($dias_extra>=($r->desde)&&$dias_extra<=($r->hasta)))
							array_push($cadena,array("id_persona" => $id_persona,"id_vacuna" => $r->id, "prioridad"=>0));
					}
				}
			}
		}
		return $cadena;
	}
	/**
	 * @access public
	 *
	 * Genera los catalogos relevantes por entorno
	 * 
	 * @param		string 		$sf            bandera que activa el filtro de fechas segun el tipo de sincronizacion
	 *
	 * @return 		echo
	 */
	public function catalogos_relevantes($sf="")
	{
		$fechis="";
		if($sf!="")$fechis=$this->session->userdata('fecha');
		$catalog_relevante = $this->Enrolamiento_model->get_catalog_relevante($fechis);
		foreach($catalog_relevante as $catalog)
		{
			$array=$this->Enrolamiento_model->get_catalog($catalog->descripcion);
			$cadena[$catalog->descripcion]= $array;
			$micadena=json_encode($cadena);
			echo ",".substr($micadena,1,strlen($micadena)-2);
			$micadena="";
			ob_flush();
			unset($cadena);
			$cadena=array();
		}	
	}
	public function prueba2($id_accion,$id_tab=null,$id_sesion=null, $version=null)
	{
		 $this->is_step_0(
		 json_encode(array("id_accion"=>$id_accion)), 
		 json_encode(array("id_tab"=>$id_tab)) , 
		 json_encode(array("id_sesion"=>$id_sesion)), 
		 $version,
		 '{
			  "id_resultado": "ok",
    "cns_visita": [
        {
            "id_persona": "00043d74df5c3f7e48f0a2776aaa2602",
            "fecha": "2014-01-07 14:57:08",
            "id_asu_um": "1019",
            "id_estado_visita": "1"
        }
    ]}' );
	}    
}
?>