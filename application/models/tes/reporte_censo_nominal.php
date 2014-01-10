<?php

/**
 * Modelo Reporte_censo_nominal
 *
 * @package    TES
 * @subpackage Modelo
 * @author     Rogelio
 * @created    2013-01-08
 */
class Reporte_censo_nominal extends CI_Model
{
    /**
     * @access public
     * @var    varchar
     */
    public $apellido_paterno;

    /**
     * @access public
     * @var    varchar
     */
    public $apellido_materno;

    /**
     * @access public
     * @var    varchar
     */
    public $nombre;
    
    /**
     * @access public
     * @var    varchar
     */
    public $domicilio;

    /**
     * @access public
     * @var    varchar
     */
    public $curp;
    
    /**
     * @access public
     * @var    varchar
     */
    public $fecha_nacimiento;
    
    /**
     * @access public
     * @var    varchar
     */
    public $sexo;
    
    /**
     * @access public
     * @var    array
     */
    public $vacunas;
    
    public function __construct()
	{
		parent::__construct();
	}
    
}
?>