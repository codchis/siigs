<?php
/**
 * Controlador Cie10
 *
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Geovanni
 * @created    2013-12-02
 */
class Cie10 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Cie10_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}
        
        /**
	 *Acción por default del controlador, carga la lista
	 *de datos disponibles en el cie10 y una lista de opciones
	 *No recibe parámetros
	 *
	 *@return void
	 */
	public function index($pag = 0)
	{
		if (empty($this->Cie10_model))
			return false;
                
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
	
                try
		{
                    $this->load->library('pagination');
                    
                        //Configuracion para la paginacion
			$configPag['base_url']   ='/'. DIR_SIIGS.'/cie10/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['total_rows'] = $this->Cie10_model->getNumRows();
			$configPag['uri_segment'] = '4';
			$configPag['per_page']   = REGISTROS_PAGINADOR;

			$this->pagination->initialize($configPag);

			$this->Cie10_model->setOffset($pag);
			$this->Cie10_model->setRows($configPag['per_page']);

			$data['title'] = 'Lista de datos en el catálogo CIE10';
			$data['datos'] = $this->Cie10_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/cie10/index', $data);
                
		$this->template->render();
	}
        
                 /***
         * Accion para agregar elementos del catalogo cie10 a los catalogos de EDA, IRA y Consultas dependiendo de los
         * parametros pasados
         * @param Int $id Es el id del registro en el catalogo de CIE10
         * @param String $catalogo para determinar a que catalogo se va a agregar o quitar el registro
         * @param Boolean $activo False para quitar del catalogo, true para agregarlo
         * @return Boolean En caso de error, o errores de referencia, etc.
         */
        
        public function view($cat){
		if (empty($this->Cie10_model))
			return false;
                
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
	
                try
		{
			$data['title'] = 'Datos en el catálogo '.$cat;
			$data['datos'] = $this->Cie10_model->getCatalogoByName($cat);
                        $data['catalogo'] = $cat;
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = 'success';
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/cie10/view', $data);
                
		$this->template->render();
        }

         /***
         * Accion para agregar elementos del catalogo cie10 a los catalogos de EDA, IRA y Consultas dependiendo de los
         * parametros pasados
         * @param Int $id Es el id del registro en el catalogo de CIE10
         * @param String $catalogo para determinar a que catalogo se va a agregar o quitar el registro
         * @param Boolean $activo False para quitar del catalogo, true para agregarlo
         * @return Boolean En caso de error, o errores de referencia, etc.
         */
        
        public function AgregaEnCatalogo(){
            
		if (!$this->input->is_ajax_request())
                show_error('', 403, 'Acceso denegado');
            
             try 
            {
		if ($this->input->is_ajax_request())
		{
                    $id = $this->input->post('id');
                    $catalogo = $this->input->post('catalogo');
                    $activo = $this->input->post('activo');
                    //$id = 6110;
                    //$catalogo = "eda";
                    //$activo = true;
                    if ($id && $catalogo)
                    {
                        $resultado = $this->Cie10_model->agregaEnCatalogo($id,"cns_".$catalogo,$activo);
                        if ($resultado == true)
                            echo "ok";
                        else
                            echo "error";
                    }
                    else
                        echo "Parametros incorrectos";
		}
		else echo 'Acceso denegado';
            }
            catch(Exception $e)
            {
		echo $e->getMessage();
            }
        }
        
         /***
         * Accion para activar o desactivar elementos en los catalogos IRA EDA Consultas
         * parametros pasados
         * @param Int $id Es el id del registro en el catalogo
         * @param String $catalogo para determinar a que catalogo se va a agregar o quitar el registro
         * @param Boolean $activo False para quitar del catalogo, true para agregarlo
         * @return Boolean En caso de error, o errores de referencia, etc.
         */
        
        public function ActivaEnCatalogo(){
                        
            if (!$this->input->is_ajax_request())
            show_error('', 403, 'Acceso denegado');
            
             try 
            {
		if ($this->input->is_ajax_request())
		{
                    $id = $this->input->post('id');
                    $catalogo = $this->input->post('catalogo');
                    $activo = $this->input->post('activo');
                    //$id = 6110;
                    //$catalogo = "eda";
                    //$activo = true;
                    if ($id && $catalogo)
                    {
                        $resultado = $this->Cie10_model->activaEnCatalogo($id,"cns_".$catalogo,$activo);
                        if ($resultado == true)
                            echo "ok";
                        else
                            echo "error";
                    }
                    else
                        echo "Parametros incorrectos";
		}
		else echo 'Acceso denegado';
            }
            catch(Exception $e)
            {
		echo $e->getMessage();
            }
        }
        
        /**
	 *Acción para cargar datos desde un archivo CSV, recibe el stream desde las variables PHP
	 *Guarda en la tabla tmp_catalogos toda la estructura del CSV e imprime las columnas del
	 *archivo
	 *
	 * @return void
	 */
	public function load()
	{
            
            if (!$this->input->is_ajax_request())
            show_error('', 403, 'Acceso denegado');
                        
		if (isset($_FILES["archivocsv"]) && is_uploaded_file($_FILES['archivocsv']['tmp_name']))
		//if (TRUE)
		{
			 $fp = fopen($_FILES['archivocsv']['tmp_name'], "r");
		//	 $fp = fopen('catalogos/estados.csv', "r");
                         $columnas = array('cie10','descripcion');
			 while (!feof($fp))
			 {
			  	$data  = explode(",", fgets($fp));
                                if (count($data) == 2)
                                {
                                    $data = preg_replace("!\r?\n!", "", $data);
                                    {
                                            //crea los rows con las filas que cumplen con la estructura en el CSV
                                            if (count($columnas) == count($data))
                                            {
                                                    $item = array_combine($columnas, $data);
                                                    array_push($rows, $item);
                                            }
                                    }
                                }
			 }

			 //Inserta los datos en lotes a la tabla temporal
			 $this->db->insert_batch('cns_cie10',$rows);

		}
		else
		{
			echo "Error:el archivo no se cargó correctamente";
		}
	}


	/**
	 *Acción para preparar la actualizacion de un catálogo ya existente,
	 *recibe un string para obtener los valores del catalogo y mostrarlos
	 *en la vista update , realiza la validacion del formulario del lado
	 *del cliente y servidor
	 *
	 * @param  string $nombre
	 * @return void
	 */
	public function update($id)
	{
            if (empty($this->Cie10_model))
                return false;
              
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
            
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = "Modificar datos del catalogo CIE10";
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[100]');

		if ($this->form_validation->run() === FALSE)
		{
			try
			{
				$data['catalogo_item'] = $this->Cie10_model->getById($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
			}

			$this->template->write_view('content',DIR_SIIGS.'/cie10/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->Cie10_model->setDescripcion($this->input->post('descripcion'));
				$this->Cie10_model->setId($this->input->post('id'));
				$this->Cie10_model->update();
			}
			catch (Exception $e)
			{
				try
				{
					$data['catalogo_item'] = $this->Cie10_model->getById($id);
				}
				catch (Exception $e)
				{
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
					$data['clsResult'] = 'error';
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/cie10/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/cie10','refresh');
			}
		}		
	}

                /**
	 *Acción para cargar datos desde un archivo CSV, recibe el stream desde las variables PHP
	 *compara los datos recibidos con los datos que contiene actualmente el catálogo, regresa como 
	 *resultado las filas nuevas y las filas a modificar 
	 *
	 * @return void
	 */
	public function insert($update = false)
	{
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
            show_error('', 403, 'Acceso denegado');
            
		//if (isset($_FILES["archivocsv"]) && is_uploaded_file($_FILES['archivocsv']['tmp_name']))
		if (TRUE)
		{
		//	 $fp = fopen($_FILES['archivocsv']['tmp_name'], "r");
			 $fp = fopen('catalogos/CIE10.csv', "r");
			 $cont = 0;
			 $columnas = array();
			 $resultado = array();
			 $rows = array();
			 $nuevos = 0; $modificados = 0; $iguales = 0; $errores = 0;

			 //obtiene los datos del catalogo
			 try 
	  		 {
                            $rowscat = $this->Cie10_model->getData();
	  		 }
                         catch (Exception $e) 
			 {
                            echo Errorlog_model::save($e->getMessage(), __METHOD__);
                            return;
			 }
			 try 
			 {
			 	//array que contiene todos los registros llave
			 	$datallaves = array();
							 	
			 	//array para hacer modificaciones por lotes
			 	$consultamodificar = array();
			 	//array para hacer inserciones por lotes
			 	$consultaagregar = array();
			 	
			 	//filtro solo los nombres de los campos
			 	$campos = array('cie10','descripcion');
			 	$llaves = array('cie10');
			 				 	
		 		//obtiene las llaves primarias del catalogo
		 		$rowsllaves = $this->db->query("select ".implode(",",$llaves)." from cns_cie10");
		 		$rowsllaves = $rowsllaves->result();
		 		//var_dump($rowsllaves);
			 } 
			 catch (Exception $e) 
			 {
			 	echo Errorlog_model::save($e->getMessage(), __METHOD__);
			 	die();
			 }
			 while (!feof($fp))
			 {
                                $utf8_encode = function($val)
                                {
                                    return utf8_encode($val);
                                };
			  	$data  = array_map($utf8_encode,explode(",", fgets($fp)));
			  	
			  	$cont +=1;
				$data = preg_replace("!\r?\n!", "", $data);
			  	if ($cont == 1)
			  	{
			  		$errorcols = false;
			  		$columnas = $data;
			  		if (count($campos) != count($columnas))
			  		$errorcols = true;
			  		for ($i = 0; $i < count($campos); $i++) 
			  		{
			  			if (!isset($columnas[$i]) || !isset($campos[$i]) || $campos[$i] != $columnas[$i])
				  			$errorcols = true;
			  		}
			  		if ($errorcols)
			  		{
			  			echo json_encode(array("Error","Las columnas del CSV no coinciden con la estructura de la tabla"));
			  			die();
			  		}
			  		else 
			  		{
			  			$indicellaves = array();
			  			//Obtiene los indices de columnas que corresponden a las llaves en el CSV
			  			for ($i = 0; $i < count($columnas); $i++) 
			  			{
			  				if (in_array($columnas[$i],$llaves))
			  				{
			  					array_push($indicellaves,$i);
			  				}
			  			}
			  		}
			  	}
			  	else
			  	{
			  		if (count($columnas) == count($data))
			  		{	
			  			$datallave = array();
			  			foreach ($indicellaves as $i)
			  			{
			  				array_push($datallave, $data[$i]);
			  			}
			  			//agrega el registro llave a la lista de llaves
			  			array_push($datallaves,$datallave);

				  		//agrega las claves con nombres de campo
			  			$procesada = array_combine($columnas,$data);
			  			//si el registro existe en el catalogo
                                                //  var_dump((object)$procesada);
                                                //  var_dump($rowscat);
                                                //  echo "<br/>";
				  		if (in_array((object)$procesada, $rowscat))
				  		{
				  			$iguales += 1;				  			
				  		}
				  		else
				  		{
				  			//si la clave existe en el catalogo
				  			if (in_array((object)array_combine($llaves,$datallave), $rowsllaves))
				  			{
								$consultaupdate = 'update cns_cie10 set ';
								$consultaupdatewhere = ' where 1=1 ';
								foreach ($procesada as $key => $value) 
								{
									$contcampos = 0; $contllaves = 0;
									if (!in_array($key, $llaves))
									{
									$consultaupdate .= $key." = '".$value."',";
									}
									else
									{
										$consultaupdatewhere .= ' and '.$key." = '".$value."'";
									}
								}
								$consultaupdate = substr($consultaupdate,0, count($consultaupdate)-2);
								$consultaupdate .= $consultaupdatewhere;
								array_push($consultamodificar, $consultaupdate);
				  				$modificados += 1;
				  			}
				  			else
				  			{
                                                        //       echo implode(',',(array)array_combine($llaves,$datallave))."<br/><br/>";
                                                        //       echo implode(',',$procesada)."<br/><br/>";
				  				array_push($consultaagregar,"insert into cns_cie10 (".implode(",", $campos).") values ('".implode("','", $data)."')");
				  				$nuevos += 1;
				  			}
				  		}
			  		}
                                        else {
                                            $errores +=1;
                                        }
			  	}
			 }
			 if (count($datallaves) != count($this->_array_unique_recursive($datallaves)))
			 {
			 	echo json_encode(array("Error","El archivo contiene llaves primarias duplicadas"));
			 	die();
			 }
			 else 
			 {
				 array_push($resultado,array('Numero de registros anteriores',count($rowscat)));
				 array_push($resultado,array('Numero de registros actuales',$cont-1));
				 array_push($resultado,array('Numero de registros a insertar',$nuevos));
				 array_push($resultado,array('Numero de registros a modificar',$modificados));
				 array_push($resultado,array('Numero de registros sin cambios',$iguales));
                                 array_push($resultado,array('Numero de registros con errores',$errores));
			 }
			 if ($update == false)
			 echo json_encode($resultado);
			 else 
			 {
                           //  var_dump($consultamodificar);
                           //  var_dump($consultaagregar);
			 	$this->db->trans_begin();
				foreach ($consultamodificar as $sql)
					$this->db->query($sql);
				foreach ($consultaagregar as $sql)
				$this->db->query($sql);{
				
				if ($this->db->trans_status() === FALSE)
				{
				    $this->db->trans_rollback();
				    echo json_encode(array("Error","Ha ocurrido un error al hacer el volcado, los datos no se modificaron."));
				}
				else
				{
				    $this->db->trans_commit();
				    echo json_encode(array("Ok","Los datos del catalogo se han modificado correctamente"));
				}
			 }
			}
		}
		else
		{
			 	echo json_encode(array("Error","El archivo no ha sido cargado correctamente."));
			 	die();
		}
	}

        /**
	 * _array_unique_recursive
	 * Revisa valores duplicados en arreglos que contienen arreglos
	 * 
	 * @param array $arr
	 */
	public function _array_unique_recursive($arr)
	{
		foreach($arr as $key=>$value)
			if(gettype($value)=='array')
			    $arr[$key]=$this->_array_unique_recursive($value);
			return array_unique($arr,SORT_REGULAR);
	}

}
