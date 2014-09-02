<?php
/**
 * Controlador Raiz
 * 
 * @package    SIIGS
 * @subpackage Controlador
 * @author     Geovanni
 * @created    2013-10-07
 */

class Raiz extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		try
		{
            $this->load->helper('url');
			$this->load->model(DIR_SIIGS.'/Raiz_model');
            $this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
			$this->load->model(DIR_SIIGS.'/Catalogo_model');
			$this->load->model(DIR_SIIGS.'/Catalogo_x_raiz_model');
		}
		catch (Exception $e)
		{
			$this->template->write("content",$e->getMessage());
			$this->template->render();
		}
	}
        
        /**
         * Crea los archivos JSON necesarios para iniciar el ASU en caché y agilizar su carga
         * 
         * @param type $id ID del asu
         */
        
        public function iniciarasu($id){
            error_reporting(E_ALL);
            try
            {
                if (($this->ArbolSegmentacion_model->getChildrenFromLevel($id,1,array(),array(), array())) != "false")
                        echo "true";
                else
                    echo "false";
            }
            catch (Exception $e)
            {
                    Errorlog_model::save($e->getMessage(), __METHOD__);
                    echo "false";
            }
            //echo json_encode($this->ArbolSegmentacion_model->getCluesFromId(781));
        }
        
	/**
	 *Acción por default del controlador, carga la lista
	 *de Raices disponibles y una lista de opciones
	 *No recibe parámetros
	 *
	 *@return void
	 */
	public function index()
	{
		if (empty($this->Raiz_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		try
		{

			$data['title'] = 'Lista de raices disponibles';
			$data['raices'] = $this->Raiz_model->getAll();
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/raiz/index', $data);
		$this->template->render();
	}

	/**
	 *Acción para visualizar información de una raiz específica, obtiene el objeto
	 *raiz por medio del id proporcionado.
	 *
	 * @param  int $id Este parametro no puede ser nulo
	 * @return void
	 */
	public function view($id)
	{
		if (empty($this->Raiz_model))
			return false;
                if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
                
		try
		{
			$data['title'] = "Detalles de la raiz";
			$data['raiz_item'] = $this->Raiz_model->getById($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		try
		{
			$data['catalogos'] = $this->Catalogo_x_raiz_model->getByArbol($id);
		}
		catch (Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}

		$this->template->write_view('content',DIR_SIIGS.'/raiz/view', $data);
                $this->template->write('menu','',true);
 		$this->template->write('sala_prensa','',true);
		$this->template->render();
	}

	/**
	 *Acción para preparar la inserción de nuevas acciones , realiza la validación
	 *del formulario del lado cliente
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

		$data['title'] = 'Crear una nueva raiz';
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[45]');

		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/raiz/insert',$data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->load->helper('url');

				$this->Raiz_model->setDescripcion($this->input->post('descripcion'));

				$this->Raiz_model->insert();
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/raiz/insert', $data);
				$this->template->render();
				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro insertado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/raiz/index','refresh');
			}
		}
	}

	/**
	 *Acción para preparar la actualización de una raiz ya existente,
	 *recibe un ID para obtener los valores de esa raiz y mostrarlos
	 *en la vista update , realiza la validación del formulario del lado
	 *del cliente
	 *
	 * @param  int $id
	 * @return void
	 */
	public function update($id)
	{
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
            
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');

		$error = false;

		$data['title'] = 'Modificar raiz';
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required|max_length[45]');

		if ($this->form_validation->run() === FALSE)
		{
			//obtiene el modelo
			try
			{
				$data['raiz_item'] = $this->Raiz_model->getById($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
			}
			//obtiene los catalogos relacionados a la raiz
			try
			{
				$data['catalogos'] = $this->Catalogo_x_raiz_model->getByArbol($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
			}
			//revisa si ya existen los registros de esta raiz en el arbol de segmentacion unica
			try
			{
				$data['existe'] = $this->Raiz_model->ExistInArbol($id);
			}
			catch (Exception $e)
			{
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$data['clsResult'] = 'error';
			}


			$this->template->write_view('content',DIR_SIIGS.'/raiz/update', $data);
			$this->template->render();
		}
		else
		{
			try
			{
				$this->Raiz_model->setDescripcion($this->input->post('descripcion'));
				$this->Raiz_model->setId($this->input->post('id'));

				$this->Raiz_model->update();
			}
			catch (Exception $e)
			{
				try
				{
					$data['raiz_item'] = $this->Raiz_model->getById($id);
				}
				catch (Exception $e)
				{
					$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
					$data['clsResult'] = 'error';
				}

				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);	
				$data['clsResult'] = 'error';
				$this->template->write_view('content',DIR_SIIGS.'/raiz/update', $data);
				$this->template->render();

				$error = true;
			}

			if ($error == false)
			{
				$this->session->set_flashdata('msgResult', 'Registro actualizado correctamente');
				$this->session->set_flashdata('clsResult', 'success');
				redirect(DIR_SIIGS.'/raiz','refresh');
			}
		}
	}
        
        /**
	 *
	 *Acción para eliminar una raiz, recibe el id de la raiz a eliminar
	 *
	 * @param  int $id
	 * @return void
	 */
	public function delete($id)
	{
            if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
		show_error('', 403, 'Acceso denegado');
            
		try
		{
			if (empty($this->Raiz_model))
				return false;

			$this->load->helper('url');
			$this->Raiz_model->setId($id);
			$this->Raiz_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			$this->session->set_flashdata('clsResult', 'success');
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
			$this->session->set_flashdata('clsResult', 'error');
		}
		redirect(DIR_SIIGS.'/raiz','refresh');
	}

	 /**
	 *
	 *Acción para crear el ASU a partir de una raiz
         *Solo se permite su acceso por medio de peticiones AJAX
	 *
	 * @param  int $id
	 * @return void
	 */
	public function createasu($id)
	{
            if ($this->input->is_ajax_request())
            {
                if ($this->db->query("select count(*) as count from asu_arbol_segmentacion where id_raiz=".$id)->result()[0]->count>0)
                {
                    echo "El arbol ya ha sido creado anteriormente";
                    die();
                }
                try
                {
                     ini_set('max_execution_time',10000);
                     ini_set('memory_limit', '-1');
                        $catalogos = $this->Catalogo_x_raiz_model->getByArbol($id);

                        foreach ($catalogos as $item) {

                                $iditem = $item->id;
                                $tabla = $item->tabla_catalogo;
                                $nivel = $item->grado_segmentacion;
                                $llave = $item->nombre_columna_llave;
                                $descripcion = $item->nombre_columna_descripcion;
                                
                                $descripcion = explode('+', $descripcion);
                                foreach ($descripcion as $item => $valor)
                                    $descripcion[$item] = $tabla.".".$valor;
                                                                
                                if (count($descripcion)>1)
                                    $descripcion = implode(",' ',",$descripcion);
                                else
                                    $descripcion = $descripcion[0];
                                
                                $descripcion = 'concat('.$descripcion.')';
                                
                                if ($nivel > 1)
                                {
                                        $consulta = "select ".$tabla.".".$llave." as llave, ";
                                        $consulta .= /*$tabla.".".*/$descripcion." as descripcion, ";
                                        $consulta .= "asu_arbol_segmentacion.id as padre ";
                                        $consulta .= " from ".$tabla;

                                        $padre = $this->Catalogo_x_raiz_model->getByNivel($id,$nivel-1);
                                        $relaciones = $this->Catalogo_x_raiz_model->getRelations($iditem);

                                        $consulta .= " join ".$padre->nombre." on 1=1";
                                        foreach ($relaciones as $relacion)
                                        {
                                                $consulta .= " and ".$tabla.".".$relacion->columna_hijo." = ".$padre->nombre.".".$relacion->columna_padre;
                                        }
                                        $consulta .= " join asu_arbol_segmentacion on grado_segmentacion=".($nivel-1);
                                        $consulta .= " and asu_arbol_segmentacion.id_raiz=".$id;
                                        $consulta .= " and asu_arbol_segmentacion.id_tabla_original=".$padre->nombre.".".$padre->llave;

                                        $filas = $this->db->query($consulta);

                                        $datosdump = array();
                                        foreach ($filas->result() as $value) {

                                                array_push($datosdump, array(
                                                'grado_segmentacion' => $nivel,
                                                'id_raiz'=> $id,
                                                'id_padre' => $value->padre,
                                                'id_tabla_original' => $value->llave,
                                                'orden' => '0',
                                                'visible' => 'true',
                                                'descripcion' => $value->descripcion
                                                ));
                                        }
                                        if (count($datosdump)>0)
                                        if ($this->db->insert_batch('asu_arbol_segmentacion',$datosdump) != 1)
                                        {
                                        echo 'false';
                                        }
                                }
                                else 
                                {
                                        $consulta = "select ".$llave." as llave, ";
                                        $consulta .= $descripcion." as descripcion ";
                                        $consulta .= " from ".$tabla;
                                        $filas = $this->db->query($consulta);
                                        $datosdump = array();
                                        foreach ($filas->result() as $key => $value) {

                                                array_push($datosdump, array(
                                                'grado_segmentacion' => $nivel,
                                                'id_raiz'=> $id,
                                                'id_padre' => '0',
                                                'id_tabla_original' => $value->llave,
                                                'orden' => '0',
                                                'visible' => 'true',
                                                'descripcion' => $value->descripcion
                                                ));
                                        }
                                        if (count($datosdump)>0)
                                        if ($this->db->insert_batch('asu_arbol_segmentacion',$datosdump) != 1)
                                        {
                                        echo 'false';
                                        }
                                }
                                echo "true";
                        }

                }
                catch(Exception $e)
                {
                        echo Errorlog_model::save($e->getMessage(), __METHOD__);
                }
            }
            else
            {
                echo "Acceso denegado";
            }
	}
        
         /**
	 *
	 *Acción para actualizar el ASU a partir de una raiz
         *Solo se permite su acceso por medio de peticiones AJAX
	 *
	 * @param  int $id
	 * @return void
	 */
        
        public function updateasu($id)
	{
            if ($this->input->is_ajax_request())
            {
		try
		{
                     ini_set('max_execution_time',10000);
                     ini_set('memory_limit', '-1');
			$catalogos = $this->Catalogo_x_raiz_model->getByArbol($id);		
                        $nuevos_registros = array();
                        $cambios_registros = array();
			foreach ($catalogos as $item) {
				
				$iditem = $item->id;
				$tabla = $item->tabla_catalogo;
				$nivel = $item->grado_segmentacion;
				$llave = $item->nombre_columna_llave;
				$descripcion = $item->nombre_columna_descripcion;
                                
                                $descripcion = explode('+', $descripcion);
                                foreach ($descripcion as $item => $valor)
                                    $descripcion[$item] = "a.".$valor;
                                                                
                                if (count($descripcion)>1)
                                    $descripcion = implode(",' ',",$descripcion);
                                else
                                    $descripcion = $descripcion[0];
                                
                                $descripcion = 'concat('.$descripcion.')';

                                //obtener todos los ID que ya estan agregados al ASU correspondiente al nivel actual
                                $filas = $this->db->query("select concat('\'',id_tabla_original,'\'') as llave from asu_arbol_segmentacion where id_raiz=".$id." and grado_segmentacion=".$nivel);
                                $get_array = function($val)
                                {
                                    return $val['llave'];
                                };
                                $datos  = array_map($get_array,$filas->result_array());
                                        
                                if ($nivel>1)
                                {
                                    //obtener las relaciones para el arbol
                                    $padre = $this->Catalogo_x_raiz_model->getByNivel($id,$nivel-1);
                                    $relaciones = $this->Catalogo_x_raiz_model->getRelations($iditem);
                                    //crear la consulta basica
                                    $consulta_base = "select a.".$llave." as llave, ".$descripcion." as descripcion, ";
                                    $consulta_base .= $padre->nombre.".".$padre->llave." as padre  from ".$tabla." a";
                                    //crear las relaciones
                                    $consulta_base .= " join ".$padre->nombre." on 1=1";
                                    foreach ($relaciones as $relacion)
                                    {
                                            $consulta_base .= " and a.".$relacion->columna_hijo." = ".$padre->nombre.".".$relacion->columna_padre;
                                    }
                                }
                                else
                                {
                                    $consulta_base = "select  a.".$llave." as llave, ".$descripcion." as descripcion , 0 as padre";
                                    $consulta_base .= " from ".$tabla." a";
                                }
                                        
                                $consulta_nuevos = $consulta_base." where a.".$llave." not in (".  implode(',', $datos).")";

                                //Nuevos registros en los catalogos para agregar al ASU
                                $resultado = $this->db->query($consulta_nuevos);
                                if ($resultado && $resultado->num_rows()>0)
                                {                                            
                                    foreach($resultado->result_array() as $fila)      
                                    array_push($nuevos_registros, array(
                                        'id_raiz' => $id,'grado_segmentacion' => $nivel,
                                        'id_padre' => $fila["padre"],'id_tabla_original' => $fila["llave"],
                                        'orden' => 0,'visible' => '1','descripcion' => $fila["descripcion"]
                                    ));
                                }

                                if ($nivel>1)
                                {
                                    //obtener las relaciones para el arbol
                                    $padre = $this->Catalogo_x_raiz_model->getByNivel($id,$nivel-1);
                                    $relaciones = $this->Catalogo_x_raiz_model->getRelations($iditem);
                                    //crear la consulta basica
                                    $consulta_base = "select b.id as id, a.".$llave." as llave, ".$descripcion." as descripcion, ";
                                    $consulta_base .= "(select id from asu_arbol_segmentacion where id_raiz=".$id." and grado_segmentacion = ".($nivel-1)." and id_tabla_original = ".$padre->nombre.".".$padre->llave.") as padre  from ".$tabla." a";
                                    //crear las relaciones
                                    $consulta_base .= " join ".$padre->nombre." on 1=1";
                                    foreach ($relaciones as $relacion)
                                    {
                                            $consulta_base .= " and a.".$relacion->columna_hijo." = ".$padre->nombre.".".$relacion->columna_padre;
                                    }

                                    $consulta_modificaciones = $consulta_base." join asu_arbol_segmentacion b on b.id_raiz=".$id." and b.grado_segmentacion=".$nivel." and b.id_tabla_original = a.".$llave." where ( b.descripcion <> ".$descripcion." or b.id_padre <> (select id from asu_arbol_segmentacion where id_raiz=".$id." and grado_segmentacion = ".($nivel-1)." and id_tabla_original = ".$padre->nombre.".".$padre->llave.") )";
                                }
                                else
                                {
                                    $consulta_base = "select b.id as id,  a.".$llave." as llave, ".$descripcion." as descripcion , 0 as padre";
                                    $consulta_base .= " from ".$tabla." a";
                                    $consulta_modificaciones = $consulta_base." join asu_arbol_segmentacion b on b.id_raiz=".$id." and b.grado_segmentacion=".$nivel." and b.id_tabla_original = a.".$llave." and ( b.descripcion <> ".$descripcion.")";
                                }
                                        
                                //Nuevos registros en los catalogos para agregar al ASU
                                $resultado = $this->db->query($consulta_modificaciones);
                                if ($resultado && $resultado->num_rows()>0)
                                {
                                    foreach($resultado->result_array() as $fila)
                                    array_push($cambios_registros, array(
                                        'id' => $fila['id'],
                                        'id_raiz' => $id, 'grado_segmentacion' => $nivel,
                                        'id_padre' => $fila["padre"],'id_tabla_original' => $fila["llave"],
                                        'orden' => 0,'visible' => '1','descripcion' => $fila["descripcion"]
                                    ));
                                }                                        
                        }
                        if (count($nuevos_registros)>0)
                        {
                            if ($this->db->insert_batch('asu_arbol_segmentacion',$nuevos_registros) != 1)
                                echo "true";
                            else
                                echo "false";
                        }
                        else
                        {
                            echo "true";
                        }
                        if(count($cambios_registros)>0)
                        {
                            if ($this->db->update_batch('asu_arbol_segmentacion',$cambios_registros,'id') != 1)
                                echo "true";
                            else
                                echo "false";
                        }
                        else
                        {
                            echo "true";
                        }
		}
		catch(Exception $e)
		{
			echo Errorlog_model::save($e->getMessage(), __METHOD__);
		}
            }
            else
            {
                echo "Acceso denegado";
            }
	}
        
        
        
        
        /**
         * Accion para regresar la descripción e informacion adicional de un arreglo 
         * de ID's desde el arbol de segmentacion
         * 
         * @param Array $claves Este parametro es pasado por POST y es la lista de valores a consultar
         * @param Int $desglose parametro pasado por POST y determina si se requiere información adicional
         * 
         * @return Object JSON con la información requerida
         * **/
        
        public function getDataTreeFromId()
        {
            try 
            {
		if ($this->input->is_ajax_request())
		{
                    $claves = $this->input->post('claves');
                    $desglose = $this->input->post('desglose');
                    //$claves = array(775,776);
                    //$claves = array(895,896);
                    //$desglose = 2;
                    if ($claves)
                        echo json_encode($this->ArbolSegmentacion_model->getDescripcionById($claves,$desglose));
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
         * Accion para regresar el arbol de segmentacion determinado, el objeto regresado contiene
         * estructura de arbol y es consumida solamente por peticiones AJAX
         * 
         * @param Int $idarbol parametro pasado por POST y determina el arbol a consultar
         * @param Int $nivel parametro pasado por POST y determina el nivel superior a desglosar en el arbol
         * @param Array $claves Este parametro es pasado por POST y es la lista de niveles a omitir en el arbol
         * @param Array $claves Este parametro es pasado por POST y es la lista de valores a preseleccionar en el arbol
         * 
         * @return Object JSON con la información requerida
         * **/

                public function getChildrenFromLevel()
        {
            try 
            {
		if ($this->input->is_ajax_request())
		{
                    $idarbol = $this->input->post('idarbol');
                    $nivel = $this->input->post('nivel');
                    $omitidos = $this->input->post('omitidos');
                    $seleccionados = $this->input->post('seleccionados');
                    $seleccionables = array();
                   
                    if (($this->input->post('seleccionables')))
                    $seleccionables = $this->input->post('seleccionables');
//                    $idarbol = 1;
//                    $nivel = 1;
//                    $omitidos = array(null);
//                    $seleccionados = array(775,776);
                    if ($idarbol && $nivel && $omitidos && $seleccionados)
                    {
                        echo json_encode($this->ArbolSegmentacion_model->getChildrenFromLevel($idarbol,$nivel,$omitidos,$seleccionados,$seleccionables),JSON_UNESCAPED_UNICODE);
                    }
                    else
                        echo "Parámetros incorrectos";
		}
		else echo 'Acceso denegado';
            }
            catch(Exception $e)
            {
		echo $e->getMessage();
            }
        }
        
        /**
         * Sirve para obtener bloques del arbol de segmentación única ASU
         * solo se puede acceder por peticiones AJAX, los parametros son pasados por GET
         * @param int $idarbol ID del arbol (el arbol usado por la TES es el 1)
         * @param int $nivel nivel del arbol que se desea obtener
         * @param array $seleccionados se especifica si dentro del arreglo de retorno, hay valores preseleccionados
         * @param bool $seleccionable especifica si los elementos del arbol pueden ser seleccionados
         * @param array $seleccionables especifica que niveles del arbol pueden ser seleccionados
         * @param array $omitidos especifica niveles omitidos dentro del arbol (Si hay un nivel intermedio omitido, los hijos de este nivel son agregados como hijos de su nivel inmediato superior)
         * 
         * @return Object JSON
         */
        
        public function getTreeBlock()
        {
            try 
            {
		if ($this->input->is_ajax_request() || true)
		{                    
                    $seleccionables = array();
                    $omitidos = array();
                    $seleccionados = array();
                    $idarbol=0;
                    $nivel=0;
                    $elegido=0;
                    
                    if (($this->input->get('idarbol',TRUE)))
                    $idarbol = $this->input->get('idarbol',TRUE);
                    if (($this->input->get('nivel',TRUE)))
                    $nivel = $this->input->get('nivel',TRUE);
                    if (($this->input->get('elegido',TRUE)))
                    $elegido = $this->input->get('elegido',TRUE);
                    if (($this->input->get('omitidos',TRUE)))
                    $omitidos = $this->input->get('omitidos',TRUE);
                    if (($this->input->get('seleccionados',TRUE)))
                    $seleccionados = $this->input->get('seleccionados',TRUE);
                    if (($this->input->get('seleccionable',TRUE)))
                    $seleccionable = $this->input->get('seleccionable',TRUE);
                    if (($this->input->get('seleccionables',TRUE)))
                    $seleccionables = $this->input->get('seleccionables',TRUE);
                    //$idarbol = 1;
                    //$nivel = 1;
                    $seleccionable = (($seleccionable == 'true') ? true : false);
                    
                    //$seleccionables = array(1);
//                    $omitidos = array(null);
//                    $seleccionados = array(775,776);
                    if ($idarbol>0 && ($nivel>0 || $elegido>0))
                    {
                        echo json_encode($this->ArbolSegmentacion_model->getTreeBlock($idarbol,$nivel,$seleccionados,$seleccionable,$elegido,$omitidos,$seleccionables),JSON_UNESCAPED_UNICODE);
                    }
                    else
                        echo "Parámetros incorrectos";
		}
		else echo 'Acceso denegado';
            }
            catch(Exception $e)
            {
		echo $e->getMessage();
            }
        }
                        
        /**
         * Accion para obtener los registros de un ASU determinado en cierto nivel y con un ID de filtro
         * 
         * @param int $idarbol
         * @param Int $nivel Nivel de desglose de información requerida
         * @param Int $filtro (Opcional) filtrar por un valor determinado
         * 
         * @return Object
         * @throws Exception Si ocurre error al recuperar datos de la base de datos
         */
        
        public function getDataKeyValue($idarbol,$nivel,$filtro = 0)
        {
            if (!$this->input->is_ajax_request())
            show_error('', 403, 'Acceso denegado');
            
            echo json_encode($this->ArbolSegmentacion_model->getDataKeyValue($idarbol,$nivel,$filtro));
        }
        
        /*
        public function prueba ($id)
        {
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($this->ArbolSegmentacion_model->getChildrenFromLevel($id,1,array(null),array(null)),JSON_UNESCAPED_UNICODE);
            //echo json_encode($this->ArbolSegmentacion_model->getCluesFromId(20063));
        }
         */
}