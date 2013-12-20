<?php

/**
 * Modelo Tipo_censo
 *
 * @package    TES
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-12-02
 */
class Tipo_censo_model extends CI_Model
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
    private $descripcion;


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

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function setDescripcion($descripcion) {
        $this->mac = $descripcion;
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
     * Obtiene los datos del registro que tiene el ID especificado
     *
     * @access public
     * @param  int $id        Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getById($id)
    {
        $result = false;
        
        $query = $this->db->get_where('tes_tipo_censo', array('id' => $id));
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
        }

        return $result;
    }

    /**
     * Obtiene todos los registros de la tabla
     *
     * @access public
     * @return array object   Devuelve un arreglo de objetos obtenidos de la base de datos
     */
    public function getAll()
    {
        $result = 0;
        
        $query = $this->db->get('tes_tipo_censo');
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
    
}
?>