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
    public $hepB1;
    
    /**
     * @access public
     * @var    int
     */
    public $hepB2;
    
    /**
     * @access public
     * @var    int
     */
    public $hepB3;
    
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
    public $penta1;
    
    /**
     * @access public
     * @var    int
     */
    public $penta2;
    
    /**
     * @access public
     * @var    int
     */
    public $penta3;
    
    /**
     * @access public
     * @var    int
     */
    public $penta4;
    
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
    public $neumo1;
    
    /**
     * @access public
     * @var    int
     */
    public $neumo2;
    
    /**
     * @access public
     * @var    int
     */
    public $neumo3;
    
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
     * @var    int
     */
    public $rota1;
    
    /**
     * @access public
     * @var    int
     */
    public $rota2;
    
    /**
     * @access public
     * @var    int
     */
    public $rota3;
    
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
    
    public function calConcordancia() {
        $this->concordancia     = $this->pob_oficial ? round($this->pob_nominal/$this->pob_oficial, 2) : 0;
        $this->bcg_cob          = $this->pob_oficial ? round($this->bcg_tot/$this->pob_oficial, 2) : 0;
        $this->hepB_cob         = $this->pob_oficial ? round($this->hepB_tot/$this->pob_oficial, 2) : 0;
        $this->penta_cob        = $this->pob_oficial ? round($this->penta_tot/$this->pob_oficial, 2) : 0;
        $this->neumo_cob        = $this->pob_oficial ? round($this->neumo_tot/$this->pob_oficial, 2) : 0;
        $this->rota_cob         = $this->pob_oficial ? round($this->rota_tot/$this->pob_oficial, 2) : 0;
        $this->srp_cob          = $this->pob_oficial ? round($this->srp_tot/$this->pob_oficial, 2) : 0;
        $this->dpt_cob          = $this->pob_oficial ? round($this->dpt_tot/$this->pob_oficial, 2) : 0;
        $this->esq_comp_oficial = $this->pob_oficial ? round($this->esq_comp_tot/$this->pob_oficial, 2) : 0;
        $this->esq_comp_nominal = $this->pob_nominal ? round($this->esq_comp_tot/$this->pob_nominal, 2) : 0;
    }
    
}
?>