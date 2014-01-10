<?php

/**
 * Modelo Menu
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-10-03
 */
class Menu_model extends CI_Model
{
    /**
     * @access private
     * @var    int
     */
    private $id;

    /**
     * @access private
     * @var    int
     */
    private $id_padre;

    /**
     * @access private
     * @var    int
     */
    private $id_raiz;

    /**
     * @access private
     * @var    string
     */
    private $nombre;

    /**
     * @access private
     * @var    int
     */
    private $id_controlador;

    /**
     * @access private
     * @var    boolean
     */
    private $ruta;


    /********************************************
     * Estas variables no pertenecen a la tabla *
     * ******************************************/

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
        $this->load->database();
        $this->error = false;
        $this->msg_error_usr = '';
        $this->msg_error_log = '';
        
        /*if( !$this->db->conn_id ) {
            throw new Exception ('ERROR: No se puede conectar con la Base de Datos');
        }*/
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getId_padre()
    {
        return $this->id_padre;
    }

    public function setId_padre($id_padre)
    {
        $this->id_padre = $id_padre;
    }

    public function getId_raiz()
    {
        return $this->id_raiz;
    }

    public function setId_raiz($id_raiz)
    {
        return $this->id_raiz = $id_raiz;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setId_controlador($id_controlador)
    {
        $this->id_controlador = $id_controlador;
    }

    public function getId_controlador()
    {
        $this->id_controlador;
    }

    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }

