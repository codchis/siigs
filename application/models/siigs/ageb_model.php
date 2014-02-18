<?php

/**
 * Modelo Ageb
 *
 * @package    SIIGS
 * @subpackage Modelo
 * @author     Geovanni
 * @created    2014-01-31
 */
class Ageb_model extends CI_Model
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
        
//        $sql = "REPLACE INTO 
//                    asu_ageb (id_asu_localidad, ageb,id_asu_um)
//                SELECT  
//                    c.id AS id_asu_localidad,
//                    LPAD(a.ageb,4,'0000') AS ageb,
//                    CASE WHEN
//                        IFNULL(d.id , '') = ''
//                    THEN
//                        a.clues
//                    ELSE
//                        d.id
//                    END 
//                    AS id_asu_um
//                    FROM 
//                        cat_ageb a
//                    JOIN 
//                        cat_localidad b 
//                    ON 
//                        b.id_localidad = a.id_localidad 
//                        AND b.id_municipio = a.id_municipio 
//                        AND a.id_estado = b.id_estado
//                    LEFT OUTER JOIN 
//                        asu_arbol_segmentacion c 
//                    ON 
//                        b.id = c.id_tabla_original 
//                        AND c.grado_segmentacion = 4 
//                        AND c.id_raiz = 1 
//                    LEFT OUTER JOIN 
//                        asu_arbol_segmentacion d 
//                    ON 
//                        d.grado_segmentacion = 5 
//                        AND d.id_raiz = 1 
//                        AND d.id_tabla_original = a.clues";
        
        $sql = "REPLACE INTO 
                    asu_ageb (id_asu_localidad, ageb,id_asu_um)
                SELECT  
                    d.id_padre AS id_asu_localidad,
                    LPAD(a.ageb,4,'0000') AS ageb,
                    d.id AS id_asu_um
                    FROM 
                        cat_ageb a
                    JOIN 
                        cat_localidad b 
                    ON 
                        b.id_localidad = a.id_localidad 
                        AND b.id_municipio = a.id_municipio 
                        AND a.id_estado = b.id_estado
                    JOIN 
                        asu_arbol_segmentacion d 
                    ON 
                        d.grado_segmentacion = 5 
                        AND d.id_raiz = 1 
                        AND d.id_tabla_original = a.clues";

        $result = $this->db->query($sql);

        if( $this->db->_error_number() ) {
            $this->error = true;
            $this->msg_error_usr = 'Error al procesar los ageb';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception('('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message());
        }
        
        return $result;
    }
    
    	/**
	 *Devuelve la información de una UM de acuerdo a su localidad y ageb
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $idlocalidad Id del ASU de la localidad
         *@param   string Ageb
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function searchUM($idlocalidad,$ageb)
	{
		$query = $this->db->get_where('asu_ageb', array('id_asu_localidad' => $idlocalidad, 'ageb' => $ageb));

		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la información de la UM por medio de la AGEB y Localidad";
			throw new Exception(__CLASS__);
		}
		else
                {
                    if ($query->num_rows()>0)
                       // if ($query->row()->id_asu_um == 0)
                       //     return -1;
                       //     else
                        return $query->row()->id_asu_um;
                    else
                        return -1;
                }
			
	}
        
    	/**
	 *Devuelve una lista de AGEBS de la localidad pasada como parámetro
	 *
	 *@access  public
	 *@return  Object
	 *@param   int $idlocalidad Id del ASU de la localidad
	 * @throws Exception En caso de algun error al consultar la base de datos
	 */
	public function searchageb($idlocalidad,$like)
	{
		$query = $this->db->query("select ageb from asu_ageb where id_asu_localidad = ".$idlocalidad." and ageb like '%".addslashes($like)."%'");
		if (!$query)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			$this->msg_error_usr = "Ocurrió un error al obtener la lista de AGEBs por localidad";
			throw new Exception(__CLASS__);
		}
		else
                {
                    return $query->result();
                }
			
	}
    
}
