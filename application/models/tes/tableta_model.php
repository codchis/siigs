<?php

/**
 * Modelo Tableta
 *
 * @package    TES
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-11-26
 */
class Tableta_model extends CI_Model
{
    /**
     * @access private
     * @var    int(11)
     */
    private $id;

    /**
     * @access private
     * @var    varchar(20)
     */
    private $mac;

    /**
     * @access varchar(10)
     * @var    int
     */
    private $id_version;

    /**
     * @access private
     * @var    datetime
     */
    private $usuarios_asignados;
    
    /**
     * @access private
     * @var    bit(1)
     */
    private $ultima_actualizacion;

    /**
     * @access private
     * @var    int(11)
     */
    private $id_tes_estado_tableta;

    /**
     * @access private
     * @var    int(11)
     */
    private $id_tipo_censo;
    
    /**
     * @access private
     * @var    int(11)
     */
    private $id_asu_um;


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

    public function getMac() {
        return $this->mac;
    }

    public function getIdVersion() {
        return $this->id_version;
    }

    public function getUltima_actualizacion() {
        return $this->ultima_actualizacion;
    }
    
    public function getUsuarios_asignados() {
        return $this->usuarios_asignados;
    }

    public function getId_tes_estado_tableta() {
        return $this->id_tes_estado_tableta;
    }

    public function getId_tipo_censo() {
        return $this->id_tipo_censo;
    }

    public function getId_asu_um() {
        return $this->id_asu_um;
    }
    
    public function setId($id)
    {
        return $this->id = $id;
    }

    public function setMac($mac) {
        $this->mac = $mac;
    }

    public function setIdVersion($id_version) {
        $this->id_version = $id_version;
    }

    public function setUltima_actualizacion($ultima_actualizacion) {
        $this->ultima_actualizacion = $ultima_actualizacion;
    }
    
    public function setUsuarios_asignados($usuarios_asignados) {
        $this->usuarios_asignados = $usuarios_asignados;
    }

    public function setId_tes_estado_tableta($id_tes_estado_tableta) {
        $this->id_tes_estado_tableta = $id_tes_estado_tableta;
    }

    public function setId_tipo_censo($id_tipo_censo) {
        $this->id_tipo_censo = $id_tipo_censo;
    }

    public function setId_asu_um($id_asu_um) {
        $this->id_asu_um = $id_asu_um;
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
        
        $data['mac'] = trim($this->mac);
        $data['usuarios_asignados'] = 0;
        $data['id_tes_estado_tableta'] = 1;

        $result = $this->db->insert('tes_tableta', $data);

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
        $data = array();
        
        $data['mac'] = $this->mac;
        
        if( !empty($this->id_tes_estado_tableta) )
            $data['id_tes_estado_tableta'] = $this->id_tes_estado_tableta;
        
        if( !empty($this->id_version) )
            $data['id_version'] = $this->id_version;

        if( !empty($this->ultima_actualizacion) )
            $data['ultima_actualizacion'] = $this->ultima_actualizacion;
        
        if( !empty($this->usuarios_asignados) )
            $data['usuarios_asignados'] = $this->usuarios_asignados;
        
        if( !empty($this->id_tipo_censo) )
            $data['id_tipo_censo'] = $this->id_tipo_censo;

        if( !empty($this->id_asu_um) )
            $data['id_asu_um'] = $this->id_asu_um;

        $id = is_null($id) ? $this->id : $id;
        $result = $this->db->update('tes_tableta', $data, array('id' => $id));

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'No se puede actualizar el registro';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else {
            // Obtiene el id asignado a la ultima inserción
            $this->id = $this->db->insert_id();
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
                $result = $this->db->delete('tes_tableta', array('id' => $idx));

                if(empty($result)) {
                    $this->error = true;
                    $this->msg_error_usr = 'No se puede eliminar el registro';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception();
                }
            }
        } else {
            // Eliminar un solo registro
            $result = $this->db->delete('tes_tableta', array('id' => $id));

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
        
        $this->db->select('tes_tableta.*, sis_estado_tableta.descripcion AS status, tes_tipo_censo.descripcion AS tipo_censo');
        $this->db->from('tes_tableta');
        $this->db->join('sis_estado_tableta', 'tes_tableta.id_tes_estado_tableta = sis_estado_tableta.id', 'left');
        $this->db->join('tes_tipo_censo', 'tes_tableta.id_tipo_censo = tes_tipo_censo.id', 'left');
        $this->db->where('tes_tableta.id', $id);
        
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
            $this->mac = $result->mac;
            $this->id_version = $result->id_version;
            $this->ultima_actualizacion = $result->ultima_actualizacion;
            $this->usuarios_asignados = $result->usuarios_asignados;
            $this->id_tes_estado_tableta = $result->id_tes_estado_tableta;
            $this->id_tipo_censo = $result->id_tipo_censo;
            $this->id_asu_um = $result->id_asu_um;
        }

        return $result;
    }
    
    /**
     * Obtiene los datos del registro la MAC especificada
     *
     * @access public
     * @param  int $mac        Direccion MAC
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getByMac($mac)
    {
        $result = false;
        
        $this->db->select('tes_tableta.*, sis_estado_tableta.descripcion AS status, tes_tipo_censo.descripcion AS tipo_censo');
        $this->db->from('tes_tableta');
        $this->db->join('sis_estado_tableta', 'tes_tableta.id_tes_estado_tableta = sis_estado_tableta.id', 'left');
        $this->db->join('tes_tipo_censo', 'tes_tableta.id_tipo_censo = tes_tipo_censo.id', 'left');
        $this->db->where('tes_tableta.mac', $mac);
        
        $query = $this->db->get();
        $result = $query->row();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        }

        if(!empty($result)) {
            $this->id = $result->id;
            $this->mac = $result->mac;
            $this->id_version = $result->id_version;
            $this->ultima_actualizacion = $result->ultima_actualizacion;
            $this->usuarios_asignados = $result->usuarios_asignados;
            $this->id_tes_estado_tableta = $result->id_tes_estado_tableta;
            $this->id_tipo_censo = $result->id_tipo_censo;
            $this->id_asu_um = $result->id_asu_um;
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
    public function getAll($offset = null, $row_count = null)
    {
        $result = 0;
        
        $this->db->select('tes_tableta.*, sis_estado_tableta.descripcion AS status, tes_tipo_censo.descripcion AS tipo_censo');
        $this->db->from('tes_tableta');
        $this->db->join('sis_estado_tableta', 'tes_tableta.id_tes_estado_tableta = sis_estado_tableta.id', 'left');
        $this->db->join('tes_tipo_censo', 'tes_tableta.id_tipo_censo = tes_tipo_censo.id', 'left');
        
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
     * en caso de existir filtros, estos son aplicados a la consulta
     *
     * @access public
     * @return int
     */
    public function getNumRows()
    {
        $result = 0;

        $result = $this->db->count_all_results('tes_tableta');

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