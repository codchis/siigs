<?php

/**
 * Modelo Hemoglobina
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2014-07-21
 */
class Hemoglobina_model extends CI_Model
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
     * Inserta los registros contenidos en la tabla cat_georeferencia
     * a la tabla asu_georeferencia
     *
     * @access public
     * @return boolean false Si no se ejecutó la inserción, true si se ejecutó la inserción
     */
    public function process()
    {
        $result = false;
        $sql = 'REPLACE INTO 
                    asu_hemoglobina_altitud (id_localidad_asu, altitud, mujer_no_embarazada, mujer_embarazada_ninio_6_59_meses)
                SELECT 
                    b.id AS id_localidad_asu,
                    cat_hemoglobina_altitud.altitud, 
                    cat_hemoglobina_altitud.mujer_no_embarazada, 
                    cat_hemoglobina_altitud.mujer_embarazada_ninio_6_59_meses
                FROM 
                    cat_localidad a 
                JOIN 
                    asu_arbol_segmentacion b 
                ON  a.id = b.id_tabla_original AND 
                    b.grado_segmentacion = 4 AND
                    b.id_raiz = 1
                JOIN
                    cat_hemoglobina_altitud
                ON
                    b.id_raiz=1 AND 
                    a.id_estado = cat_hemoglobina_altitud.id_estado AND 
                    a.id_municipio = cat_hemoglobina_altitud.id_municipio AND 
                    a.id_localidad = cat_hemoglobina_altitud.id_localidad';

        $result = $this->db->query($sql);

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'Error al procesar la tabla georeferencia';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception('('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message());
        }
        
        return $result;
    }
    
}
