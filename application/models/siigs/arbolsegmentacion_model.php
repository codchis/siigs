<?php

/**
 * Modelo ArbolSegmentacion
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2013-12-02
 */
class ArbolSegmentacion_model extends CI_Model {

   	private $msg_error_log;

   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_usr;

        /**
	 * Devuelve los mensajes de error en caso de ocurrir alguna excepción
	 * 'usr' devuelve el mensaje para la vista de usuario
	 * 'log' devuelve el mensaje para el log de errores
	 *
	 * @access  public
	 * @return  string|boolean
	 *  @param  string $value, default 'usr' (Tipo mensaje)
	 */
	public function getMsgError($value = 'usr')
	{
		if (!empty($this->msg_error_usr))
		{
			if ($value == 'usr')
				return $this->msg_error_usr;
			else if ($value == 'log')
				return $this->msg_error_log;
		}
		else
		{
			return false;
		}
	}
        
	public function __construct()
	{
		$this->load->database();
		if(!$this->db->conn_id)
		{
			throw new Exception("No se pudo conectar a la base de datos");
		}
	}
        
        /**
	 * Regresa el la información de los padres de una unidad medica en el ASU
	 *
	 * @access public
	 * @return Object arreglo con padres de la um
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
        
        public function getUMParentsById($clave)
        {
            $desglose = $this->db->query('select grado_segmentacion - 1 as desglose from asu_arbol_segmentacion where id='.$clave);

            if (!$desglose)
            {
                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por id";
                throw new Exception(__CLASS__);
            }
            
            $desglose = $desglose->result()[0]->desglose;
            
            //si se van a hacer joins para informacion adicional, se empieza a crear la estructura de la consulta
            $consultavalues = 'select a.id as parent0';
            $consultafrom = ' from asu_arbol_segmentacion a ';
            $consultawhere = ' where a.id = '.$clave;

          //crear los joins a partir del numero de niveles de desglose requeridos
            for($i=1;$i<=$desglose;$i++)   
            {
                $tablajoin = ($i==1) ? array("a","tb".$i) : array("tb".($i-1),"tb".$i);
                $consultavalues .= ",case when ifnull(tb".$i.".descripcion,'') = '' then '' else tb".$i.".id end as parent".$i;
                $consultafrom .= " left outer join asu_arbol_segmentacion ".$tablajoin[1]." on ".$tablajoin[0].".id_padre = ".$tablajoin[1].".id"; 
            }
            $consulta = $consultavalues . $consultafrom . $consultawhere;

            $query = $this->db->query($consulta);
            
            if (!$query)
            {
                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por id";
                throw new Exception(__CLASS__);
            }
            else
            {
                $resultado = array();
                foreach ($query->result()[0] as $fila => $clave)
                {
                    array_push($resultado, $clave);
                }
                
                return $resultado;
            }
        }  
        
        /**
	 * Regresa el objeto del arbol de segmentacion
	 *
	 * @access public
	 * @return Object un arreglo con la estructura del arbol
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */

        public function getTree($idarbol , $nivel, $nivelesocultos = array())
        {
            $consultavalues = " select distinct ";
            $consultafrom = " from ";
            //consulta todos los catalogos que forman el arbol de segmentacion requerido
            $query = $this->db->query('select * from asu_raiz_x_catalogo where id_raiz_arbol = ' . $idarbol . ' and grado_segmentacion >= '.$nivel.' order by grado_segmentacion');
                if (!$query)
                {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
                    throw new Exception(__CLASS__);
                }
                else
                {
                    $cont = 0;
                    $padreactivo = "";
                    if ($query->num_rows() == 0)
                        return array();
                    foreach($query->result() as $fila)
                    {
                        //si el valor del grado de segmentacion existe en la lista de niveles 
                        //ocultos, entonces se aumenta el contador y se guarda el valor del padre
                        //activo para su uso posterior
                        if (!in_array($fila->grado_segmentacion,$nivelesocultos))
                        {
                            $cont += 1;
                            
                            //si no existe ningun catalogo padre activo, se asigna el grado de segmentacion actual
                            if ($padreactivo == "")
                                $padreactivo = $fila->grado_segmentacion;
                            
                            //se agrega a la lista de valores el grado de segmentacion del catalogo 
                            $consultavalues .= " tabla".$fila->grado_segmentacion.
                            ".grado_segmentacion as nivel_".$cont.", ";
                            if ($cont == 1)
                            {
                                //se agrega a la lista de valores el id padre del registro actual
                                $consultavalues .= "tabla".$padreactivo.
                                ".id_padre as padre_".$cont.", ";
                            }
                            else
                            {
                                //se agrega a la lista de valores el id padre del registro actual
                                $consultavalues .= "tabla".$padreactivo.
                                ".id as padre_".$cont.", ";
                            }
                            
                            $consultavalues .= "tabla".$fila->grado_segmentacion.
                            ".id as id_".$cont.", ".
                            "tabla".$fila->grado_segmentacion.
                            ".descripcion as descripcion_".$cont.", ";
                        $padreactivo = $fila->grado_segmentacion;
                        }
                        
                        if ($fila->grado_segmentacion == $nivel)
                        {
                            $consultafrom .= " asu_arbol_segmentacion tabla".$fila->grado_segmentacion;
                        }
                        else
                        {
                            $consultafrom .= " left outer join asu_arbol_segmentacion tabla".$fila->grado_segmentacion;
                            $consultafrom .= " on tabla".$fila->grado_segmentacion.".id_padre = tabla".($fila->grado_segmentacion-1).". id ";
                        }
                    }
                    $consultavalues = substr($consultavalues, 0, count($consultavalues)-3);
                    $consulta = $consultavalues.$consultafrom. " where tabla".$nivel.".grado_segmentacion = ".$nivel. " and tabla".$nivel.".id_raiz=".$idarbol;
                    
                    //var_dump($consulta);
                    //die();
                    
                    $resultado = $this->db->query($consulta);

                    if (!$resultado)
                    {
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
                        throw new Exception(__CLASS__);
                    }
                    else
                    {
                     return array('niveles' => $cont,'resultado' => $resultado->result());   
                    }
                }
        }

