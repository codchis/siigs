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
     * @var    varchar
     */
    public $bcg;
    
    /**
     * @access public
     * @var    varchar
     */
    public $hepaB1;
    /**
     * @access public
     * @var    varchar
     */
    public $hepaB2;
    /**
     * @access public
     * @var    varchar
     */
    public $hepaB3;
    /**
     * @access public
     * @var    varchar
     */
    public $penta1;
    /**
     * @access public
     * @var    varchar
     */
    public $penta2;
    /**
     * @access public
     * @var    varchar
     */
    public $penta3;
    /**
     * @access public
     * @var    varchar
     */
    public $penta4;
    /**
     * @access public
     * @var    varchar
     */
    public $dptR;
    /**
     * @access public
     * @var    varchar
     */
    public $srp1;
    /**
     * @access public
     * @var    varchar
     */
    public $srp2;
    /**
     * @access public
     * @var    varchar
     */
    public $rota1;
    /**
     * @access public
     * @var    varchar
     */
    public $rota2;
    /**
     * @access public
     * @var    varchar
     */
    public $rota3;
    /**
     * @access public
     * @var    varchar
     */
    public $neumo1;
    /**
     * @access public
     * @var    varchar
     */
    public $neumo2;
    /**
     * @access public
     * @var    varchar
     */
    public $neumo3;
    /**
     * @access public
     * @var    varchar
     */
    public $influenza1;
    /**
     * @access public
     * @var    varchar
     */
    public $influenza2;
    /**
     * @access public
     * @var    varchar
     */
    public $influenzaR;

    
    public function __construct()
	{
		parent::__construct();
	}
    
}
?>