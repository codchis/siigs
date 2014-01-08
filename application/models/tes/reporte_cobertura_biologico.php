<?php

/**
 * Modelo Reporte_cobertura_biologico
 *
 * @package    TES
 * @subpackage Modelo
 * @author     Pascual
 * @created    2013-01-07
 */
class Reporte_cobertura_biologico extends CI_Model
{
    /**
     * @access public
     * @var    varchar
     */
    public $grupo_etareo;

    /**
     * @access public
     * @var    int
     */
    public $pob_oficial;

    /**
     * @access public
     * @var    int
     */
    public $pob_nominal;
    
    /**
     * @access public
     * @var    int
     */
    public $concordancia;

    /**
     * @access public
     * @var    int
     */
    public $bcg_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $bcg_cob;
    
    /**
     * @access public
     * @var    int
     */
    public $hepB_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $hepB_cob;
    
    /**
     * @access public
     * @var    int
     */
    public $penta_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $penta_cob;
    
    /**
     * @access public
     * @var    int
     */
    public $neumo_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $neumo_cob;
    
    /**
     * @access public
     * @var    int
     */
    public $rota_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $rota_cob;
    
    /**
     * @access public
     * @var    int
     */
    public $srp_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $srp_cob;
    
    /**
     * @access public
     * @var    int
     */
    public $dpt_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $dpt_cob;
    
    /**
     * @access public
     * @var    float
     */
    public $esq_comp_tot;
    
    /**
     * @access public
     * @var    float
     */
    public $esq_comp_oficial;
    
    /**
     * @access public
     * @var    float
     */
    public $esq_comp_nominal;
    
    public function __construct()
	{
		parent::__construct();
	}
    
}
?>