        /*
         * Obtiene las unidades medicas correspondientes a un ID
         * independientemente su nivel en el ASU
         */
        
        public function getCluesFromId($id)
        {
             $datos = $this->db->query("select * from asu_arbol_segmentacion where id=".$id);
            
            if (!$datos)
            {
                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por ID";
                throw new Exception(__CLASS__);
            }
            else
            {
                $nivelmaximo = $this->db->query("select max(grado_segmentacion) as maximo from asu_arbol_segmentacion where id_raiz=".$datos->result()[0]->id_raiz);
                $nivel = $datos->result()[0]->grado_segmentacion;
                $nivelmaximo = $nivelmaximo->result()[0]->maximo;
                $omitidos = array();
                for($i = $nivel+1 ; $i < $nivelmaximo ; $i ++)
                    array_push ($omitidos, $i);
                return $this->getChildrenFromId($id , $omitidos);
            }
        }
        
        /**
         * Accion para devolver los hijos de un elemento en el ASU a partir de su ID
         * @param int $id
         * @param Array int $omitidos
         * @return Object
         * @throws Exception
         */
        
        public function getChildrenFromId($id , $omitidos = array())
        {
            $datos = $this->db->query("select * from asu_arbol_segmentacion where id=".$id);
            
            if (!$datos)
            {
                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por ID";
                throw new Exception(__CLASS__);
            }
            else
            {
                if (count($datos->result()) == 0)
                    return array();
                
                //var_dump($datos->result()[0]->id_raiz);
                //var_dump($datos->result()[0]->grado_segmentacion);
                //var_dump($omitidos);
                
                $arregloparcial = $this->getChildrenFromLevel($datos->result()[0]->id_raiz, $datos->result()[0]->grado_segmentacion,$omitidos);
                $resultado = array();
                //var_dump($arregloparcial);
                
                foreach ($arregloparcial as $arreglo)
                {
                    if ($arreglo["key"] == $id)
                        return $arreglo;
                }
            }
        }
        
        /**
         * Accion para devolver el esquema completo del ASU a partir de un nivel especificado, niveles omitidos y elementos preseleccionados
         * @param Int $idarbol
         * @param Int $nivel
         * @param Array int $omitidos
         * @param Array int $seleccionados
         * @return Object
         */
               
