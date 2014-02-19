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
	
	// IRA
	private $ira= array();
	private $fira= array();
	private $tira= array();
	
	// EDA
	private $eda= array();
	private $feda= array();
	private $teda= array();
	
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
	private $fnutricion= array();
	
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
	
	public function getira()
	{
	    return $this->ira;
	}

	public function setira($value) 
	{
		$this->ira = $value;
	}
	
	public function getfira()
	{
	    return $this->fira;
	}

	public function setfira($value) 
	{
		$this->fira = $value;
	}
	
	public function gettira()
	{
	    return $this->tira;
	}

	public function settira($value) 
	{
		$this->tira = $value;
	}
	
	public function geteda()
	{
	    return $this->eda;
	}

	public function seteda($value) 
	{
		$this->eda = $value;
	}
	
	public function getfeda()
	{
	    return $this->feda;
	}

	public function setfeda($value) 
	{
		$this->feda = $value;
	}
	
	public function getteda()
	{
	    return $this->teda;
	}

	public function setteda($value) 
	{
		$this->teda = $value;
	}
	
	public function getconsulta()
	{
	    return $this->consulta;
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
	public function cns_update($tabla,$array,$id,$campo="", $valor="")
	{
		$this->db->where('id' , $id);
		if($campo!="")
			$this->db->where($campo , $valor);
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
			throw new Exception(__CLASS__);
		}
		else
		{
			$dat = array(
				'id_persona' => $this->id,
				'fecha_registro' => date('Y-m-d H:i:s', strtotime($this->fechacivil)),
				'id_localidad_registro_civil' => $this->lugarcivil,
			);
			$res = $this->db->insert('cns_registro_civil', $dat);
			$companiaT=$this->companiaT;
			if($companiaT=="")$companiaT=NULL;
			$this->setid($unico_id);
			$unico_idtutor=md5(uniqid());
			$data0 = array(
					// tutor
				'id' => $unico_idtutor,
				'nombre' => $this->nombreT,
				'apellido_paterno' => $this->paternoT,
				'apellido_materno' => $this->maternoT,
				'curp' => $this->curpT,
				'sexo' => $this->sexoT,
				
				'telefono' => $this->telefonoT,
				'id_operadora_celular' => $companiaT,
				'celular' => $this->celularT,
			);
			if($this->idtutor=="")
			{
				$companiaT=$this->companiaT;
				if($companiaT=="")$companiaT=NULL;
				
				$result0 = $this->db->insert('cns_tutor', $data0);
				if (!$result0)
				{
					$this->msg_error_usr = "No se guardo Tutor.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
					}
				}
			}
			
			for($i=0;$i<sizeof($this->vacuna);$i++)
			{
				$data2 = array(
					// vacuna
					'id_persona' => $this->id,
					'id_vacuna' => $this->vacuna[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fvacuna[$i])),
					'id_asu_um' => $id_asu_um,
					
				);
				if($this->vacuna[$i]!="")
				{
					$result2 = $this->db->insert('cns_control_vacuna', $data2);
					if (!$result2)
					{
						$this->msg_error_usr = "Error vacunas.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					}
				}
			}
			
			for($i=0;$i<sizeof($this->ira);$i++)
			{
				$t=$this->tira[$i];
				if($t=="")$t=1;
				$data3 = array(
					// ira
					'id_persona' => $this->id,
					'id_ira' => $this->ira[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fira[$i])),
					'id_asu_um' => $id_asu_um,
					'id_tratamiento' => $t,
					'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->fira[$i])),
					
				);
				if($this->ira[$i]!="")
				{
					$result3 = $this->db->insert('cns_control_ira', $data3);
					if (!$result3)
					{
						$this->msg_error_usr = "Error IRA.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					}
				}
			}
			
			for($i=0;$i<sizeof($this->eda);$i++)
			{
				$t=$this->teda[$i];
				if($t=="")$t=1;
				$data4 = array(
					// eda
					'id_persona' => $this->id,
					'id_eda' => $this->eda[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->feda[$i])),
					'id_asu_um' => $id_asu_um,
					'id_tratamiento' => $t,
					'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->feda[$i])),
					
				);
				if($this->eda[$i]!="")
				{
					$result4 = $this->db->insert('cns_control_eda', $data4);
					if (!$result4)
					{
						$this->msg_error_usr = "Error EDA.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					}
				}
			}
			
			for($i=0;$i<sizeof($this->consulta);$i++)
			{
				$t=$this->tconsulta[$i];
				if($t=="")$t=1;
				$data5 = array(
					// consulta
					'id_persona' => $this->id,
					'id_consulta' => $this->consulta[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
					'id_asu_um' => $id_asu_um,
					'id_tratamiento' => $t,
					'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
					
				);
				if($this->consulta[$i]!="")
				{
					$result5 = $this->db->insert('cns_control_consulta', $data5);
					if (!$result5)
					{
						$this->msg_error_usr = "Error Consulta.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					}
				}
			}
			
			for($i=0;$i<sizeof($this->accion_nutricional);$i++)
			{
				$data6 = array(
					// accion nutricional
					'id_persona' => $this->id,
					'id_accion_nutricional' => $this->accion_nutricional[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->faccion_nutricional[$i])),
					'id_asu_um' => $id_asu_um,
					
				);
				if($this->accion_nutricional[$i]!="")
				{
					$result6 = $this->db->insert('cns_control_accion_nutricional', $data6);
					if (!$result6)
					{
						$this->msg_error_usr = "Error Accion nutricional.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fnutricion[$i])),
					'id_asu_um' => $id_asu_um,
					
				);
				if($this->peso[$i]!=""||$this->altura[$i]!=""||$this->talla[$i]!="")
				{
					$result7 = $this->db->insert('cns_control_nutricional', $data7);
					if (!$result7)
					{
						$this->msg_error_usr = "Error Nutricion.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
			'fecha_nacimiento' => date('Y-m-d H:i:s', strtotime($this->fnacimiento)));
		$this->db->where('id' , $this->id);
		$result = $this->db->update('cns_persona', $data); 
		if (!$result)
		{
			$this->msg_error_usr = "Actualizacion Fallida.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
				'fecha' => date('Y-m-d H:i:s', strtotime($this->fvacuna[$i])),
				'id_asu_um' => $id_asu_um,
				
			);
			if($this->vacuna[$i]!="")
			{
				$result2 = $this->db->insert('cns_control_vacuna', $data2);
				if (!$result2)
				{
					$this->msg_error_usr = "Error actualizando vacunas.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza el control de ira del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_ira()
	{
		$id_asu_um=$this->umt;
		if ($this->db->delete('cns_control_ira', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->ira);$i++)
		{
			$t=$this->tira[$i];
			if($t=="")$t=1;
			$data3 = array(
				// ira
				'id_persona' => $this->id,
				'id_ira' => $this->ira[$i],
				'fecha' => date('Y-m-d H:i:s', strtotime($this->fira[$i])),
				'id_asu_um' => $id_asu_um,
				'id_tratamiento' => $t,
				'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->fira[$i])),
				
			);
			if($this->ira[$i]!="")
			{
				$result3 = $this->db->insert('cns_control_ira', $data3);
				if (!$result3)
				{
					$this->msg_error_usr = "Error  actualizando IRA.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				}
			}
		}
	}
	/**
	 * @access public
	 *
	 * Actualiza el control eda del paciente
	 *
	 * @return 		result()
	 *
	 */
	public function update_eda()
	{
		$id_asu_um=$this->umt;
		if ($this->db->delete('cns_control_eda', array('id_persona' => $this->id)))
		for($i=0;$i<sizeof($this->eda);$i++)
		{
			$t=$this->teda[$i];
			if($t=="")$t=1;
			$data4 = array(
				// eda
				'id_persona' => $this->id,
				'id_eda' => $this->eda[$i],
				'fecha' => date('Y-m-d H:i:s', strtotime($this->feda[$i])),
				'id_asu_um' => $id_asu_um,
				'id_tratamiento' => $t,
				'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->feda[$i])),
				
			);
			if($this->eda[$i]!="")
			{
				$result4 = $this->db->insert('cns_control_eda', $data4);
				if (!$result4)
				{
					$this->msg_error_usr = "Error actualizando EDA.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
			$t=$this->tconsulta[$i];
			if($t=="")$t=1;
			$data5 = array(
				// consulta
				'id_persona' => $this->id,
				'id_consulta' => $this->consulta[$i],
				'fecha' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
				'id_asu_um' => $id_asu_um,
				'id_tratamiento' => $t,
				'grupo_fecha_secuencial' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
				
			);
			if($this->consulta[$i]!="")
			{
				$result5 = $this->db->insert('cns_control_consulta', $data5);
				if (!$result5)
				{
					$this->msg_error_usr = "Error actualizando Consulta.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
				'fecha' => date('Y-m-d H:i:s', strtotime($this->faccion_nutricional[$i])),
				'id_asu_um' => $id_asu_um,
				
			);
			if($this->accion_nutricional[$i]!="")
			{
				$result6 = $this->db->insert('cns_control_accion_nutricional', $data6);
				if (!$result6)
				{
					$this->msg_error_usr = "Error actualizando Accion nutricional.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
				'fecha' => date('Y-m-d H:i:s', strtotime($this->fnutricion[$i])),
				'id_asu_um' => $id_asu_um,
				
			);
			if($this->peso[$i]!=""||$this->altura[$i]!=""||$this->talla[$i]!="")
			{
				$result7 = $this->db->insert('cns_control_nutricional', $data7);
				if (!$result7)
				{
					$this->msg_error_usr = "Error actualizando Nutricion.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
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
				$this->db->like('nombre', $cadena[0]);
				$this->db->like('apellido_paterno', $cadena[1]);
			}
			else if (count($cadena)==3)
			{
				$this->db->like('nombre', $cadena[0]);
				$this->db->like('apellido_paterno', $cadena[1]);
				$this->db->like('apellido_materno', $cadena[2]);
			}
			else if (count($cadena)==4)
			{
				$this->db->like('nombre', $cadena[0].' '.$cadena[1]);
				$this->db->like('apellido_paterno', $cadena[2]);
				$this->db->like('apellido_materno', $cadena[3]);
			}
			$query = $this->db->get();
		}
		
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
				$this->db->like('nombre', $cadena[0]);
				$this->db->like('apellido_paterno', $cadena[1]);
			}
			else if (count($cadena)==3)
			{
				$this->db->like('nombre', $cadena[0]);
				$this->db->like('apellido_paterno', $cadena[1]);
				$this->db->like('apellido_materno', $cadena[2]);
			}
			else if (count($cadena)==4)
			{
				$this->db->like('nombre', $cadena[0].' '.$cadena[1]);
				$this->db->like('apellido_paterno', $cadena[2]);
				$this->db->like('apellido_materno', $cadena[3]);
			}
			$query = $this->db->get();
		}
		if(!$query) 
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = '('.__METHOD__.') => '.$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
		
		$this->db->select('p.*,s.id as sangre, s.descripcion as tsangre, n.id as nacionalidadid, n.descripcion as nacionalidad, o.id as operadoraid,o.descripcion as operadora, t.id as idT, t.curp as curpT, t.nombre as nombreT, t.apellido_paterno as paternoT, t.apellido_materno as maternoT, t.sexo as sexoT, t.telefono as telefonoT, t.celular as celularT,o1.id as operadoraTid, o1.descripcion as operadoraT, rc.id_localidad_registro_civil');
		$this->db->from('cns_persona p');
		$this->db->join('cns_nacionalidad n', 'n.id = p.id_nacionalidad','left');
		$this->db->join('cns_tipo_sanguineo s', 's.id = p.id_tipo_sanguineo','left');
		$this->db->join('cns_operadora_celular o', 'o.id = p.id_operadora_celular','left');
		$this->db->join('cns_persona_x_tutor pt', 'pt.id_persona = p.id','left');
		$this->db->join('cns_tutor t', 't.id = pt.id_tutor','left');
		$this->db->join('cns_operadora_celular o1', 'o1.id = t.id_operadora_celular','left');
		$this->db->join('cns_registro_civil rc', 'rc.id_persona = p.id','left');
		$this->db->where('p.id', $id);
		$query = $this->db->get();
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
		$this->db->order_by($order, "desc"); 
		$query = $this->db->get();
		
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
		$this->db->order_by($order, "desc");
		$query = $this->db->get();
		
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
		$this->db->order_by($order2, "desc");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
		$this->db->order_by($order, "desc");
		else
		$this->db->order_by("fecha", "ASC");
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
		}
		else
			return $query->result();
		return null;
	}
	
	/**
	 * @access public
	 *
	 * Hace select de los tratamientos de las iras, edas y consulta
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
		$this->db->select('id, curp, nombre, apellido_paterno, apellido_materno, sexo, id_tipo_sanguineo, fecha_nacimiento, id_asu_localidad_nacimiento, calle_domicilio, numero_domicilio, colonia_domicilio, referencia_domicilio, ageb, manzana, sector, id_asu_localidad_domicilio, cp_domicilio, telefono_domicilio, fecha_registro, id_asu_um_tratante, celular, ultima_actualizacion, id_nacionalidad, id_operadora_celular, ultima_sincronizacion');
		$this->db->from("cns_persona");
		if($fecha!="")
		$this->db->where("ultima_actualizacion >=", $fecha);
		$this->db->where_in("id_asu_um_tratante", $array);
		$this->db->where("activo", "1");
		$this->db->where("(DATEDIFF(NOW(),fecha_nacimiento)/365)<",5);
		$query = $this->db->get(); 
		if (!$query)
		{
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
			throw new Exception(__CLASS__);
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
}
?>