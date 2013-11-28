<?php

/**
 * Modelo Raiz
 *
 * @author     Geovanni
 * @created    2013-10-07
 */
class Raiz_model extends CI_Model {

	/**
	 * @access private
	 * @var    int
	 */
	private $id;

	/**
	 * @access private
	 * @var    string
	 */
	private $descripcion;

	/**
	 * @access private
	 * @var    string
	 */
   	private $msg_error_log;

   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_usr;

   	/***************************/
	/*Getters and setters block*/
   	/***************************/
   	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getDescripcion() {
		return $this->descripcion;
	}

	public function setDescripcion($value) {
		$this->descripcion = $value;
	}

	/*******************************/
	/*Getters and setters block END*/
	/*******************************/

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
	 *Devuelve todos los registros de la tabla raiz
	 *
	 *@access  public
	 *@return  ArrayObject
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getAll()
	{
		$query = $this->db->get('asu_raiz');

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de raices";
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
	}
	
	/**
	 *Revisa si la raiz pasada como parametro existe en el ASU
	 *
	 *@access  public
	 *@param   int $id
	 *@return  Boolean
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function ExistInArbol($id)
	{
		$query = $this->db->query('select * from asu_arbol_segmentacion where id_raiz='.$id);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener los datos de raices";
			throw new Exception(__CLASS__);
		}
		else
			return ($query->num_rows() == 0 ? false : true);
	}

	/**
	 *Devuelve la informaci�n de una raiz por su ID
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $id ID (Llave primaria)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('asu_raiz', array('id' => $id));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información de la raiz";
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
	}

	/**
	 *Inserta en la tabla raiz, la información contenida en el objeto
	 *
	 *@access  public
	 *@return  int (Id de la inserción si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function insert()
	{
		$data = array(
				'descripcion' => $this->descripcion
		);

		$query = $this->db->insert('asu_raiz', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al insertar la raiz";
			throw new Exception(__CLASS__);
		}
		else
			return $this->db->insert_id($query);
	}

	/**
	 *Actualiza el objeto actual en la base de datos
	 *
	 *@access  public
	 *@return  boolean (Si no hubo errores al actualizar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function update()
	{
		$data = array(
				'descripcion' => $this->descripcion
		);

		$this->db->where('id' , $this->getId());
		$query = $this->db->update('asu_raiz', $data);

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al actualizar los datos de la raiz";
			throw new Exception(__CLASS__);
		}
		else
			return true;
	}

	/**
	 * Elimina el registro actual de la base de datos
	 *
	 * @access public
	 * @return boolean (Si no hubo errores al eliminar)
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function delete()
	{

		$query = $this->db->delete('asu_raiz', array('id' => $this->getId()));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al eliminar la raiz";
			throw new Exception(__CLASS__);
		}
		else
			return true;
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
}