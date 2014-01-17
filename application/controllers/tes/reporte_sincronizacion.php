<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controller Usuario
 *
 * @package     TES
 * @subpackage  Controlador
 * @author     	Eliecer
 * @created     2013-12-17
 */
class Reporte_sincronizacion extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		try
		{
			$this->load->helper('url');
			$this->load->helper('date');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}
	
	/**
	 *Este es el metodo por default, obtiene el listado de las perosnas
	 *se recibe el parametro $pag de tipo int que representa la paginacion
	 *
	 */
	public function index()
	{
		try{
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			if (empty($this->Reporte_sincronizacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			
			$data['title'] = 'Reporte Sincronización';
			
			$ttr=$this->Reporte_sincronizacion_model->getCount("tes_tableta");
			$array[0] = (array("atributo"=>"Total de tabletas registradas","valor"=>$ttr,"lista"=>"0"));
			
			$ttsa=$this->Reporte_sincronizacion_model->getCount("", "select * from tes_tableta where id_tes_estado_tableta in(1)");
			$array[1] = (array("atributo"=>"Total de tabletas sin asignar","valor"=>$ttsa,"lista"=>"1"));
			
			$td=$this->Reporte_sincronizacion_model->getCount("", "select * from tes_tableta where id_tes_estado_tableta in(4)");
			$array[2] = (array("atributo"=>"Porcentaje de tabletas desactualizadas","valor"=>number_format(($td*100)/$ttr,"2"),"lista"=>"2"));
			
			$tir=$this->Reporte_sincronizacion_model->getCount("", "select * from tes_tableta where id_tes_estado_tableta in(5,6)");
			$array[3] = (array("atributo"=>"Porcentaje de tabletas inactivas o en reparación","valor"=>number_format(($tir*100)/$ttr,"2"),"lista"=>"3"));
			
			$tut=$this->Reporte_sincronizacion_model->getCount("","select distinct(id_asu_um) from tes_tableta");
			$array[4] = (array("atributo"=>"Total de UM con tabletas","valor"=>$tut,"lista"=>"4"));
			
			$us=$this->Reporte_sincronizacion_model->getCount("","select * from tes_tableta where id_tes_estado_tableta in(2,3)");
			$array[5] = (array("atributo"=>"Porcentaje de tabletas sincronizadas","valor"=>number_format(($us*100)/$ttr,"2"),"lista"=>"5"));
			
			$array[6] = (array("atributo"=>"Total de tabletas sincronizadas","valor"=>$us,"lista"=>"6"));
			
			$array[7] = (array("atributo"=>"Porcentaje de tabletas desincronizadas","valor"=>number_format(100-(($us*100)/$ttr),"2"),"lista"=>"7"));
			
			$ttd=$this->Reporte_sincronizacion_model->getCount("","select * from tes_tableta where id_tes_estado_tableta not in(2,3)");
			$array[8] = (array("atributo"=>"Total de tabletas desincronizadas","valor"=>$ttd,"lista"=>"8"));
			
			$tpn=$this->Reporte_sincronizacion_model->getCount("","select distinct(id_persona)  from tes_pendientes_tarjeta ");
			$array[9] = (array("atributo"=>"Total de pacientes que no llevan su tes sincrinizada con la plataforma","valor"=>$tpn,"lista"=>"9"));
			
			$tpt=$this->Reporte_sincronizacion_model->getCount("tes_pendientes_tarjeta");
			$array[10] = (array("atributo"=>"Total de controles no registrados en la tes","valor"=>$tpt,"lista"=>"10"));
			
			$version=$this->Reporte_sincronizacion_model->get_version();
			$array[11] = (array("atributo"=>"Ultima version de la app","valor"=>$version[0]->version,"lista"=>"11"));
			$array[12] = (array("atributo"=>"Fechas de la ultima version de la app","valor"=>$version[0]->fecha_liberacion,"lista"=>"12"));
			
			$data['datos']=$array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
 		$this->template->write_view('content',DIR_TES.'/reporteador/sincronizacion', $data);
 		$this->template->render();
	}
	
	public function view($op,$title)
	{
		try{
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			if (empty($this->Reporte_sincronizacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			
			$data['title'] = $title;
			$array=array();
			$campos="t.id as No,mac as Mac, tv.version+' -> '+tv.descripcion as Version, et.descripcion as Estado, tc.descripcion as 'Tipo Censo', asu.descripcion as 'Unidad Medica'";
			$join = "left join tes_version tv on tv.id= t.id_version
					 left join sis_estado_tableta et on et.id=t.id_tes_estado_tableta
					 left join tes_tipo_censo tc on tc.id=t.id_tipo_censo
					 left join asu_arbol_segmentacion asu on asu.id=t.id_asu_um";
			if($op==0)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join");
			
			if($op==1)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN ('1')");	
			
			if($op==2)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN ('1')");	
			
			if($op==3)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN (5,6)");
			
			if($op==4)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE t.id_asu_um!=''");
			
			if($op==5||$op==6)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta IN (3,2)");
			
			if($op==7||$op==8)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT $campos FROM tes_tableta t $join WHERE id_tes_estado_tableta NOT IN (3,2)");	
			
			if($op==9)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT distinct(*) FROM tes_pendientes_tarjeta");
			
			if($op==10)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT * FROM tes_pendientes_tarjeta");
			
			if($op==11)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT * FROM tes_version");
			
			if($op==12)
			$array=$this->Reporte_sincronizacion_model->getListado("SELECT * FROM tes_version order by fecha_liberacion DESC");		
			
			$data['datos']=$array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
		$this->template->write('sala_prensa','',true);
		$this->template->write_view('content',DIR_TES.'/reporteador/reporte_view', $data);
 		$this->template->render();
	}
	public function lote()
	{
		try{
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
			if (empty($this->Reporte_sincronizacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Seguimiento de lotes de vacunación';
			$this->load->helper('form');
			$unidad="";
			$desde=date('Y-m-d H:i:s', strtotime($this->input->post('desde')));
			if($this->input->post('hasta')=="")
				$hasta=date("Y-m-d H:i:s");
			else
				$hasta=date('Y-m-d H:i:s', strtotime($this->input->post('hasta')));
			$lotes=$this->input->post('lote');
			
			$jurid=$this->input->post('juris');
			$munic=$this->input->post('municipios');
			$local=$this->input->post('localidades');
			$ums  =$this->input->post('ums');
			
			if($ums!="")
				$unidad = "AND id_asu_um='$ums'";
				
			if($local!="")
				$unidad = "AND id_asu_um IN (
									SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$local.")"; // ums por loc
			if($munic!="")
				$unidad = "AND id_asu_um IN (
								SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
								SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$munic.") )"; // locs por mpio
			if($jurid!="")
				$unidad = "AND id_asu_um IN (
							SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
							SELECT id FROM asu_arbol_segmentacion WHERE id_padre IN (
							SELECT id FROM asu_arbol_segmentacion WHERE id_padre=".$jurid.") ) )"; // mpios por juris
							
			if($lotes=="")$mas="OR codigo_barras IS NULL";else $mas="";
			$consulta="select distinct(codigo_barras) from cns_control_vacuna where (codigo_barras like '%$lotes%' $mas) $unidad and (fecha between '$desde' and '$hasta') ";
			
			$count=$this->Reporte_sincronizacion_model->getCount("",$consulta);
			$array=$this->Reporte_sincronizacion_model->getListado($consulta);
			$i=0;$midato=array();
			foreach($array as $x)
			{
				$consulta="select * from cns_control_vacuna where codigo_barras ";
				$consultb="select distinct(id_asu_um) from cns_control_vacuna where codigo_barras ";
				$consultc="select distinct(cv.id_vacuna),v.descripcion from cns_control_vacuna cv left join cns_vacuna v on v.id=cv.id_vacuna where cv.codigo_barras";
				$consultd="select distinct(p.id_persona) from cns_control_vacuna cv  left join tes_pendientes_tarjeta p on p.id_persona=cv.id_persona where p.id_persona!='' and cv.codigo_barras ";
				$in="";
				if($x->codigo_barras=="")
				{
					$tipoa="";
					$midato[$i]["lote"]="Sin lote";
					$cantidad=$this->Reporte_sincronizacion_model->getCount("",$consulta." IS NULL");
					$ums=$this->Reporte_sincronizacion_model->getCount("",$consultb." IS NULL");
					$personas=$this->Reporte_sincronizacion_model->getCount("",$consultd." IS NULL");
					
					$tipo1=$this->Reporte_sincronizacion_model->getListado($consultc." IS NULL");
					foreach($tipo1 as $y)
					{
						$tipoa.=$y->descripcion." - ";
					}
					$umsx=$this->Reporte_sincronizacion_model->getListado($consultb." IS NULL");
					
					foreach($umsx as $u)
					{
						$in.=$u->id_asu_um.",";
					}
					$localidades=$this->Reporte_sincronizacion_model->getCount("","select distinct(id_padre) from asu_arbol_segmentacion where id in(".substr($in,0,strlen($in)-1).") and id_padre!=0");
					
					$midato[$i]["tipo"]=substr($tipoa,0,strlen($tipoa)-3);
					$midato[$i]["cantidad"]=$cantidad;
					$midato[$i]["ums"]=$ums;
					$midato[$i]["localidades"]=$localidades;
					$midato[$i]["personas"]=$personas;
					$vacunas=$this->Reporte_sincronizacion_model->getListado("select distinct(id_asu_um) from cns_control_vacuna where codigo_barras IS NULL");
					$dom=$this->ArbolSegmentacion_model->getDescripcionById(array($vacunas[0]->id_asu_um),5);
					$dom=(explode(",",$dom[0]->descripcion));
					$midato[$i]["lugar"]=$dom[count($dom)-1];
				}
				else 
				{
					$tipob="";
					$midato[$i]["lote"]=$x->codigo_barras;
					$cantidad=$this->Reporte_sincronizacion_model->getCount("",$consulta."='".$x->codigo_barras."'");
					$ums=$this->Reporte_sincronizacion_model->getCount("",$consultb."='".$x->codigo_barras."'");
					$personas=$this->Reporte_sincronizacion_model->getCount("",$consultd."='".$x->codigo_barras."'");
					
					$tipo2=$this->Reporte_sincronizacion_model->getListado($consultc."='".$x->codigo_barras."'");
					foreach($tipo2 as $y)
					{
						$tipob.=$y->descripcion." - ";
					}
					
					$umsx=$this->Reporte_sincronizacion_model->getListado($consultb."='".$x->codigo_barras."'");
					
					foreach($umsx as $u)
					{
						$in.=$u->id_asu_um.",";
					}
					$localidades=$this->Reporte_sincronizacion_model->getCount("","select distinct(id_padre) from asu_arbol_segmentacion where id in(".substr($in,0,strlen($in)-1).") and id_padre!=0");
					
					$midato[$i]["tipo"]=substr($tipob,0,strlen($tipob)-3);
					$midato[$i]["cantidad"]=$cantidad;
					$midato[$i]["ums"]=$ums;
					$midato[$i]["localidades"]=$localidades;
					$midato[$i]["personas"]=$personas;
					$vacunas=$this->Reporte_sincronizacion_model->getListado("select distinct(id_asu_um) from cns_control_vacuna where codigo_barras "."='".$x->codigo_barras."'");
					$dom=$this->ArbolSegmentacion_model->getDescripcionById(array($vacunas[0]->id_asu_um),5);
					$dom=(explode(",",$dom[0]->descripcion));
					$midato[$i]["lugar"]=$dom[count($dom)-1];
				}
				$i++;
			}
			$data["count"]=$count;
			$data["datos"]=$midato;
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['jurisdicciones'] = (array)$this->ArbolSegmentacion_model->getDataKeyValue(1, 2);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
 		$this->template->write_view('content',DIR_TES.'/reporteador/lote', $data);
 		$this->template->render();
	}
	
	public function lote_view($lote,$title,$op,$lugar="Chiapas")
	{
		try{
			$this->load->model(DIR_TES.'/Reporte_sincronizacion_model');
			$this->load->model(DIR_SIIGS.'/ArbolSegmentacion_model');
			if (empty($this->Reporte_sincronizacion_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_TES.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$pagina="reporte_view";
			$lote=urldecode($lote);
			$data['title'] = $title." - ".$lote;
			$array=array();
			$consulta="select distinct(id_persona) from cns_control_vacuna where codigo_barras ";
			$consultb="select distinct(id_asu_um) from cns_control_vacuna where codigo_barras ";
			$consultc="select distinct(cv.id_vacuna),v.descripcion from cns_control_vacuna cv left join cns_vacuna v on v.id=cv.id_vacuna where cv.codigo_barras";
			$consultd="select distinct(p.id_persona) from cns_control_vacuna cv  left join tes_pendientes_tarjeta p on p.id_persona=cv.id_persona where p.id_persona!='' and cv.codigo_barras ";
			if($lote=="Sin lote")
			{
				$consulta.=" IS NULL";
				$consultb.=" IS NULL";
				$consultc.=" IS NULL";
				$consultd.=" IS NULL";
			}
			else
			{
				$consulta.="='".$lote."'";
				$consultb.="='".$lote."'";
				$consultc.="='".$lote."'";
				$consultd.="='".$lote."'";
			}

				
			if($op==1)
			{
				$vacunas=$this->Reporte_sincronizacion_model->getListado($consulta);
				foreach($vacunas as $x)
				{
					$result=$this->Reporte_sincronizacion_model->getListado("SELECT distinct curp,nombre, apellido_paterno as paterno, apellido_materno as materno, sexo FROM cns_persona WHERE id='".$x->id_persona."'");	
					foreach($result as $y)
					{
						$array[]=array("Curp"=>$y->curp, "Ap. Paterno"=>$y->paterno, "Ap. Materno"=>$y->materno, "Sexo"=>$y->sexo);
					}
				}
			}
			
			if($op==2)
			{
				$vacunas=$this->Reporte_sincronizacion_model->getListado($consultb);
				foreach($vacunas as $x)
				{
					$dom=$this->ArbolSegmentacion_model->getDescripcionById(array($x->id_asu_um),5);
					$array[]=array("Id"=>$x->id_asu_um, "Unidad Medica"=>$dom[0]->descripcion);
				}
			}
			
			if($op==3||$op==4)
			{
				$in="";$pagina="reporte_map";
				$umsx=$this->Reporte_sincronizacion_model->getListado($consultb);
				if($op==4)
				{
					$id_p="";
					$cns_p=(array)$this->Reporte_sincronizacion_model->getListado($consultd);
					foreach($cns_p as $person)
						$id_p.="'".$person->id_persona."',";
					$id_p=substr($id_p,0,strlen($id_p)-1);
					$umsx=$this->Reporte_sincronizacion_model->getListado("SELECT id_asu_um_tratante AS id_asu_um FROM cns_persona WHERE id IN($id_p) ");
				}
				foreach($umsx as $u)
				{
					$in.=$u->id_asu_um.",";
				}
				$tipo1=$this->Reporte_sincronizacion_model->getListado($consultc);
				$tipoa="";
				foreach($tipo1 as $y)
				{
					$tipoa.=$y->descripcion." - ";
				}
					
				$localidades=$this->Reporte_sincronizacion_model->getListado("select distinct(id_padre) from asu_arbol_segmentacion where id in(".substr($in,0,strlen($in)-1).") and id_padre!=0");
				$arbol=array();
				$mapas=array();
				foreach($localidades as $x)
				{
					$arbol[]=$x->id_padre;					
				}
				$dom=$this->ArbolSegmentacion_model->getDescripcionById($arbol,1);
				$datos=array();
				if($dom)
				foreach($dom as $y)
				{
					$m=explode(",",$y->descripcion);
					$datos[]=array("Id"=>$y->id, "Localidad"=>$m[0], "Municipio"=>$m[1]);
					$latlon=$this->Reporte_sincronizacion_model->getListado("SELECT * FROM asu_georeferencia WHERE id_asu='".$y->id."'");
					if($latlon)
					{
						$descripcion="";
						$consultx=$this->Reporte_sincronizacion_model->getListado("SELECT DISTINCT(id_asu_um) FROM cns_control_vacuna WHERE id_asu_um IN(SELECT id FROM asu_arbol_segmentacion WHERE id_padre='".$y->id."')");
						$consulty=array();$i=0;
						foreach($consultx as $cx)
						{
							$consulty[$i]=$cx->id_asu_um;
							$i++;
						}
						$consultx=implode(",",$consulty);
						
						$mres=$this->Reporte_sincronizacion_model->getListado("select id,descripcion from asu_arbol_segmentacion where id in ($consultx)");
						foreach($mres as $xy) 
						{
							$ccc=$this->Reporte_sincronizacion_model->getCount("","select id_asu_um from cns_control_vacuna where id_asu_um='".$xy->id."'");
							$descripcion.="<tr><td>".$xy->id."</td><td>".$xy->descripcion."</td><td>$ccc</td></tr>";
						}
						$table="<strong>Tipo: </strong>$tipoa<br><table width='300'><tr><th align='left'>No</th><th align='left'>UM.</th><th align='left'>Cant.</th></tr>$descripcion</table>";
						$mapas[]=array(
						"localidad"=>$m[0],
						"lat"=> $latlon[0]->lat_dec,
						"lon"=> $latlon[0]->lon_dec,
						"descripcion"=> $table,
						"imagen"=>"/resources/images/1info.png",
						"icono"=>"/resources/images/1success.png" );
					}
				}
				$data["zoom"]=8;
				$data["lugar"]=$lugar;
				$data["array"]=$mapas;				
				$array=$datos;
			}
			
			$data['datos']=$array;
		}
		catch(Exception $e)
		{
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
		}
		//$this->load->view('usuario/index', $data);
		$this->template->write('header','',true);
		$this->template->write('footer','',true);
		$this->template->write('menu','',true);
		$this->template->write('sala_prensa','',true);
		$this->template->write_view('content',DIR_TES.'/reporteador/'.$pagina, $data);
 		$this->template->render();
	}
}