        public function getChildrenFromLevel($idarbol, $nivel , $omitidos = array() , $seleccionados = array(), $seleccionables = array())
        {
            try
            {
                $fecha_update_asu = $this->db->query("select max(fecha_update) as fecha from asu_arbol_segmentacion where id_raiz=".$idarbol);
                if (!$fecha_update_asu)
                {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al obtener la ultima actualizacion del asu";
                    return "false";
                }

                $fecha_update_asu = $fecha_update_asu->result()[0]->fecha;
                $ruta = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR;
                $archivo = 'asu_data_'.$idarbol.'_'.$nivel.'_'.  implode(',', $omitidos).'_'.strtotime($fecha_update_asu).'.json';

                if (!is_dir($ruta))
                   mkdir($ruta, 0777, true);

                $ruta.= $archivo;

                if (file_exists($ruta))
                {
                    $str_datos = file_get_contents($ruta);
                    $datos = json_decode($str_datos,true);
                    //if (count($seleccionados)>0)
                    $datos = $this->_addSelectedItems($datos,$seleccionados, $nivel , $omitidos, $seleccionables);
                    //var_dump($datos);
                    return $datos;
                }
                else
                {  
                    ini_set('max_execution_time',1000);

                    $arbol = $this->getTree($idarbol, $nivel, $omitidos);
                    
                    //var_dump($omitidos);
                    //echo $nivel."<br/><br/>";
                    //var_dump(json_encode($arbol));
                    //die();
                    
                    if (count($arbol) == 0)
                    {
                        return array();
                    }
                    
                    if ($nivel<=$arbol['niveles'] || true)
                    {
                        $resultado = array();
                        $niveltemp = array();

                        for($i = 1 ; $i<=$arbol['niveles'];$i++)
                        {
                            $esseleccionable = false;
                            if (count($seleccionables)==0)
                                $esseleccionable = true;
                            else if (in_array($i, $seleccionables))
                                $esseleccionable = true;
                                
                                
                            foreach($arbol['resultado'] as $fila)
                            {
                                $fila = (array) $fila;
                                if ($fila['id_'.$i] != null)
                                {
                                    if ($i == $arbol['niveles'])
                                        $arraytemp = array('key' => $fila['id_'.$i] , 'parent' => $fila['padre_'.$i] , 'title'=> ($fila['descripcion_'.$i]));
                                    else
                                        $arraytemp = array('key' => $fila['id_'.$i], 'parent' => $fila['padre_'.$i], 'title'=> ($fila['descripcion_'.$i]) , 'children'=>array());    

                                    if (in_array($fila['id_'.$i],$seleccionados))
                                            $arraytemp["select"] = true;
                                    
                                    if (!$esseleccionable)
                                            $arraytemp["unselectable"] = true;

                                    if (!isset($resultado[$i]))
                                        $resultado[$i] = array();

                                    if ( !in_array($arraytemp,$resultado[$i]))
                                    {
                                        array_push($resultado[$i], $arraytemp);
                                    }
                                }
                            }
                        }
                        for($i = count($resultado) ; $i > 1;$i--)
                        {
                            $arreglohijo = $resultado[$i];
                            $arreglopadre = $resultado[$i-1];
                            foreach ($arreglohijo as $clave1 => $hijo)
                            {
                                foreach ($arreglopadre as $clave2 => $padre)
                                {
                                    if ($hijo['parent'] == $padre['key'])
                                    {
                                        array_push($arreglopadre[$clave2]['children'], $resultado[$i][$clave1]);
                                    }
                                }
                            }
                            $resultado[$i-1] = $arreglopadre;
                        }

                        try
                        {
                        $fh = fopen($ruta, 'c')
                        or die("Error al abrir fichero para el asu");

                        fwrite($fh, json_encode($resultado[1],JSON_UNESCAPED_UNICODE));
                        fclose($fh);
                        }
                        catch(Exception $e)
                        {
                            $this->msg_error_log = "(". __METHOD__.") => " .'ASU'.': '."No se pudo crear el archivo JSON para el asu (".$ruta.") ::".$e->getMessage();        
                            return "false";
                        }
                        return $resultado[1];
                    }
                }
            }
            catch(Exception $e)
            {
                return "false";
            }
        }
        
        public function _addSelectedItems($datos,$seleccionados, $nivel, $omitidos, $seleccionables)
        {
            while(in_array($nivel, $omitidos))
                $nivel += 1;
            
            //var_dump($nivel);
            
            foreach($datos as $clave => $dato)
            {
                if (array_key_exists('children', $dato) && count($dato['children'])>0)
                {
                    $nivel += 1;
                    $datos[$clave]['children'] = $this->_addSelectedItems($datos[$clave]['children'],$seleccionados, $nivel, $omitidos , $seleccionables);
                    $nivel -= 1;
                }
                if (in_array($dato["key"],$seleccionados))
                {
                    $datos[$clave]["select"] = true;
                }
                if (count($seleccionables)>0)
                if (!in_array($nivel,$seleccionables))
                {
                   // echo $nivel;
                   // var_dump($seleccionables);
                   // echo "<br/><br/><br/>";
                    $datos[$clave]["unselectable"] = true;
                }
            }
            return $datos;
        }
        
//        public function getChildrenFromId($id , $json = true)
//        {
//            $datos = array();
//            $query = $this->db->query('select * from asu_arbol_segmentacion where id_padre = ' . $id);
//            if (!$query)
//            {
//                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
//                    $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
//                    throw new Exception(__CLASS__);
//            }
//            else
//            {
//                foreach ($query->result() as $dato) {
//                    array_push($datos, array('valor' =>$dato->descripcion ,'clave' => $dato->id));
//                }
//                if ($json == false)
//                    return $datos;
//                else
//                    echo json_encode($datos);
//                
//            }
//        }

