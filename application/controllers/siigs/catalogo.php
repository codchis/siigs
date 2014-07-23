<?php
/**
 * Controlador Catalogo
 * 
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Geovanni
 * @created    2013-10-07
 */
class Catalogo extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

			try
		{
                        $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Catalogo_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}

	/**
	 *Acción por default del controlador, carga la lista
	 *de catálogos disponibles y una lista de opciones
	 *No recibe parámetros
	 *
	 *@return void
	 */
	public function index()
	{
		if (empty($this->Catalogo_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
		try
		{

			$data['title'] = 'Lista de catálogos disponibles';
			$data['catalogos'] = $this->Catalogo_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
                        $data['clsResult'] = $this->session->flashdata('clsResult');
		}
		catch (Exception $e)
		{
                        $data['clsResult'] = "error";
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/catalogo/index', $data);
                
		$this->template->render();
	}

	/**
	 *Acción para visualizar información de un catálogo específico, obtiene el objeto
	 *catálogo por medio del nombre proporcionado
	 *
	 * @param  string $nombre Este parámetro no puede ser nulo
         * @param int $pag Numero de registro para el paginador
	 * @return void
	 */
	public function view($nombre, $pag = 0)
	{
		if (empty($this->Catalogo_model))
			return false;
                
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		if ($this->input->is_ajax_request())
		{
			try
			{
				$data['catalogo_item'] = $this->Catalogo_model->getByName($nombre);
				echo json_encode($data['catalogo_item']);

			} catch (Exception $e)
			{
				echo 'false';
			}
		exit;
		}
                
                                
                $this->load->library('pagination');
                $this->load->helper('form');

                //Configuracion para la paginacion
                $configPag['base_url']   ='/'. DIR_SIIGS.'/catalogo/view/'.$nombre.'/';
                $configPag['first_link'] = 'Primero';
                $configPag['last_link']  = '&Uacute;ltimo';
                $configPag['total_rows'] = $this->Catalogo_model->getNumRows($nombre);
                $configPag['uri_segment'] = '5';
                $configPag['per_page']   = 50;

                $this->pagination->initialize($configPag);
                $this->Catalogo_model->setOffset($pag);
                $this->Catalogo_model->setRows($configPag['per_page']);                
                
		try
		{
			$data['title'] = "Detalles del catálogo";
			$data['catalogo_item'] = $this->Catalogo_model->getByName($nombre);
                        $data['datos_cat'] = $this->Catalogo_model->getAllData($nombre);
		}
		catch (Exception $e)
		{
                        $data['clsResult'] = "error";
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

		$this->template->write_view('content',DIR_SIIGS.'/catalogo/view', $data);
		$this->template->render();
	}

	/**
	 *Acción para cargar datos desde un archivo CSV, recibe el stream desde las variables PHP
	 *Guarda en la tabla tmp_catalogos toda la estructura del CSV e imprime las columnas del
	 *archivo. Solo se permite su acceso por medio de peticiones AJAX
	 *
         * @param $_FILES[] archivocsv Variable pasada por POST con el archivo csv para cargar datos
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
			 $cont = 0;
			 $columnas = array();
			 $resultado = array();
			 $rows = array();

			 $consulta = 'select id_cat_tipo_columna as id,descripcion as descripcion from asu_tipo_columna;';
			 $dbtiposdatos = $this->db->query($consulta);
			 $tiposdatos = array();
			 foreach ($dbtiposdatos->result() as $item)
			 {
			 	array_push($tiposdatos, array('clave' => $item->id , 'valor' => $item->descripcion));
			 }
			 //while (!feof($fp))
                         while (($data = fgetcsv($fp)) !== FALSE)
			 {
			  	$utf8_encode = function($val)
                                {
                                    return utf8_encode($val);
                                };
			  	$data  = array_map($utf8_encode,$data);
                                
			  	$cont +=1;
				$data = preg_replace("!\r?\n!", "", $data);

			  	if ($cont == 1)
			  	{
			  		$columnas = $data;
                                        
                                        //Revisar si hay columnas con el mismo nombre
                                        if (count($columnas) <> count(array_unique($columnas)))
                                        {
                                            echo json_encode(array("Error","El archivo contiene columnas con el mismo nombre"));
                                            return;
                                        }
                                        if (in_array('id', $columnas))
                                        {
                                            echo json_encode(array("Error","No puede usar el nombre de columna 'id', por favor intente con id_{nombre del catalogo}"));
                                            return;
                                        }
                                        
			  		//elimina la tabla temporal para crear catalogos
			  		$consulta = 'drop table if exists tmp_catalogo;';
			  		$query = $this->db->query($consulta);
			  		//crea la tabla temporal para catalogos con la estructura del CSV
			  		$consulta = 'create table if not exists tmp_catalogo (';
			  		foreach ($columnas as $col)
			  		{
			  			array_push($resultado, array('columnName' => $col , 'tiposDato' => $tiposdatos));
			  			$consulta .= $col.' varchar(50),';
			  		}
			  		$consulta = substr($consulta, 0,count($consulta)-2);
			  		$consulta .= ');';
			  		$query = $this->db->query($consulta);

			  		//enviar el resultado con el numero de columnas del csv
			  		echo json_encode((object)$resultado);
			  	}
			  	else
			  	{
			  		//crea los rows con las filas que cumplen con la estructura en el CSV
			  		if (count($columnas) == count($data))
			  		{
				  		$item = array_combine($columnas, $data);
				  		array_push($rows, $item);
			  		}
			  	}
			 }
                         fclose($fp);
			 //Inserta los datos en lotes a la tabla temporal
			 $this->db->insert_batch('tmp_catalogo',$rows);

		}
		else
		{
			echo json_encode("Error:el archivo no se cargó correctamente");
                        return;
		}
	}

	
        /**
	 *Acción para cargar datos desde un archivo CSV, recibe el stream desde las variables PHP
	 *compara los datos recibidos con los datos que contiene actualmente el catálogo, regresa como 
	 *resultado las filas nuevas y las filas a modificar.
         * Solo se permite su acceso por medio de peticiones AJAX
	 *
         * @param $_FILES[] archivocsv Variable pasada por POST con el archivo csv para cargar datos
	 * @return void
	 */
	public function loadupdate($nombrecat , $update = false)
	{

		if (!$this->input->is_ajax_request())
                show_error('', 403, 'Acceso denegado');
            
                ini_set('max_execution_time',1000);
                
		if (isset($_FILES["archivocsv"]) && is_uploaded_file($_FILES['archivocsv']['tmp_name']))
		//if (TRUE)
		{
			 $fp = fopen($_FILES['archivocsv']['tmp_name'], "r");
			 //$fp = fopen('catalogos/estados.csv', "r");
			 $cont = 0;
			 $columnas = array();
			 $resultado = array();
			 $rows = array();
			 $nuevos = 0; $modificados = 0; $iguales = 0;$errores = 0; $lineaserrores = array();

			 //obtiene los datos del catalogo
			 try 
	  		 {
	  			$rowscat = $this->Catalogo_model->getAllData($nombrecat);
                                if (count($rowscat)>0)
                                {
                                    //Si el catalogo contiene una columna ID
                                    //se hace el recorrido del arreglo para quitar esa columna
                                    if (array_key_exists('id', $rowscat[0]))
                                    {
                                            $temprowscat = array();
                                            foreach ($rowscat as $row)
                                            {
                                                    $row = get_object_vars($row);
                                                    $nuevorow = array();
                                                    foreach (array_keys($row) as $rowkey)
                                                    {
                                                            if ($rowkey != 'id')
                                                                    $nuevorow[$rowkey] = $row[$rowkey];
                                                    }
                                                    array_push($temprowscat, (object)$nuevorow);
                                            }
                                            $rowscat = $temprowscat;
                                            //var_dump($rowscat);
                                    }
                                }
	  		 }
                         catch (Exception $e) 
			 {
			 	Errorlog_model::save($e->getMessage(), __METHOD__);
			 	return;
			 }
			 
			 try 
			 {
			 	//array que contiene todos los registros llave
			 	$datallaves = array();
				//obtiene la estructura del catalogo
			 	$catalogo = $this->Catalogo_model->getByName($nombrecat);
                                 
			 	//obtiene los nombres de los campos con su tipo de dato y otros valores
			 	$campostemp = explode('||', $catalogo->campos);
			 	$llavestemp = explode('||',$catalogo->llave);
			 	
			 	//array para hacer modificaciones por lotes
			 	$consultamodificar = array();
			 	//array para hacer inserciones por lotes
			 	$consultaagregar = array();
			 	
			 	//filtro solo los nombres de los campos
			 	$campos = array();
			 	$llaves = array();
			 	//obtiene el nombre de los campos y las llaves
			 	foreach($campostemp as $item)
			 		array_push($campos, explode('|',$item)[0]);
                                                                
			 	foreach($llavestemp as $item)
			 	{
			 		$key = explode('|',$item)[0];
			 		if ($key != 'id')
			 			array_push($llaves, $key);
			 	}                                
			 	if (count($llaves) == 0)
			 	{
				 	// se obtienen todos los campos que contengan el subfijo id_ y se toman
				 	//como llaves primarias para el mapeo de datos repetidos en caso de que 
				 	//la llave primaria sea 'id' creada por el sistema
				 	foreach($campostemp as $item)
				 	{
				 		$key = explode('|',$item)[0];
				 		if (strpos($key, 'id_') !== false)
				 			array_push($llaves, $key);
				 	}
			 	}
			 	else 
			 	{
				 	$colstemp = $llaves;
			 		foreach ($campos as $campo)
			 		{
			 			array_push($colstemp, $campo);
			 		}
			 		$campos = $colstemp;
			 	}
			 	//agrega las llaves como campos normales para obtener el numero total de columnas
                                //echo "select ".implode(",",$llaves)." from ".$nombrecat;
		 		//obtiene las llaves primarias del catalogo
		 		$rowsllaves = $this->db->query("select ".implode(",",$llaves)." from ".$nombrecat);
		 		$rowsllaves = $rowsllaves->result();
		 		//var_dump($rowsllaves);
			 } 
			 catch (Exception $e) 
			 {
			 	Errorlog_model::save($e->getMessage(), __METHOD__);
			 	die();
			 }
                         ini_set('memory_limit', '1024M');
                         $error = false;
			 //while (!feof($fp))
                         while (($data = fgetcsv($fp)) !== FALSE)
			 {
			  	//$data  = explode(",", fgets($fp));
			  	
                                $utf8_encode = function($val)
                                {
                                    return utf8_encode(addslashes($val));
                                };
			  	$data  = array_map($utf8_encode,$data);
			  	
                                
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
			  			echo json_encode(array("Error","Las columnas del CSV no coinciden con la estructura de la tabla".  implode(',', $campos)."  ".  implode(',', $columnas)));
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
				  		if (in_array((object)$procesada, $rowscat))
				  		{
				  			$iguales += 1;				  			
				  		}
				  		else
				  		{
				  			//si la clave existe en el catalogo
				  			if (in_array((object)array_combine($llaves,$datallave), $rowsllaves))
				  			{
                                                            
							if ($update == true)
                                                            {
								$consultaupdate = 'update '.$nombrecat. ' set ';
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
                                                                
                                                                if (count($consultamodificar)== 1000)
                                                                {
                                                                   foreach ($consultamodificar as $sql)
                                                                        if(!$this->db->query($sql))
                                                                            $error = true;
                                                                    $consultamodificar = array();
                                                                }
                                                            }
				  				$modificados += 1;
				  			}
				  			else
				  			{
                                                            if ($update == true)
                                                            {
                                                                $arraytemp = array();
                                                               foreach($campos as $clave=>$valor)
                                                               {
                                                                   $arraytemp[$valor] = $data[$clave];
                                                               }
                                                               array_push($consultaagregar, $arraytemp);
                                                                if (count($consultaagregar)== 1000)
                                                                {
                                                                    if ($this->db->insert_batch($nombrecat,$consultaagregar)==0)
                                                                        $error = true;
                                                                    $consultaagregar = array();
                                                                }
                                                            }
				  				$nuevos += 1;
				  				//array_push($consultaagregar,"insert into ".$nombrecat. " (".implode(",", $campos).") values ('".implode("','", $data)."')");
				  			}
				  		}
			  		}
                                        else {
                                            $lineaserrores[] = $cont;
                                            $errores +=1;
                                        }
			  	}
			 }
                         fclose($fp);
			 if (count($datallaves) > count($this->_array_unique_recursive($datallaves)))
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
                                 array_push($resultado,array('Numero de registros con errores',$errores."(".implode(',',$lineaserrores).")"));
			 }
			 if ($update == false)
			 echo json_encode($resultado);
			 else 
			 {
                                if (count($consultaagregar)>0)
                                {
                                    if ($this->db->insert_batch($nombrecat,$consultaagregar)==0)
                                        $error = true;
                                }
                                if (count($consultamodificar)>0)
                                {
                                    
                                     foreach ($consultamodificar as $sql)
                                        if(!$this->db->query($sql))
                                            $error = true;
                                }
				
				if ($error == true)
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
		else
		{
        	 	echo json_encode(array("Error","El archivo no ha sido cargado correctamente."));
		 	die();
		}
	}
	
	/**
	 * _array_unique_recursive
	 * Revisa valores duplicados en arreglos multidimensionales
	 * 
	 * @param array $arr Arreglo multidimensional
	 */
	public function _array_unique_recursive($arr)
	{
		foreach($arr as $key=>$value)
			if(gettype($value)=='array')
                        {
                            $arr[$key]=implode(",",$value);
                        }
			return array_unique($arr,SORT_REGULAR);
	}
	
	/**
	 *Acción para preparar la inserción de nuevos catálogos , realiza la validación
	 *del formulario del lado del servidor y crea la estructura para el catálogo, crea
	 *la tabla y obtiene los datos a partir de la tabla tmp_catalogo
	 *
	 *@return void
	 */
	public function insert()
	{
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
            
		$error = false;
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Crear un nuevo catálogo';
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|alpha|max_length[30]');
		$this->form_validation->set_rules('campos[]', 'Campos', 'trim|xss_clean|required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/catalogo/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$nombrepost = $this->input->post('nombre');
				$campospost = $this->input->post('campos');
				$llavespost = $this->input->post('llaves');

				$querycreate = '';

				$queryselect = 'INSERT INTO cat_'.$nombrepost;
				$querycreate = 'create table cat_'.$nombrepost. '(';
				$camposquery = '';
				$campos = array();
				$llaves = array();

				//guarda el tamano de la clave en caso de ser compuesta
				$lenclave = 0;

				foreach($campospost as $campo)
				{
					//valida si el campo fue asignado como llave primaria
					if ($this->input->post('pk'.$campo) =='on')
					{
						if ($llavespost == '1')
						{
							//Si la llave primaria es de un campo entonces solo se agrega al arreglo llave
							array_push($llaves, $campo);
						}
						else
						{
							//se agrega a la lista de campos
							array_push($campos, $campo);
							//se agrega a la lista de llaves con LPAD para rellenar dependiendo el tamano del campo
							array_push($llaves,'LPAD('.$campo.', '.$this->input->post('len'.$campo).' , "0")');
							//obtener el tamano final de la llave compuesta
							$lenclave += $this->input->post('len'.$campo);
							//se agrega a la lista de campos para el query con su tipo de dato y tamano
							$camposquery .= $campo.' '.$this->input->post('type'.$campo).'('.$this->input->post('len'.$campo).'),';
						}
					}
					else
					{
						//se agrega a la lista de campos
						array_push($campos, $campo);
						//se agrega a la lista de campos para el query con su tipo de dato y tamano
						$camposquery .= $campo.' '.$this->input->post('type'.$campo).'('.$this->input->post('len'.$campo).'),';
					}
				}
				if (count($llaves) > 1)
				{
					//si la llave es compuesta se crea el arreglo de llaves en tmpllave
					$tmpllave = implode(",", $llaves);
					//se limpia el arreglo de llaves
					$llaves = array();
					//se asigna la llave compuesta al arreglo de llaves
					//array_push($llaves, 'concat('.$tmpllave.') as id');
					//se asigna la llave al query para crear la tabla
					$querycreate .= 'id int('.$lenclave.') primary key auto_increment,';
				}
				else
				{
					foreach ($llaves as $key=>$value)
					{
						//se asigna la unica llave existente al query para crear la tabla
						$querycreate .= $value.' '.$this->input->post('type'.$value).'('.$this->input->post('len'.$value).') primary key,';
					}
				}

				//se asignan los campos para crear la tabla
				//anteriormente se asignan las llaves
				$camposquery = substr($camposquery, 0,  count($camposquery)-2);
				$querycreate .= $camposquery.') comment "'.$this->input->post('comentario').'";';

				$queryselect .= '('.(($llavespost == 1) ?implode(",", $llaves).',' : '').implode(",", $campos).') select ';

				//se asignan las llaves al select
				$queryselect .= implode(",", $llaves);

				//se asignan los campos al select
				$queryselect .= (($llavespost==2) ? '' : ',').implode(",", $campos);
				$queryselect .= ' from tmp_catalogo';

				$this->Catalogo_model->insert($querycreate,$queryselect);
			}
			catch (Exception $e)
			{
                                $data['clsResult'] = "error";
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/catalogo/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
                            	$this->session->set_flashdata('clsResult', 'success');
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				redirect(DIR_SIIGS.'/catalogo/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualizacion de un catálogo ya existente,
	 *recibe un string para obtener los valores del catalogo y mostrarlos
	 *en la vista update , realiza la validación del formulario del lado
	 *del cliente y servidor
	 *
	 * @param  string $nombre
         * @param int $pag Numero de registro para el paginador
	 * @return void
	 */
	public function update($nombre, $pag=0)
	{
		if (empty($this->Catalogo_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');		
                
                                
                $this->load->library('pagination');
                $this->load->helper('form');

                //Configuracion para la paginacion
                $configPag['base_url']   ='/'. DIR_SIIGS.'/catalogo/update/'.$nombre.'/';
                $configPag['first_link'] = 'Primero';
                $configPag['last_link']  = '&Uacute;ltimo';
                $configPag['total_rows'] = $this->Catalogo_model->getNumRows($nombre);
                $configPag['uri_segment'] = '5';
                $configPag['per_page']   = 50;

                $this->pagination->initialize($configPag);
                $this->Catalogo_model->setOffset($pag);
                $this->Catalogo_model->setRows($configPag['per_page']); 
                
                if ($this->input->post('comentario'))
		{
                    try
			{
                            $this->Catalogo_model->updateComentario($nombre, $this->input->post('comentario'));
                            $data['clsResult'] = 'success';
                            $data['msgResult'] = 'Se modificó correctamente el comentario del catalogo';
			}
			catch (Exception $e)
			{
                        $data['clsResult'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);       
                        }
                }
                
		try
		{
			$data['title'] = "Modificar datos del catálogo";
			$data['catalogo_item'] = $this->Catalogo_model->getByName($nombre);
                        $data['catalogo_item']->nombre = $nombre;
                        $data['datos'] = $this->Catalogo_model->getAllData($nombre);
		}
		catch (Exception $e)
		{
                        $data['clsResult'] = 'error';
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}

        $this->template->write('ajustaAncho',1,true);
		$this->template->write_view('content',DIR_SIIGS.'/catalogo/update', $data);
		$this->template->render();
		
	}

	/**
	 *
	 *Acción para revisar registros repetidos en las columnas designadas como primary key
	 *
	 * @param  string $campos
	 * @return void
	 */
	public function checkpk($campos)
	{
		try
		{
			if (empty($this->Catalogo_model))
				return false;

			$this->load->helper('url');
			$result = $this->Catalogo_model->checkPk($campos);
			if (count($result) == 0)
				echo "true";
			else
				echo "false";
			
		}
		catch(Exception $e)
		{
			Errorlog_model::save($e->getMessage(), __METHOD__);	
			echo "false";
		}
	}
        
	/**
	 *
	 *Acción para revisar si los tipos de datos coinciden con los datos contenidos en 
         *la tabla temporal que fueron tomados del CSV
	 *
	 * @param  string $campo Nombre del campo a revisar
         * @param string $type Define el tipo de dato del campo
	 * @return void
	 */
	public function checkTypeData($campo,$type)
	{
		try
		{
			if (empty($this->Catalogo_model))
				return false;
                        
                        if (!$this->input->is_ajax_request())
                        show_error('', 403, 'Acceso denegado');

			$result = $this->Catalogo_model->checkTypeData($campo,$type);
			
                        echo $result;
			
		}
		catch(Exception $e)
		{
			Errorlog_model::save($e->getMessage(), __METHOD__);	
			echo "false";
		}
	}

	/**
	 *
	 *Acción para eliminar un catálogo, recibe el nombre del catalogo a eliminar
	 *
	 * @param  string $nombre
	 * @return void
	 */
	public function delete($nombre)
	{
		try
		{
			
			if (empty($this->Catalogo_model))
				return false;

                        if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
                        
			$this->load->helper('url');
				
			$existe = $this->db->query('select * from asu_raiz_x_catalogo where tabla_catalogo="'.$nombre.'"');
			
			if ($existe->num_rows() > 0)
			{
			$this->session->set_flashdata('msgResult', 'No se puede eliminar el catálogo porque forma parte de un Arbol');
			$this->session->set_flashdata('clsResult', 'warning');
                        redirect(DIR_SIIGS.'/catalogo','refresh');
			die();
			}
		
			$this->Catalogo_model->setNombre($nombre);
			$this->Catalogo_model->delete();
			$this->session->set_flashdata('msgResult', 'Catálogo eliminado exitosamente');
                        $this->session->set_flashdata('clsResult', 'success');
		}
		catch(Exception $e)
		{
                        $this->session->set_flashdata('clsResult', 'error');
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
		redirect(DIR_SIIGS.'/catalogo','refresh');
	}
}
