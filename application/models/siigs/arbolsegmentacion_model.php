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
	 * Regresa la información de los padres de una unidad medica en el ASU
	 *
	 * @access public
         * @param int $clave Clave de la unidad medica u elemento en el ASU
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
         * 
         * @param int $idarbol ID del arbol a crear
         * @param int $nivel Nivel de segmentacion desde el cual se iniciará a desarrollar el arbol
         * @param $nivelesocultos Array con los niveles que se deben ocultar 
         * 
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

/**
	 * Regresa el objeto del arbol de segmentacion por nivel o hijos de un elemento seleccionado
	 *
	 * @access public
         * 
         * @param int $idarbol ID del arbol a crear
         * @param int $nivel Nivel de segmentacion desde el cual se iniciará a desarrollar el arbol
         * @param $nivelesocultos Array con los niveles que se deben ocultar 
         * 
	 * @return Object un arreglo con la estructura del arbol
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */

        public function getTreeBlockData($idarbol , $nivel,$elegido)
        {
            if ($nivel>0)
            {
                if ($elegido > 0)
                {
                    $nivelpadre = $this->db->query("select grado_segmentacion as nivel from asu_arbol_segmentacion where id=".$elegido)->result()[0]->nivel;
                    $consulta = "select tabla".($nivel).".id_padre as parent, tabla".($nivel).".id as id, tabla".($nivel).".grado_segmentacion as nivel, tabla".($nivel).".descripcion as descripcion from asu_arbol_segmentacion tabla".$nivelpadre;
                    $tempnivel = $nivelpadre;
                    while ($nivelpadre<$nivel)
                    {
                        $nivelpadre +=1;
                        $consulta .= " join asu_arbol_segmentacion tabla".($nivelpadre)." on tabla".($nivelpadre).".id_padre = tabla".($nivelpadre-1).".id";
                    }
                    $consulta .= " where tabla".($tempnivel).".id=".$elegido." order by tabla".($nivelpadre).".descripcion";
                
                    //var_dump($consulta);
                    $query = $this->db->query($consulta);
                    if (!$query)
                    {
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
                        throw new Exception(__CLASS__);
                    }
                    else
                    {
                    return array('resultado' => $query->result());  
                    }
                    
                    //var_dump($consulta);
                    
                }
                else
                {
                    $consulta = "select id_padre as parent, id,grado_segmentacion as nivel,descripcion from asu_arbol_segmentacion where id_raiz = ". $idarbol . " and grado_segmentacion = ".$nivel." order by descripcion";
                    $query = $this->db->query($consulta);
                    if (!$query)
                    {
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
                        throw new Exception(__CLASS__);
                    }
                    else
                    {
                    return array('resultado' => $query->result());  
                    }
                }
            }
            else if ($elegido > 0)
            {
                $consulta = "select id,grado_segmentacion as nivel,descripcion from asu_arbol_segmentacion where id_raiz = ". $idarbol . " and id_padre = ".$elegido." order by descripcion";
                $query = $this->db->query($consulta);
                if (!$query)
                {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
                    throw new Exception(__CLASS__);
                }
                else
                {
                return array('resultado' => $query->result());  
                }
            }
            
        }

        
        /***
         * 
         * Obtiene las unidades medicas correspondientes a un ID de un elemento
         * independientemente su nivel en el ASU
         * 
         * @param int $id Id del elemento en el asu
         *
         * * @return Object un arreglo con la estructura del arbol
	 * @throws Exception En caso de algun error al consultar la base de datos
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
         * 
         * @param int $id Id del elemento en el ASU
         * @param Array int $omitidos Array de niveles omitidos 
         * 
         * @return Object un arreglo con la estructura del arbol
	 * @throws Exception En caso de algun error al consultar la base de datos
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
         * Obtener el elemento del ASU por medio de su ID
         * @param int item seleccionado
         * @return object con los datos del elemento
         * 
         * regresa array vacio si el elemento no existe en el ASU
         * 
         * @throws Exception En caso de algun error al consultar la base de datos
         */
        
        public function getById($item)
        {
            $resultado = $this->db->query("select * from asu_arbol_segmentacion where id=".$item);
            if (!$resultado)
            {
                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                $this->msg_error_usr = "Ocurrió un error al obtener el elemento padre";
                throw new Exception(__CLASS__);
            }
            else
            {
                if ($resultado->num_rows()>0)
                    return $resultado->result();
                else
                    return array();
            }
            
        }
        
        /**
         * Convierte el tipo de arreglo para enviarlo como se debe recibir en el cliente de Javascript
         * @param Array fila array de valores
         * @param bool Seleccionable opcion para que este elemento sea seleccionable
         * @return Array Preparado para el javascript cliente
         */
        
        public function convertType($arbol,$seleccionable, $seleccionados)
        {
            $resultado = array();
            foreach($arbol as $fila)
            {
                $fila = (array) $fila;
                if ($fila['id'] != null)
                {
                    $arraytemp =  array('key' => $fila['id'], 'title'=> $fila['descripcion'],'tooltip'=>$fila['nivel']);
                    if (isset($fila['children']))
                        $arraytemp["children"] = $fila['children'];
                    else
                        $arraytemp["isLazy"] = true;
                    if (!$seleccionable)
                    {
                        $arraytemp["unselectable"] = true;
                        $arraytemp["hideCheckbox"] = true;
                    }
                    if (in_array($fila["id"], $seleccionados))
                    {
                        $arraytemp["select"] = true;
                    }
                    array_push($resultado, $arraytemp);
                }
            }            
            return $resultado;
        }
        
        /**
         * Accion para devolver un bloque del ASU a partir de un nivel especificado o una clave seleccionada, niveles omitidos y elementos preseleccionados
         * 
         * @param Int $idarbol Id del arbol a crear
         * @param Int $nivel Nivel de segmentacion desde la cual se desarrolla el arbol
         * @param Array int $omitidos Array de niveles omitidos
         * @param Array int $seleccionados Array de elementos seleccionados
         * @param Array int $seleccionables Array de niveles que son seleccionables
         * @param Array int $elegido Clave seleccionada para obtener sus hijos
         * 
         * @return Object un arreglo con la estructura del arbol
	 * @throws Exception En caso de algun error al consultar la base de datos
         * 
         */
               
        public function getTreeBlock($idarbol, $nivel , $seleccionados = array(), $seleccionable , $elegido, $omitidos = array(), $seleccionables = array())
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
                $archivo = 'asu_data_'.$idarbol.'_'.$nivel.'_'.$elegido.'_'.$seleccionable.'_'.strtotime($fecha_update_asu).'.json';
                $ruta_temp = $ruta;
                if (!is_dir($ruta))
                {
                    if(!mkdir($ruta, 0777, true))
                            return "false";
                }

                $ruta.= $archivo;

                if (file_exists($ruta) && (count($seleccionados)==0 || $seleccionados[0]=="") && false)
                {
                    $str_datos = file_get_contents($ruta);
                    $datos = json_decode($str_datos,true);
                    //if (count($seleccionados)>0)
                    $datos = $this->_addSelectedItems($datos, $nivel, $seleccionados, $seleccionable);
                    //var_dump($datos);          
                    return $datos;
                }
                else
                {  
                    ini_set('max_execution_time',1000);
                    ini_set('memory_limit', '1024M');

                    if (count($seleccionados)>0 && $seleccionados[0]!="")
                    {
                       
                        $resultadotemp = array();
                        $resultadotemp[0] = $this->getTreeBlockData($idarbol, $nivel,0);
                        $ultimaclave = 0;
                        foreach($seleccionados as $seleccionado)
                        {
                           $item = $this->getById($seleccionado);
                           if (count($item)> 0)
                           {
                               $tempnivel = $item[0]->grado_segmentacion;
                               
                               if (in_array($tempnivel,$omitidos))
                               {
                                   continue;
                               }
                               $id = $item[0]->id;
                               while($tempnivel>$nivel)
                               {
                                   //echo $tempnivel."..";
                                   $item = $this->getById($id);
                                    if (count($item)> 0)
                                    {
                                        if (!isset($resultadotemp[$item[0]->id_padre]))
                                        {
                                        $resultadotemp[$item[0]->id_padre] = 
                                                $this->getTreeBlockData($idarbol, $item[0]->grado_segmentacion, $item[0]->id_padre);
                                        $id = $item[0]->id_padre;
                                        $ultimaclave = $item[0]->id_padre;
                                        }
                                    }
                                    $tempnivel -=1;
                                    while(in_array($tempnivel,$omitidos))
                                    {
                                        $itempadre = $this->getById($ultimaclave);
                                        if (count($itempadre)>0)
                                        {
                                            foreach($resultadotemp[$ultimaclave]["resultado"] as $clave => $valor)
                                            {
                                           //     echo        $resultadotemp[$ultimaclave]["resultado"][$clave]->parent."...".$itempadre[0]->id_padre."<br/>" ;
                                                $resultadotemp[$ultimaclave]["resultado"][$clave]->parent = $itempadre[0]->id_padre;
                                           //     echo        $resultadotemp[$ultimaclave]["resultado"][$clave]->parent."...".$itempadre[0]->id_padre."<br/>" ;
                                            }
                                            //$resultadotemp[$itempadre[0]->id] = $resultadotemp[$ultimaclave];
                                            //$resultadotemp[$ultimaclave]["resultado"] = null;
                                        }
                                        $tempnivel -=1;
                                    }
                               }
                           }
                        }
                        
                        $resultado = array();
                        
//                        foreach ($resultadotemp as $clave => $valor)
//                        {
//                            echo $clave;
//                            var_dump($resultadotemp[$clave]);
//                            echo "<br/><br/>";
//                        }
                        //die();
                        
                        //Nivel de busqueda para ir agregando hijos a padres
                        $search = 0;
                        //el nivel mayor en la lista de items
                        $search = $this->getListChildrenLevel($resultadotemp);
                        $contador = 0;
//                        
//                        var_dump($search);
//                        echo "<br/><br/>";
                        while($search["nivel"]>1 && $contador<20)
                        {
                            $contador +=1;
                            $clave1 = $search["id"];
//                        foreach($resultadotemp as $clave1 => $valor1)
//                        {
                            $childrens = array();
                            $childrens = $this->getListChildrenLevel($resultadotemp,$clave1);
//                          var_dump($childrens);
//                          echo "<br/><br/>";
                           foreach($childrens as $clave2)
                            {
                                
                               //echo $clave1."....".$clave2."<br/><br/>";
                                                                
                                 foreach($resultadotemp[$clave2]["resultado"] as $clave=>$valor)
                                 {
                                    if (!empty($resultadotemp[$clave1]["resultado"]) && $resultadotemp[$clave2]["resultado"][$clave]->id == $resultadotemp[$clave1]["resultado"][0]->parent)
                                    {
                                        //echo $resultadotemp[$clave2]["resultado"][$clave]->id."----".$clave1."<br/>";
                                        $resultadotemp[$clave2]["resultado"][$clave]->children = $this->convertType($resultadotemp[$clave1]["resultado"],in_array($resultadotemp[$clave1]["resultado"][0]->nivel, $seleccionables),$seleccionados);
                                        $resultadotemp[$clave1]["resultado"] = null;
                                        continue;
                                    }

                                 }
                            }
                        //}
                            $search = $this->getListChildrenLevel($resultadotemp);
                            //var_dump($search);
                        }
                        $resultadotemp[0]["resultado"] = $this->convertType($resultadotemp[0]["resultado"],in_array($resultadotemp[0]["resultado"][0]->nivel, $seleccionables),$seleccionados);
                                                       
                        return $resultadotemp[0]["resultado"];
                        
                    }
                    else
                    {
                        $arbol = $this->getTreeBlockData($idarbol, $nivel,$elegido);
                                   
                        if (count($arbol['resultado']) == 0)
                        {
                            return array();
                        }

                        $resultado = $this->convertType($arbol["resultado"], $seleccionable, $seleccionados);
                    }
//                        try
//                        {
//                            $fh = fopen($ruta, 'c');
//                            if(!$fh)
//                            {
//                               return "false";
//                            }
//
//                            fwrite($fh, json_encode($resultado,JSON_UNESCAPED_UNICODE));
//                            fclose($fh);
//
//                            $fechas = $this->db->query("select distinct fecha_update as fecha from asu_arbol_segmentacion where id_raiz=".$idarbol);
//                            if (!$fechas)
//                            {
//                                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
//                                $this->msg_error_usr = "Ocurrió un error al obtener el registro de actualizaciones del asu";
//                                return "false";
//                            }
//                            else
//                            {
//                                foreach ($fechas->result() as $item)
//                                {
//                                    $archivotemp = 'asu_data_'.$idarbol.'_'.$nivel.'_'.$elegido.'_'.$seleccionable.'_'.strtotime($item->fecha).'.json';
//                                    if ($archivo != $archivotemp)
//                                        if (file_exists($ruta_temp.$archivotemp))
//                                            unlink($ruta_temp.$archivotemp);                            
//                                }
//                            }               
//                        }
//                        catch(Exception $e)
//                        {
//                            $this->msg_error_log = "(". __METHOD__.") => " .'ASU'.': '."No se pudo crear el archivo JSON para el asu (".$ruta.") ::".$e->getMessage();
//                            return "false";
//                        }
                        return $resultado;
                }
            }
            catch(Exception $e)
            {
                return "false";
            }
        }
        
        public function getListChildrenLevel($resultadotemp, $clave2=0)
        {
            if ($clave2==0)
            {
                $nivel = array("nivel"=>0,"id"=>0);
                foreach($resultadotemp as $clave1 => $valor1)
                {
                 if (!empty($resultadotemp[$clave1]["resultado"]))
                 {
                  if ($resultadotemp[$clave1]["resultado"][0]->nivel>$nivel["nivel"])
                  {
                      $nivel = array("nivel"=>$resultadotemp[$clave1]["resultado"][0]->nivel,"id"=>$clave1);
                  }
                 }
                }
                return $nivel;
            }
            else 
            {
                $lista = array();
                foreach($resultadotemp as $clave1 => $valor1)
                {
                 if (!empty($resultadotemp[$clave1]["resultado"]) && !empty($resultadotemp[$clave2]["resultado"])) 
                 {
                    if($resultadotemp[$clave1]["resultado"][0]->nivel<$resultadotemp[$clave2]["resultado"][0]->nivel)
                     {
                        //echo "coincidencia...".$clave1;
                         array_push($lista, $clave1);
                     }
                 }
                }
                return $lista;
            }
        }
        