                /**
         * Accion para obtener la descripcion e información adicional del elemento en el ASU
         * @param Array int $claves arreglo de valores a recuperar
         * @param Int $desglose Nivel de desglose de información requerida
         * @return Object
         * @throws Exception Si ocurre error al recuperar datos de la base de datos
         */
        
        public function getDescripcionById($claves,$desglose = 0)
        {
            if (count($claves)>0)
            {
                if ($desglose == 0)
                    $consulta = 'select id,descripcion from asu_arbol_segmentacion where id in ('.implode(',',$claves).')';
                else
                {
                    //si se van a hacer joins para informacion adicional, se empieza a crear la estructura de la consulta
                    $consultavalues = 'select a.id,concat("",a.descripcion';
                    $consultafrom = ') as descripcion from asu_arbol_segmentacion a ';
                    $consultawhere = ' where a.id in ('.implode(',',$claves).')';
                       
                  //crear los joins a partir del numero de niveles de desglose requeridos
                    for($i=1;$i<=$desglose;$i++)   
                    {
                        $tablajoin = ($i==1) ? array("a","tb".$i) : array("tb".($i-1),"tb".$i);
                        $consultavalues .= ",case when ifnull(tb".$i.".descripcion,'') = '' then '' else concat(', ',tb".$i.".descripcion) end";
                        $consultafrom .= " left outer join asu_arbol_segmentacion ".$tablajoin[1]." on ".$tablajoin[0].".id_padre = ".$tablajoin[1].".id"; 
                    }
                    $consulta = $consultavalues . $consultafrom . $consultawhere;
                    //var_dump($consulta);
                }
                $query = $this->db->query($consulta);

                if (!$query)
                {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por id";
                    throw new Exception(__CLASS__);
                }
                else
                {
                    return $query->result();
                }
            }
        }
        
        /**
         * Accion para obtener la descripcion e información adicional del elemento en el ASU
         * @param Array int $claves arreglo de valores a recuperar
         * @param Int $desglose Nivel de desglose de información requerida
         * @return Object
         * @throws Exception Si ocurre error al recuperar datos de la base de datos
         */
        
        public function isChild($claves,$desglose = 0)
        {
            if (count($claves)>0)
            {
                if ($desglose == 0)
                    $consulta = 'select id,descripcion from asu_arbol_segmentacion where id in ('.implode(',',$claves).')';
                else
                {
                    //si se van a hacer joins para informacion adicional, se empieza a crear la estructura de la consulta
                    $consultavalues = 'select a.id,concat("",a.descripcion';
                    $consultafrom = ') as descripcion from asu_arbol_segmentacion a ';
                    $consultawhere = ' where a.id in ('.implode(',',$claves).')';
                       
                  //crear los joins a partir del numero de niveles de desglose requeridos
                    for($i=1;$i<=$desglose;$i++)   
                    {
                        $tablajoin = ($i==1) ? array("a","tb".$i) : array("tb".($i-1),"tb".$i);
                        $consultavalues .= ",case when ifnull(tb".$i.".descripcion,'') = '' then '' else concat(', ',tb".$i.".descripcion) end";
                        $consultafrom .= " left outer join asu_arbol_segmentacion ".$tablajoin[1]." on ".$tablajoin[0].".id_padre = ".$tablajoin[1].".id"; 
                    }
                    $consulta = $consultavalues . $consultafrom . $consultawhere;
                    //var_dump($consulta);
                }
                $query = $this->db->query($consulta);

                if (!$query)
                {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por id";
                    throw new Exception(__CLASS__);
                }
                else
                {
                    return $query->result();
                }
            }
        }
            
         /**
         * Accion para obtener los registros de un ASU determinado en cierto nivel y con un ID de filtro
         * @param int $idarbol
         * @param Int $nivel Nivel de desglose de información requerida
         * @param Int $filtro (Opcional) filtrar por un valor determinado
         * @return Object
         * @throws Exception Si ocurre error al recuperar datos de la base de datos
         */
        
        public function getDataKeyValue($idarbol,$nivel,$filtro = 0)
        {
            $consulta = 'select id,descripcion from asu_arbol_segmentacion where id_raiz='.$idarbol." and grado_segmentacion=".$nivel.(($filtro != 0) ? " and id_padre=".$filtro : '').' order by descripcion';
            $query = $this->db->query($consulta);

            if (!$query)
            {
                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion por nivel y filtro";
                throw new Exception(__CLASS__);
            }
            else
            {
                return $query->result();
            }
        }
}