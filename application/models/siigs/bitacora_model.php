<?php

/**
 * Modelo Bitacora
 *
 * @author     Pascual
 * @created    2013-09-26
 */
class Bitacora_model extends CI_Model
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
     * @var    datetime
     */
    private $fecha_hora;

    /**
     * @access private
     * @var    string
     */
    private $parametros;

    /**
     * @access private
     * @var    int
     */
    private $id_controlador_accion;

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

    /**
     * @access private
     * @var    boolean
     */
    private $error;

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
        $this->fecha_hora = date('Y-m-d H:i:s');
        $this->error = false;
        $this->msg_error_usr = '';
        $this->msg_error_log = '';
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

    public function getFecha_hora()
    {
        return $this->fecha_hora;
    }

    public function setFecha_hora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;
    }

    public function getParametros()
    {
        return $this->parametros;
    }

    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
    }

    public function getId_controlador_accion()
    {
        return $this->id_controlador_accion;
    }

    public function setId_controlador_accion($id_controlador_accion)
    {
        $this->id_controlador_accion = $id_controlador_accion;
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
     * Devuelve el mensaje de error,
     * en caso de existir un error despues de ejecutar un metodo,
     * de lo contrario false
     *
     * @access public
     * @param  string $type usr si se quiere devolver el mensaje de error a mostrar en la vista,
     *                      log obtiene el mensaje de error con mas detalles para depuración,
     *                      valor por defecto usr
     * @return boolean|string
     */
    public function getMsgError($type = 'usr')
    {
        if($this->error) {
            
            if($type == 'usr')
                return $this->msg_error_usr;
            else if($type == 'log')
                return $this->msg_error_log;
            else
                return false;
        }

        return false;
    }

    /**
     * Inserta a la bitacora la información porporcionada
     *
     * @access public static
     * @param  string $path Concatenación de directorio del proyeto, clase y metodo, unidos por dos dobles puntos '::'
     * @param  string $parametros
     * @return boolean false Si no se ejecutó la inserción, true si se ejecutó la inserción
     */
    public static function insert($path, $parametros)
    {
        self::$CI->load->model('siigs/ControladorAccion_model');
        $id_controlador_accion = self::$CI->ControladorAccion_model->getIdByPath($path);

        if(empty($id_controlador_accion)) {
            Errorlog_model::insert(DIR_SIIGS.'::'.__METHOD__, '(Bitacora_model::insert) No se encuentra la relación entre el controlador y la acción: '.$path.', Error '.self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
        }

        $data = array(
            'id_usuario' => self::$CI->session->userdata(USER_LOGGED),
            'fecha_hora' => date('Y-m-d H:i:s'), // inserta con la fecha y hora actual del sistema,
            'parametros' => $parametros,
            'id_controlador_accion' => $id_controlador_accion
        );

        self::$CI->db->insert('sis_bitacora', $data);

        if(self::$CI->db->_error_number()) {
            log_message('error', '(Bitacora_model::insert) Usuario: '.self::$CI->session->userdata(USER_LOGGED).', Path: '.$path.', Parametros: '.$parametros.', Error '.self::$CI->db->_error_number().': '.self::$CI->db->_error_message());
            return false;
        }

        return true;
    }

    /**
     * Actualiza los datos del objeto actual
     *
     * @access public
     * @param  int $id Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return boolean false Si no se ejecutó la actualización, true si se ejecutó la actualización
     */
    public function update($id = null)
    {
        $result = false;

        // Obtener el id_controlador_accion a partir del id_controlador y el id_accion
        if(empty($this->id_controlador_accion)) {
            $this->load->model('siigs/ControladorAccion_model');
            $this->id_controlador_accion = $this->ControladorAccion_model->getId($this->id_controlador,$this->id_accion);
        }

        $data = array(
            'id_usuario' => $this->id_usuario,
            'fecha_hora' => $this->fecha_hora,
            'parametros' => $this->parametros,
            'id_controlador_accion' => $this->id_controlador_accion
        );

        $id = is_null($id) ? $this->id : $id;
        $result = $this->db->update('sis_bitacora', $data, array('id' => $id));

        if(empty($result)) {
            $this->error = true;
            $this->msg_error_usr = 'No se puede actualizar el registro';
            $this->msg_error_log = '('.__METHOD__.') =>  '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception(__CLASS__);
        }

        return $result;
    }

    /**
     * Elimina el registro actual de la base de datos
     *
     * @access public
     * @param  int $id Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return int     false Si no se eliminó el registro, true si se ejecutó la eliminación
     */
    public function delete($id = null)
    {
        $result = false;
        
        $id = is_null($id) ? $this->id : $id;

        if(is_array($id)) {
            // Eliminar un conjunto de registros
            foreach ($id as $idx) {
                $result = $this->db->delete('sis_bitacora', array('id' => $idx));

                if(empty($result)) {
                    $this->error = true;
                    $this->msg_error_usr = 'No se puede eliminar el registro';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception(__CLASS__);
                }
            }
        } else {
            // Eliminar un solo registro
            $result = $this->db->delete('sis_bitacora', array('id' => $id));

            if(empty($result)) {
                $this->error = true;
                $this->msg_error_usr = 'No se puede eliminar el registro';
                $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                throw new Exception(__CLASS__);
            }
        }

        return $result;
    }

    /**
     * Elimina el conjunto de registros que cumplen con el o los criterios de filtrado
     *
     * @access public
     * @return int false Si no se ejecutó la inserción, true si se ejecutó la inserción
     */
    public function deleteByFilter()
    {
        $result = false;
        
        if(empty($this->filters)) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron filtros definidos';
            $this->msg_error_log = '('.__METHOD__.') => No se encontraron filtros definidos para la eliminación';
            
            throw new Exception(__CLASS__);
        }

        $this->db->where($this->filters);
        $result = $this->db->delete('sis_bitacora');

        if(empty($result)) {
            $this->error = true;
            $this->msg_error_usr = 'No se pueden eliminar los registros';
            $this->msg_error_log = '('.__METHOD__.') => No se pueden eliminar los registros';
            throw new Exception(__CLASS__);
        }

        return $result;
    }

    /**
     * Obtiene los datos del registro de la bitacora que tiene el ID especificado
     *
     * @access public
     * @param  int $id        Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getById($id)
    {
        $result = false;
        
        $this->db->select('entorno.nombre AS entorno, controlador.nombre AS controlador, accion.nombre AS accion, sis_bitacora.*,
                           usuario.nombre_usuario AS usuario, usuario.nombre, usuario.apellido_paterno, usuario.apellido_materno');
        $this->db->from('sis_bitacora');
        $this->db->join('usuario', 'usuario.id = sis_bitacora.id_usuario');
        $this->db->join('controlador_x_accion', 'controlador_x_accion.id = sis_bitacora.id_controlador_accion');
        $this->db->join('controlador', 'controlador.id = controlador_x_accion.id_controlador');
        $this->db->join('accion', 'accion.id = controlador_x_accion.id_accion');
        $this->db->join('entorno', 'entorno.id = controlador.id_entorno');
        $this->db->where('sis_bitacora.id', $id);
        $query = $this->db->get();
        $result = $query->row();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception(__CLASS__);
        }

        if(!empty($result)) {
            $this->load->model('siigs/ControladorAccion_model');

            $this->id = $id;
            $this->id_usuario = $result->id_usuario;
            $this->fecha_hora = $result->fecha_hora;
            $this->parametros = $result->parametros;
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
     * @param  string $columna   Puede ser cualquier campo del objeto (id, id_usuario, fecha_hora, parametros, id_controlador_accion)
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
            'parametros',
            'id_entorno',
            'id_controlador',
            'id_accion'
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
            $this->msg_error_usr = 'ERROR: Condición no permitida en el filtro ('.$condicion.')';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->msg_error_usr;
            
            throw new Exception(__CLASS__);
        }

        if(empty($valor)) {
            $this->error = true;
            $this->msg_error_usr = 'ERROR: Debe definir un valor para el filtro';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->msg_error_usr;
            
            throw new Exception(__CLASS__);
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
     * Obtiene todos los registros de la tabla Bitacora
     * en caso de existir filtros, estos son aplicados a la consulta
     *
     * @access public
     * @param  int $offset    Establece el desplazamiento del primer registro a devolver,
     *                        si se define solo el valor de offset
     *                        el valor especifica el número de registros a retornar desde el comienzo del conjunto de resultados.
     * @param  int $row_count Establece la cantidad de registros a devolver
     * @return array object   Devuelve un arreglo de objetos obtenidos de la base de datos
     */
    public function getAll($offset = null, $row_count = null)
    {
        $result = 0;

        $this->db->select('entorno.nombre AS entorno, controlador.nombre AS controlador, accion.nombre AS accion, sis_bitacora.*,
                           usuario.nombre_usuario AS usuario, usuario.nombre, usuario.apellido_paterno, usuario.apellido_materno');
        $this->db->from('sis_bitacora');
        $this->db->join('usuario', 'usuario.id = sis_bitacora.id_usuario');
        $this->db->join('controlador_x_accion', 'controlador_x_accion.id = sis_bitacora.id_controlador_accion');
        $this->db->join('controlador', 'controlador.id = controlador_x_accion.id_controlador');
        $this->db->join('accion', 'accion.id = controlador_x_accion.id_accion');
        $this->db->join('entorno', 'entorno.id = controlador.id_entorno');
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
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception(__CLASS__);
        } else {
            $result = $query->result();
        }

        return $result;
    }


    /**
     * Obtiene el numero total de registros en la tabla Bitacora
     * en caso de existir filtros, estos son aplicados a la consulta
     *
     * @access public
     * @return int
     */
    public function getNumRows()
    {
        $result = 0;

        $this->db->select('entorno.nombre AS entorno, controlador.nombre AS controlador, accion.nombre AS accion, sis_bitacora.*,
                           usuario.nombre_usuario AS usuario, usuario.nombre, usuario.apellido_paterno, usuario.apellido_materno');
        $this->db->from('sis_bitacora');
        $this->db->join('usuario', 'usuario.id = sis_bitacora.id_usuario');
        $this->db->join('controlador_x_accion', 'controlador_x_accion.id = sis_bitacora.id_controlador_accion');
        $this->db->join('controlador', 'controlador.id = controlador_x_accion.id_controlador');
        $this->db->join('accion', 'accion.id = controlador_x_accion.id_accion');
        $this->db->join('entorno', 'entorno.id = controlador.id_entorno');

        if(!empty($this->filters))
            $this->db->where($this->filters);

        $result = $this->db->count_all_results();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception(__CLASS__);
        }

        return $result;
    }

}
?>