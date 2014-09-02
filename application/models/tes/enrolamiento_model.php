<?php
/**
 * Modelo Usuario
 *
 * @package     TES
 * @subpackage  Modelo
 * @author     	Eliecer
 * @created     2013-12-17
 */
class Enrolamiento_model extends CI_Model 
{
	/**
	 * Guarda la instancia del objeto global CodeIgniter
	 * para utilizarlo en la función estática
	 *
	 * @access private
	 * @var    instance
	 */
	private static $CI;
	
	/**
	 * @variables
	 */
	// Basico 
	private $id;
	private $nacionalidad;
   	private $nombre;
   	private $paterno;
   	private $materno;
   	private $lnacimiento;
	private $curp;
	private $sexo;
	private $sangre;
	private $fnacimiento;
	private $tbeneficiario;
	private $parto;
        private $tamiz_neonatal;
        private $precurp;
	
	// civil
   	private $fechacivil;
   	private $lugarcivil;
	private $umt;
	
	// direccion
	private $calle;
	private $referencia;
   	private $colonia;
   	private $localidad;
   	private $numero;
   	private $cp;
	private $telefono;
	private $compania;
	private $celular;
	private $ageb;
	private $sector;
	private $manzana;
	
	// Tipo de beneficiario
	private $afiliacion= array();
	
	// historial de alergias
	private $alergias= array();
	
	// vacunacion
	private $vacuna= array();
	private $fvacuna= array();
	private $codigo_barras= array();
    private $lat_vac= array();
    private $lon_vac= array();
    private $temp_vac= array();
	
	// Consulta
	private $consulta= array();
	private $fconsulta= array();
	private $tconsulta= array();
	
	// Accion nutricional
	private $accion_nutricional= array();
	private $faccion_nutricional= array();
	
	// nutricion
	private $peso= array();
	private $altura= array();
	private $talla= array();
	private $hemoglobina= array();
	private $fnutricion= array();
	private $peri_cefa= array();
	private $fecha_peri_cefa= array();
    
	private $estimulacion_fecha= array();
	private $estimulacion_capacitado= array();
    
    private $sales_fecha= array();
	private $sales_cantidad= array();
	
   	/********************************************
   	 * Estas variables no pertenecen a la tabla *
   	* ******************************************/
   	
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
		
		self::$CI = &get_instance();
		
