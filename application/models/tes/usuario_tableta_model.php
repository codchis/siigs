<?php

/**
 * Modelo Estado_tableta
 *
 * @author     Pascual
 * @created    2013-11-27
 */
class Usuario_tableta_model extends CI_Model
{
    /**
     * @access private
     * @var    int(11)
     */
    private $id_tes_tableta;

    /**
     * @access private
     * @var    int(11)
     */
    private $id_usuario;


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

    public function getTableta()
    {
        return $this->id_tes_tableta;
    }

    public function getUsuario() {
        return $this->id_usuario;
    }

    public function setTableta($id_tes_tableta)
    {
        return $this->id_tes_tableta = $id_tes_tableta;
    }

    public function setUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
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
     * Obtiene el id de todos los usuarios asignados a la tableta especificada
     *
     * @access public
     * @param  int $id_tes_tableta Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean      Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getUsuariosByTableta($id_tes_tableta = null)
    {
        $tableta = is_null($id_tes_tableta) ? $this->id_tes_tableta : $id_tes_tableta;
        
        $query = $this->db->get_where('tes_usuario_x_tableta', array('id_tes_tableta' => $tableta));
        $result = $query->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        }

        return $result;
    }

    /**
     * Obtiene el id de todas las tabletas relacionadas con el usuario especificado
     *
     * @access public
     * @param  int $id_usuario Si no se establece el valor de ID, se toma el valor del objeto actual
     * @return object|boolean  Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getTabletasByUsuario($id_usuario = null)
    {
        $usuario = is_null($id_usuario) ? $this->id_usuario : $id_usuario;
        
        $query = $this->db->get_where('tes_usuario_x_tableta', array('id_usuario' => $usuario));
        $result = $query->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        }

        return $result;
    }
    
}
?>