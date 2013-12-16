<?php

/**
 * Modelo Errorlog
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-10-02
 */
class Errorlog_model extends CI_Model
{
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
     * @var    int
     */
    private $id_usuario;

    /**
     * @access private
     * @var    int
     */
    private $id_controlador_accion;

    /**
     * @access private
     * @var    datetime
     */
    private $fecha_hora;

    /**
     * @access private
     * @var    string
     */
    private $descripcion;
    

    /********************************************
     * Estas variables no pertenecen a la tabla *
     * ******************************************/

    /**
     * @access private
     * @var    int
     */
    private $id_controlador;

    /**
     * @access private
     * @var    int
     */
    private $id_accion;
    
    /**
     * @access private
     * @var    array
     */
    private $filters;

    
    public function __construct()
    {
        parent::__construct();

        self::$CI = &get_instance();

        $this->load->database();
        $this->fecha_hora = date('Y-m-d H:i:s');
        $this->filters = array();

        if( !$this->db->conn_id ) {
            echo '<div class="error">ERROR: No se puede conectar con la Base de Datos</div>';
            die();
            //return false;
            //throw new Exception ('ERROR: No se puede conectar con la Base de Datos');
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getId_controlador_accion()
    {
        return $this->id_controlador_accion;
    }

    public function setId_controlador_accion($id_controlador_accion)
    {
        $this->id_controlador_accion = $id_controlador_accion;
    }

    public function getFecha_hora()
    {
        return $this->fecha_hora;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setId_controlador($id_controlador)
    {
        $this->id_controlador = $id_controlador;
    }

    public function setId_accion($id_accion)
    {
        $this->id_accion = $id_accion;
    }

    /**
     * Inserta en la base de datos la informacion del error
     *
     * @access public  static
     * @param  int     $id_controlador
     * @param  int     $id_accion
     * @param  string  $descripcion
     * @return void
     */
    public static function insert($path, $descripcion)
    {
        // Obtener el id_controlador_accion a partir del id_controlador y el id_accion
        self::$CI->load->model(DIR_SIIGS.'/ControladorAccion_model');
        try{
            $id_controlador_accion = self::$CI->ControladorAccion_model->getIdByPath($path);
        
            if(empty($id_controlador_accion)) {
                log_message('error', '(Errorlog_model::insert) No se encuentra la relación entre el controlador y la acción: '.$path.', Error '.
                            self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
            }

            $data = array(
                'id_usuario' => self::$CI->session->userdata(USER_LOGGED),
                'id_controlador_accion' => $id_controlador_accion,
                'fecha_hora' => date('Y-m-d H:i:s'), // inserta con la fecha y hora actual del sistema
                'descripcion' => $descripcion
            );

            self::$CI->db->insert('sis_error', $data);

            if(self::$CI->db->_error_number()) {
                log_message('error', '(Errorlog_model::insert) Usuario: '.self::$CI->session->userdata(USER_LOGGED).', Path: '.$path.', Descripción: '.
                            $descripcion.', Error '.self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
            }
        } catch(Exception $e) {
            log_message('error', '(Errorlog_model::insert) Usuario: '.self::$CI->session->userdata(USER_LOGGED).', Path: '.$path.', Descripción: '.
                        $descripcion.', Error '.$e->getMessage());
        }
    }

    /**
     * Obtiene los datos del registro del Error que tiene el ID especificado
     *
     * @access public
     * @param  int $id        Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean Devuelve un objeto con los datos del elemento solicitado, de lo contrario, false Si no se encontró el registro
     */
    public function getById($id)
    {
        $result = false;
        
        $this->db->select('sis_entorno.nombre AS entorno, sis_controlador.nombre AS controlador, sis_accion.nombre AS accion, sis_error.*,
                           sis_usuario.nombre_usuario AS usuario, sis_usuario.nombre, sis_usuario.apellido_paterno, sis_usuario.apellido_materno');
        $this->db->from('sis_error');
        $this->db->join('sis_usuario', 'sis_usuario.id = sis_error.id_usuario');
        $this->db->join('sis_controlador_x_accion', 'sis_controlador_x_accion.id = sis_error.id_controlador_accion');
        $this->db->join('sis_controlador', 'sis_controlador.id = sis_controlador_x_accion.id_controlador');
        $this->db->join('sis_accion', 'sis_accion.id = sis_controlador_x_accion.id_accion');
        $this->db->join('sis_entorno', 'sis_entorno.id = sis_controlador.id_entorno');
        $this->db->where('sis_error.id', $id);
        $query = $this->db->get();
        $result = $query->row();

        if($this->db->_error_number()) {
            log_message('sis_error', __METHOD__.', Error '.self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
            return false;
        }

        if(!empty($result)) {
            $this->load->model(DIR_SIIGS.'/ControladorAccion_model');

            $this->id = $id;
            $this->id_usuario = $result->id_usuario;
            $this->fecha_hora = $result->fecha_hora;
            $this->descripcion = $result->descripcion;
            $this->id_controlador_accion = $result->id_controlador_accion;
            
            $controlador_accion = $this->ControladorAccion_model->getById($result->id_controlador_accion);
            $this->id_controlador = $controlador_accion->id_controlador;
            $this->id_accion = $controlador_accion->id_accion;
        }

        return $result;
    }

    /**
     * Agrega una nueva regla de filtrado al arreglo de filtros
     *
     * @access public
     * @param  string $columna   Puede ser cualquier campo del objeto (id, id_usuario, fecha_hora, descripcion, id_controlador_accion)
     * @param  string $condicion Establece la condicion a evaluar, entre los valores permitidos estan: =, !=, >=, <=, like
     * @param  string $valor     Valor contra el cual se realizará la evaluación del campo
     * @return void|boolean      Devuelve falso en caso de no poder establecer el filtro
     */
    public function addFilter($columna, $condicion, $valor)
    {
        $columnasPermitidas = array(
            'id',
            'id_usuario',
            'fecha_hora',
            'descripcion',
            'id_entorno',
            'id_controlador',
            'id_accion'
            //'id_controlador_accion',
        );

        $condicionesPermitidas = array('=', '>', '<', '!=', '>=', '<=', 'like');

        if(!in_array($columna, $columnasPermitidas)) {
            return false;
        }

        if(!in_array($condicion, $condicionesPermitidas)) {
            return false;
        }

        if(empty($valor)) {
            return false;
        }
        
        // Ejemplo de filtros permitidos por where de active records
        // $filtros = array(
        //     'name !=' => $name,
        //     'id <'    => $id,
        //     'date >'  => $date
        // );
        $this->filters[$columna.' '.$condicion] = $valor;
    }

    /**
     * Elimina todos los filtros registrados
     *
     * @access public
     * @return void
     */
    public function resetFilter()
    {
        $this->filters = array();
    }

    /**
     * Obtiene todos los registros de la tabla Error
     * en caso de existir filtros, estos son aplicados a la consulta
     *
     * @access public
     * @param  int $offset    Establece el desplazamiento del primer registro a devolver,
     *                        si se define solo el valor de offset
     *                        el valor especifica el número de registros a retornar desde el comienzo del conjunto de resultados.
     * @param  int $row_count Establece la cantidad de registros a devolver
     * @return array          Devuelve conjunto de datos, como areglo, obtenidos de la tabla
     */
    public function getAll($offset = null, $row_count = null)
    {
        $result = 0;

        $this->db->select('sis_entorno.nombre AS entorno, sis_controlador.nombre AS controlador, sis_accion.nombre AS accion, sis_error.*,
                           sis_usuario.nombre_usuario AS usuario, sis_usuario.nombre, sis_usuario.apellido_paterno, sis_usuario.apellido_materno');
        $this->db->from('sis_error');
        $this->db->join('sis_usuario', 'sis_usuario.id = sis_error.id_usuario');
        $this->db->join('sis_controlador_x_accion', 'sis_controlador_x_accion.id = sis_error.id_controlador_accion');
        $this->db->join('sis_controlador', 'sis_controlador.id = sis_controlador_x_accion.id_controlador');
        $this->db->join('sis_accion', 'sis_accion.id = sis_controlador_x_accion.id_accion');
        $this->db->join('sis_entorno', 'sis_entorno.id = sis_controlador.id_entorno');
        $this->db->order_by('fecha_hora', 'desc');
        $this->db->order_by('id_entorno', 'desc');
        $this->db->order_by('id_controlador', 'desc');
        $this->db->order_by('id_accion', 'desc');
        
        if( !empty($this->filters) )
            $this->db->where($this->filters);
        
        if(!empty($offset) && !empty($row_count))
            $this->db->limit($offset, $row_count);
        else if (!empty($offset))
            $this->db->limit($offset);

        $query = $this->db->get();

        if($this->db->_error_number()) {
            return false;
        } else {
            $result = $query->result();
        }

        return $result;
    }

    /**
     * Obtiene el numero total de registros en la tabla Error
     * en caso de existir filtros, estos son aplicados a la consulta
     *
     * @access public
     * @return int|boolean Numero de registros encontrados o false en caso de algun error
     */
    public function getNumRows()
    {
        $result = false;

        $this->db->select('sis_entorno.nombre AS entorno, sis_controlador.nombre AS controlador, sis_accion.nombre AS accion, sis_error.*,
                           sis_usuario.nombre_usuario AS usuario, sis_usuario.nombre, sis_usuario.apellido_paterno, sis_usuario.apellido_materno');
        $this->db->from('sis_error');
        $this->db->join('sis_usuario', 'sis_usuario.id = sis_error.id_usuario');
        $this->db->join('sis_controlador_x_accion', 'sis_controlador_x_accion.id = sis_error.id_controlador_accion');
        $this->db->join('sis_controlador', 'sis_controlador.id = sis_controlador_x_accion.id_controlador');
        $this->db->join('sis_accion', 'sis_accion.id = sis_controlador_x_accion.id_accion');
        $this->db->join('sis_entorno', 'sis_entorno.id = sis_controlador.id_entorno');

        if(!empty($this->filters))
            $this->db->where($this->filters);


        $result = $this->db->count_all_results();

        if($this->db->_error_number()) {
            return false;
        }

        return $result;
    }


    /**
     * Guardar el mensaje de error descriptivo en la base de datos,
     * si no puede insertar el registro a la base de datos,
     * el mensaje de error se guarda en el directorio logs,
     * devuelve el mensaje de error para el usuario final
     *
     * @access public
     * @param  string $modelo Nombre del modelo que lanzó la excepción
     * @param  string $method Contiene el nombre de la clase y metodo donde se originó el error o
     *                        el mensaje de error para mostrar al usuario final
     * @return string Mensaje de error
     */
    public static function save($model, $method)
    {
        if (isset(self::$CI->$model))
        {
            $msgErrUsr = self::$CI->$model->getMsgError();
            $msgErrLog = self::$CI->$model->getMsgError('log');

            Errorlog_model::insert(DIR_SIIGS.'::'.$method, $msgErrLog);
        }
        else
        {
            $msgErrUsr = $model;
            Errorlog_model::insert(DIR_SIIGS.'::'.$method, $msgErrUsr);
        }

        return $msgErrUsr;
    }

}
?>