//        public function checkChildren($arreglo,$indice,$seleccionables,$seleccionados) {
//            foreach($arreglo[0]["resultado"] as $clave2 => $valor2)
//            {
//                if ($arreglo[0]["resultado"][$clave2]->id == $arreglo[$clave1]["resultado"][0]->parent)
//                {
//                    $arreglo[0]["resultado"][$clave2]->children = $this->convertType($arreglo[$clave1]["resultado"],in_array($arreglo[$clave1]["resultado"][0]->nivel, $seleccionables),$seleccionados);
//                    $arreglo[$clave1]["resultado"] = null;
//                    break;
//                }
//                else if (isset($arreglo[0]["resultado"][$clave2]->children))
//                {
//                 $arreglo[0]["resultado"][$clave2]->children = $this->checkChildren($arreglo, $clave2, $seleccionables, $seleccionados)   ;
//                }
//            }
//            return $arreglo;
//        }
        
        /**
         * Accion para devolver el esquema completo del ASU a partir de un nivel especificado, niveles omitidos y elementos preseleccionados
         * 
         * @param Int $idarbol Id del arbol a crear
         * @param Int $nivel Nivel de segmentacion desde la cual se desarrolla el arbol
         * @param Array int $omitidos Array de niveles omitidos
         * @param Array int $seleccionados Array de elementos seleccionados
         * @param Array int $seleccionables Array de niveles que son seleccionables
         * 
         * @return Object un arreglo con la estructura del arbol
	 * @throws Exception En caso de algun error al consultar la base de datos
         * 
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
                $ruta_temp = $ruta;
                if (!is_dir($ruta))
                {
                    if(!mkdir($ruta, 0777, true))
                            return "false";
                }

                $ruta.= $archivo;

                if (file_exists($ruta))
                {
                    $str_datos = file_get_contents($ruta);
                    $datos = json_decode($str_datos,true);
                    //if (count($seleccionados)>0)
                    $datos = $this->_addSelectedItems_($datos,$seleccionados, $nivel , $omitidos, $seleccionables);
                    //var_dump($datos);
                                        
                    return $datos;
                }
                else
                {  
                    ini_set('max_execution_time',1000);
                    ini_set('memory_limit', '1024M');

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
                                    {
                                            $arraytemp["unselectable"] = true;
                                            $arraytemp["hideCheckbox"] = true;
                                    }

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
                            $fh = fopen($ruta, 'c');
                            if(!$fh)
                            {
                               return "false";
                            }
                            

                            fwrite($fh, json_encode($resultado[1],JSON_UNESCAPED_UNICODE));
                            fclose($fh);

                            $fechas = $this->db->query("select distinct fecha_update as fecha from asu_arbol_segmentacion where id_raiz=".$idarbol);
                            if (!$fechas)
                            {
                                $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                                $this->msg_error_usr = "Ocurrió un error al obtener el registro de actualizaciones del asu";
                                return "false";
                            }
                            else
                            {
                                foreach ($fechas->result() as $item)
                                {
                                    $archivotemp = 'asu_data_'.$idarbol.'_'.$nivel.'_'.  implode(',', $omitidos).'_'.strtotime($item->fecha).'.json';
                                    if ($archivo != $archivotemp)
                                        if (file_exists($ruta_temp.$archivotemp))
                                            unlink($ruta_temp.$archivotemp);                            
                                }
                            }               
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
        
        public function _addSelectedItems($datos,$nivel, $seleccionados, $seleccionables)
        {
            
            
            foreach($datos as $clave => $dato)
            {
                if (in_array($dato["key"],$seleccionados))
                {
                    $datos[$clave]["select"] = true;
                }
                
                if(gettype($seleccionables)=='array')
                {
                    if (!in_array($nivel,$seleccionables))
                    {
                        $datos[$clave]["unselectable"] = true;
                        $datos[$clave]["hideCheckbox"] = true;
                    }
                }
                else
                {
                    if ($seleccionables==false)
                    {
                        $datos[$clave]["unselectable"] = true;
                        $datos[$clave]["hideCheckbox"] = true;
                    }
                }
            }
            return $datos;
        }
        
        public function _addSelectedItems_($datos,$seleccionados, $nivel, $omitidos, $seleccionables)
        {
            while(in_array($nivel, $omitidos))
                $nivel += 1;
            
            //var_dump($nivel);
            
            foreach($datos as $clave => $dato)
            {
                if (array_key_exists('children', $dato) && count($dato['children'])>0)
                {
                    $nivel += 1;
                    $datos[$clave]['children'] = $this->_addSelectedItems_($datos[$clave]['children'],$seleccionados, $nivel, $omitidos , $seleccionables);
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
                    $datos[$clave]["hideCheckbox"] = true;
                }
            }
            return $datos;
        }
        
         /**
         * Accion para obtener la descripcion e información adicional del elemento en el ASU
         * 
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
        
        /*
         * Accion para obtener la descripcion e información adicional del elemento en el ASU
         * @param Array int $claves arreglo de valores a recuperar
         * @param Int $desglose Nivel de desglose de información requerida
         * @return Object
         * @throws Exception Si ocurre error al recuperar datos de la base de datos
         *
        
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
         */
        
            
         /**
         * Accion para obtener los registros de un ASU determinado en cierto nivel y con un ID de filtro
         * 
         * @param int $idarbol Id del arbol a buscar
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