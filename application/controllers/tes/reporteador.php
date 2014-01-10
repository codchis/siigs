<?php
/**
 * Controlador Reporteador
 *
 * @package		TES
 * @subpackage	Controlador
 * @author     	Rogelio
 * @created		2013-12-20
 */
class Reporteador extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->helper('url');
			$this->load->model(DIR_TES.'/Reporteador_model');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}

	/**
	 * Visualiza los reportes existentes
	 *
	 * @access		public
	 * @return 		void
	 */
	public function index()
	{
		try{
			if (empty($this->Reporteador_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Reportes';
			$this->load->helper('form');
			$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
			
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
			$data['estados'] = (array)$this->ArbolSegmentacion_model->getDataKeyValue(1, 1);

			$array[0] = (array("atributo"=>"Cobertura por Tipo de Biológico","valor"=>"","lista"=>"0"));
			$array[1] = (array("atributo"=>"Concentrado de Actividades","valor"=>"","lista"=>"1"));
			$array[2] = (array("atributo"=>"Seguimiento RV-1 y RV-5 a menores de 1 año","valor"=>"","lista"=>"2"));
			$array[3] = (array("atributo"=>"Censo Nominal","valor"=>"","lista"=>"3"));
			$array[4] = (array("atributo"=>"Esquemas Incompletos","valor"=>"","lista"=>"4"));
			$data['datos']=$array;
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}
 		$this->template->write_view('content',DIR_TES.'/reporteador/index', $data);
 		$this->template->render();

	}
	
	public function view($op, $title, $nivel, $id, $fecha = '')
	{
		try{
			if (empty($this->Reporteador_model))
				return false;
            
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
            
            $array=array();
			$data['title'] = $title;
            $data['datos'] = $array;

			if($op==0) {
                $this->load->helper('formatFecha');
                $this->load->model(DIR_TES.'/Reporte_cobertura_biologico');
				$array = $this->Reporteador_model->getCoberturaBiologicoListado($nivel, $id, $fecha);
                $data['headTable'] = '<thead>
                        <tr>
                            <th rowspan="3">Grupo de edad</th>
                            <th colspan="3">Población</th>
                            <th colspan="14">Total de esquemas completos por biológico</th>
                            <th rowspan="2" colspan="3">Esquemas completos</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Oficial</th>
                            <th rowspan="2">Nominal</th>
                            <th rowspan="2">% Conc.</th>
                            <th colspan="2">BCG</th>
                            <th colspan="2">Antihepatitis B</th>
                            <th colspan="2">DPaT + VIP + Hib</th>
                            <th colspan="2">Antineumococica</th>
                            <th colspan="2">Antirotavirus</th>
                            <th colspan="2">Tripe viral SRP</th>
                            <th colspan="2">DPT</th>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Cob.</th>
                            <th>Total</th>
                            <th>% Of.</th>
                            <th>% Nom.</th>
                        </tr></thead>';
            }
			if($op==1)
				$array=$this->Reporteador_model->getConcentradoActividades($nivel, $id, $fecha);
			if($op==2)
				$array=$this->Reporteador_model->getSeguimientoRV1RV5($nivel, $id, $fecha);
			if($op==3){
				$this->load->model(DIR_TES.'/Reporte_censo_nominal');
				$array = $this->Reporteador_model->getCensoNominal($nivel, $id, $th);
				$data['headTable'] = $th;
			}
			if($op==4)
				$array=$this->Reporteador_model->getEsquemasIncompletos($nivel, $id);
				
			$data['datos'] = $array;
			//var_dump($array);
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['infoclass'] = 'error';
		}
		
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
        $this->template->write('ajustaAncho',1,true);
		$this->template->write_view('content',DIR_TES.'/reporteador/reporte_view', $data);
		$this->template->render();
	}
}