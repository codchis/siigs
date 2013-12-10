<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Obtenercurp extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	public function curp($paterno,$materno,$nombre,$dia,$mes,$year,$sexo,$estado,$regresar)
	{
		$ap=strtoupper($paterno);
		$am=strtoupper($materno);
		$na=strtoupper($nombre);
		
		$d=$dia;
		if($d<10) $d="0".(int)$d;
		$m=$mes;
		if($m<10) $m="0".(int)$m;
		
		$y=$year;
		$se=$sexo;
		$se=strtoupper($se);
		if($se=="HOMBRE"||$se=="MASCULINO"||$se=="M")
			$se="H";
		if($se=="MUJER"||$se=="FEMENINO"||$se=="F")
			$se="M";
		$estado=strtoupper($estado); 
		$estados=
		array(
			array(
				"AGUASCALIENTES"=>"AS",
				"BAJA CALIFORNIA NTE"=>"BC",
				"BAJA CALIFORNIA NORTE"=>"BC",
				"BAJA CALIFORNIA"=>"BC",
				"BAJA CALIFORNIA SUR"=>"BS",
				"CAMPECHE"=>"CC",
				"COAHUILA"=>"CL",
				"COLIMA"=>"CM",
				"CHIAPAS"=>"CS",
				"CHIHUAHUA"=>"CH",
				"DISTRITO FEDERAL"=>"DF",
				"DURANGO"=>"DG",
				"GUANAJUATO"=>"GT",
				"GUERRERO"=>"GR",
				"HIDALGO"=>"HG",
				"JALISCO"=>"JC",
				"MEXICO"=>"MC",
				"MICHOACAN"=>"MN",
				"MORELOS"=>"MS",
				"NAYARIT"=>"NT",
				"NUEVO LEON"=>"NL",
				"OAXACA"=>"OC",
				"PUEBLA"=>"PL",
				"QUERETARO"=>"QT",
				"QUINTANA ROO"=>"QR",
				"SAN LUIS POTOSI"=>"SP",
				"SINALOA"=>"SL",
				"SONORA"=>"SR",
				"TABASCO"=>"TC",
				"TAMAULIPAS"=>"TS",
				"TLAXCALA"=>"TL",
				"VERACRUZ"=>"VZ",
				"ZACATECAS"=>"ZS",
				"EXTERIOR MEXICANO"=>"SM",
				"NACIDO EN EL EXTRANJERO "=>"NE"
			)
		);
		$edo=$estados[0][$estado];
		if ($ap!=""&&$am!=""&&$na!=""&&$d!=""&&$m!=""&&$y!=""&&$se!=""&&$edo!="")
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://consultas.curp.gob.mx/CurpSP/curp1.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)");
			$html = curl_exec($ch);
			curl_close($ch);
			
			$pos=stripos($html,'<td class="TablaTitulo2"><span class="NotaBlanca">Curp</span></td>
	<td><b class="Nota">');
			$t=34;
			$html=substr($html,$pos,strlen($html)-$pos);
			
			$cu=substr($html,stripos($html,'>Curp')+($t+6),18);
			
			$ap=substr($html,stripos($html,'>Primer Apellido')+($t+17),38);
			$ap=substr($ap,0,stripos($ap,'<'));
	
			$am=substr($html,stripos($html,'>Segundo Apellido')+($t+20),38);
			$am=substr($am,0,stripos($am,'<'));
		
			$na=substr($html,stripos($html,'>Nombre(s)')+($t+11),18);
			$na=substr($na,0,stripos($na,'<'));
	
			$se=substr($html,stripos($html,'>Sexo')+($t+6),18);
			$se=substr($se,0,stripos($se,'<'));
	
			$fn=substr($html,stripos($html,'>Fecha de Nacimiento')+($t+33),12);

			$se=substr($html,stripos($html,'>Sexo')+($t+9),18);
			$se=substr($se,0,stripos($se,'<'));
	
			$nw=substr($html,stripos($html,'>Nacionalidad')+($t+16),18);
			$nw=substr($nw,0,stripos($nw,'<'));
	
			$ed=substr($html,stripos($html,'>Entidad de Nacimiento')+($t+25),28);
			$ed=substr($ed,0,stripos($ed,'<'));
	
			$dc=substr($html,stripos($html,'>Tipo Doc. Probatorio')+($t+25),28);
			$dc=substr($dc,0,stripos($dc,'<'));
	
			$if=substr($html,stripos($html,'<table'),strlen($html)-stripos($html,'</b></td>
		    </tr>
		    </table>')+40);
				
			$cp=substr($html,stripos($html,'>Historicas')+($t+15),18);
			$array=
			array(
				array(
					"curp"=>$cu,
					"paterno"=>$ap,
					"materno"=>$am,
					"nombre"=>$na,
					"nacimiento"=>$fn,
					"sexo"=>$se,
					"nacionalidad"=>$nw,
					"entidad"=>$ed,
					"documeto"=>$dc,
					"informacion"=>utf8_encode(trim($if)),
					"curpo"=>$cp
				)
			);
			if(!stripos($cu,'Curp')&&!stripos($cu,'ink'))
			{
				if($regresar==1)
					return $array;
				else
					echo json_encode($array);
			}
			
		}
	}
}
?>