<?php
/**
 * Modelo Usuario
 *
 * @author     	Rogelio
 * @created		2013-11-26
 */
class Notificacion_model extends CI_Model {
	/**
	 * @access private
	 * @var    int
	 */
	private $id;
	/**
	 * @access private
	 * @var    string
	 */
   	private $titulo;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $contenido;
   	/**
   	 * @access private
   	 * @var    datetime
   	 */
   	private $fecha_inicio;
   	/**
   	 * @access private
   	 * @var    datetime
   	 */
   	private $fecha_fin;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $id_arr_asu;

   	/********************************************
   	 * Estas variables no pertenecen a la tabla *
   	* ******************************************/
   	
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_usr;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $msg_error_log;
   	/**
   	 * @access private
   	 * @var    array
   	 */
   	private $tabletas;
   	
   	/**
   	 * @access private
   	 * @var    array
   	 */
   	private $filters;
   	
   	/**
   	 * @access private
   	 * @var    array
   	 */
   	private $filtersOr;
   	
	public function __construct()
	{
		parent::__construct();
		
		$this->filters = array();
		$this->filtersOr = array();
		
		$this->load->database();
		if (!$this->db->conn_id)
			throw new Exception("No se pudo conectar a la base de datos");
	}

	public function getId()
	{
	    return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}
	
	public function getTitulo()
	{
	    return $this->titulo;
	}

	public function setTitulo($titulo)
	{
	    $this->titulo = $titulo;
	}

	public function getContenido()
	{
		return $this->contenido;
	}
	
	public function setContenido($contenido)
	{
		$this->contenido = $contenido;
	}
	
	public function getFechaInicio()
	{
		return $this->fecha_inicio;
	}
	
	public function setFechaInicio($fecha_inicio)
	{
		$this->fecha_inicio = $fecha_inicio;
	}

	public function getFechaFin()
	{
		return $this->fecha_fin;
	}
	
	public function setFechaFin($fecha_fin)
	{
		$this->fecha_fin = $fecha_fin;
	}
	
	public function getIdsTabletas()
	{
		return $this->id_arr_asu;
	}
	
	public function setIdsTabletas($id_arr_asu)
	{
		$this->id_arr_asu = $id_arr_asu;
	}

