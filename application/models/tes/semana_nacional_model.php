<?php

/**
 * Modelo Tableta
 *
 * @package    TES
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-11-26
 */
class Semana_nacional_model extends CI_Model
{
    /**
     * @access private
     * @var    int(11)
     */
    private $id;

    /**
     * @access private
     * @var    varchar(45)
     */
    private $descripcion;

    /**
     * @access private
     * @var    date
     */
    private $fecha_inicio;
    
    /**
     * @access private
     * @var    date
     */
    private $fecha_fin;
    

    /********************************************
     * Estas variables no pertenecen a la tabla *
     * ******************************************/

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
        
        if( !$this->db->conn_id ) {
            throw new Exception ('ERROR: No se puede conectar con la Base de Datos');
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getFecha_inicio()
    {
        return $this->fecha_inicio;
    }

    public function getFecha_fin()
    {
        return $this->fecha_fin;
    }

    public function setDescripcion($descripcion) 
    {
        $this->descripcion = $descripcion;
    }

    public function setFecha_inicio($fecha_inicio)
    {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFecha_fin($fecha_fin)
    {
        $this->fecha_fin = $fecha_fin;
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
        
        $data['descripcion'] = $this->descripcion;
        $data['fecha_inicio'] = $this->fecha_inicio;
        $data['fecha_fin'] = $this->fecha_fin;

        $result = $this->db->insert('cns_semana_vacunacion', $data);

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'No se puede insertar el registro';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception($this->msg_error_log);
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
        $data = array();
        
        $data['descripcion'] = $this->descripcion;
        $data['fecha_inicio'] = $this->fecha_inicio;
        $data['fecha_fin'] = $this->fecha_fin;

        $id = is_null($id) ? $this->id : $id;
        $result = $this->db->update('cns_semana_vacunacion', $data, array('id' => $id));

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'No se puede actualizar el registro';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else {
            // Obtiene el id asignado a la ultima inserción
            $this->id = $id;
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
                $result = $this->db->delete('cns_semana_vacunacion', array('id' => $idx));

                if(empty($result)) {
                    $this->error = true;
                    $this->msg_error_usr = 'No se puede eliminar el registro';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception();
                }
            }
        } else {
            // Eliminar un solo registro
            $result = $this->db->delete('cns_semana_vacunacion', array('id' => $id));

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
     * Obtiene los datos del registro que tiene el ID especificado
     *
     * @access public
     * @param  int $id        Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getById($id)
    {
        $result = false;
        
        $this->db->select('*');
        $this->db->from('cns_semana_vacunacion');
        $this->db->where('id', $id);
        
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
            $this->descripcion = $result->descripcion;
            $this->fecha_inicio = $result->fecha_inicio;
            $this->fecha_fin = $result->fecha_fin;
        }

        return $result;
    }
    
    /**
     * Obtiene todos los registros de la tabla
     *
     * @access public
     * @param  int $offset    Establece el desplazamiento del primer registro a devolver,
     *                        si se define solo el valor de offset
     *                        el valor especifica el número de registros a retornar desde el comienzo del conjunto de resultados.
     * @param  int $row_count Establece la cantidad de registros a devolver
     * @return array object   Devuelve un arreglo de objetos obtenidos de la base de datos
     */
    public function getAll($offset=null, $row_count=null)
    {
        $result = 0;
        
        $this->db->select('id, descripcion, fecha_inicio, fecha_fin');
        $this->db->from('cns_semana_vacunacion');
        
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
     * Obtiene el numero total de registros en la tabla
     *
     * @access public
     * @return int
     */
    public function getNumRows()
    {
        $result = 0;

        $result = $this->db->count_all_results('cns_semana_vacunacion');

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
}
?>