		$this->load->database();
		if (!$this->db->conn_id)
			throw new Exception("No se pudo conectar a la base de datos");
	}
	
	public function getId()
	{
	    return $this->id;
	}

	public function setId($value) 
	{
		$this->id = $value;
	}
	
	public function getnombre()
	{
	    return $this->nombre;
	}

	public function setnombre($value) 
	{
		$this->nombre = $value;
	}
	
	public function getpaterno()
	{
	    return $this->paterno;
	}

	public function setpaterno($value) 
	{
		$this->paterno = $value;
	}
	
	public function getmaterno()
	{
	    return $this->materno;
	}

	public function setmaterno($value) 
	{
		$this->materno = $value;
	}
	
	public function getlnacimiento()
	{
	    return $this->lnacimiento;
	}

	public function setlnacimiento($value) 
	{
		$this->lnacimiento = $value;
	}
	
	public function getcurp()
	{
	    return $this->curp;
	}

	public function setcurp($value) 
	{
		$this->curp = $value;
	}
	
	public function getsexo()
	{
	    return $this->sexo;
	}

	public function setsexo($value) 
	{
		$this->sexo = $value;
	}
	
	public function getsangre()
	{
	    return $this->sangre;
	}

	public function setsangre($value) 
	{
		$this->sangre = $value;
	}
	
	public function getfnacimiento()
	{
	    return $this->fnacimiento;
	}

	public function setfnacimiento($value) 
	{
		$this->fnacimiento = $value;
	}
	
	public function gettbeneficiario()
	{
	    return $this->tbeneficiario;
	}

	public function settbeneficiario($value) 
	{
		$this->tbeneficiario = $value;
	}
	
	public function getparto()
	{
	    return $this->parto;
	}
        
        public function gettamiz()
	{
	    return $this->tamiz_neonatal;
	}
        
        public function getprecurp()
	{
	    return $this->precurp;
	}
        
        public function setprecurp($value) 
	{
		$this->precurp = $value;
	}

	public function setparto($value) 
	{
		$this->parto = $value;
	}
        
        public function settamiz($value) 
	{
		$this->tamiz_neonatal = $value;
	}
	//tutor
	public function getidtutor()
	{
	    return $this->idtutor;
	}

	public function setidtutor($value) 
	{
		$this->idtutor = $value;
	}
	
	public function getnombreT()
	{
	    return $this->nombreT;
	}

	public function setnombreT($value) 
	{
		$this->nombreT = $value;
	}
	
	public function getpaternoT()
	{
	    return $this->paternoT;
	}

	public function setpaternoT($value) 
	{
		$this->paternoT = $value;
	}
	
	public function getmaternoT()
	{
	    return $this->maternoT;
	}
	public function setmaternoT($value) 
	{
		$this->maternoT = $value;
	}
	
	public function getcurpT()
	{
	    return $this->curpT;
	}

	public function setcurpT($value) 
	{
		$this->curpT = $value;
	}
	
	public function getsexoT()
	{
	    return $this->sexoT;
	}

	public function setsexoT($value) 
	{
		$this->sexoT = $value;
	}
	public function gettelefonoT()
	{
	    return $this->telefonoT;
	}

	public function settelefonoT($value) 
	{
		$this->telefonoT = $value;
	}
	
	public function getcompaniaT()
	{
	    return $this->companiaT;
	}

	public function setcompaniaT($value) 
	{
		$this->companiaT = $value;
	}
	
	public function getcelularT()
	{
	    return $this->celularT;
	}

	public function setcelularT($value) 
	{
		$this->celularT = $value;
	}
	//
	public function getfechacivil()
	{
	    return $this->fechacivil;
	}

	public function setfechacivil($value) 
	{
		$this->fechacivil = $value;
	}
	
	public function getlugarcivil()
	{
	    return $this->lugarcivil;
	}

	public function setlugarcivil($value) 
	{
		$this->lugarcivil = $value;
	}
	
	public function getumt()
	{
	    return $this->umt;
	}

	public function setumt($value) 
	{
		$this->umt = $value;
	}
	
	public function getreferencia()
	{
	    return $this->referencia;
	}

	public function setreferencia($value) 
	{
		$this->referencia = $value;
	}
	
	public function getcalle()
	{
	    return $this->calle;
	}

	public function setcalle($value) 
	{
		$this->calle = $value;
	}
	
	public function getcolonia()
	{
	    return $this->colonia;
	}

	public function setcolonia($value) 
	{
		$this->colonia = $value;
	}
	
	public function getlocalidad()
	{
	    return $this->localidad;
	}

	public function setlocalidad($value) 
	{
		$this->localidad = $value;
	}
	
	public function getnumero()
	{
	    return $this->numero;
	}

	public function setnumero($value) 
	{
		$this->numero = $value;
	}
	
	public function getcp()
	{
	    return $this->cp;
	}

	public function setcp($value) 
	{
		$this->cp = $value;
	}
	
	public function getageb()
	{
	    return $this->ageb;
	}

	public function setageb($value) 
	{
		$this->ageb = $value;
	}
	
	public function getsector()
	{
	    return $this->sector;
	}

	public function setsector($value) 
	{
		$this->sector = $value;
	}
	
	public function getmanzana()
	{
	    return $this->manzana;
	}

	public function setmanzana($value) 
	{
		$this->manzana = $value;
	}
	//*******************************
	
	public function getafiliacion()
	{
	    return $this->afiliacion;
	}

	public function setafiliacion($value) 
	{
		$this->afiliacion = $value;
	}
	
	public function getalergias()
	{
	    return $this->alergias;
	}

	public function setalergias($value) 
	{
		$this->alergias = $value;
	}
	
	public function getvacuna()
	{
	    return $this->vacuna;
	}

	public function setvacuna($value) 
	{
		$this->vacuna = $value;
	}
	
	public function getfvacuna()
	{
	    return $this->fvacuna;
	}

	public function setfvacuna($value) 
	{
		$this->fvacuna = $value;
	}
	
	public function getcodigo_barras()
	{
	    return $this->codigo_barras;
	}

	public function setcodigo_barras($value) 
	{
		$this->codigo_barras = $value;
	}
	
	public function getconsulta()
	{
	    return $this->consulta;
	}
    
    public function setlat_vac($value)  {
		$this->lat_vac = $value;
	}
	
	public function getlat_vac() {
	    return $this->lat_vac;
	}
    
    public function setlon_vac($value)  {
		$this->lon_vac = $value;
	}
	
	public function getlon_vac() {
	    return $this->lon_vac;
	}
    
    public function settemp_vac($value)  {
		$this->temp_vac = $value;
	}
	
	public function gettemp_vac() {
	    return $this->temp_vac;
	}

	public function setconsulta($value) 
	{
		$this->consulta = $value;
	}
	
	public function getfconsulta()
	{
	    return $this->fconsulta;
	}

	public function setfconsulta($value) 
	{
		$this->fconsulta = $value;
	}
	
	public function gettconsulta()
	{
	    return $this->tconsulta;
	}

	public function settconsulta($value) 
	{
		$this->tconsulta = $value;
	}
	
	public function getaccion_nutricional()
	{
	    return $this->accion_nutricional;
	}

	public function setaccion_nutricional($value) 
	{
		$this->accion_nutricional = $value;
	}
	
	public function getfaccion_nutricional()
	{
	    return $this->faccion_nutricional;
	}

	public function setfaccion_nutricional($value) 
	{
		$this->faccion_nutricional = $value;
	}
	
	public function getpeso()
	{
	    return $this->peso;
	}

	public function setpeso($value) 
	{
		$this->peso = $value;
	}
	
	public function getaltura()
	{
	    return $this->altura;
	}

	public function setaltura($value) 
	{
		$this->altura = $value;
	}
	public function gettalla()
	{
	    return $this->talla;
	}

	public function settalla($value) 
	{
		$this->talla = $value;
	}
    
    public function gethemoglobina()
	{
	    return $this->hemoglobina;
	}

	public function sethemoglobina($value) 
	{
		$this->hemoglobina = $value;
	}
	
	public function getfnutricion()
	{
	    return $this->fnutricion;
	}

	public function setfnutricion($value) 
	{
		$this->fnutricion = $value;
	}
	
	public function gettelefono()
	{
	    return $this->telefono;
	}

	public function settelefono($value) 
	{
		$this->telefono = $value;
	}
	
	public function getcompania()
	{
	    return $this->compania;
	}

	public function setcompania($value) 
	{
		$this->compania = $value;
	}
	
	public function getcelular()
	{
	    return $this->celular;
	}

	public function setcelular($value) 
	{
		$this->celular = $value;
	}
	
	public function getnacionalidad()
	{
	    return $this->nacionalidad;
	}

	public function setnacionalidad($value) 
	{
		$this->nacionalidad = $value;
	}
    
    public function getperi_cefa()
	{
	    return $this->peri_cefa;
	}

	public function setperi_cefa($value) 
	{
		$this->peri_cefa = $value;
	}
    
    public function getfecha_peri_cefa()
	{
	    return $this->fecha_peri_cefa;
	}

	public function setfecha_peri_cefa($value) 
	{
		$this->fecha_peri_cefa = $value;
	}
    
    public function getestimulacion_fecha()
	{
	    return $this->estimulacion_fecha;
	}

	public function setestimulacion_fecha($value) 
	{
		$this->estimulacion_fecha = $value;
	}
    
    public function getestimulacion_capacitado()
	{
	    return $this->estimulacion_capacitado;
	}

	public function setestimulacion_capacitado($value) 
	{
		$this->estimulacion_capacitado = $value;
	}
    
    public function getsales_cantidad()
	{
	    return $this->sales_cantidad;
	}

	public function setsales_cantidad($value) 
	{
		$this->sales_cantidad = $value;
	}
    
    public function getsales_fecha()
	{
	    return $this->sales_fecha;
	}

	public function setsales_fecha($value) 
	{
		$this->sales_fecha = $value;
	}
    
	 /**
	 * @access public
	 *
	 * Hace insert de las tablas cns_control_x que se reciben en la sincronizacion secuencial
	 * 
	 * @param		string 		$tabla       Nombre de la tabla a la que se afectara
	 * @param		array 		$array       Datos que se guardaran en la tabla
	 *
	 * @return 		result()
	 *
	 */
	public function cns_insert($tabla,$array)
	{
		$result = $this->db->insert($tabla, $array);
		
		if (!$result)
		{
			$this->msg_error_usr = "Error $tabla.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
		}
	}
	
	/**
	 * @access public
	 *
	 * Actualiza la tabla especificada 
	 * 
	 * @param		string 		$tabla      Nombre de la tabla afectada
	 * @param		array 		$array      Datos a actualizar
	 * @param		string 		$id         identificador para el where
	 * @param		string 		$campo      campo si se necesitarar un segundo where
	 * @param		string 		$valor      valor del campo a comparar
	 *
	 * @return 		result()
	 *
	 */
	public function cns_update($tabla,$array,$id,$campo="", $valor="",$campo2="", $valor2="")
	{
		if($id!="")
			$this->db->where('id' , $id);
		if($campo!="")
			$this->db->where($campo , $valor);
		if($campo2!="")
			$this->db->where($campo2 , $valor2);
		$result = $this->db->update($tabla, $array);  
		
		if (!$result)
		{
			$this->msg_error_usr = "Error $tabla.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
		}
	}
	public function cns_update_visita($id)
	{
		$this->db->set('contador_visitas' , 'contador_visitas+1',false);
		$this->db->where('id' , $id);
		$result = $this->db->update("cns_persona"); 
		
		if (!$result)
		{
			$this->msg_error_usr = "Error contador_visitas.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
		}
	}
	
	/**
	 * @access public
	 *
	 * Guarda la persona capturada mediante el formulario web 
	 * 
	 * @return 		result()
	 *
	 */
	public function insert()
	{
		$unico_id=md5(uniqid());
		$compania=$this->compania;
		if($compania=="")$compania=NULL;
		$data = array(
			// basico
			'id' => $unico_id,
			'id_nacionalidad' => $this->nacionalidad,
			'nombre' => $this->nombre,
			'apellido_paterno' => $this->paterno,
			'apellido_materno' => $this->materno,
			'id_asu_localidad_nacimiento' => $this->lnacimiento,
			'curp' => $this->curp,
			'sexo' => $this->sexo,
			'id_tipo_sanguineo' => $this->sangre,
			'fecha_nacimiento' => date('Y-m-d H:i:s', strtotime($this->fnacimiento)),
			'id_parto_multiple' => $this->parto,
                        'tamiz_neonatal' => $this->tamiz_neonatal,
                        'precurp' => $this->precurp,
			
			// civil
			'fecha_registro' => date('Y-m-d H:i:s', strtotime($this->fechacivil)),
			'id_asu_um_tratante' => $this->umt,
			
			// direccion
			'calle_domicilio' => $this->calle,
			'referencia_domicilio' => $this->referencia,
			'colonia_domicilio' => $this->colonia,
			'id_asu_localidad_domicilio' => $this->localidad,
			'numero_domicilio' => $this->numero,
			'cp_domicilio' => $this->cp,
			'ageb' => $this->ageb,
			'sector' => $this->sector,
			'manzana' => $this->manzana,
			
			'telefono_domicilio' => $this->telefono,
			'id_operadora_celular' => $compania,
			'celular' => $this->celular,
			'ultima_actualizacion' => date("Y-m-d H:i:s")
			);
		$result = $this->db->insert('cns_persona', $data);
		if (!$result)
		{
			$this->msg_error_usr = "Enrolamiento Fallido.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
		{
			$this->setid($unico_id);
			$dat = array(
				'id_persona' => $this->id,
				'fecha_registro' => date('Y-m-d H:i:s', strtotime($this->fechacivil)),
				'id_localidad_registro_civil' => $this->lugarcivil,
			);
			$res = $this->db->insert('cns_registro_civil', $dat);
			
			$companiaT=$this->companiaT;
			if($companiaT=="")$companiaT=NULL;
			
			$unico_idtutor=md5(uniqid());
			$data0 = array(
					// tutor
				//'id' => $unico_idtutor,
				'nombre' => $this->nombreT,
				'apellido_paterno' => $this->paternoT,
				'apellido_materno' => $this->maternoT,
				'curp' => $this->curpT,
				'sexo' => $this->sexoT,
				
				'telefono' => $this->telefonoT,
				'id_operadora_celular' => $companiaT,
				'celular' => $this->celularT,
				'ultima_actualizacion' => date("Y-m-d H:i:s")
			);
			if($this->idtutor=="")
			{
                // Se le asigna ID al tutor en caso de que sea una nueva captura
                $data0['id'] = $unico_idtutor;
				$companiaT=$this->companiaT;
				if($companiaT=="")$companiaT=NULL;
				
				$result0 = $this->db->insert('cns_tutor', $data0);
				if (!$result0)
				{
					$this->msg_error_usr = "No se guardo Tutor.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
				else
				{
					$this->setidtutor($unico_idtutor);
				}
			}
			else
			{	
				$this->db->where('id' , $this->idtutor);
				$result0 = $this->db->update('cns_tutor', $data0);
				if (!$result0)
				{
					$this->msg_error_usr = "No se actualizo Tutor.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
			$data01 = array(
				'id_persona' => $this->id,
				'id_tutor' => $this->idtutor,
				'ultima_actualizacion' => date('Y-m-d H:i:s'),						
				);
			$result01 = $this->db->insert('cns_persona_x_tutor', $data01);
			if (!$result01)
			{
				$this->msg_error_usr = "No se relaciono Tutor.";
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
			}
			$id_asu_um=$this->umt;
			
			for($i=0;$i<sizeof($this->alergias);$i++)
			{
				$data1 = array(
					// alergias
					'id_persona' => $this->id,
					'id_alergia' => $this->alergias[$i],
					'ultima_actualizacion' => date('Y-m-d H:i:s'),	
					
				);
				if($this->alergias[$i]!="")
				{
					$result1 = $this->db->insert('cns_persona_x_alergia', $data1);
					if (!$result1)
					{
						$this->msg_error_usr = "Error Alergias.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
					}
				}
			}
			
			for($i=0;$i<sizeof($this->vacuna);$i++)
			{
				$data2 = array(
					// vacuna
					'id_persona' => $this->id,
					'id_vacuna' => $this->vacuna[$i],
					'fecha' => date('Y-m-d', strtotime($this->fvacuna[$i])).' '.date('H:i:s'),
					'id_asu_um' => $id_asu_um,
					'codigo_barras' => $this->codigo_barras[$i],
					'latitud' => $this->lat_vac[$i],
					'longitud' => $this->lon_vac[$i],
					'temperatura' => ( $this->temp_vac[$i] ? number_format($this->temp_vac[$i], 2) : null ),
				);
				if($this->fvacuna[$i]!="")
				{
					$result2 = $this->db->insert('cns_control_vacuna', $data2);
					if (!$result2)
					{
						$this->msg_error_usr = "Error vacunas.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
					}
				}
			}
			
			for($i=0;$i<sizeof($this->consulta);$i++)
			{
				$data5 = array(
					// consulta
					'id_persona' => $this->id,
					'clave_cie10' => $this->consulta[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
					'id_asu_um' => $id_asu_um,
					'id_tratamiento' => $this->tconsulta[$i],
					'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
					
				);
				if($this->consulta[$i]!="")
				{
					$result5 = $this->db->insert('cns_control_consulta', $data5);
					if (!$result5)
					{
						$this->msg_error_usr = "Error Consulta.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
					}
				}
			}
			
			for($i=0;$i<sizeof($this->accion_nutricional);$i++)
			{
				$data6 = array(
					// accion nutricional
					'id_persona' => $this->id,
					'id_accion_nutricional' => $this->accion_nutricional[$i],
					'fecha' => date('Y-m-d', strtotime($this->faccion_nutricional[$i])).' '.date('H:i:s'),
					'id_asu_um' => $id_asu_um,
					
				);
				if($this->accion_nutricional[$i]!="")
				{
					$result6 = $this->db->insert('cns_control_accion_nutricional', $data6);
					if (!$result6)
					{
						$this->msg_error_usr = "Error Accion nutricional.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
					}
				}
			}
			
			for($i=0;$i<sizeof($this->peso);$i++)
			{
				$data7 = array(
					// nutricion
					'id_persona' => $this->id,
					'peso' => $this->peso[$i],
					'altura' => $this->altura[$i],
					'talla' => $this->talla[$i],
					'hemoglobina' => $this->hemoglobina[$i],
					'fecha' => date('Y-m-d', strtotime($this->fnutricion[$i])).' '.date('H:i:s'),
					'id_asu_um' => $id_asu_um,
					
				);
				if($this->peso[$i]!=""||$this->altura[$i]!=""||$this->talla[$i]!=""||$this->hemoglobina[$i]!="")
				{
					$result7 = $this->db->insert('cns_control_nutricional', $data7);
					if (!$result7)
					{
						$this->msg_error_usr = "Error Nutricion.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
					}
				}
			}
			
			for($i=0;$i<sizeof($this->afiliacion);$i++)
			{
				$data8 = array(
					// afiliacion
					'id_persona' => $this->id,
					'id_afiliacion' => $this->afiliacion[$i],
					'ultima_actualizacion' => date('Y-m-d H:i:s'),	
				);
				if($this->afiliacion[$i]!="")
				{
					$result8 = $this->db->insert('cns_persona_x_afiliacion', $data8);
					if (!$result8)
					{
						$this->msg_error_usr = "Error Afiliacion.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
						throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
					}
				}
			}
            
            if(!empty($this->peri_cefa)){
                for($index=0; $index<sizeof($this->peri_cefa); $index++){
                    $datosPeriCefa = array(
                        'id_persona' => $this->id,
                        'fecha' => date('Y-m-d', strtotime($this->fecha_peri_cefa[$index])).' '.date('H:i:s'),
                        'perimetro_cefalico' => $this->peri_cefa[$index],
                        'id_asu_um' => $id_asu_um);

                    $resultPeriCefa = $this->db->insert('cns_control_peri_cefa', $datosPeriCefa);
                    if (!$resultPeriCefa)
                    {
                        $this->msg_error_usr = "Error Perímetro Cefálico.";
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                    }
                }
            }
            
            if(!empty($this->estimulacion_fecha)){
                for($index=0; $index<sizeof($this->estimulacion_fecha); $index++){
                    $datosEstimulacion = array(
                        'id_persona' => $this->id,
                        'fecha' => date('Y-m-d', strtotime($this->estimulacion_fecha[$index])).' '.date('H:i:s'),
                        'tutor_capacitado' => $this->estimulacion_capacitado[$index],
                        'id_asu_um' => $id_asu_um);

                    $resultEstimulacion = $this->db->insert('cns_estimulacion_temprana', $datosEstimulacion);
                    if (!$resultEstimulacion)
                    {
                        $this->msg_error_usr = "Error Estimulación Temprana.";
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                    }
                }
            }
            
            if(!empty($this->sales_fecha)){
                for($index=0; $index<sizeof($this->sales_fecha); $index++){
                    $datosSRO = array(
                        'id_persona' => $this->id,
                        'fecha' => date('Y-m-d', strtotime($this->sales_fecha[$index])).' '.date('H:i:s'),
                        'cantidad' => $this->sales_cantidad[$index],
                        'id_asu_um' => $id_asu_um);

                    $resultSRO = $this->db->insert('cns_sales_rehidratacion', $datosSRO);
                    if (!$resultSRO)
                    {
                        $this->msg_error_usr = "Error Sales de Rehidratración Oral.";
                        $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                        throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                    }
                }
            }
		}
		return $this->id;
	}
	 /**
	 * @access public
	 *
	 * Este metodo actualiza o inserta los datos que permiten el envio de la informacion a la tarjeta por nfc
	 * 
	 * @param		string 		$entorno      id del entorno 
	 * @param		strin 		$persona      id de la persona a la que se le asigna una tarjeta
	 * @param		string 		$fecha        fecha en que se genera el evento
	 * @param		string 		$archivo      nombre del archivo que se genero
	 * @param		boolena		$impreso      determina si el archivo fue escrita en la tarjeta o no
	 *
	 * @return 		result()
	 *
	 */
	public function entorno_x_persona($entorno,$persona,$fecha,$archivo,$impreso)
	{
		$data = array(
			'id_entorno' => $entorno,
			'id_persona' => $persona,
			'fecha_entrega' =>  $fecha,
			'nombre_archivo' => $archivo,
			'impreso_tes' => $impreso,
		);
		$query = $this->db->get_where('tes_entorno_x_persona', array('id_persona' => $persona));
		if ($query->num_rows() <=0)
			$result = $this->db->insert('tes_entorno_x_persona', $data);
		else
		{
			$this->db->where('id_persona' , $persona);
			$result = $this->db->update('tes_entorno_x_persona', $data); 
		}
		if (!$result)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}	
	}
	
	/**
	 * @access public
	 *
	 * Este metodo valida que exista un archivo para enviar a la tarjeta por nfc
	 * 
	 * @param		strin 		$persona      id de la persona a la que se le asigna una tarjeta
	 * @param		string 		$archivo      nombre del archivo que se genero
	 *
	 * @return 		result()
	 *
	 */
	public function valid_card($persona,$archivo)
	{
		$query = $this->db->get_where('tes_entorno_x_persona', array('id_persona' => $persona,"nombre_archivo"=>$archivo));
		return ($query->num_rows() >0);		
	}
	 /**
	 * @access public
	 *
	 * Extrae el folio que se anexa en el envio para la tarjeta 
	 * 
	 * @param		strin 		$persona      id de la persona a la que se le asigna una tarjeta
	 *
	 * @return 		result()
	 *
	 */
	public function getfolio($persona)
	{
		$query = $this->db->get_where('tes_entorno_x_persona', array('id_persona' => $persona));
		return $query->result();		
	}
	
	/**
	 * @access public
	 *
	 * Actualiza los datos basicos del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_basico()
	{
		//date_default_timezone_set('UTC');
		$data = array(
			// basico
			'id_nacionalidad' => $this->nacionalidad,
			'nombre' => $this->nombre,
			'apellido_paterno' => $this->paterno,
			'apellido_materno' => $this->materno,
			'id_asu_localidad_nacimiento' => $this->lnacimiento,
			'curp' => $this->curp,
			'sexo' => $this->sexo,
			'id_tipo_sanguineo' => $this->sangre,
			'id_parto_multiple' => $this->parto,
                        'tamiz_neonatal' => $this->tamiz_neonatal,
                        'precurp' => $this->precurp,
			'fecha_nacimiento' => date('Y-m-d H:i:s', strtotime($this->fnacimiento)));
		$this->db->where('id' , $this->id);
		$result = $this->db->update('cns_persona', $data); 
		if (!$result)
		{
			$this->msg_error_usr = "Actualizacion Fallida.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza la unidad medica tratante del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_umt()
	{
		//date_default_timezone_set('UTC');
		$data = array(
			// civil
			'fecha_registro' => date('Y-m-d H:i:s', strtotime($this->fechacivil)),
			'id_asu_um_tratante' => $this->umt);
		$this->db->where('id' , $this->id);
		$result = $this->db->update('cns_persona', $data); 
		if (!$result)
		{
			$this->msg_error_usr = "Actualizacion Fallida.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
	}
	/**
	 * @access public
	 *
	 * actualiza la direccion del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_direccion()
	{
		//date_default_timezone_set('UTC');
		$compania=$this->compania;
		if($compania=="")$compania=NULL;
		$data = array(
			// direccion 
			'calle_domicilio' => $this->calle,
			'referencia_domicilio' => $this->referencia,
			'colonia_domicilio' => $this->colonia,
			'id_asu_localidad_domicilio' => $this->localidad,
			'numero_domicilio' => $this->numero,
			'cp_domicilio' => $this->cp,
			'ageb' => $this->ageb,
			'sector' => $this->sector,
			'manzana' => $this->manzana,
			
			'telefono_domicilio' => $this->telefono,
			'id_operadora_celular' => $compania,
			'celular' => $this->celular,
			'ultima_actualizacion' => date("Y-m-d H:i:s")
			);
		$this->db->where('id' , $this->id);
		$result = $this->db->update('cns_persona', $data); 
		if (!$result)
		{
			$this->msg_error_usr = "Actualizacion Fallida.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
	}
	/**
	 * @access public
	 *
	 * actualiza el registro civil del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_regcivil()
	{
		
		$data = array(
			'id_persona' => $this->id,
			'fecha_registro' => date('Y-m-d H:i:s', strtotime($this->fechacivil)),
			'id_localidad_registro_civil' => $this->lugarcivil,
		);
		$query = $this->db->get_where('cns_registro_civil', array('id_persona' => $this->id));
		if($query->num_rows() >0)
		{
			$this->db->where('id_persona' , $this->id);
			$result = $this->db->update('cns_registro_civil', $data);
		}
		else
			$res = $this->db->insert('cns_registro_civil', $data);
	}
	/**
	 * @access public
	 *
	 * Actualiza los datos del tutor del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_tutor()
	{
		$companiaT=$this->companiaT;
		if($companiaT=="")$companiaT=NULL;
		$data0 = array(
			// tutor
			'nombre' => $this->nombreT,
			'apellido_paterno' => $this->paternoT,
			'apellido_materno' => $this->maternoT,
			'curp' => $this->curpT,
			'sexo' => $this->sexoT,
			
			'telefono' => $this->telefonoT,
			'id_operadora_celular' => $companiaT,
			'celular' => $this->celularT,
			
			);
		//
		if($this->idtutor=="")
		{
			$unico_idtutor=md5(uniqid());
			$data0['id']=$unico_idtutor;
			$result0 = $this->db->insert('cns_tutor', $data0);
			if (!$result0)
			{
				$this->msg_error_usr = "No se guardo Tutor.";
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
			}
			else
			{
				$this->setidtutor($unico_idtutor);
				$this->idtutor=$unico_idtutor;
			}
		}
		else
		{	
			$this->db->where('id' , $this->idtutor);
			$result0 = $this->db->update('cns_tutor', $data0);
			if (!$result0)
			{
				$this->msg_error_usr = "No se actualizo Tutor.";
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
			}
		}
	
		// relacion tutor paciente
		$data01 = array(
			'id_persona' => $this->id,
			'id_tutor' => $this->idtutor,	
			'ultima_actualizacion' => date('Y-m-d H:i:s'),						
			);
		if ($this->db->delete('cns_persona_x_tutor', array('id_persona' => $this->id)))
		{
			$result01 = $this->db->insert('cns_persona_x_tutor', $data01);
			if (!$result01)
			{
				$this->msg_error_usr = "Error actualizando Tutor.";
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza los datos de las alergias del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_alergia()
	{
		$id_asu_um=$this->umt;
		if ($this->db->delete('cns_persona_x_alergia', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->alergias);$i++)
		{
			$data1 = array(
				// alergias
				'id_persona' => $this->id,
				'id_alergia' => $this->alergias[$i],
				'ultima_actualizacion' => date('Y-m-d H:i:s'),	
			);
			if($this->alergias[$i]!="")
			{
				$result1 = $this->db->insert('cns_persona_x_alergia', $data1);
				if (!$result1)
				{
					$this->msg_error_usr = "Error actualizando Alergias.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza las vacunas del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_vacuna()
	{
		$id_asu_um=$this->umt;
		if ($this->db->delete('cns_control_vacuna', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->vacuna);$i++)
		{
			$data2 = array(
				// vacuna
				'id_persona' => $this->id,
				'id_vacuna' => $this->vacuna[$i],
				'fecha' => date('Y-m-d', strtotime($this->fvacuna[$i])).' '.date('H:i:s'),
				'id_asu_um' => $id_asu_um,
				'codigo_barras' => $this->codigo_barras[$i],
                'latitud' => $this->lat_vac[$i],
                'longitud' => $this->lon_vac[$i],
                'temperatura' => ( $this->temp_vac[$i] ? number_format($this->temp_vac[$i], 2) : null ),
			);
			if($this->fvacuna[$i]!="")
			{
				$result2 = $this->db->insert('cns_control_vacuna', $data2);
				if (!$result2)
				{
					$this->msg_error_usr = "Error actualizando vacunas.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza el control consulta del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_consulta()
	{
		$id_asu_um=$this->umt;
		if ($this->db->delete('cns_control_consulta', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->consulta);$i++)
		{
			$data5 = array(
				// consulta
				'id_persona' => $this->id,
                'clave_cie10' => $this->consulta[$i],
                'fecha' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
                'id_asu_um' => $id_asu_um,
                'id_tratamiento' => $this->tconsulta[$i],
				'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
				
			);
			if($this->consulta[$i]!="")
			{
				$result5 = $this->db->insert('cns_control_consulta', $data5);
				if (!$result5)
				{
					$this->msg_error_usr = "Error actualizando Consulta.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza el control accion nutricional del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_accion()
	{
		$id_asu_um=$this->umt;
		if ($this->db->delete('cns_control_accion_nutricional', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->accion_nutricional);$i++)
		{
			$data6 = array(
				// accion nutricional
				'id_persona' => $this->id,
				'id_accion_nutricional' => $this->accion_nutricional[$i],
				'fecha' => date('Y-m-d', strtotime($this->faccion_nutricional[$i])).' '.date('H:i:s'),
				'id_asu_um' => $id_asu_um,
				
			);
			if($this->accion_nutricional[$i]!="")
			{
				$result6 = $this->db->insert('cns_control_accion_nutricional', $data6);
				if (!$result6)
				{
					$this->msg_error_usr = "Error actualizando Accion nutricional.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza el control nutricional del paciente
	 *
	 * @return 		result()
	 *
	 */	
	public function update_nutricion()
	{
		$id_asu_um=$this->umt;	
		if ($this->db->delete('cns_control_nutricional', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->peso);$i++)
		{
			$data7 = array(
				// nutricion
				'id_persona' => $this->id,
				'peso' => $this->peso[$i],
				'altura' => $this->altura[$i],
				'talla' => $this->talla[$i],
				'hemoglobina' => $this->hemoglobina[$i],
				'fecha' => date('Y-m-d', strtotime($this->fnutricion[$i])).' '.date('H:i:s'),
				'id_asu_um' => $id_asu_um,
				
			);
			if($this->peso[$i]!=""||$this->altura[$i]!=""||$this->talla[$i]!=""||$this->hemoglobina[$i]!="")
			{
				$result7 = $this->db->insert('cns_control_nutricional', $data7);
				if (!$result7)
				{
					$this->msg_error_usr = "Error actualizando Nutricion.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza el tipo de beneficiario del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_beneficiario()
	{
		$id_asu_um=$this->umt;
			
		if ($this->db->delete('cns_persona_x_afiliacion', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->afiliacion);$i++)
		{
			$data8 = array(
				// afiliacion
				'id_persona' => $this->id,
				'id_afiliacion' => $this->afiliacion[$i],
				'ultima_actualizacion' => date('Y-m-d H:i:s'),	
			);
			if($this->afiliacion[$i]!="")
			{
				$result8 = $this->db->insert('cns_persona_x_afiliacion', $data8);
				if (!$result8)
				{
					$this->msg_error_usr = "Error actualizando Afiliacion.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
				}
			}
		}
	}
	
	 /**
	 * @access public
	 *
	 * Hace update de la tableta que este sincronizando dependiendo del resultado
	 * 
	 * @param		string 		$mac         Mac de la tableta
	 * @param		strin 		$status      nuevo status
	 * @param		string 		$version     version de la apk de la tableta
	 * @param		string 		$fecha       fecha del evento
	 *
	 * @return 		result()
	 *
	 */
	public function update_status_tableta($mac,$status,$version,$fecha)
	{
		$data = array
		(
			'id_tes_estado_tableta' => $status,
			'id_version'               => $version,
			'ultima_actualizacion'  => $fecha,
		);
		$this->db->where('mac' , $mac);
		$result0 = $this->db->update('tes_tableta', $data);
		if (!$result0)
		{
			$this->msg_error_usr = "No se actualizo Tutor.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
	}
	 /**
	 * @access public
	 *
	 * Este metodo retorna el list de las personas enroladas
	 * 
	 * @param		string 		$keywords    palabras claves para hacer el filtro
	 * @param		strin 		$offset      inicio del registro
	 * @param		string 		$row_count   numero de filas a mostrar por pagina
	 *
	 * @return 		result()
	 *
	 */
	public function getListEnrolamiento($keywords = '', $offset = null, $row_count = null)
	{
		if(!empty($offset) && !empty($row_count))
			$this->db->limit($offset, $row_count);
		else if (!empty($offset))
			$this->db->limit($offset);
		
		if (empty($keywords)){
			
			$query = $this->db->get('cns_persona');
		}
		else
		{
			$this->db->select('*');
			$this->db->from('cns_persona');
			$cadena=explode(" ",$keywords);
			if(count($cadena)==1)
			{
				$this->db->like('curp', $keywords);
				$this->db->or_like('nombre', $keywords);
				$this->db->or_like('apellido_paterno', $keywords);
				$this->db->or_like('apellido_materno', $keywords);
			}
			else if(count($cadena)==2)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and (nombre like '%".$cadena[1]."%' or apellido_paterno like '%".$cadena[1]."%')) or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_materno like '%".$cadena[1]."%')");
			}
			else if (count($cadena)==3)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%') or
				(nombre like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%') or 
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_materno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%')");
			}
			else if (count($cadena)==4)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%') or
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%') or 
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%') or
				(apellido_paterno like '%".$cadena[0]."%' and apellido_materno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%')");
			}
			else if (count($cadena)==5)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_paterno like '%".$cadena[4]."%') or 				
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or 
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_paterno like '%".$cadena[4]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or
				(nombre like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%')");
			}
			$query = $this->db->get();
		}
		
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}	
		else
			return $query->result();
		return;
	}
	 /**
	 * @access public
	 *
	 * Devuelve el numero de filas en la tabla cns_persona
	 * 
	 * @param		string 		$keywords      palabras clave para hacer el filtro
	 *
	 * @return 		result()
	 *
	 */
	public function getNumRows($keywords = '')
	{
		if (!$keywords)
			$query = $this->db->get('cns_persona');
		else 
		{
			
			$this->db->select('*');
			$this->db->from('cns_persona');
			$cadena=explode(" ",$keywords);
			if(count($cadena)==1)
			{
				$this->db->like('curp', $keywords);
				$this->db->or_like('nombre', $keywords);
				$this->db->or_like('apellido_paterno', $keywords);
				$this->db->or_like('apellido_materno', $keywords);
			}
			else if(count($cadena)==2)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and (nombre like '%".$cadena[1]."%' or apellido_paterno like '%".$cadena[1]."%')) or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_materno like '%".$cadena[1]."%')");
			}
			else if (count($cadena)==3)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%') or
				(nombre like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%') or 
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_materno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%')");
			}
			else if (count($cadena)==4)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%') or
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%') or 
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%') or
				(apellido_paterno like '%".$cadena[0]."%' and apellido_materno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%')");
			}
			else if (count($cadena)==5)
			{
				$this->db->where("(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and nombre like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_paterno like '%".$cadena[4]."%') or 				
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or 
				(nombre like '%".$cadena[0]."%' and nombre like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_paterno like '%".$cadena[3]."%' and apellido_paterno like '%".$cadena[4]."%') or 
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or
				(apellido_paterno like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_materno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%') or
				(nombre like '%".$cadena[0]."%' and apellido_paterno like '%".$cadena[1]."%' and apellido_paterno like '%".$cadena[2]."%' and apellido_materno like '%".$cadena[3]."%' and apellido_materno like '%".$cadena[4]."%')");
			}
			$query = $this->db->get();
		}
		if(!$query) 
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		return $query->num_rows;
	}
	
	 /**
	 * @access public
	 *
	 * Obtiene la informacion de la persona
	 * 
	 * @param		string 		$id      identificado de la persona
	 *
	 * @return 		result()
	 *
	 */
	public function getById($id)
	{
		
		$this->db->select('p.*,s.id as sangre, s.descripcion as tsangre, n.id as nacionalidadid, n.descripcion as nacionalidad, o.id as operadoraid,o.descripcion as operadora, t.id as idT, t.curp as curpT, t.nombre as nombreT, t.apellido_paterno as paternoT, t.apellido_materno as maternoT, t.sexo as sexoT, t.telefono as telefonoT, t.celular as celularT,o1.id as operadoraTid, o1.descripcion as operadoraT, rc.id_localidad_registro_civil, pm.descripcion as parto, TIMESTAMPDIFF(month, fecha_nacimiento, CURDATE()) AS edad_meses, tamiz_neonatal');
		$this->db->from('cns_persona p');
		$this->db->join('cns_nacionalidad n', 'n.id = p.id_nacionalidad','left');
		$this->db->join('cns_tipo_sanguineo s', 's.id = p.id_tipo_sanguineo','left');
		$this->db->join('cns_operadora_celular o', 'o.id = p.id_operadora_celular','left');
		$this->db->join('cns_persona_x_tutor pt', 'pt.id_persona = p.id','left');
		$this->db->join('cns_tutor t', 't.id = pt.id_tutor','left');
		$this->db->join('cns_operadora_celular o1', 'o1.id = t.id_operadora_celular','left');
		$this->db->join('cns_registro_civil rc', 'rc.id_persona = p.id','left');
		$this->db->join('cns_parto_multiple pm', 'pm.id = p.id_parto_multiple','left');
		$this->db->where('p.id', $id);
		$query = $this->db->get();
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->row();
		return;
	}
	/**
	 * @access public
	 *
	 * obtiene informacion del registro civil
	 * 
	 * @param		string 		$id      identificador de la persona
	 *
	 * @return 		result()
	 *
	 */
	public function getRegistro_civil($id)
	{
		
		$this->db->select('*');
		$this->db->from('cns_registro_civil');
		$this->db->where('id_persona', $id);
		$query = $this->db->get();
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->row();
		return;
	}
	
	/**
	 * @access public
	 *
	 * Obtiene las alergias asociadas a una persona
	 * 
	 * @param		string 		$id      identificador de la persona
	 * @param		strin 		$order   nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function getAlergia($id = '',$order='')
	{
		$this->db->select('a.id, a.descripcion');
		$this->db->from('cns_persona_x_alergia p');
		$this->db->join('cns_alergia a', 'a.id = p.id_alergia','left');
		$this->db->where('p.id_persona', $id);
		if($order!="")
		$this->db->order_by($order, "asc"); 
		$query = $this->db->get();
		
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}	
		else
			return $query->result();
		return;
	}
	
		/**
	 * @access public
	 *
	 * Obtiene las afiliaciones asociadas a una persona
	 * 
	 * @param		string 		$id      identificador de la persona
	 * @param		strin 		$order   nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */

	public function getAfiliaciones($id = '',$order="")
	{
		$this->db->select('a.id, a.descripcion');
		$this->db->from('cns_persona_x_afiliacion p');
		$this->db->join('cns_afiliacion a', 'a.id = p.id_afiliacion','left');
		$this->db->where('p.id_persona', $id);
		if($order!="")
		$this->db->order_by($order, "asc");
		$query = $this->db->get();
		
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}	
		else
			return $query->result();
		return;
	}
	 /**
	 * @access public
	 *
	 * Hace select de los catalogos que tengan relacion con una persona para mostrarlos en el view
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 
	 * @param		string 		$id       identificador de la persona
	 * @param		strin 		$order1   nombre del campo para hacer el order by
	 * @param		strin 		$order2   nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */

	public function get_catalog_view($catalog,$id,$order1="",$order2="")
	{
		$this->db->select('*');
		$this->db->from('cns_control_'.$catalog.' p');
		$this->db->join('cns_'.$catalog.' a', 'a.id = p.id_'.$catalog,'left');
		$this->db->where('p.id_persona', $id);
		if($order1!="")
		$this->db->order_by($order1, "asc");
		if($order2!="")
		$this->db->order_by($order2, "asc");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	/**
	 * @access public
	 *
	 * Obtiene los datos del control nutricional asociados a una persona
	 * 
	 * @param		string 		$id       identificador de la persona
	 * @param		strin 		$order    nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function get_control_nutricional($id,$order="")
	{
		$this->db->select('*');
		$this->db->from('cns_control_nutricional');
		$this->db->where('id_persona', $id);
		if($order!="")
		$this->db->order_by($order, "asc");
		else
		$this->db->order_by("fecha", "ASC");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	 /**
	 * @access public
	 *
	 * Hace select de las tablas cns_x que representa a los catalogos
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 
	 * @param		strin 		$campo    nombre del campo para hacer el where
	 * @param		string 		$id       valor del campo para el where
	 * @param		strin 		$order    nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function get_catalog($catalog,$campo="",$id="",$orden="")
	{
		 if($catalog=="cns_regla_vacuna")
		$this->db->select('id, id_vacuna, dia_inicio_aplicacion_nacido, dia_fin_aplicacion_nacido, id_vacuna_secuencial, dia_inicio_aplicacion_secuencial, dia_fin_aplicacion_secuencial, ultima_actualizacion, activo, id_via_vacuna,  dosis, region, esq_com, orden_esq_com, alergias, forzar_aplicacion, observacion_region');
		else
		$this->db->select('*');
		$this->db->from($catalog);
		if($id!="")
		$this->db->where($campo, $id);
		
		if($orden!="")
		$this->db->order_by($orden, "asc");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	
	/**
	 * @access public
	 *
	 * Hace select de los tratamientos de las consultas
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 
	 * @param		strin 		$campo    nombre del campo para hacer el where
	 * @param		string 		$valor    valor del campo para el where
	 * @param		strin 		$order    nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function get_catalog_tratamiento($catalog,$campo,$valor,$orden)
	{
		if($orden=="tipo"||$orden=="cc")
			$this->db->select('distinct(tipo),id');
		else
			$this->db->distinct('*');
		$this->db->from($catalog);
		if($campo!="")
			$this->db->where($campo, $valor);
		if($orden!="cc"&&$campo!="tipo")
			$this->db->group_by("tipo");
		if($orden!=""&&$orden!="cc")
			$this->db->order_by($orden, "asc");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	
	/**
	 * @access public
	 *
	 * Obtiene el numero de resultados de una tabla
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 	 
	 *
	 * @return 		count
	 *
	 */
	public function get_catalog_count($catalog,$campo="",$valor="")
	{
		if($campo!="")
		{
			$this->db->where($campo, $valor);
			$this->db->from($catalog);
			return $this->db->count_all_results();
		}
		else
			return $this->db->count_all($catalog);
	}
	
	/**
	 * @access public
	 *
	 * Obtiene los datos de una tabla
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 
	 * @param		strin 		$campo1   nombre del campo para hacer el where
	 * @param		string 		$id1      valor del campo para el where
	 * @param		strin 		$campo2   nombre del campo para hacer el where
	 * @param		string 		$id2      valor del campo para el where
	 * @param		strin 		$l1       nombre del campo para hacer el limit offset
	 * @param		strin 		$l2       nombre del campo para hacer el limit count
	 *
	 * @return 		result()
	 *
	 */
	public function get_catalog2($catalog,$campo1="",$id1="",$campo2="",$id2="",$l1="",$l2="")
	{
            ini_set("memory_limit","-1");
            
		if($catalog=="tes_notificacion")
			$this->db->select('id,titulo,contenido,fecha_inicio,fecha_fin');
		else if($catalog=="asu_arbol_segmentacion")
			$this->db->select('id,grado_segmentacion,id_padre,orden, visible, descripcion');
		else if($catalog=="tes_pendientes_tarjeta")
			$this->db->select('fecha, id_persona, tabla, registro_json,');
		else
			$this->db->select('*');
		$this->db->from($catalog);
		if($id1!="")
		$this->db->where($campo1, $id1);
		if($id2!="")
		$this->db->where($campo2, $id2);
		if($l2!="")
		$this->db->limit($l2, $l1);
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	/**
	 * @access public
	 *
	 * Este metodo obtiene las notificaciones que se enviaran en la sincronizacion
	 * 
	 * @param		string 		$id       id del arbol de segmentacion
	 
	 *
	 * @return 		result()
	 *
	 */
	public function get_notificacion($id)
	{
		$this->db->select('id,titulo,contenido,fecha_inicio,fecha_fin');
		$this->db->from("tes_notificacion");
		$this->db->like("id_arr_asu", $id);
		$this->db->where("fecha_fin >=",date("Y-m-d"));
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	/**
	 * @access public
	 *
	 * Este metodo obtiene las personas que seran enviadas en la sincronizacion
	 * 
	 * @param		string 		$array    arreglo con los ids de las personas que cumplen con los requisitos del envio
	 * @param		strin 		$fecha    fecha que determina si se envia o no una persona
	 *
	 * @return 		result()
	 *
	 */
	public function get_cns_persona($array,$fecha="")
	{
		$this->db->select('id, curp, nombre, apellido_paterno, apellido_materno, sexo, id_tipo_sanguineo, fecha_nacimiento, id_asu_localidad_nacimiento, calle_domicilio, numero_domicilio, colonia_domicilio, referencia_domicilio, ageb, manzana, sector, id_asu_localidad_domicilio, cp_domicilio, telefono_domicilio, fecha_registro, id_asu_um_tratante, celular, ultima_actualizacion, id_nacionalidad, id_operadora_celular, ultima_sincronizacion, id_parto_multiple, tamiz_neonatal');
		$this->db->from("cns_persona");
		if($fecha!="")
		$this->db->where("ultima_actualizacion >=", $fecha);
		$this->db->where_in("id_asu_um_tratante", $array);
		$this->db->where("activo", "1");
		$this->db->where("(DATEDIFF(NOW(),fecha_nacimiento)/365.25)<",5); 
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}

	 /**
	 * @access public
	 *
	 * Este metodo obtiene los controles que le corresponde a cada persona y que seran incluidas en la sincronizacion
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 
	 * @param		strin 		$array    ids de personas
	 * @param		string 		$l1       ofsset para el limit
	 * @param		strin 		$l2       count para el limit
	 
	 *
	 * @return 		result()
	 *
	 */
	public function get_cns_cat_persona($catalog, $array, $l1="", $l2="")
	{
		if($catalog=="tes_notificacion")
			$this->db->select('id,titulo,contenido,fecha_inicio,fecha_fin');
		else if($catalog=="asu_arbol_segmentacion")
			$this->db->select('id,grado_segmentacion,id_padre,orden, visible, descripcion');
		else if($catalog=="tes_pendientes_tarjeta")
			$this->db->select('fecha, id_persona, tabla, registro_json,');
		else
			$this->db->select('*');
		$this->db->from($catalog);
		$this->db->where_in("id_persona", $array);
		if($l1!=""||$l2!="")
		$this->db->limit($l2, $l1);
		$query = $this->db->get();
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	 /**
	 * @access public
	 *
	 * Hace el count de personas que se envian en la sincronizacion
	 * 
	 * @param		string 		$catalog  Nombre de la tabla 
	 * @param		strin 		$personas personas que cumplen el requisito	 
	 *
	 * @return 		result()
	 *
	 */
	public function get_cns_cat_persona_count($catalog,$persona)
	{
		$this->db->select ( 'COUNT(*) AS numrows' );
		$this->db->from($catalog);
		$this->db->where_in("id_persona", $persona);
		$query = $this->db->get(); 
		return $query->row()->numrows;
	}

	 /**
	 * @access public
	 *
	 * obtiene los tutores de las personas que se envian en la sincronizacion
	 * 
	 * @param		string 		$array   tutores que tienen asignado un paciente 
	 *
	 * @return 		result()
	 *
	 */
	public function get_persona_x_tutor($array)
	{
		$this->db->distinct('*');
		$this->db->from("cns_tutor");
		$this->db->where_in("id", $array);
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	 /**
	 * @access public
	 *
	 * obtiene los catalogos relevante x entorno para la sincronizacion	 
	 *
	 * @return 		result()
	 *
	 */
	public function get_catalog_relevante($fecha="")
	{
		$this->db->select('*');
		$this->db->from('cns_catalogo_relevante_x_entorno r');
		$this->db->join('cns_tabla_catalogo c', 'c.id = r.id_tabla_catalogo','left');
		$this->db->where('fecha_actualizacion >=',$fecha);
		$query = $this->db->get();
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}

	 /**
	 * @access public
	 *
	 * Obtiene las transacciones relevante para la sincronizacion
	 * 
	 *
	 * @return 		result()
	 *
	 */
	public function get_transaction_relevante()
	{
		$this->db->select('*');
		$this->db->from('cns_transaccion_relevante_x_entorno r');
		$this->db->join('cns_tabla_transaccion c', 'c.id = r.id_tabla_transaccion','left');
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	
	 /**
	 * @access public
	 *
	 * obtiene cual es la ultima version de apk de la tableta
	 * 
	 * @return 		result()
	 *
	 */
	public function get_version()
	{
		$this->db->select('host');
		$this->db->select_max('version');
		$this->db->from('tes_version');
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}

	 /**
	 * @access public
	 *
	 * obtiene informacion del tutor
	 * 
	 * @param		string 		$curp     Curp del tutor
	 *
	 * @return 		result()
	 *
	 */
	public function data_tutor($curp)
	{
		$query = $this->db->get_where('cns_tutor', array('curp' => $curp));
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	 /**
	 * @access public
	 *
	 * obtiene informacion del tutor para genberar el autocomplete
	 * 
	 * @param		string 		$keywords  palabras claves para hacer el filtro 
	 *
	 * @return 		result()
	 *
	 */
	public function autocomplete_tutor($keywords)
	{
		$this->db->select('*');
		$this->db->from('cns_tutor');
		$this->db->like('curp', $keywords);
		$this->db->or_like('nombre', $keywords);
		$this->db->or_like('apellido_paterno', $keywords);
		$this->db->or_like('apellido_materno', $keywords);
		$this->db->or_like('CONCAT(nombre," ",apellido_paterno," ",apellido_materno)', $keywords);
		$query = $this->db->get();
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
	 /**
	 * @access public
	 *
	 * valida que no se repita la curp en personas y tutor
	 * 
	 * @param		string 		$curp     Curp a validar
	 * @param		strin 		$tabla    Tabla en la que se debe hacer la validacion
	 * @param		string 		$id       id de la persona o tutor para excluirse
	 *
	 * @return 		result()
	 *
	 */
	public function getByCurp($curp,$tabla,$id)
	{
		if($id!="")
			$query = $this->db->get_where($tabla, array('curp' => $curp,"id !=" => $id));
		else
			$query = $this->db->get_where($tabla, array('curp' => $curp));
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->row();
		return;
	}
	/**
	 * @access public
	 *
	 * Elimina los pendientes de las personas que no tengan asignado una tarjeta
	 * 
	 * @return 		result()
	 *
	 */
	public function tes_pendientes_tarjeta_delete()
	{
		$query = $this->db->query("DELETE FROM tes_pendientes_tarjeta WHERE id_persona NOT IN(SELECT id_persona FROM tes_entorno_x_persona)"); 
	
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
	}
	
	/**
	 * @access public
	 *
	 * devuelve todos los pacientes de la base de datos
	 * 
	 * @return 		result()
	 *
	 */
	public function get_pacientes()
	{
		$query = $this->db->query("SELECT p.id, p.curp, p.nombre, p.apellido_paterno, p.apellido_materno, p.fecha_nacimiento, p.calle_domicilio, p.numero_domicilio, p.colonia_domicilio, p.referencia_domicilio, p.cp_domicilio,
CONCAT(t.nombre,' ',t.apellido_paterno,' ',t.apellido_materno) AS nombreT, t.curp AS curpT, a.descripcion AS lugar
FROM cns_persona p
LEFT JOIN cns_persona_x_tutor pt ON pt.id_persona=p.id
LEFT JOIN cns_tutor t ON t.id=pt.id_tutor
LEFT JOIN asu_arbol_segmentacion a ON a.id=p.id_asu_localidad_nacimiento"); 
	
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
		return $query->result();
	}
	
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}
    
    /**
     * Obtiene los datos especificos de un catálogo para ser visualizados en una gráfica
     *
     * @access public
     * @param  int    $catalogo   Determina el catalogo a consultar
     * @param  int    $sexo       El sexo del paciente (F, M)
     * @param  int    $edad_meses Edad del paciente en meses
     * @param  int    $id_persona Identificador del paciente
     * @param  int    $asu_locali Identificador del asu de la localidad del domicilio
     * @return void
     */
    public function get_datos_grafica($catalogo, $sexo, $edad_meses, $id_persona, $asu_locali='')
	{
        $datos = array('series' => NULL, 
                       'labels' => NULL
                );
        $talla = 45; // Talla ideal al nacimiento
        $series = NULL;
        $puntos = NULL;
        
        // Obtiene datos del paciente
        $queryPaciente = 'SELECT
                TIMESTAMPDIFF(MONTH, fecha_nacimiento, fecha) AS edad_meses,
                peso, altura, hemoglobina,
                ROUND((peso/POW((altura/100),2)), 1) AS imc
            FROM cns_control_nutricional
            INNER JOIN cns_persona ON cns_persona.id = cns_control_nutricional.id_persona
            WHERE cns_control_nutricional.id_persona="'.$id_persona.'"
            ORDER BY fecha ASC';
                
        $objQueryPac = $this->db->query($queryPaciente);
        $objResultPac = $objQueryPac->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'Error al obtener los datos para las gráficas';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception($this->msg_error_log);
        }
        
        switch($catalogo){
            case 'peso_edad':
                foreach ($objResultPac as $pac) {
                    $puntos[] = array($pac->edad_meses, $pac->peso);
                }
                break;
            case 'peso_talla':
                foreach ($objResultPac as $pac) {
                    $puntos[] = array($pac->altura, $pac->peso);
                    
                    if($pac->altura > $talla){
                        $talla = $pac->altura;
                    }
                }
                break;
            case 'talla_edad':
                foreach ($objResultPac as $pac) {
                    $puntos[] = array($pac->edad_meses, $pac->altura);
                }
                break;
            case 'imc':
                foreach ($objResultPac as $pac) {
                    $puntos[] = array($pac->edad_meses, $pac->imc);
                }
                break;
            case 'peri_cefa':
                $queryPaciente = 'SELECT
                        TIMESTAMPDIFF(MONTH, fecha_nacimiento, fecha) AS edad_meses,
                        perimetro_cefalico
                    FROM cns_control_peri_cefa
                    INNER JOIN cns_persona ON cns_persona.id = cns_control_peri_cefa.id_persona
                    WHERE cns_control_peri_cefa.id_persona="'.$id_persona.'"
                    ORDER BY fecha ASC';

                $objQueryPac = $this->db->query($queryPaciente);
                $objResultPac = $objQueryPac->result();
                
                if($this->db->_error_number()) {
                    $this->error = true;
                    $this->msg_error_usr = 'Error al obtener los datos para las gráficas';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception($this->msg_error_log);
                }
                
                foreach ($objResultPac as $pac) {
                    $puntos[] = array($pac->edad_meses, $pac->perimetro_cefalico);
                }
                break;
            case 'con_hemo':
                foreach ($objResultPac as $pac) {
                    if($pac->hemoglobina != 0 && $pac->hemoglobina != null && $pac->hemoglobina != '') {
                        $puntos[] = array($pac->edad_meses, $pac->hemoglobina);
                    }
                }
        }
        
        $series[] = array(
                'color'  => 'black',
                'label'  => ' &nbsp; Paciente',
                'data'   => $puntos,
                'points' => array( 'show' => true )
            );
        
        $puntos = NULL;
        
        // Obtiene datos de los catálogos
        if ($catalogo == 'con_hemo') {
            $objQueryDat = $this->db->query('SELECT mujer_embarazada_ninio_6_59_meses AS hb FROM asu_hemoglobina_altitud WHERE id_localidad_asu='.$asu_locali);
            
            if ($objQueryDat->num_rows() > 0) {
                $objResultDat = $objQueryDat->row();

                if($this->db->_error_number()) {
                    $this->error = true;
                    $this->msg_error_usr = 'Error al obtener los datos para las gráficas';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception($this->msg_error_log);
                }

                $puntos[] = array(0, $objResultDat->hb);
                $puntos[] = array(($edad_meses+3), $objResultDat->hb);

                $series[] = array(
                    'color' => 'blue',
                    'label' => ' &nbsp; Concentración de Hemoglobina',
                    'data'  => $puntos,
                );
            }
            
            $datos['labels'] = array('xaxes'=>'Edad (meses)', 'yaxes'=>'Hb (g/dL)');    
            $datos['series'] = $series;
        } else {
            switch($catalogo){
                case 'peso_edad':
                    $queryCatalogo = 'SELECT id, descripcion, color FROM cns_estado_nutricion_peso';
                    $queryDatos = 'SELECT edad_meses AS x, peso AS y FROM cns_edo_nutri_peso_x_edad WHERE sexo="'.$sexo.'" AND edad_meses<='.($edad_meses+3).' AND id_estado_nutricion_peso=';
                    $datos['labels'] = array('xaxes'=>'Edad (meses)', 'yaxes'=>'Peso (Kg)');
                    break;
                case 'peso_talla':
                    $queryCatalogo = 'SELECT id, descripcion, color FROM cns_estado_nutricion_peso';
                    $queryDatos = 'SELECT altura AS x, peso AS y FROM cns_edo_nutri_peso_x_altura WHERE sexo="'.$sexo.'" AND altura<='.($talla+10).' AND id_estado_nutricion_peso=';
                    $datos['labels'] = array('xaxes'=>'Talla (cm)', 'yaxes'=>'Peso (Kg)');
                    break;
                case 'talla_edad':
                    $queryCatalogo = 'SELECT id, descripcion, color FROM cns_estado_nutricion_altura';
                    $queryDatos = 'SELECT edad_meses AS x, altura AS y FROM cns_edo_nutri_altura_x_edad WHERE sexo="'.$sexo.'" AND edad_meses<='.($edad_meses+3).' AND id_estado_nutricion_altura=';
                    $datos['labels'] = array('xaxes'=>'Edad (meses)', 'yaxes'=>'Talla (cm)');
                    break;
                case 'imc':
                    $queryCatalogo = 'SELECT id, descripcion, color FROM cns_estado_imc';
                    $queryDatos = 'SELECT edad_meses AS x, imc AS y FROM cns_imc_x_edad WHERE sexo="'.$sexo.'" AND edad_meses<='.($edad_meses+3).' AND id_estado_imc=';
                    $datos['labels'] = array('xaxes'=>'Edad (meses)', 'yaxes'=>'IMC (Kg/m2)');
                    break;
                case 'peri_cefa':
                    $queryCatalogo = 'SELECT id, descripcion, color FROM cns_estado_peri_cefa';
                    $queryDatos = 'SELECT edad_meses AS x, perimetro AS y FROM cns_perimetro_cefalico WHERE sexo="'.$sexo.'" AND edad_meses<='.($edad_meses+3).' AND id_estado_peri_cefa=';
                    $datos['labels'] = array('xaxes'=>'Edad (meses)', 'yaxes'=>'Perímetro cefálico (cm)');
            }

            $objQueryCat = $this->db->query($queryCatalogo);
            $objResultCat = $objQueryCat->result();

            if($this->db->_error_number()) {
                $this->error = true;
                $this->msg_error_usr = 'Error al obtener los datos para las gráficas';
                $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                throw new Exception($this->msg_error_log);
            }
            
            foreach ($objResultCat as $cat) {
                $objQueryDat = $this->db->query($queryDatos.$cat->id);
                $objResultDat = $objQueryDat->result();
                
                if($this->db->_error_number()) {
                    $this->error = true;
                    $this->msg_error_usr = 'Error al obtener los datos para las gráficas';
                    $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception($this->msg_error_log);
                }
                
                $puntos = NULL;
                
                foreach ($objResultDat as $dat) {
                    $puntos[] = array($dat->x, $dat->y);
                }
                
                $series[] = array(
                    'color' => $cat->color,
                    'label' => ' &nbsp; '.$cat->descripcion,
                    'data'  => $puntos,
                );
            }
            
            $datos['series'] = $series;
        }
        
        return $datos;
	}
    
    /**
	 * @access public
	 *
	 * Obtiene los registros de perimetro cefalico asociados a una persona
	 * 
	 * @param		string 		$id       identificador de la persona
	 * @param		strin 		$order    nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function get_peri_cefa($id,$order="")
	{
		$this->db->select('*');
		$this->db->from('cns_control_peri_cefa');
		$this->db->where('id_persona', $id);
		if($order!="")
		$this->db->order_by($order, "asc");
		else
		$this->db->order_by("fecha", "ASC");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
    
    /**
	 * @access public
	 *
	 * Actualiza los registros de perimetro cefalico
	 *
	 * @return 		result()
	 *
	 */
	public function update_peri_cefa()
	{
		$id_asu_um = $this->umt;
		if ($this->db->delete('cns_control_peri_cefa', array('id_persona' => $this->id))) {
            for($index=0; $index<sizeof($this->peri_cefa); $index++){
                $datosPeriCefa = array(
                    'id_persona' => $this->id,
                    'fecha' => date('Y-m-d', strtotime($this->fecha_peri_cefa[$index])).' '.date('H:i:s'),
                    'perimetro_cefalico' => $this->peri_cefa[$index],
                    'id_asu_um' => $id_asu_um);

                $resultPeriCefa = $this->db->insert('cns_control_peri_cefa', $datosPeriCefa);
                if (!$resultPeriCefa)
                {
                    $this->msg_error_usr = "Error Perímetro Cefálico.";
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                }
            }
        }
	}
    
    /**
	 * @access public
	 *
	 * Obtiene los registros de estimulacion temprana asociados a una persona
	 * 
	 * @param		string 		$id       identificador de la persona
	 * @param		strin 		$order    nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function get_estimulacion($id,$order="")
	{
		$this->db->select('*');
		$this->db->from('cns_estimulacion_temprana');
		$this->db->where('id_persona', $id);
		if($order!="")
		$this->db->order_by($order, "asc");
		else
		$this->db->order_by("fecha", "ASC");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
    
    /**
	 * @access public
	 *
	 * Actualiza los registros de estimulacion temprana
	 *
	 * @return 		result()
	 *
	 */
	public function update_estimulacion()
	{
		$id_asu_um = $this->umt;
		if ($this->db->delete('cns_estimulacion_temprana', array('id_persona' => $this->id))) {
            for($index=0; $index<sizeof($this->estimulacion_fecha); $index++){
                $datosEstimulacion = array(
                    'id_persona' => $this->id,
                    'fecha' => date('Y-m-d', strtotime($this->estimulacion_fecha[$index])).' '.date('H:i:s'),
                    'tutor_capacitado' => $this->estimulacion_capacitado[$index],
                    'id_asu_um' => $id_asu_um);

                $resultEstimulacion = $this->db->insert('cns_estimulacion_temprana', $datosEstimulacion);
                if (!$resultEstimulacion)
                {
                    $this->msg_error_usr = "Error Estimulación Temprana.";
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                }
            }
        }
	}
    
    /**
     * Obtiene el listado de categorias de CIE10
     *
     * @access public
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getCategoriaCIE10()
    {
        $result = false;
        
        $this->db->select('*');
        $this->db->from('cns_categoria_cie10');
        $this->db->order_by('descripcion', 'ASC'); 
        
        $query = $this->db->get();
        $result = $query->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else if(empty($result)) {
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
        }

        return $result;
    }
    
    /**
     * Obtiene el listado de CIE10 correspondientes a una CIE10
     *
     * @access public
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getCIE10($categoria)
    {
        $result = false;
        
        $this->db->select('*');
        $this->db->from('cns_cie10');
        $this->db->where('id_categoria' , urldecode($categoria));
        $this->db->order_by('descripcion', 'ASC'); 
        
        $query = $this->db->get();
        $result = $query->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else if(empty($result)) {
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
        }

        return $result;
    }
    
    /**
     * Obtiene todas las consultas asociadas a un paciente
     *
     * @access public
     * @return object|boolean Devuelve el objeto con sus datos correspondientes, de lo contrario, false Si no se encontró el registro
     */
    public function getControlConsultas($idPersona)
    {
        $result = false;
        
        $this->db->select('*');
        $this->db->from('cns_control_consulta');
        $this->db->where('id_persona' , $idPersona);
        $this->db->order_by('fecha', 'ASC'); 
        
        $query = $this->db->get();
        $result = $query->result();

        if($this->db->_error_number()) {
            $this->error = true;
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
            $this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
            throw new Exception();
        } else if(empty($result)) {
            $this->msg_error_usr = 'No se encontraron registros en la busqueda';
        } else {
            foreach ($result as $idxConsulta => $consulta) {
                $this->db->select('descripcion');
                $this->db->from('cns_cie10');
                $this->db->where('id_cie10' , $consulta->clave_cie10);

                $query = $this->db->get();
                $cie10 = $query->row();
                
                $result[$idxConsulta]->descripCIE10 = $cie10->descripcion;
                
                $medicamentos = '';
                $idsMedicamentos = explode(',', $result[$idxConsulta]->id_tratamiento);
                
                foreach ($idsMedicamentos as $idMed) {
                    $this->db->select('descripcion');
                    $this->db->from('cns_tratamiento');
                    $this->db->where('id' , $idMed);

                    $query = $this->db->get();
                    $med = $query->row();

                    $medicamentos .= $med->descripcion.', ';
                }
                
                $result[$idxConsulta]->descripTratamiento = substr($medicamentos, 0, -2);
            }
        }

        return $result;
    }
    
    /**
	 * @access public
	 *
	 * Actualiza los registros de sales de rehidratacion oral
	 *
	 * @return 		result()
	 *
	 */
	public function update_sales()
	{
		$id_asu_um = $this->umt;
		if ($this->db->delete('cns_sales_rehidratacion', array('id_persona' => $this->id))) {
            for($index=0; $index<sizeof($this->sales_fecha); $index++){
                $datosSRO = array(
                    'id_persona' => $this->id,
                    'fecha' => date('Y-m-d', strtotime($this->sales_fecha[$index])).' '.date('H:i:s'),
                    'cantidad' => $this->sales_cantidad[$index],
                    'id_asu_um' => $id_asu_um);

                $resultSRO = $this->db->insert('cns_sales_rehidratacion', $datosSRO);
                if (!$resultSRO)
                {
                    $this->msg_error_usr = "Error Sales de Rehidratación Oral.";
                    $this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
                    throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
                }
            }
        }
	}
    
    /**
	 * @access public
	 *
	 * Obtiene los registros de sales de rehidratación oral asociados a una persona
	 * 
	 * @param		string 		$id       identificador de la persona
	 * @param		strin 		$order    nombre del campo para hacer el order by
	 *
	 * @return 		result()
	 *
	 */
	public function get_sales($id,$order="")
	{
		$this->db->select('*');
		$this->db->from('cns_sales_rehidratacion');
		$this->db->where('id_persona', $id);
		if($order!="")
		$this->db->order_by($order, "asc");
		else
		$this->db->order_by("fecha", "ASC");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception("(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message());
		}
		else
			return $query->result();
		return null;
	}
}
?>