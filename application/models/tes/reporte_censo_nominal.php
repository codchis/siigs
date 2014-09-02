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
     * @var    varchar
     */
    public $apellido_paterno;

    /**
     * @var    varchar
     */
    public $apellido_materno;

    /**
     * @var    varchar
     */
    public $nombre;
    
    /**
     * @var    varchar
     */
    public $domicilio;

    /**
     * @var    varchar
     */
    public $curp;
    
    /**
     * @var    varchar
     */
    public $fecha_nacimiento;

    /**
     * @var    varchar
     */
    public $parto_multiple;
    
    /**
     * @var    varchar
     */
    public $sexo;
    
    /**
     * @var    array
     */
    public $vacunas;
    
    public function __construct()
	{
		parent::__construct();
	}
    
}
?>