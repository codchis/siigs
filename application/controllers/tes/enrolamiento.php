<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controller Usuario
 *
 * @package     TES
 * @subpackage  Controlador
 * @author     	Eliecer
 * @created     2013-12-17
 */
class Enrolamiento extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		try
		{
			$this->load->helper('url');
			$this->load->helper('date');
			$this->load->helper('formatFecha');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}
	
	/**
	 *
	 *se recibe el parametro $pag de tipo int que representa la paginacion
	 *
	 */
	 /**
	 * @access public
	 *
	 * Este es el metodo por default, obtiene el listado de las perosnas
	 * 
	 * @param		string 		$pag        numero de pagina para la posicion
	 * @param		string 		$id         id de una persona
	 *
	 * @return 		echo
	 */
	public function index($pag = 0, $id="", $array="")
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->load->model(DIR_TES.'/Enrolamiento_model');
			$data['title'] = 'Lista Enrolados';
			$this->load->helper('form');
			$this->load->library('pagination');
			
			$data['id']  = $id;
			$data['pag'] = $pag;
			if($array!="")
			{
				$data['infoclass'] = $array['infoclass'];
				$data['msgResult'] = $array['msgResult'];
			}
			
			// Configuración para el Paginador
			$configPag['base_url']   = '/'.DIR_TES.'/enrolamiento/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['uri_segment'] = '4';
			$configPag['total_rows'] = $this->Enrolamiento_model->getNumRows($this->input->post('busqueda'));
			$configPag['per_page']   = 20;
			$this->pagination->initialize($configPag);
			if ($this->input->post('busqueda'))
				$data['users'] = $this->Enrolamiento_model->getListEnrolamiento($this->input->post('busqueda'), $configPag['per_page'], $pag);
			else 
				$data['users'] = $this->Enrolamiento_model->getListEnrolamiento('', $configPag['per_page'], $pag);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
 		$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_list', $data);
 		$this->template->render();
	}
	 /**
	 * @access public
	 *
	 * Este metodo estrae la informacion del paciente que sera impreso en la tarjeta
	 * 
	 * @param		string 		$id        identificador de la persona 
	 *
	 * @return 		echo
	 */
	public function print_card($id)
	{
		$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$persona=$this->Enrolamiento_model->getById($id);
		$alergia=$this->Enrolamiento_model->getAlergia($id);
		//$persona = (array) $persona;
		//var_dump($persona);
		$datos=array("nombre"=>$persona->apellido_paterno." ".$persona->apellido_materno." ".$persona->nombre,
					 "sexo"=>$persona->sexo,
					 "nombre_madre"=>$persona->nombreT." ".$persona->paternoT." ".$persona->maternoT,
					 "domicilio"=>$persona->calle_domicilio." ".$persona->numero_domicilio.", ".$persona->colonia_domicilio
		);
		$dom=$this->ArbolSegmentacion_model->getDescripcionById(array($persona->id_asu_localidad_domicilio),3);// loca edo
		$asu=$this->ArbolSegmentacion_model->getDescripcionById(array($persona->id_asu_um_tratante),3);// um juridiccion
		if($dom)
		{
			$dom=explode(",",$dom[0]->descripcion);
			$datos["localidad"]=trim($dom[0]);
			$datos["municipio"]=trim($dom[1]);
		}
		if($asu)
		{
			$asu=explode(",",$asu[0]->descripcion);
			$datos["um"]=trim($asu[0]);
			$datos["juridiccion"]=trim($asu[3]);
		}
		$valor=array();
		foreach($alergia as $x)
		{
			$valor[]=$x->descripcion;
		}
		$datos["alergias"]=implode(", ",$valor);
		$folio=$this->Enrolamiento_model->getfolio($persona->id);
		$datos["folio"]=$folio[0]->folio;
		
		echo implode("|",$datos);
	}
	  /**
	 * @access public
	 *
	 * Crea la pagina para ver la infromacion de la persona
	 * 
	 * @param		string 		$id        identificador de la persona 
	 *
	 * @return 		echo
	 */
	public function view($id)
	{
		try 
		{
			$this->load->model(DIR_TES.'/Enrolamiento_model');
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			if (empty($this->Enrolamiento_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			
			$data['title'] = 'Ver Paciente';
			$data['enrolado'] = $this->Enrolamiento_model->getById($id);
			if(empty($data['enrolado']))
			{
				$data['infoclass'] = 'error';
				$data['msgResult'] = "Registro no encontrado";
				
				$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_view', $data);
 				$this->template->render();
				return true;
			}
			$data['alergias'] = $this->Enrolamiento_model->getAlergia($id);
			$data['afiliaciones'] = $this->Enrolamiento_model->getAfiliaciones($id);
			
			$data['consultas']=$this->Enrolamiento_model->getControlConsultas($id);
			$data['nutricionales']=$this->Enrolamiento_model->get_catalog_view("accion_nutricional",$id);
			$fecha=$data['enrolado']->fecha_nacimiento;
			$data['vacunacion']=$this->Reporte_sincronizacion_model->getListado("SELECT DISTINCT r.id_vacuna, cv.codigo_barras, v.descripcion,r.dia_inicio_aplicacion_nacido, r.dia_fin_aplicacion_nacido, p.fecha_nacimiento, CASE WHEN r.id_vacuna = cv.id_vacuna THEN 'X' ELSE '' END AS tiene, CASE WHEN cv.fecha IS NULL THEN CONCAT('Desde:',r.dia_inicio_aplicacion_nacido,' Hasta:',r.dia_fin_aplicacion_nacido) ELSE CONCAT('Fecha Aplicada: ',' ',DATE_FORMAT(cv.fecha, '%d-%m-%Y')) END AS fecha,DATEDIFF(NOW(),'$fecha') AS dias,CASE WHEN DATEDIFF(NOW(),'$fecha')>=r.dia_inicio_aplicacion_nacido AND DATEDIFF(NOW(),'$fecha')<=r.dia_fin_aplicacion_nacido  THEN '1' ELSE (CASE WHEN DATEDIFF(NOW(),'$fecha')>r.dia_fin_aplicacion_nacido AND cv.fecha IS NULL THEN '2' ELSE (CASE WHEN DATEDIFF(NOW(),'$fecha')<r.dia_inicio_aplicacion_nacido AND cv.fecha IS NULL THEN '3' END) END) END AS prioridad FROM cns_regla_vacuna r LEFT JOIN cns_vacuna v ON v.id=r.id_vacuna LEFT JOIN cns_control_vacuna cv ON cv.id_persona='$id' AND cv.id_vacuna=r.id_vacuna  LEFT JOIN cns_persona p ON p.id=cv.id_persona GROUP BY v.descripcion ORDER BY r.id_vacuna,r.orden_esq_com ASC");
			$data['estimulacion_temprana'] = $this->Enrolamiento_model->get_estimulacion($id);
			$data['sales'] = $this->Enrolamiento_model->get_sales($id);
            
            // Obtiene los datos para las graficas
            $data['peso_edad']  = json_encode($this->Enrolamiento_model->get_datos_grafica('peso_edad', $data['enrolado']->sexo, $data['enrolado']->edad_meses, $data['enrolado']->id));
            $data['peso_talla'] = json_encode($this->Enrolamiento_model->get_datos_grafica('peso_talla', $data['enrolado']->sexo, $data['enrolado']->edad_meses, $data['enrolado']->id));
            $data['talla_edad'] = json_encode($this->Enrolamiento_model->get_datos_grafica('talla_edad', $data['enrolado']->sexo, $data['enrolado']->edad_meses, $data['enrolado']->id));
            $data['imc']        = json_encode($this->Enrolamiento_model->get_datos_grafica('imc', $data['enrolado']->sexo, $data['enrolado']->edad_meses, $data['enrolado']->id));
            $data['peri_cefa']  = json_encode($this->Enrolamiento_model->get_datos_grafica('peri_cefa', $data['enrolado']->sexo, $data['enrolado']->edad_meses, $data['enrolado']->id));
            $data['con_hemo']   = json_encode($this->Enrolamiento_model->get_datos_grafica('con_hemo', $data['enrolado']->sexo, $data['enrolado']->edad_meses, $data['enrolado']->id, $data['enrolado']->id_asu_localidad_domicilio));
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
		$this->template->write('sala_prensa','',true);
 		$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_view', $data);
 		$this->template->render();
	}
	
	 /**
	 * @access public
	 *
	 * Crea el fromulario para editar la informacion de la persona
	 * 
	 * @param		string 		$id        identificador de la persona 
	 *
	 * @return 		echo
	 */
	public function update($id)
	{
		try 
		{
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->load->model(DIR_TES.'/Enrolamiento_model');
			$data['id'] = $id;
			$data['title'] = 'Ver Paciente';
			$data['enrolado'] = $this->Enrolamiento_model->getById($id);
			if(empty($data['enrolado']))
			{
				$data['infoclass'] = 'error';
				$data['msgResult'] = "Registro no encontrado";
				
				$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_update', $data);
 				$this->template->render();
				return true;
			}
			$data['alergias'] = $this->Enrolamiento_model->getAlergia($id);
			$data['afiliaciones'] = $this->Enrolamiento_model->getAfiliaciones($id);
			
			$data['vacunas']=$this->Enrolamiento_model->get_catalog_view("vacuna",$id,"id_vacuna");
			
			$data['consultas']=$this->Enrolamiento_model->getControlConsultas($id);
			$data['nutricionales']=$this->Enrolamiento_model->get_catalog_view("accion_nutricional",$id);
			
			$nutricion=$this->Enrolamiento_model->get_control_nutricional($id);
			$data['nutriciones']=$nutricion;
            
            $data['peri_cefa'] = $this->Enrolamiento_model->get_peri_cefa($id);
            $data['estimulacion_temprana'] = $this->Enrolamiento_model->get_estimulacion($id);
            $data['sales'] = $this->Enrolamiento_model->get_sales($id);
		}
		catch(Exception $e)
		{
            $data['infoclass'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		try
		{
			if (empty($this->Enrolamiento_model))
				return false;
		/*
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');*/
			
			if($this->validarForm("update"))
			{
				try 
				{
					$this->Enrolamiento_model->setId($id);
					$this->addForm();
					// actualizar si todo bien
					if(isset($_POST["id_cns_basico"]))
						$id=$this->Enrolamiento_model->update_basico();
					
					if(isset($_POST["id_cns_beneficiario"]))
						$id=$this->Enrolamiento_model->update_beneficiario();
					
					if(isset($_POST["id_cns_tutor"]))
						$id=$this->Enrolamiento_model->update_tutor();
						
					if(isset($_POST["id_cns_umt"]))
						$id=$this->Enrolamiento_model->update_umt();
						
					if(isset($_POST["id_cns_regcivil"]))
						$id=$this->Enrolamiento_model->update_regcivil();
						
					if(isset($_POST["id_cns_direccion"]))
						$id=$this->Enrolamiento_model->update_direccion();
						
					if(isset($_POST["id_cns_alergia"]))
						$id=$this->Enrolamiento_model->update_alergia();
						
					if(isset($_POST["id_cns_vacuna"]))
						$id=$this->Enrolamiento_model->update_vacuna();
					
					if(isset($_POST["id_cns_consulta"]))
						$id=$this->Enrolamiento_model->update_consulta();
						
					if(isset($_POST["id_cns_accion"]))
						$id=$this->Enrolamiento_model->update_accion();
						
					if(isset($_POST["id_cns_nutricion"]))
						$id=$this->Enrolamiento_model->update_nutricion();
					
                    if(!empty($_POST["peri_cefa"]))
						$id=$this->Enrolamiento_model->update_peri_cefa();
                    
                    if(!empty($_POST["estimulacion_fecha"]))
						$id=$this->Enrolamiento_model->update_estimulacion();
                    
                    if(!empty($_POST["sales_fecha"]))
						$id=$this->Enrolamiento_model->update_sales();
                    
					$data['id'] = $this->Enrolamiento_model->getId();	
					$midata['infoclass'] = 'success';
					$midata['msgResult'] = 'Registro Actualizado Exitosamente';
					Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario Enrolado: '.strtoupper($this->input->post('nombre')));
					
 					$this->index(0,$data['id'],$midata);
				}
				catch (Exception $e)
				{
					$data['infoclass'] = 'error';
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
					$data["title"]="TES";
					$data["titulo"]="Enrolamiento";
					
					//$this->template->write_view('header',DIR_TES.'/header.php');
					//$this->template->write_view('menu',DIR_TES.'/menu.php');
					$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_update',$data);
					//$this->template->write_view('footer',DIR_TES.'/footer.php');	
					$this->template->render();
				}
			}
			else
			{
				$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_update', $data);
 				$this->template->render();
			}
		}
		catch(Exception $e)
		{
            $data['infoclass'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		
	}
	/**
	 * @access public
	 *
	 * Genera los options de un campo tipo select 
	 * 
	 * @param		string 		$catalog    tabla de donde se extrae la informacion
	 * @param		string 		$sel        identifica si un valor ya esta seleccionado 
	 * @param		string 		$orden      columna para hacer el ordenamiento
	 * @param		string 		$campo      campo de la tabla para hacer el where
	 * @param		string 		$valor      valor a comparar en el where
	 *
	 * @return 		echo
	 */
	public function catalog_select($catalog,$sel="",$orden="",$campo="",$valor="")
	{
		$opcion="";
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$datos=$this->Enrolamiento_model->get_catalog("cns_".$catalog,$campo,$valor,$orden);
		if(sizeof($datos)!=0)
		{
			$opcion.="<option value=''>Seleccione...</option>";
			foreach($datos as $dato)
			{
				$id=$dato->id;
				$che="";
				if(stripos(".".$sel,$id))$che="selected";
				$descripcion=$dato->descripcion;
				$opcion.="<option value='$id' $che>$descripcion</option>";
			}
			echo $opcion;
		}
		else
		echo "<option>No hay Datos</option>";
	}	 
	
	/**
	 * @access public
	 *
	 * Genera los options de un campo tipo select para los tratamientos de consultas
	 * 
	 * @param		string 		$campo      campo de la tabla para hacer el where
	 * @param		string 		$valor      valor a comparar en el where
	 * @param		string 		$sel        identifica si un valor ya esta seleccionado 
	 * @param		string 		$orden      columna para hacer el ordenamiento
	 *
	 * @return 		echo
	 */
	public function tratamiento_select($campo="",$valor="",$sel="",$orden="")
	{
		$opcion="";
		$valor=urldecode($valor);
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$datos=$this->Enrolamiento_model->get_catalog_tratamiento("cns_tratamiento",$campo,$valor,$orden);
		if(sizeof($datos)!=0)
		{
			$opcion.="<option value=''>Seleccione...</option>";
			foreach($datos as $dato)
			{
				$che="";
				
				if($orden=="tipo"||$orden=="cc")
				{
					$yd=$dato->id;
					$id=$dato->tipo;
					$descripcion=$dato->tipo;
					$che="";
					if(stripos(".".$sel,$yd))$che="selected";
				}
				else
				{
					$id=$dato->id;
					$descripcion=$dato->descripcion;
					if(stripos(".".$sel,$id))$che="selected";
				}
				$opcion.="<option value='$id' $che>$descripcion</option>";
			}
			echo $opcion;
		}
		else
		echo "<option>No hay Datos</option>";
	}	 
	/**
	 * @access public
	 *
	 * Crea un grupo de radio o check con la informacion de los catalogos
	 * 
	 * @param		string 		$catalog    tabla de donde se extrae la informacion
	 * @param		string 		$tipo       tipo de control radio o check
	 * @param		int 		$col        numero de columnas en la tabla
	 * @param		string 		$sel        identifica si un valor ya esta seleccionado 
	 * @param		string 		$orden      columna para hacer el ordenamiento
	 *
	 * @return 		echo
	 */
	public function catalog_check($catalog,$tipo,$col=1,$sel="",$orden="")
	{
		$opcion="";
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$datos=$this->Enrolamiento_model->get_catalog("cns_".$catalog,"","",$orden);
		if(sizeof($datos)!=0)
		{
			$i=0;$a=0;$y=0;$temp="";$x=0;
			$opcion='<table width="100%" ><tr>';
			foreach($datos as $dato)
			{
				$id=$dato->id;
				$descripcion=$dato->descripcion;
				$che="";
				if($catalog=="alergia")
				{
					if($temp!=$dato->tipo)
					{
						if($y>0)
						{
							$x++;
							$opcion.="</tr></table>";
							if($x==$col){$opcion.="<tr>"; $x=0;}
						}
						$opcion.="<td width='33%' valign='top'><table width='98%' cellpadding=1 cellspacing=1 border=0><tr><th bgcolor='#CCC'> ".$dato->tipo." </th></tr><tr>";		
						$y++;				
					}
					else $opcion.="</tr><tr>";
					$temp=$dato->tipo;
					
				}
				if(stripos(".".$sel,$id))$che="checked";
				if($a==$col&&$catalog!="alergia"){$opcion.="</tr><tr>"; $a=0;}
				if($catalog=="afiliacion")
				{$xi=$i+200; $xy=$xi+1;}
				if($catalog=="alergia")
				{$xi=$i+400; $xy=$xi+1;}
				$opcion.="<td width='33%' valign='top'><label><input name='".$catalog."[]' id='$catalog$i' type='$tipo' value='$id' $che style='margin-top:-2px;' tabindex='$xi' onkeydown='return entertab(event,0)'> $descripcion</label></td>";
				$i++;$a++;
			}
			$opcion.='</tr></table>';
			echo $opcion;
			
		}
		else
		echo "No hay Datos";
	}
	 /**
	 * @access public
	 *
	 * Crea el autocomplete para facilitar la busqueda de un tutor
	 * 
	 * @return 		echo
	 */
	public function autocomplete()
	{
		$term=$_GET["term"];
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$datos=$this->Enrolamiento_model->autocomplete_tutor($term);
		$array = array();
		$i=0;
		foreach($datos as $data)
		{
				$array[$i] = trim(($data->curp)." => ".$data->nombre." ".$data->apellido_paterno." ".$data->apellido_materno);
				$i++;
		}
		echo json_encode($array);
	}
	 /**
	 * @access public
	 *
	 * Obtiene inofrmacion del tutor
	 * 
	 * @param		string 		$curp    curp del tutor
	 *
	 * @return 		echo
	 */
	public function data_tutor($curp)
	{
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$datos=$this->Enrolamiento_model->data_tutor($curp);
		if(sizeof($datos)!=0)
		{
			
			foreach($datos as $dato)
			{
				$m=FALSE;$f=FALSE;
				if($dato->sexo=="M") $m=1;
				if($dato->sexo=="F") $f=1;
				$array=array(
					array(
						"idtutor" => $dato->id,
						"nombreT" => $dato->nombre,
						"paternoT" => $dato->apellido_paterno,
						"maternoT" => $dato->apellido_materno,
						"celularT" => $dato->celular,
						"curpT" => $dato->curp,
						"telefonoT" => $dato->telefono,
						"companiaT" => $dato->id_operadora_celular,
						"sexoT_1" =>  $m,
						"sexoT_2" => $f,
						"error" => "",
					)
				);
			}
		}
		else
		$array=array(
					array(
					"error" => "No existe curp: ".$curp,
					)
				);
		
		echo json_encode($array);
	}
	 /**
	 * @access public
	 *
	 *  crea un archivo descargable el cual se necesita para el envio por nfc a la tarjeta del paciente
	 * 
	 * @param		string 		$id    identificador de la persona
	 *
	 * @return 		echo
	 */
	public function file_to_card($id,$tipo="")
	{
		$archivo=date("YmdHis").".tesf";
        $SEPARADOR_BLOQUE = '^';
        
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		if($tipo=="")
		{
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment;filename=".$archivo);
		}
		header("Content-Transfer-Encoding: binary ");
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$data="";
		
        // Bloque Comun
        
		$version =$this->Enrolamiento_model->get_version();
		$data.=$version[0]->version."~";
		
		$enrolado =(array)$this->Enrolamiento_model->getById($id);
		$data.=$enrolado["id"]."=";
		$data.=$enrolado["curp"]."=";	
		$data.=$enrolado["nombre"]."=";
		$data.=$enrolado["apellido_paterno"]."=";
		$data.=$enrolado["apellido_materno"]."=";
		$data.=$enrolado["sexo"]."=";
		$data.=$enrolado["id_tipo_sanguineo"];                if($enrolado["id_tipo_sanguineo"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["fecha_nacimiento"]."=";
		$data.=$enrolado["id_asu_localidad_nacimiento"];      if($enrolado["id_asu_localidad_nacimiento"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["calle_domicilio"] ;                 if($enrolado["calle_domicilio"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["numero_domicilio"] ;                if($enrolado["numero_domicilio"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["colonia_domicilio"] ;               if($enrolado["colonia_domicilio"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["referencia_domicilio"] ;            if($enrolado["referencia_domicilio"]=="")$data.="¬=";else $data.="=";
		
		$data.=$enrolado["ageb"] ;                            if($enrolado["ageb"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["manzana"] ;            			  if($enrolado["manzana"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["sector"] ;            			  if($enrolado["sector"]=="")$data.="¬=";else $data.="=";
		
		$data.=$enrolado["id_asu_localidad_domicilio"] ;      if($enrolado["id_asu_localidad_domicilio"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["cp_domicilio"] ;                    if($enrolado["cp_domicilio"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["telefono_domicilio"] ;              if($enrolado["telefono_domicilio"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["fecha_registro"] ;                  if($enrolado["fecha_registro"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["id_asu_um_tratante"] ;              if($enrolado["id_asu_um_tratante"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["celular"] ;                         if($enrolado["celular"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["ultima_actualizacion"] ;            if($enrolado["ultima_actualizacion"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["id_nacionalidad"] ;                 if($enrolado["id_nacionalidad"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["id_operadora_celular"];             if($enrolado["id_operadora_celular"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["tamiz_neonatal"];                   if($enrolado["tamiz_neonatal"]=="")$data.="¬";
		$data.="~";
		
		$data.=$enrolado["idT"]."=";
		$data.=$enrolado["curpT"];						 if($enrolado["curpT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["nombreT"];					 if($enrolado["nombreT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["paternoT"];					 if($enrolado["paternoT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["maternoT"];					 if($enrolado["maternoT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["sexoT"];                       if($enrolado["sexoT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["telefonoT"];                   if($enrolado["telefonoT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["celularT"];                    if($enrolado["celularT"]=="")$data.="¬=";else $data.="=";
		$data.=$enrolado["operadoraTid"];                if($enrolado["operadoraTid"]=="")$data.="¬";
		$data.="~";
		
		$registro = (array)$this->Enrolamiento_model->getRegistro_civil($id);
		if(count($registro)>0)
		{
			$data.=$registro["id_localidad_registro_civil"]."=";     
			$data.=$registro["fecha_registro"];					
		}
		$data.="~";
		$alergias = $this->Enrolamiento_model->getAlergia($id,'ultima_actualizacion');
		foreach($alergias as $x)
		{
			$data.=$x->id."°";
		}
		if(empty($alergias))
			$data.="~";
		else
		$data=substr($data,0,strlen($data)-2)."~";
		$afiliaciones = $this->Enrolamiento_model->getAfiliaciones($id,'ultima_actualizacion');
		foreach($afiliaciones as $x)
		{
			$data.=$x->id."°";
		}
		if(empty($afiliaciones))
			$data.="~";
		else
		$data=substr($data,0,strlen($data)-2)."~";
		$vacunas=$this->Enrolamiento_model->get_catalog_view("vacuna",$id,'','fecha');
		foreach($vacunas as $x)
		{
			$data.=$x->id."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."°";
		}
		if(empty($vacunas))
			$data.="~";
		else
		$data=substr($data,0,strlen($data)-2)."~";
        
        // Bloque exclusivo ISECH
        $data .= $SEPARADOR_BLOQUE;
        
		$consultas=$this->Enrolamiento_model->get_catalog("cns_control_consulta", 'id_persona', $id, 'fecha');
		foreach($consultas as $x)
		{
			$data.=$x->clave_cie10."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."=";
			$data.=$x->id_tratamiento;					 if($x->id_tratamiento=="")$data.="¬=";else $data.="=";
			$data.=$x->grupo_fecha_secuencial;			 if($x->grupo_fecha_secuencial=="")$data.="¬°";else $data.="°";
		}
		if(empty($consultas))
			$data.="~";
		else
		$data=substr($data,0,strlen($data)-2)."~";
        
		$anutricional=$this->Enrolamiento_model->get_catalog_view("accion_nutricional",$id,'','fecha');
		foreach($anutricional as $x)
		{
			$data.=$x->id."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."°";
		}
		if(empty($anutricional))
			$data.="~";
		else
		$data=substr($data,0,strlen($data)-2)."~";
		
        $nutricion=$this->Enrolamiento_model->get_control_nutricional($id,'fecha');
		foreach($nutricion as $x)
		{
			$data.=$x->peso."=";
			$data.=$x->altura."=";
			$data.=$x->talla."=";
			$data.=$x->hemoglobina."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."°";
		}
		if(empty($nutricion))
			$data.="~";
		else
            $data=substr($data,0,strlen($data)-2)."~";
        
        $peri_cefa=$this->Enrolamiento_model->get_peri_cefa($id);
		foreach($peri_cefa as $x)
		{
			$data.=$x->perimetro_cefalico."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."°";
		}
		if(empty($peri_cefa)) 
			$data.="~";
		else 
            $data=substr($data,0,strlen($data)-2)."~";
        
        $sales=$this->Enrolamiento_model->get_sales($id);
		foreach($sales as $x)
		{
			$data.=$x->cantidad."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."°";
		}
		if(empty($sales))
			$data.="~";
		else
            $data=substr($data,0,strlen($data)-2)."~";
        
        $estimulacion=$this->Enrolamiento_model->get_estimulacion($id);
		foreach($estimulacion as $x)
		{
			$data.=$x->tutor_capacitado."=";
			$data.=$x->fecha."=";
			$data.=$x->id_asu_um."°";
		}
		if(empty($estimulacion))
			$data.="~";
		else
            $data=substr($data,0,strlen($data)-2);
        
		$this->update_card($id,0,'',$archivo,4);
		echo $data;
        
        // Bloque exclusivo ICSS
        echo $SEPARADOR_BLOQUE;
	}
	 /**
	 * @access public
	 *
	 * Este metodo actualiza el estado del archivo descargado si fue escrito correctamente o no en la tarjeta
	 * 
	 * @param		string 		$persona      id de la persona
	 * @param		boolean		$impreso      identifica si el proceso de impresion fue correcto o no
	 * @param		int 		$fecha        fecha del evento
	 * @param		string 		$archivo      archivo generado
	 * @param		string 		$entorno      tipo de entorno
	 *
	 * @return 		echo
	 */
	public function update_card($persona,$impreso,$fecha="",$archivo="",$entorno='4')
	{
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		if($impreso==1)$fecha=date("Y-m-d H:i:s");
		$this->Enrolamiento_model->entorno_x_persona($entorno,$persona,$fecha,$archivo,$impreso);
	}
	 
	 /**
	 * @access public
	 *
	 * valida que un archivo sea valido para enviar a la tarjeta por nfc
	 * 
	 * @param		string 		$persona      id de la persona
	 * @param		string 		$archivo      archivo generado
	 *
	 * @return 		echo
	 */
	public function validate_card($persona,$archivo)
	{
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		echo $this->Enrolamiento_model->valid_card($persona,$archivo);
	}
	 /**
	 * @access public
	 *
	 * prepara los datos para insertarlos
	 *
	 * @return 		echo
	 */
	public  function insert()
	{
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		try
		{
			if (empty($this->Enrolamiento_model))
				return false;
		
		if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
			
			if($this->validarForm())
			{
				try 
				{						
					$this->addForm();
					
					$id=$this->Enrolamiento_model->insert();
					$midata['infoclass'] = 'success';
					$midata['msgResult'] = 'Registro Agregado Exitosamente';
					Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario Enrolado: '.strtoupper($this->input->post('nombre')));
					$this->session->set_userdata( 'umt', $this->Enrolamiento_model->getumt() );
 					$this->index(0,$id,$midata);					
				}
				catch (Exception $e)
				{
					$data['infoclass'] = 'error';
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
					$data["title"]="TES";
					$data["titulo"]="Enrolamiento";
					$data["session"]=$this->session->userdata('umt');
					
					//$this->template->write_view('header',DIR_TES.'/header.php');
					//$this->template->write_view('menu',DIR_TES.'/menu.php');
					$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento',$data);
					//$this->template->write_view('footer',DIR_TES.'/footer.php');	
					$this->template->render();
				}
			}
			else
			{
				$data["title"]="TES";
				$data["titulo"]="Enrolamiento";
				$data["session"]=$this->session->userdata('umt');
				
				//$this->template->write_view('header',DIR_TES.'/header.php');
				//$this->template->write_view('menu',DIR_TES.'/menu.php');
				$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento',$data);
				//$this->template->write_view('footer',DIR_TES.'/footer.php');	
				$this->template->render();
			}
		
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
	}
	 
	 /**
	 * @access public
	 *
	 * valida los datos de entrada en el formulario
	 * 
	 * @param		string 		$op      bandera que identifica si una seccion entra en validacion
	 *
	 * @return 		echo
	 */
	public function validarForm($op="")
	{
		$data['titulo'] = 'Nuevo Enrolamiento';
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		if(isset($_POST["id_cns_basico"])||$op=="")
		{
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[40]');
			$this->form_validation->set_rules('paterno', 'Apellido Paterno', 'trim|xss_clean|required|max_length[25]');
			$this->form_validation->set_rules('materno', 'Apellido Materno', 'trim|xss_clean|required|max_length[25]');
			$this->form_validation->set_rules('sexo', 'Sexo', 'trim|required');
			$this->form_validation->set_rules('sangre', 'Tipo de Sangre', 'trim|required');
			$this->form_validation->set_rules('fnacimiento', 'Fecha de Nacimiento', 'trim|required');
			$this->form_validation->set_rules('lnacimiento', 'Lugar de Nacimiento', 'trim|required');
			$this->form_validation->set_rules('lnacimientoT', 'Lugar de Nacimiento', 'xss_clean|trim');
			$this->form_validation->set_rules('curp', 'CURP', 'trim|xss_clean');
			$this->form_validation->set_rules('curp2', 'CURP', 'xss_clean');//|callback_ifCurpExists
			$this->form_validation->set_rules('nacionalidad', 'Nacionalidad', '');
		}
		
		if(isset($_POST["id_cns_regcivil"])||$op=="")
		{
			$this->form_validation->set_rules('fechacivil', 'Fecha Civil', 'trim|xss_clean');
			$this->form_validation->set_rules('lugarcivil', 'Lugar Civil', 'trim|xss_clean');
			$this->form_validation->set_rules('lugarcivilT', 'Lugar Civil', '');
		}
		
		if(isset($_POST["id_cns_umt"])||$op=="")
		{
			$this->form_validation->set_rules('um', 'Unidad medica tratante', 'trim|required');
			$this->form_validation->set_rules('umt', 'Unidad medica tratante', '');
		}
		
		if(isset($_POST["id_cns_direccion"])||$op=="")
		{
			$this->form_validation->set_rules('calle', 'Calle', 'trim|xss_clean');
			$this->form_validation->set_rules('numero', 'numero', '');
			$this->form_validation->set_rules('referencia', 'referencia', '');
			$this->form_validation->set_rules('colonia', 'colonia', '');
			$this->form_validation->set_rules('cp', 'Codigo Postal', 'trim|xss_clean');
			$this->form_validation->set_rules('ageb', 'ageb', 'xss_clean');
			$this->form_validation->set_rules('sector', 'sector', 'xss_clean');
			$this->form_validation->set_rules('manzana', 'manzana', 'xss_clean');
			$this->form_validation->set_rules('localidad', 'Localidad', 'trim|xss_clean');
			$this->form_validation->set_rules('localidadT', 'Localidad', '');
			$this->form_validation->set_rules('celular', 'celular', '');
			$this->form_validation->set_rules('telefono', 'telefono', '');
			$this->form_validation->set_rules('compania', 'compania', 'trim');
		}
		
		if(isset($_POST["id_cns_tutor"])||$op=="")
		{
			$this->form_validation->set_rules('buscar', 'buscar', 'xss_clean');
			$this->form_validation->set_rules('captura', 'captura', '');
			$this->form_validation->set_rules('nombreT', 'nombreT', 'xss_clean');
			$this->form_validation->set_rules('paternoT', 'paternoT', 'xss_clean');
			$this->form_validation->set_rules('maternoT', 'maternoT', 'xss_clean');
			$this->form_validation->set_rules('celularT', 'celularT', 'xss_clean');
			$this->form_validation->set_rules('curpT', 'curpT', 'xss_clean');//|callback_ifCurpTExists
			$this->form_validation->set_rules('telefonoT', 'telefonoT', 'xss_clean');
			$this->form_validation->set_rules('sexoT', 'sexoT', '');
			$this->form_validation->set_rules('companiaT', 'companiaT', 'trim');
		}
		if($op=="update")
			$this->form_validation->set_rules('id', 'id', 'trim');		
		return $this->form_validation->run();
	}
	
	 /**
	 * @access public
	 *
	 * Pase de parametros para la insercion o actualizacion
	 *
	 * @return 		echo
	 */
	public function addForm()
	{
		$this->Enrolamiento_model->setnacionalidad($this->input->post('nacionalidad'));				
		$this->Enrolamiento_model->setnombre(trim(strtoupper($this->input->post('nombre'))));
		$this->Enrolamiento_model->setpaterno(trim(strtoupper($this->input->post('paterno'))));
		$this->Enrolamiento_model->setmaterno(trim(strtoupper($this->input->post('materno'))));
		$this->Enrolamiento_model->setlnacimiento($this->input->post('lnacimiento'));
		$this->Enrolamiento_model->setcurp($this->input->post('curp').$this->input->post('curp2'));
		$this->Enrolamiento_model->setsexo($this->input->post('sexo'));
		$this->Enrolamiento_model->setsangre($this->input->post('sangre'));
		$this->Enrolamiento_model->setfnacimiento($this->input->post('fnacimiento'));
		$this->Enrolamiento_model->settbeneficiario($this->input->post('tbeneficiario'));
		$this->Enrolamiento_model->setparto($this->input->post('parto'));
                $this->Enrolamiento_model->settamiz($this->input->post('tamiz'));
                $this->Enrolamiento_model->setprecurp($this->input->post('precurp'));
		
		$this->Enrolamiento_model->setidtutor($this->input->post('idtutor'));				
		$this->Enrolamiento_model->setnombreT(trim(strtoupper($this->input->post('nombreT'))));
		$this->Enrolamiento_model->setpaternoT(trim(strtoupper($this->input->post('paternoT'))));
		$this->Enrolamiento_model->setmaternoT(trim(strtoupper($this->input->post('maternoT'))));
		$this->Enrolamiento_model->setcurpT($this->input->post('curpT'));
		$this->Enrolamiento_model->setsexoT($this->input->post('sexoT'));
		$this->Enrolamiento_model->settelefonoT($this->input->post('telefonoT'));
		$this->Enrolamiento_model->setcompaniaT($this->input->post('companiaT'));
		$this->Enrolamiento_model->setcelularT($this->input->post('celularT'));
		
		$this->Enrolamiento_model->setfechacivil($this->input->post('fechacivil'));				
		$this->Enrolamiento_model->setlugarcivil($this->input->post('lugarcivil'));
		$this->Enrolamiento_model->setumt($this->input->post('um'));
		
		$this->Enrolamiento_model->setcalle(trim(strtoupper($this->input->post('calle'))));
		$this->Enrolamiento_model->setreferencia(trim(strtoupper($this->input->post('referencia'))));				
		$this->Enrolamiento_model->setcolonia(trim(strtoupper($this->input->post('colonia'))));
		$this->Enrolamiento_model->setlocalidad($this->input->post('localidad'));
		$this->Enrolamiento_model->settelefono($this->input->post('telefono'));
		$this->Enrolamiento_model->setcompania($this->input->post('compania'));
		$this->Enrolamiento_model->setcelular($this->input->post('celular'));
		$this->Enrolamiento_model->setnumero($this->input->post('numero'));
		$this->Enrolamiento_model->setcp($this->input->post('cp'));
		$this->Enrolamiento_model->setageb(str_pad(strtoupper($this->input->post('ageb')), 4, '0', STR_PAD_LEFT));
		$this->Enrolamiento_model->setsector($this->input->post('sector'));
		$this->Enrolamiento_model->setmanzana($this->input->post('manzana'));
		
		$this->Enrolamiento_model->setafiliacion($this->input->post('afiliacion'));
		$this->Enrolamiento_model->setalergias($this->input->post('alergia'));
		
		$this->Enrolamiento_model->setvacuna($this->input->post('vacuna'));
		$this->Enrolamiento_model->setfvacuna($this->input->post('fvacuna'));
		$this->Enrolamiento_model->setcodigo_barras($this->input->post('ffoliovacuna'));
		
		$this->Enrolamiento_model->setconsulta($this->input->post('id_enfermedad_consulta'));
		$this->Enrolamiento_model->setfconsulta($this->input->post('fecha_consulta'));
		$this->Enrolamiento_model->settconsulta($this->input->post('ids_tratamiento_consulta'));
		
		$this->Enrolamiento_model->setaccion_nutricional($this->input->post('accion_nutricional'));
		$this->Enrolamiento_model->setfaccion_nutricional($this->input->post('faccion_nutricional'));
		
		$this->Enrolamiento_model->setpeso($this->input->post('cpeso'));
		$this->Enrolamiento_model->setaltura($this->input->post('caltura'));
		$this->Enrolamiento_model->settalla($this->input->post('ctalla'));
		$this->Enrolamiento_model->sethemoglobina($this->input->post('chemoglobina'));
		$this->Enrolamiento_model->setfnutricion($this->input->post('fCNu'));
        $this->Enrolamiento_model->setperi_cefa($this->input->post('peri_cefa'));
        $this->Enrolamiento_model->setfecha_peri_cefa($this->input->post('fecha_peri_cefa'));
        $this->Enrolamiento_model->setestimulacion_fecha($this->input->post('estimulacion_fecha'));
        $this->Enrolamiento_model->setestimulacion_capacitado($this->input->post('estimulacion_capacitado'));
        $this->Enrolamiento_model->setsales_fecha($this->input->post('sales_fecha'));
        $this->Enrolamiento_model->setsales_cantidad($this->input->post('sales_cantidad'));
	}
	
	/**
	 * @access public
	 *
	 * Valida que la curp del paciente no exista
	 * 
	 * @param		string 		$curp      curp de la persona
	 *
	 * @return 		echo
	 */
	public function ifCurpExists($curp) 
	{
		$id=$this->input->post('id');
		if($id!="")
		{
			$curp = $this->input->post('curp').$this->input->post('curp2');
			if (empty($this->Enrolamiento_model))
				return false;
			$is_exist = null;
			try {
				$is_exist = $this->Enrolamiento_model->getByCurp($curp,'cns_persona',$id);
			}
			catch(Exception $e){
			}
			if ($is_exist) 
			{
				$this->form_validation->set_message(
						'ifCurpExists', 'El curp del paciente ya existe.'
				);
				return false;
			} 
			else 
			{
				if (!$this->Enrolamiento_model->getMsgError())
					return true;
				else
				{
					$this->form_validation->set_message(
							'ifCurpExists', $this->Enrolamiento_model->getMsgError()
					);
					return false;
				}
			}
		}else return true;
	}
	/**
	 * @access public
	 *
	 * valida que la curp del tutor no exista
	 * 
	 * @param		string 		$curp      curp de la persona
	 *
	 * @return 		echo
	 */
	public function ifCurpTExists($curp) 
	{
		$id=$this->input->post('idtutor');
		if($id!="")
		{
			if (empty($this->Enrolamiento_model))
				return false;
			$is_exist = null;
			try {
				$is_exist = $this->Enrolamiento_model->getByCurp($curp,'cns_tutor',$id);
			}
			catch(Exception $e){
			}
			if ($is_exist) 
			{
				$this->form_validation->set_message(
						'ifCurpTExists', 'El curp del tutor ya existe.'
				);
				return false;
			} 
			else 
			{
				if (!$this->Enrolamiento_model->getMsgError())
					return true;
				else{
					$this->form_validation->set_message(
							'ifCurpTExists', $this->Enrolamiento_model->getMsgError()
					);
					return false;
				}
			}
		}
	}
	 /**
	 * @access public
	 *
	 * Este metodo verifica si un paciente comparte un mismo tutor
	 * 
	 * @param		string 		$id      id de la persona
	 *
	 * @return 		echo
	 */
	public function brother_found($id)
	{
		$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
		$result=$this->Reporte_sincronizacion_model->getListado("SELECT p.id,  UPPER(CONCAT(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno)) AS nombre, p.calle_domicilio,  p.numero_domicilio, 
p.referencia_domicilio, p.colonia_domicilio, p.cp_domicilio, p.ageb, p.sector, p.manzana, p.id_asu_localidad_domicilio, p.telefono_domicilio
FROM  cns_persona p WHERE p.id='$id'");
		echo json_encode($result);
	}
	 
	 /**
	 * @access public
	 *
	 * Este metodo extrae la informacion de las personas con las que se comparte el mismo tutor si se selecciona una de estas importa los datos para el apartado direccion
	 * 
	 * @param		string 		$tutor      id del tutor compartido
	 *
	 * @return 		echo
	 */
	public function brothers_search($tutor)
	{
		$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
		$result=$this->Reporte_sincronizacion_model->getListado("SELECT DISTINCT t.id_persona,  UPPER(CONCAT(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno)) AS nombre, p.calle_domicilio,  p.numero_domicilio, 
p.referencia_domicilio, p.colonia_domicilio, p.cp_domicilio, p.ageb, p.sector, p.manzana, p.id_asu_localidad_domicilio, p.telefono_domicilio
FROM cns_persona_x_tutor t
LEFT JOIN cns_persona p ON p.id=t.id_persona
WHERE t.id_tutor='$tutor' and t.id_tutor!='ffec1916fae9ee3q3a1a98f0a7b31400'");
		echo json_encode($result);
	}
	 /**
	 * @access public
	 *
	 * valida que el nodo seleccionado en el arbol sea una unidad medica
	 * 
	 * @param		string 		$id      id de arbol de segmentacion
	 *
	 * @return 		echo
	 */
	public function validarisum($id)
	{
		$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
		$result=$this->Reporte_sincronizacion_model->getListado("SELECT grado_segmentacion FROM asu_arbol_segmentacion WHERE id='".$id."'");
		
		if($result[0]->grado_segmentacion!="5")
		echo "no";
	}
        
	 /**
	 * @access public
	 *
	 * Busca dentro del catalogo asu_ageb la unidad médica de acuerdo a la localidad y ageb
	 * Solo se permite su acceso por medio de peticiones AJAX
         * 
	 * @param int $idasulocalidad Id de la localidad en el ASU 
	 * @param string $ageb Numero de ageb
         * 
	 * @return Object 
         * (key,value)
         * (-1 , Clues) = La clues existe en el catalogo AGEB pero no está registrada en nuestro arbol ASU
         * (0,0) = No existe ninguna unidad medica con esa localidad y ageb
         * (1,Clues) = Si existe en el catalogo AGEB y en el ASU
	 */
	public function searchum($idasulocalidad,$ageb)
	{
		if (!$this->input->is_ajax_request())
		show_error('', 403, 'Acceso denegado');
                
		$this->load->model(DIR_SIIGS.'/Ageb_model');
		$result=$this->Ageb_model->searchUM($idasulocalidad,$ageb);
                
		if ($result == -1)
			echo json_encode (array("clave"=> 0,"valor"=>0));
		else if ($result == 0) {
			echo json_encode (array("clave"=> -1,"valor"=>$result));
		}
		else{
			echo json_encode (array("clave"=> 1,"valor"=>$result));
		}
	}
        
        
	 /**
	 * @access public
	 *
	 * Regresa un objeto JSON con la lista de agebs disponibles en la localidad
	 * Solo se permite su acceso por medio de peticiones AJAX
         * 
	 * @param int $idasulocalidad Id de la localidad en el ASU
         * 
	 * @return Object 
	 */
	public function searchageb($idasulocalidad="",$like="")
	{
		if (!$this->input->is_ajax_request())
		show_error('', 403, 'Acceso denegado');
                
		$this->load->model(DIR_SIIGS.'/Ageb_model');
		$result=$this->Ageb_model->searchageb($idasulocalidad,$like);
                $dato = array();
                foreach ($result as $item)
                    $dato[] = $item->ageb;
                echo json_encode ($dato);
	}
        
        
	/**
	 * @access public
	 *
	 * Comprueba la similitud de un paciente que se este capturando con los que ya existe en la base de datos, 
	 * esto con la finalidad de disminuir datos repetidos
	 * 
	 * @param		string 		$nombre       nombre del paciente que se esta capturando
	 * @param		string 		$paterno      apellido paterno del paciente
	 * @param		string 		$materno      apellido materno del paciente
	 * @param		string 		$curp         curp del paciente
	 * @param		string 		$nacimiento   fecha de nacimiento del paciente
	 * @param		string 		$calle        calle del domicilio del paciente
	 * @param		string 		$referencia   referencia del domicilio del paciente
	 * @param		string 		$colonia      colonia del paciente
	 * @param		string 		$cp           cp de la colonia donde vive el paciente
	 * @param		string 		$numero       numero de la vivienda
	 *
	 * @return 		json($porcentaje_similitud,$persona)
	 */
	public function paciente_similar($nombre, $paterno, $materno, $curp, $nacimiento, $lugar, $calle="", $referencia="", $colonia="", $curpT="")
	{
		$this->load->model(DIR_TES.'/Enrolamiento_model');
		$result=$this->Enrolamiento_model->get_pacientes();
		
		$array=array();
		if($result)
		{
			foreach($result as $x)
			{
				$similar=0;
				similar_text(urldecode($nombre), $x->nombre, $percent); 
				$similar=$similar+$percent;
				
				similar_text(urldecode($paterno), $x->apellido_paterno, $percent); 
				$similar=$similar+$percent;
				
				similar_text(urldecode($materno), $x->apellido_materno, $percent); 
				$similar=$similar+$percent;
				
				similar_text(urldecode($curp), $x->curp, $percent); 
				$similar=$similar+$percent;								
				
				similar_text(urldecode($calle), $x->calle_domicilio, $percent); 
				$similar=$similar+$percent;
				
				similar_text(urldecode($colonia), $x->colonia_domicilio, $percent); 
				$similar=$similar+$percent;
				
				similar_text(urldecode($lugar), $x->lugar, $percent); 
				$similar=$similar+$percent;
				
				similar_text(urldecode($curpT), $x->curpT, $percent); 
				$similar=$similar+$percent;
				
				$total=$similar/8;
				if($total>50&&(date('Y-m-d', strtotime($nacimiento)) == $x->fecha_nacimiento)||$total>92)
				$array[]=array("nombre" => $x->nombre.' '.$x->apellido_paterno.' '.$x->apellido_materno, "id" => $x->id, "total" => round($total, 2));
			}
		}
		echo json_encode($array); 
	}
	
	/**
	 * @access public
	 *
	 * Crea la pagina para ver la infromacion de la persona y comprararla con la persona capturada
	 * 
	 * @param		string 		$id        identificador de la persona 
	 *
	 * @return 		echo
	 */
	public function comparar_view($id,$prod1="",$prod2="",$prod3="")
	{
		try 
		{
			$this->load->model(DIR_TES.'/Enrolamiento_model');
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			if (empty($this->Enrolamiento_model))
				return false;
			
			$data['title'] = 'Ver Paciente';
			$data['enrolado'] = $this->Enrolamiento_model->getById($id);
			if(empty($data['enrolado']))
			{
				$data['infoclass'] = 'error';
				$data['msgResult'] = "Registro no encontrado";
				
				$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_compara', $data);
 				$this->template->render();
				return true;
			}
			$prod1=urldecode($prod1);
			$data['prod1']=explode("°",$prod1);
			
			$prod2=urldecode($prod2);
			$data['prod2']=explode("°",$prod2);
			
			$prod3=urldecode($prod3);
			$data['prod3']=explode("°",$prod3);
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
		$this->template->write('sala_prensa','',true);
 		$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento_compara', $data);
 		$this->template->render();
	}
	public function vacunacion($fecha,$id="")
	{
		$fecha=date("Y-m-d",strtotime($fecha));
		$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
                //echo "SELECT DISTINCT	r.id_vacuna,cv.codigo_barras, v.descripcion,r.dia_inicio_aplicacion_nacido, r.dia_fin_aplicacion_nacido, p.fecha_nacimiento, CASE WHEN r.id_vacuna = cv.id_vacuna THEN 'X' ELSE '' END AS tiene, CASE WHEN cv.fecha IS NULL THEN CONCAT('Desde:',r.dia_inicio_aplicacion_nacido,' Hasta:',r.dia_fin_aplicacion_nacido) ELSE CONCAT('Fecha Aplicada: ',' ',DATE_FORMAT(cv.fecha, '%d-%m-%Y')) END AS fecha,DATEDIFF(NOW(),'$fecha') AS dias,CASE WHEN DATEDIFF(NOW(),'$fecha')>=r.dia_inicio_aplicacion_nacido AND DATEDIFF(NOW(),'$fecha')<=r.dia_fin_aplicacion_nacido  THEN '1' ELSE (CASE WHEN DATEDIFF(NOW(),'$fecha')>r.dia_fin_aplicacion_nacido AND cv.fecha IS NULL THEN '2' ELSE (CASE WHEN DATEDIFF(NOW(),'$fecha')< r.dia_inicio_aplicacion_nacido AND cv.fecha IS NULL THEN '3' END) END) END AS prioridad FROM cns_regla_vacuna r LEFT JOIN cns_vacuna v ON v.id=r.id_vacuna LEFT JOIN cns_control_vacuna cv ON cv.id_persona='$id' AND cv.id_vacuna=r.id_vacuna  LEFT JOIN cns_persona p ON p.id=cv.id_persona GROUP BY v.descripcion ORDER BY r.id_vacuna,r.orden_esq_com ASC";
                //die();
		$data['vacunacion']=$this->Reporte_sincronizacion_model->getListado("
SELECT DISTINCT	r.id_vacuna,cv.codigo_barras, v.descripcion,r.dia_inicio_aplicacion_nacido, r.dia_fin_aplicacion_nacido, p.fecha_nacimiento, CASE WHEN r.id_vacuna = cv.id_vacuna THEN 'X' ELSE '' END AS tiene, CASE WHEN cv.fecha IS NULL THEN CONCAT('Desde:',r.dia_inicio_aplicacion_nacido,' Hasta:',r.dia_fin_aplicacion_nacido) ELSE CONCAT('Fecha Aplicada: ',' ',DATE_FORMAT(cv.fecha, '%d-%m-%Y')) END AS fecha,DATEDIFF(NOW(),'$fecha') AS dias,CASE WHEN DATEDIFF(NOW(),'$fecha')>=r.dia_inicio_aplicacion_nacido AND DATEDIFF(NOW(),'$fecha')<=r.dia_fin_aplicacion_nacido  THEN '1' ELSE (CASE WHEN DATEDIFF(NOW(),'$fecha')>r.dia_fin_aplicacion_nacido AND cv.fecha IS NULL THEN '2' ELSE (CASE WHEN DATEDIFF(NOW(),'$fecha')< r.dia_inicio_aplicacion_nacido AND cv.fecha IS NULL THEN '3' END) END) END AS prioridad FROM cns_regla_vacuna r LEFT JOIN cns_vacuna v ON v.id=r.id_vacuna LEFT JOIN cns_control_vacuna cv ON cv.id_persona='$id' AND cv.id_vacuna=r.id_vacuna  LEFT JOIN cns_persona p ON p.id=cv.id_persona GROUP BY v.descripcion ORDER BY r.id_vacuna,r.orden_esq_com ASC");
		$data["fecha"]=$fecha;
		$data['id_x']=$id;
		$this->load->view(DIR_TES.'/enrolamiento/vacuna', $data);
	}
	public function checar_session()
	{
		$this->load->library('session');
		if ($this->session->userdata(GROUP_ID)=="")
			echo "no";
		else 
			echo "si";
	}
	
	public function accion()
	{
		$data['prefix']='hola';
		$this->load->view(DIR_TES.'/TESNFC/Web/index',$data);
	}
    
	/**
	 * @access public
	 *
	 * Genera los options de un campo tipo select para las categorías de CIE10
	 * 
	 * @return 		echo
	 */
	public function categoriacie10_select()
	{
        $this->load->model(DIR_TES.'/Enrolamiento_model');
        
		$opcion = "";
		$datos = $this->Enrolamiento_model->getCategoriaCIE10();
        
		if(sizeof($datos) != 0)
		{
			$opcion .= "<option value=''>Seleccione...</option>";
			foreach($datos as $dato)
			{
				$opcion .= "<option value='$dato->id' >$dato->descripcion</option>";
			}
			echo $opcion;
		}
		else
            echo "<option>No hay Datos</option>";
	}
    
    /**
	 * @access public
	 *
	 * Genera los options de un campo tipo select para los CIE10 correspondientes a una categoría
	 * 
	 * @param		string 		$categoria   Categoría de la CIE10
	 * @return 		echo
	 */
	public function cie10_select($categoria)
	{
        $this->load->model(DIR_TES.'/Enrolamiento_model');
        
		$opcion = "";
		$datos = $this->Enrolamiento_model->getCIE10($categoria);
        
		if(sizeof($datos) != 0)
		{
			$opcion .= "<option value=''>Seleccione...</option>";
			foreach($datos as $dato)
			{
				$opcion .= "<option value='$dato->id_cie10' >$dato->descripcion</option>";
			}
			echo $opcion;
		}
		else
            echo "<option>No hay Datos</option>";
	}
    
}