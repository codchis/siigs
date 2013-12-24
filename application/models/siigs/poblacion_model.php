<?php

/**
 * Modelo Poblacion
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-12-24
 */
class Poblacion_model extends CI_Model
{
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
        
        /*if( !$this->db->conn_id ) {
            throw new Exception ('ERROR: No se puede conectar con la Base de Datos');
        }*/
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
     * Inserta los registros contenidos en la tabla cat_poblacion 
     * a la tabla asu_poblacion
     *
     * @access public
     * @return boolean false Si no se ejecutó la inserción, true si se ejecutó la inserción
     */
    public function process()
    {
        $result = false;
        $sql = 'INSERT INTO 
                    asu_poblacion (id_asu, id_grupo_etareo, ano, poblacion)
                SELECT
                    (SELECT b.id 
                    FROM   cat_municipio a 
                    JOIN   asu_arbol_segmentacion b 
                    ON  a.id = b.id_tabla_original AND 
                        b.grado_segmentacion = 3 
                    WHERE  
                        a.id_estado = cat_poblacion.id_estado AND 
                        a.id_jurisdiccion = cat_poblacion.id_jurisdiccion AND
                        a.id_municipio = cat_poblacion.id_municipio) AS id_asu,
                    (SELECT id 
                    FROM asu_grupo_etareo
                    WHERE RTRIM(LTRIM(LOWER(descripcion))) = RTRIM(LTRIM(LOWER(cat_poblacion.grupo_etareo))) ) AS grupo_etareo,
                    cat_poblacion.ano, 
                    cat_poblacion.poblacion 
                FROM 
                    cat_poblacion';

        $result = $this->db->query($sql);

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'Error al procesar la tabla población';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception('('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message());
        }
        
        return $result;
    }
    
}
