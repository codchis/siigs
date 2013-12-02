<?php

/**
 * Modelo ArbolSegmentacion
 *
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
            $query = $this->db->query('select * from asu_raiz_x_catalogo where id_raiz_arbol = ' . $idarbol . ' order by grado_segmentacion');
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
                        if (!in_array($fila->grado_segmentacion,$nivelesocultos))
                        {
                            $cont += 1;
                            
                            if ($padreactivo == "")
                                $padreactivo = $fila->grado_segmentacion;
                            
                            $consultavalues .= " tabla".$fila->grado_segmentacion.
                            ".grado_segmentacion as nivel_".$cont.", ";
                            if ($cont == 1)
                            {
                                $consultavalues .= "tabla".$padreactivo.
                                ".id_padre as padre_".$cont.", ";
                            }
                            else
                            {
                                $consultavalues .= "tabla".$padreactivo.
                                ".id as padre_".$cont.", ";
                            }
                            
                            $consultavalues .= "tabla".$fila->grado_segmentacion.
                            ".id as id_".$cont.", ".
                            "tabla".$fila->grado_segmentacion.
                            ".descripcion as descripcion_".$cont.", ";
                        $padreactivo = $fila->grado_segmentacion;
                        }
                        
                        if ($fila->grado_segmentacion == 1)
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
                    $consulta = $consultavalues.$consultafrom. " where tabla1.grado_segmentacion >= ".$nivel;
                    $resultado = $this->db->query($consulta);
                    //var_dump($consulta);
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
        
        public function getChildrenFromLevel($idarbol, $nivel , $omitidos = array())
        {
            $arbol = $this->getTree($idarbol, $nivel, $omitidos);
            if (count($arbol) == 0)
            {
                return json_encode(array());
            }
            if ($nivel<=$arbol['niveles'])
            {
                $resultado = array();
                $niveltemp = array();

                    for($i = 1 ; $i<=$arbol['niveles'];$i++)
                    {
                        foreach($arbol['resultado'] as $fila)
                        {
                            $fila = (array) $fila;
                            if ($fila['id_'.$i] != null)
                            {
                                if ($i == $arbol['niveles'])
                                    $arraytemp = array('key' => $fila['id_'.$i] , 'parent' => $fila['padre_'.$i] , 'title'=> $fila['descripcion_'.$i]);
                                else
                                    $arraytemp = array('key' => $fila['id_'.$i], 'parent' => $fila['padre_'.$i], 'title'=> $fila['descripcion_'.$i] , 'children'=>array());    

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
                    return json_encode($resultado[1]);
            }
        }
        
        public function getChildrenFromId($id , $json = true)
        {
            $datos = array();
            $query = $this->db->query('select * from asu_arbol_segmentacion where id_padre = ' . $id);
            if (!$query)
            {
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    $this->msg_error_usr = "Ocurrió un error al obtener los datos del arbol de segmentacion";
                    throw new Exception(__CLASS__);
            }
            else
            {
                foreach ($query->result() as $dato) {
                    array_push($datos, array('valor' =>$dato->descripcion ,'clave' => $dato->id));
                }
                if ($json == false)
                    return $datos;
                else
                    echo json_encode($datos);
                
            }
        }
        
        public function getDescripcionById($claves)
        {
            if (count($claves)>0)
            {
                $query = $this->db->query('select id,descripcion from asu_arbol_segmentacion where id in ('.implode(',',$claves).')');

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
}