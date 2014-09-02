<?php 
/**
 * Controlador Objeto
 *
 * @package		Libreria
 * @subpackage	Controlador
 * @author     	Eliecer
 * @created		2013-12-10
 */ 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Obtenercurp extends CI_Controller 
{
    /**
     *Arreglo con los estados y sus abreviaturas, sirven para el cálculo de la CURP
     * @var $estados
     */
	public $estados=
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
				"NACIDO EN EL EXTRANJERO"=>"NE"
			)
		);
        
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	/**
	 *
	 * Consulta si la curp existe en la base de datos de la condusef
	 * 
	 * @param		string 		$paterno        Apellido paterno de la persona 
	 * @param		string 		$materno        Apellido materno
	 * @param		string 		$nombre         Nombre o nombres
	 * @param		int 		$dia            Dia de nacimiento
	 * @param		int 		$mes            Mes de nacimiento
	 * @param		int 		$year           Año de nacimiento
	 * @param		string 		$sexo           Sexo
	 * @param		string 		$estado         Lugar de nacimiento
	 * @param		string 		$regresar       Tipo de retorno =1 return array !=1 json
	 *
	 * @return 		echo
	 */
	public function curp($paterno,$materno,$nombre,$dia,$mes,$year,$sexo,$estado,$regresar="")
	{
		$estados=$this->estados;
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
		
		$edo=$estados[0][$estado];
		if ($ap!=""&&$am!=""&&$na!=""&&$d!=""&&$m!=""&&$y!=""&&$se!=""&&$edo!="")
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://consultas.curp.gob.mx/CurpSP/curp11.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A&codigo=bf139");
			
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: JSESSIONID=XrQFT2YSf8BMmwnbJ7HyFlnfttYcjqp3dtJDjQ7HM2NRz84GGW12!-767651644"));
			
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)");
			$html = curl_exec($ch);
			curl_close($ch);
                        
                        echo "http://consultas.curp.gob.mx/CurpSP/curp11.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A";
                        echo $html;
			
			$pos=stripos($html,'<td class="TablaTitulo2"><span class="NotaBlanca">Curp</span></td>
	<td><b class="Nota">');
			$t=34;
			$html=substr($html,$pos,strlen($html)-$pos);
			
                        //echo "http://consultas.curp.gob.mx/CurpSP/curp11.do?strPrimerApellido=$ap&strSegundoAplido=$am&strNombre=$na&strdia=$d&strmes=$m&stranio=$y&sSexoA=$se&sEntidadA=$edo&rdbBD=myoracle&strTipo=A";
                        //print($html);
                        
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
			$if=str_replace("\r","",$if);
			$if=str_replace("\n","",$if);
			$if=str_replace("\t","",$if);	
			
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
					"curpo"=>$cp,
					"informacion"=>utf8_encode(trim($if))					
				)
			);
			if(!stripos($cu,'Curp')&&!stripos($cu,'ink')&&!stripos($cu,"<"))
			{
				if($regresar==1)
					return $array;
				else
					echo json_encode($array);
			}
			
		}
	}
        
	/**
	 *
	 * Calcula la curp y el rfc con los datos proporcionados
	 * 
	 * @param		string 		$paterno        Apellido paterno de la persona 
	 * @param		string 		$materno        Apellido materno
	 * @param		string 		$nombre         Nombre o nombres
	 * @param		int 		$dia            Dia de nacimiento
	 * @param		int 		$mes            Mes de nacimiento
	 * @param		int 		$year           Año de nacimiento
	 * @param		string 		$sexo           Sexo
	 * @param		string 		$estado         Lugar de nacimiento
	 * @param		string 		$regresar       Tipo de retorno =1 return array !=1 json
	 *
	 * @return 		echo
	 */
	public function calcular_curp($paterno,$materno,$nombre,$dia,$mes,$year,$sexo,$estado,$regresar="")
	{
		$estados=$this->estados;
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
		
		$edo=$estados[0][$estado];
		if ($ap!=""&&$am!=""&&$na!=""&&$d!=""&&$m!=""&&$y!=""&&$se!=""&&$edo!="")
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.consisa.com.mx/calcula.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,"PATERNO=$ap&MATERNO=$am&NOMBRES=$na&DIA=$d&MES=$m&ANIO=$y&SEXO=$se&ESTADO=$edo");
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)");
			$html = curl_exec($ch);
			curl_close($ch);
                        //modificados los valores de offset para recortar los TAGS del DOM 
                        //correspondientes a RFC y CURP (Podría cambiar con el paso del tiempo)
			$cur=substr($html,stripos($html,'CURP      </span>')+(160),18);
			$rfc=substr($html,stripos($html,'RFC      </span>')+(159),13);
			$array=
			array(
				array(
					"curp"=>$cur,
					"rfc"=>$rfc,
				)
			);
			if(strlen($cur)>10&&!stripos($cur,"<"))
			{
				if($regresar==1)
					return $array;
				else
					echo json_encode($array);
			}			
		}
	}
        
        /**
	 *
	 * Calcula la curp y el rfc con los datos proporcionados
	 * 
	 * @param		string 		$paterno        Apellido paterno de la persona 
	 * @param		string 		$materno        Apellido materno
	 * @param		string 		$nombre         Nombre o nombres
	 * @param		int 		$dia            Dia de nacimiento
	 * @param		int 		$mes            Mes de nacimiento
	 * @param		int 		$year           Año de nacimiento
	 * @param		string 		$sexo           Sexo
	 * @param		string 		$estado         Lugar de nacimiento
	 * @param		string 		$regresar       Tipo de retorno =1 return array !=1 json
	 *
	 * @return 		echo
	 */
	public function calculacurp($paterno,$materno,$nombre,$dia,$mes,$year,$sexo,$estado,$regresar="")
	{
		$estados=$this->estados;
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
		
		$edo=$estados[0][$estado];
		if ($ap!=""&&$am!=""&&$na!=""&&$d!=""&&$m!=""&&$y!=""&&$se!=""&&$edo!="")
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://losimpuestos.com.mx/rfc/calcular-rfc.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,"paterno=$ap&materno=$am&nombre=$na&dia=$d&mes=$m&anno=$y&sexo=$se&entidad=$edo");
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6 (.NET CLR 3.5.30729)");
			$html = curl_exec($ch);
			curl_close($ch);

                        //modificados los valores de offset para recortar los TAGS del DOM 
                        //correspondientes a RFC y CURP (Podría cambiar con el paso del tiempo)
			$infoinicio=substr($html,stripos($html,'<table>'));
			$info=substr($infoinicio,0,stripos($infoinicio,'</table>'));
                        $info = str_replace(' ', '', $info);
                        
                        $rfc = substr($info,  stripos($info, '<strong>RFC</strong>'));
                        $cur = substr($info,  stripos($info, '<strong>CURP</strong>'));
                        
                        $rfc = substr($rfc, 0,stripos($rfc,'</span></strong>'));
                        $cur = substr($cur, 0,stripos($cur,'</span></strong>'));
                        
                        $rfc = preg_replace('/[^ A-Za-z0-9_-ñÑ]/', '', $rfc);
                        $cur = preg_replace('/[^ A-Za-z0-9_-ñÑ]/', '', $cur);
                        
                        $replaces = array(
                            'strong' => '',
                            'td' => '',
                            'RFC'=>'',
                            'CURP'=>'',
                            'span' => '',
                            'style' => '',
                            'color'=>'',
                            'f00' => ''
                        );
                        
                        $rfc = str_replace(array_keys($replaces),array_values($replaces), $rfc);
                        $cur = str_replace(array_keys($replaces),array_values($replaces), $cur);
                                                
			$array=
			array(
				array(
					"curp"=>$cur,
					"rfc"=>$rfc,
				)
			);
                      
			if(strlen($cur)>10&&!stripos($cur,"<"))
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