	/**
	 * Asigna el mensaje de error a visualizar: para usuario final (usr) o para bit谩cora (log)
	 *
	 * @access		public
	 * @param		string		$value		tipo de error a visualizar: usr o log, default: usr
	 * @return 		boolean		false 		si ocurri贸 alg煤n error, true si se ejecut贸 correctamente
	 */
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}
	
	/**
	 * Obtiene todas las notificaciones existentes, se puede filtrar por: texto a buscar si se desea
	 *
	 * @access 	public
	 * @param 	boolean|string		$keywords		false no hay texto a buscar|string con texto a buscar
     * @param  	int 				$offset    		Establece el desplazamiento del primer registro a devolver,
     *                        						si se define solo el valor de offset
     *                        						el valor especifica el n煤mero de registros a retornar desde el comienzo del conjunto de resultados.
     * @param  int 					$row_count 		Establece la cantidad de registros a devolver
	 * @return 	void|array object					false si ocurri贸 alg煤n error, array object si se ejecut贸 correctamente
	 */
	public function getAll($keywords = '', $offset = null, $row_count = null)
	{
		if(!empty($offset) && !empty($row_count))
			$this->db->limit($offset, $row_count);
		else if (!empty($offset))
			$this->db->limit($offset);
		if (empty($keywords) && empty($this->filters)){	
			$query = $this->db->get('tes_notificacion');
			return $query->result();
		}
		else
		{
			$this->db->select('*');
			$this->db->from('tes_notificacion');
			if (!empty($keywords)){
				$this->db->where("(titulo LIKE '%$keywords%' OR contenido LIKE '%$keywords%')");
			}
		}
		if( !empty($this->filters) ){
			$this->db->where($this->filters);
		}
		$query = $this->db->get();
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}	
		else
			return $query->result();
		return;
	}

	/**
	 * Obtiene la notificaci髇 solicitada
	 *
	 * @access 		public
	 * @param 		int			$id			id de notificaci髇
	 * @return void|object		false si ocurri贸 alg煤n error, object si se ejecut贸 correctamente
	 */
	public function getById($id)
	{
		$query = $this->db->get_where('tes_notificacion', array('id' => $id));
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return;
	}
	
	/**
	 * Obtiene el numero total de notificaciones
	 *
	 * @access public
	 * @param 	boolean|string		$keywords		false no hay texto a buscar|string con texto a buscar
	 * @return int
	 */
	public function getNumRows($keywords = '')
	{
		if (!$keywords)
			$query = $this->db->get('tes_notificacion');
		else
		{
			$this->db->select('*');
			$this->db->from('tes_notificacion');
			$this->db->like('titulo', $keywords);
			$this->db->or_like('contenido', $keywords);
			$query = $this->db->get();
		}
		if(!$query) {
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $query->num_rows;
	}
	
	/**
	 * Inserta en la base de datos los datos de la notificaci髇 (datos en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurri贸 alg煤n error, true si se ejecut贸 correctamente
	 */
	public function insert()
	{
		$data = array(
			'titulo' => $this->titulo,
			'contenido' => $this->contenido,
			'fecha_inicio' => $this->fecha_inicio,
			'fecha_fin' => $this->fecha_fin,
			'id_arr_asu' => $this->id_arr_asu
		);
		$result = $this->db->insert('tes_notificacion', $data);
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Actualiza en la base de datos los datos de la notificaci髇 (datos en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurri贸 alg煤n error, true si se ejecut贸 correctamente
	 */
	public function update()
	{
		$data = array(
			'titulo' => $this->titulo,
			'contenido' => $this->contenido,
			'fecha_inicio' => $this->fecha_inicio,
			'fecha_fin' => $this->fecha_fin,
			'id_arr_asu' => $this->id_arr_asu
		);
		$this->db->where('id' , $this->id);
		$result = $this->db->update('tes_notificacion', $data);
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Elimina de la base de datos la notificaci髇 (id en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurri贸 alg煤n error, true si se ejecut贸 correctamente
	 */
	public function delete()
	{
		$result = $this->db->delete('tes_notificacion', array('id' => $this->getId()));
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Agrega una nueva regla de filtrado al arreglo de filtros
	 *
	 * @access public
	 * @param  string $columna   Puede ser cualquier campo del objeto (id, id_usuario, fecha_hora, parametros, id_controlador_accion)
	 * @param  string $condicion Establece la condicion a evaluar, entre los valores permitidos estan: =, !=, >=, <=, like
	 * @param  string $valor     Valor contra el cual se realizar谩 la evaluaci贸n del campo
	 * @return void|boolean      Devuelve falso en caso de no poder establecer el filtro
	 */
	public function addFilter($columna, $condicion, $valor)
	{
		$columnasPermitidas = array(
				'titulo',
				'contenido',
				'fecha_inicio'
		);
	
		$condicionesPermitidas = array('=', '>', '<', '!=', '>=', '<=', 'like');
	
		if(!in_array($columna, $columnasPermitidas)) {
			$this->error = true;
			$this->msg_error_usr = 'ERROR: Columna no permitida en el filtro ('.$columna.')';
			$this->msg_error_log = '('.__METHOD__.') => '.$this->msg_error_usr;
	
			throw new Exception(__CLASS__);
		}
	
		if(!in_array($condicion, $condicionesPermitidas)) {
			$this->error = true;
			$this->msg_error_usr = 'ERROR: Condici贸n no permitida en el filtro ('.$condicion.')';
			$this->msg_error_log = '('.__METHOD__.') => '.$this->msg_error_usr;
	
			throw new Exception(__CLASS__);
		}
	
		if(empty($valor)) {
			$this->error = true;
			$this->msg_error_usr = 'ERROR: Debe definir un valor para el filtro';
			$this->msg_error_log = '('.__METHOD__.') => '.$this->msg_error_usr;
	
			throw new Exception(__CLASS__);
		}
	
		$this->filters[$columna.' '.$condicion] = $valor;
	}
}
?>