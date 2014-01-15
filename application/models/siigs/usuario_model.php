<?php
/**
 * Modelo Usuario
 *
 * @author     	Rogelio
 * @created		2013-09-25
 */
class Usuario_model extends CI_Model {
	/**
	 * Guarda la instancia del objeto global CodeIgniter
	 * para utilizarlo en la función estática
	 *
	 * @access private
	 * @var    instance
	 */
	private static $CI;
	
	/**
	 * @access private
	 * @var    int
	 */
	private $id;
	/**
	 * @access private
	 * @var    string
	 */
   	private $nombre_usuario;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $clave;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $nombre;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $apellido_paterno;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $apellido_materno;
   	/**
   	 * @access private
   	 * @var    string
   	 */
   	private $correo;
   	/**
   	 * @access private
   	 * @var    boolean
   	 */
   	private $activo;
   	/**
   	 * @access private
   	 * @var    int
   	 */
   	private $id_grupo;

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
   	
	public function __construct()
	{
		parent::__construct();
		
		self::$CI = &get_instance();
		
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
	
	public function getNombreUsuario()
	{
	    return $this->nombre_usuario;
	}

	public function setNombreUsuario($nombre_usuario)
	{
	    $this->nombre_usuario = $nombre_usuario;
	}

	public function getClave()
	{
		return $this->clave;
	}
	
	public function setClave($clave)
	{
		$this->clave = $clave;
	}
	
	public function getNombre()
	{
		return $this->nombre;
	}
	
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	public function getApellidoPaterno()
	{
		return $this->apellido_paterno;
	}
	
	public function setApellidoPaterno($apellido_paterno)
	{
		$this->apellido_paterno = $apellido_paterno;
	}
	
	public function getApellidoMaterno()
	{
		return $this->apellido_materno;
	}
	
	public function setApellidoMaterno($apellido_materno)
	{
		$this->apellido_materno = $apellido_materno;
	}

	public function getCorreo()
	{
		return $this->correo;
	}
	
	public function setCorreo($correo)
	{
		$this->correo = $correo;
	}
	
	public function getActivo()
	{
		return $this->activo;
	}
	
	public function setActivo($activo)
	{
		$this->activo = $activo;
	}
	
	public function getIdGrupo()
	{
		return $this->id_grupo;
	}
	
	public function setIdGrupo($id_grupo)
	{
		$this->id_grupo = $id_grupo;
	}

	/**
	 * Asigna el mensaje de error a visualizar: para usuario final (usr) o para bitácora (log)
	 *
	 * @access		public
	 * @param		string		$value		tipo de error a visualizar: usr o log, default: usr
	 * @return 		boolean		false 		si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}
	
	/**
	 * Obtiene todos los usuarios existentes, se puede filtrar por: texto a buscar o solo activos si se desea
	 *
	 * @access 	public
	 * @param 	boolean|string		$keywords		false no hay texto a buscar|string con texto a buscar
	 * @param 	boolean				$onlyActives	true obtiene solo usuarios activos, false o null obtiene todos los usuarios
     * @param  	int 				$offset    		Establece el desplazamiento del primer registro a devolver,
     *                        						si se define solo el valor de offset
     *                        						el valor especifica el número de registros a retornar desde el comienzo del conjunto de resultados.
     * @param  int 					$row_count 		Establece la cantidad de registros a devolver
	 * @return 	void|array object					false si ocurrió algún error, array object si se ejecutó correctamente
	 */
	public function getOnlyActives($keywords = '', $onlyActives = TRUE, $offset = null, $row_count = null)
	{
		if(!empty($offset) && !empty($row_count))
			$this->db->limit($offset, $row_count);
		else if (!empty($offset))
			$this->db->limit($offset);
		if ($onlyActives === TRUE)
		{
			$query = $this->db->get_where('sis_usuario', array('activo' => $onlyActives));
		}
		else
		{
			if (empty($keywords)){
				
				$query = $this->db->get('sis_usuario');
			}
			else
			{
				$this->db->select('*');
				$this->db->from('sis_usuario');
				$this->db->like('nombre_usuario', $keywords);
				$this->db->or_like('nombre', $keywords);
				$this->db->or_like('apellido_paterno', $keywords);
				$this->db->or_like('apellido_materno', $keywords);
				$query = $this->db->get();
			}
		}
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
	 * Obtiene el usuario solicitado, se puede obtener el registro normal o personalizado para visualización (descripciones
	 * en tablas vinculadas)
	 *
	 * @access 		public
	 * @param 		int			$id			id del usuario
	 * @param 		boolean		$viewMode	true obtiene el modo visualización, false o null obtiene el registro normal
	 * @return void|object		false si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function getById($id, $viewMode = FALSE)
	{
		if (!$viewMode)
			$query = $this->db->get_where('sis_usuario', array('id' => $id));
		else
		{
			$this->db->select('sis_usuario.*,sis_grupo.nombre as Grupo');
			$this->db->from('sis_usuario');
			$this->db->join('sis_grupo', 'sis_grupo.id = sis_usuario.id_grupo');
			$this->db->where('sis_usuario.id', $id);
			$query = $this->db->get();
		}
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return;
	}

	/**
	 * Obtiene los usuarios activos del grupo solicitado
	 *
	 * @access 		public
	 * @param 		int			$id			id del usuario
	 * @param 		boolean		$viewMode	true obtiene el modo visualización, false o null obtiene el registro normal
	 * @return void|object		false si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function getActivesByGroup($group_id)
	{
		$query = $this->db->get_where('sis_usuario', array('id_grupo' => $group_id, 'activo' => 1));
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
	 * Obtiene el usuario solicitado
	 *
	 * @access 		public
	 * @param 		string		$username		nombre de usuario
	 * @return void|object		false si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function getByUsername($username)
	{
		$query = $this->db->get_where('sis_usuario', array('nombre_usuario' => $username));
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return;
	}
	
	/**
	 * Obtiene el numero total de usuarios
	 *
	 * @access public
	 * @param 	boolean|string		$keywords		false no hay texto a buscar|string con texto a buscar	
	 * @return int
	 */
	public function getNumRows($keywords = '')
	{
		if (!$keywords)
			$query = $this->db->get('sis_usuario');
		else 
		{
			$this->db->select('*');
			$this->db->from('sis_usuario');
			$this->db->like('nombre_usuario', $keywords);
			$this->db->or_like('nombre', $keywords);
			$this->db->or_like('apellido_paterno', $keywords);
			$this->db->or_like('apellido_materno', $keywords);
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
	 * Inserta en la base de datos los datos del usuario (datos en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function insert()
	{
		$data = array(
			'nombre_usuario' => $this->nombre_usuario,
			'clave' => $this->clave,
			'nombre' => $this->nombre,
			'apellido_paterno' => $this->apellido_paterno,
			'apellido_materno' => $this->apellido_materno,
			'correo' => $this->correo,
			'activo' => $this->activo,
			'id_grupo' => $this->id_grupo
		);
		$result = $this->db->insert('sis_usuario', $data);
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Actualiza en la base de datos los datos del usuario (datos en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function update()
	{
		$data = array(
			'nombre' => $this->nombre,
			'apellido_paterno' => $this->apellido_paterno,
			'apellido_materno' => $this->apellido_materno,
			'correo' => $this->correo,
			'activo' => $this->activo,
			'id_grupo' => $this->id_grupo
		);
		$this->db->where('id' , $this->id);
		$result = $this->db->update('sis_usuario', $data);
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Elimina de la base de datos al usuario (id en propiedades)
	 *
	 * @access		public
	 * @return 		boolean					false si ocurrió algún error, true si se ejecutó correctamente
	 */
	public function delete()
	{
		$result = $this->db->delete('sis_usuario', array('id' => $this->getId()));
		if (!$result){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	/**
	 * Valida las credenciales recibidas
	 *
	 * @access 		public
	 * @param 		string		$username		nombre de usuario
	 * @param 		string		$password		clave
	 * @return null|object		null si ocurrió algún error, object si se ejecutó correctamente
	 */
	public function authenticate($username, $password)
	{
		$query = $this->db->get_where('sis_usuario', array('nombre_usuario' => $username, 'clave' => $password));
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return null;
	}

	/** 
	 * Verifica que el usuario haya iniciado sesión y además tenga permiso en la acción recibida
	 *
	 * @access public  	static
	 * @param  string  	$path			entorno::controlador::accion
	 * @param  int		$group_id		id del grupo a validar permisos
	 * @return void
	 */
	public static function checkCredentials($path, $pathURL)
	{
		self::$CI->load->helper('url');
		if (!self::$CI->session->userdata(GROUP_ID)) // si no esta logueado lo debe mandar al login
		{
			self::$CI->session->set_userdata(REDIRECT_TO, $pathURL);
			redirect(DIR_SIIGS.'/usuario/login', 'refresh');
		}
		self::$CI->load->model(DIR_SIIGS.'/ControladorAccion_model');
		try{
			// Obtener el id_controlador_accion a partir del id_controlador y el id_accion
			$id_controlador_accion = self::$CI->ControladorAccion_model->getIdByPath($path);
			if($id_controlador_accion == 0) {
				log_message('error', '(Usuario_model::checkCredentials) No se encuentra la relación entre el controlador y la acción: '.$path.', Error '.self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
				return false;
			}
			self::$CI->load->model(DIR_SIIGS.'/Permiso_model');
			// Obtener permisos sobre la accion recibida 
			$row = self::$CI->Permiso_model->getPermission($id_controlador_accion);
			if ($row)
				return true;
			Bitacora_model::insert($path, 'Acceso denegado a: '.self::$CI->session->userdata(USERNAME));
		} 
		catch(Exception $e) {
			log_message('error', '(Usuario_model::checkCredentials) Error al obtener permisos: '.self::$CI->session->userdata(USERNAME).', Path: '.$path.', Error '.self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
		}
		return false;
	}
	
	
	
	/////////// recuperar contraseña
	
	public function check_data($usuario,$correo)
	{
		$query = $this->db->get_where('sis_usuario', array('nombre_usuario' => $usuario, 'correo' => $correo));
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return null;
	}
	public function check_token($correo)
	{
		$query = $this->db->get_where('sis_usuario', array('correo' => $correo));
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return null;
	}
	public function update_pass($pass,$id)
	{
		$data = array('clave' => $pass);
		$this->db->where('id' , $id);
		$result = $this->db->update('sis_usuario', $data);
		if (!$result)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	////// modificar datos
	
	public function getuser($id)
	{
		$query = $this->db->get_where('sis_usuario', array('id' => $id));
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return null;
	}	
	public function getgrupo($grupo)
	{
		$query = $this->db->get_where('sis_grupo', array('id' => $grupo));
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return null;
	}	
	public function update_user($id,$clave,$correo)
	{
		$data = array('clave' => $clave,'correo' => $correo);
		$this->db->where('id' , $id);
		$result = $this->db->update('sis_usuario', $data);
		if (!$result)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		return $result;
	}
	
	// obtiene los permisos que pertenescan a un entorno parametros nombre entorno
	public function get_permiso_entorno($nombre)
	{
		$this->db->distinct();
		$this->db->select('p.id, p.id_grupo, p.fecha, p.id_controlador_accion');
		$this->db->from('sis_entorno e');
		$this->db->join('sis_controlador c', 'c.id_entorno = e.id','left');
		$this->db->join('sis_controlador_x_accion ca', 'ca.id_controlador = c.id','left');
		$this->db->join('sis_permiso p', 'p.id_controlador_accion = ca.id','left');
		$this->db->where('e.nombre' , $nombre);
		$this->db->where('p.id !=' , '');
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return null;
	}
	
	// obtiene los grupos que pertenescan a un entorno parametros nombre entorno
	public function get_grupo_entorno($nombre)
	{
		$this->db->distinct();
		$this->db->select('g.id, g.nombre, g.descripcion');
		$this->db->from('sis_entorno e');
		$this->db->join('sis_controlador c', 'c.id_entorno = e.id','left');
		$this->db->join('sis_controlador_x_accion ca', 'ca.id_controlador = c.id','left');
		$this->db->join('sis_permiso p', 'p.id_controlador_accion = ca.id','left');
		$this->db->join('sis_grupo g', 'g.id = p.id_grupo','left');
		$this->db->where('e.nombre' , $nombre);
		$this->db->where('g.id !=' , '');
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return null;
	}
	
	// obtiene los usuario que pertenescan a un entorno parametros nombre entorno
	public function get_usuario_entorno($nombre,$inusuario="")
	{
		$this->db->distinct();
		$this->db->select(' u.id, u.nombre_usuario, u.clave, u.nombre, u.apellido_paterno, u.apellido_materno, u.correo, u.activo, u.id_grupo');
		$this->db->from('sis_entorno e');
		$this->db->join('sis_controlador c', 'c.id_entorno = e.id','left');
		$this->db->join('sis_controlador_x_accion ca', 'ca.id_controlador = c.id','left');
		$this->db->join('sis_permiso p', 'p.id_controlador_accion = ca.id','left');
		$this->db->join('sis_grupo g', 'g.id = p.id_grupo','left');
		$this->db->join('sis_usuario u', 'u.id_grupo = g.id','left');
		$this->db->where('e.nombre' , $nombre);
		$this->db->where('u.activo' , 1);
		$this->db->where('u.id !=' , '');
		if($inusuario!="")
		$this->db->where_in('u.id' , $inusuario);
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return null;
	}
}
?>