    public function getRuta()
    {
        return $this->ruta;
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
     * Inserta en la base de datos, la informacion contenida en el objeto
     *
     * @access public
     * @return boolean false Si no se ejecutó la inserción, true si se ejecutó la inserción
     */
    public function insert()
    {
        $result = false;
        $data = array();

        if( isset($this->id_padre) )
            $data['id_padre'] = $this->id_padre;

        if( isset($this->id_raiz) )
            $data['id_raiz'] = $this->id_raiz;

        if( isset($this->id_controlador) )
            $data['id_controlador'] = $this->id_controlador;

        if( isset($this->ruta) )
            $data['ruta'] = $this->ruta;

        $data['nombre'] = $this->nombre;

        $result = $this->db->insert('sis_menu', $data);

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'No se puede insertar el registro';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else {
            // Obtiene el id asignado a la ultima inserción
            $this->id = $this->db->insert_id();
        }

        return $result;
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

        //if( isset($this->id_padre) )
            $data['id_padre' ] = $this->id_padre;

        //if( isset($this->id_raiz) )
            $data['id_raiz'] = $this->id_raiz;

        //if( isset($this->id_controlador) )
            $data['id_controlador'] = $this->id_controlador;

        //if( isset($this->ruta) )
            $data['ruta'] = $this->ruta;

        $data['nombre'] = $this->nombre;

        $id = is_null($id) ? $this->id : $id;
        $result = $this->db->update('sis_menu', $data, array('id' => $id));
        
        echo $this->db->last_query();
        echo '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
        
        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'No se puede insertar el registro';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception('('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message());
        } /*else {
            // Obtiene el id asignado a la ultima inserción
            $this->id = $this->db->insert_id();
        }*/

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
                $result = $this->db->delete('sis_menu', array('id' => $idx));

                if(empty($result)) {
                    $this->error = true;
                    $this->msg_error_usr = 'No se puede eliminar el registro';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception();
                }
            }
        } else {
            // Eliminar un solo registro
            $result = $this->db->delete('sis_menu', array('id' => $id));

            if(empty($result)) {
                $this->error = true;
                $this->msg_error_usr = 'No se puede eliminar el registro';
                $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                throw new Exception();
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
            
            throw new Exception();
        }

        $this->db->where($this->filters);
        $result = $this->db->delete('sis_menu');

        if(empty($result)) {
            $this->error = true;
            $this->msg_error_usr = 'No se pueden eliminar los registros';
            $this->msg_error_log = '('.__METHOD__.') => No se pueden eliminar los registros';
            throw new Exception();
        }

        return $result;
    }

    /**
     * Obtiene los datos del registro de la menu que tiene el ID especificado
     *
     * @access public
     * @param  int $id        Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getById($id)
    {
        $result = false;
        
        $this->db->select('sis_menu.id, sis_menu.id_padre, sis_menu.id_raiz, sis_menu.nombre, sis_menu.id_controlador, sis_entorno.nombre AS nombre_entorno, sis_entorno.id AS id_entorno,
                          sis_menu.ruta, raiz.nombre AS nombre_raiz, padre.nombre AS nombre_padre, sis_controlador.nombre AS nombre_controlador');
        $this->db->from('sis_menu');
        $this->db->join('sis_menu raiz', 'raiz.id=sis_menu.id_raiz', 'left');
        $this->db->join('sis_menu padre', 'padre.id=sis_menu.id_padre', 'left');
        $this->db->join('sis_controlador', 'sis_controlador.id=sis_menu.id_controlador', 'left');
        $this->db->join('sis_entorno', 'sis_entorno.id=sis_controlador.id_entorno', 'left');
        $this->db->where('sis_menu.id', $id);
        $query = $this->db->get();
        $result = $query->row();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        }

        if(!empty($result)) {
            $this->id = $id;
            $this->id_padre = $result->id_padre;
            $this->id_raiz = $result->id_raiz;
            $this->id_controlador = $result->id_controlador;
            $this->ruta = $result->ruta;
            $this->nombre = $result->nombre;
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
            'id_padre',
            'id_raiz',
            'id_controlador',
            'ruta',
        );

        $condicionesPermitidas = array('=', '>', '<', '!=', '>=', '<=', 'like');

        if(!in_array($columna, $columnasPermitidas)) {
            $this->error = true;
            $this->msg_error_usr = 'ERROR: Columna no permitida en el filtro';
            $this->msg_error_log = '('.__METHOD__.') => Columna no permitida en el filtro';
            
            throw new Exception();
        }

        if(!in_array($condicion, $condicionesPermitidas)) {
            $this->error = true;
            $this->msg_error_usr = 'ERROR: Condición no permitida en el filtro';
            $this->msg_error_log = '('.__METHOD__.') => Condición no permitida en el filtro';
            
            throw new Exception();
        }

        if(empty($valor)) {
            $this->error = true;
            $this->msg_error_usr = 'ERROR: Debe definir un valor para el filtro';
            $this->msg_error_log = '('.__METHOD__.') => Debe definir un valor para el filtro';
            
            throw new Exception();
        }
        
        // Ejemplo de filtros permitidos por where de active records
        // $filtros = array(
        //     'name !=' => $name,
        //     'id <'    => $id,
        //     'date >'  => $date
        // );
        $this->filters['sis_menu.'.$columna.' '.$condicion] = $valor;
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
     * Obtiene todos los registros de la tabla Menu
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
        
        $this->db->select('sis_menu.id, sis_menu.id_padre, sis_menu.id_raiz, sis_menu.nombre, sis_menu.id_controlador,
                          sis_menu.ruta, raiz.nombre AS nombre_raiz, padre.nombre AS nombre_padre, sis_controlador.nombre AS nombre_controlador');
        $this->db->from('sis_menu');
        $this->db->join('sis_menu raiz', 'raiz.id=sis_menu.id_raiz', 'left');
        $this->db->join('sis_menu padre', 'padre.id=sis_menu.id_padre', 'left');
        $this->db->join('sis_controlador', 'sis_controlador.id=sis_menu.id_controlador', 'left');

        if( !empty($this->filters) )
            $this->db->where($this->filters);
        
        if(!empty($offset) && !empty($row_count))
            $this->db->limit($offset, $row_count);
        else if (!empty($offset))
            $this->db->limit($offset);

        $query = $this->db->get();
        $result = $query->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else if(empty ($result)) {
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
        }

        return $result;
    }


    /**
     * Obtiene el numero total de registros en la tabla Menu
     * en caso de existir filtros, estos son aplicados a la consulta
     *
     * @access public
     * @return int
     */
    public function getNumRows()
    {
        $result = 0;

        $this->db->select('sis_menu.id, sis_menu.id_padre, sis_menu.id_raiz, sis_menu.nombre, sis_menu.id_controlador,
                          sis_menu.ruta, raiz.nombre AS nombre_raiz, padre.nombre AS nombre_padre, sis_controlador.nombre AS nombre_controlador');
        $this->db->from('sis_menu');
        $this->db->join('sis_menu raiz', 'raiz.id=sis_menu.id_raiz', 'left');
        $this->db->join('sis_menu padre', 'padre.id=sis_menu.id_padre', 'left');
        $this->db->join('sis_controlador', 'sis_controlador.id=sis_menu.id_controlador', 'left');

        if( !empty($this->filters) )
            $this->db->where($this->filters);

        if(!empty($offset) && !empty($row_count))
            $this->db->limit($offset, $row_count);
        else if (!empty($offset))
            $this->db->limit($offset);

        $result = $this->db->count_all_results();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else if(empty ($result)) {
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
        }

        return $result;
    }

    public function hasChild($id) {
        $query = $this->db->query('SELECT COUNT(id) as num_children FROM sis_menu WHERE id_padre='.$id);
        $result = $query->row();

        if($result->num_children == 0)
            return false;

        return true;
    }

    public function getByPadre($padre) {
        $cond = '';

        // Obtiene todas las raices
        if($padre=='NULL' || $padre=='null' || $padre==0)
            $cond = ' IS NULL';
        else
            $cond = ' = '.$padre;

        $query = $this->db->query('SELECT * FROM sis_menu WHERE id_padre '.$cond);
        $result = $query->result();
        
        return $result;
    }

    /*public function getMenuTree(){
        $query = $this->db->query('SELECT
                        menu.id_padre,
                        menu_padre.nombre AS nombre_padre,
                        menu.id,
                        menu.nombre,
                        menu.nivel
                    FROM
                        menu
                    LEFT JOIN menu AS menu_raiz ON menu.id_raiz = menu_raiz.id
                    LEFT JOIN menu AS menu_padre ON menu.id_padre = menu_padre.id');

        $result = $query->result_array();

        return $result;
    }*/

}
?>