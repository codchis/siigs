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
	
	// EDA
	private $eda= array();
	private $feda= array();
	
	// Consulta
	private $consulta= array();
	private $fconsulta= array();
	
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
	 *Hace insert de las tablas cns_control_x que se reciben en la sincronizacion secuencial
	 *se recibe el parametro $tabla de tipo String que representa la tabla a la que se le hara la insercion
	 *el parametro $array tipo array() contiene los datos  
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
	 *Este metodo trabaja hace update de la sincronizacion de los datos que se repitan el id
	 *se recibe el parametro $tabla de tipo String que representa la tabla a la que se le hara la insercion
	 *el parametro $array tipo array() contiene los datos  
	 *
	 */
	public function cns_update($tabla,$array,$id)
	{
		$this->db->where('id' , $id);
		$result = $this->db->update($tabla, $array); $fp = fopen(APPPATH."logs/sinconizacionsecuencial.txt", "a");fputs($fp, $this->db->last_query()."\r\n"); //echo $this->db->last_query()."; <br>";
		if (!$result)
		{
			$this->msg_error_usr = "Error $tabla.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
		}
	}
	
	/**
	 *Hace insert de la persona capturada o enrolada en la parte web
	 *@return el resultado de la consulta
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
			
			$this->setid($unico_id);
			$unico_idtutor=md5(uniqid());
			if($this->idtutor=="")
			{
				$companiaT=$this->companiaT;
				if($companiaT=="")$companiaT=NULL;
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
				);
			$result01 = $this->db->insert('cns_persona_x_tutor', $data01);
			if (!$result01)
			{
				$this->msg_error_usr = "No se relaciono Tutor.";
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			}
			$id_asu_um=1;
			
			for($i=0;$i<sizeof($this->alergias);$i++)
			{
				$data1 = array(
					// alergias
					'id_persona' => $this->id,
					'id_alergia' => $this->alergias[$i],
					
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
				$data3 = array(
					// ira
					'id_persona' => $this->id,
					'id_ira' => $this->ira[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fira[$i])),
					'id_asu_um' => $id_asu_um,
					
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
				$data4 = array(
					// eda
					'id_persona' => $this->id,
					'id_eda' => $this->eda[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->feda[$i])),
					'id_asu_um' => $id_asu_um,
					
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
				$data5 = array(
					// consulta
					'id_persona' => $this->id,
					'id_consulta' => $this->consulta[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
					'id_asu_um' => $id_asu_um,
					
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
				if($this->peso[$i]!="")
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
		}//echo $this->db->last_query();
		if (!$result)
		{
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
		}	
	}
	public function valid_card($persona,$archivo)
	{
		$query = $this->db->get_where('tes_entorno_x_persona', array('id_persona' => $persona,"nombre_archivo"=>$archivo));
		return ($query->num_rows() >0);		
	}
	
	/**
	 *Actualiza la informacion del paciente enrolado
	 *@return el resulatdo de la consulta
	 */
	public function update()
	{
		$compania=$this->compania;
		if($compania=="")$compania=NULL;
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
		else
		{
			$dat = array(
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
			$res = $this->db->insert('cns_registro_civil', $dat);
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
			
			
			$id_asu_um=1;
			if ($this->db->delete('cns_persona_x_alergia', array('id_persona' => $this->id)))
			for($i=0;$i<sizeof($this->alergias);$i++)
			{
				$data1 = array(
					// alergias
					'id_persona' => $this->id,
					'id_alergia' => $this->alergias[$i],
					
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
			
			if ($this->db->delete('cns_control_ira', array('id_persona' => $this->id)))
			for($i=0;$i<sizeof($this->ira);$i++)
			{
				$data3 = array(
					// ira
					'id_persona' => $this->id,
					'id_ira' => $this->ira[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fira[$i])),
					'id_asu_um' => $id_asu_um,
					
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
			
			if ($this->db->delete('cns_control_eda', array('id_persona' => $this->id)))
			for($i=0;$i<sizeof($this->eda);$i++)
			{
				$data4 = array(
					// eda
					'id_persona' => $this->id,
					'id_eda' => $this->eda[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->feda[$i])),
					'id_asu_um' => $id_asu_um,
					
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
			
			if ($this->db->delete('cns_control_consulta', array('id_persona' => $this->id)))
			for($i=0;$i<sizeof($this->consulta);$i++)
			{
				$data5 = array(
					// consulta
					'id_persona' => $this->id,
					'id_consulta' => $this->consulta[$i],
					'fecha' => date('Y-m-d H:i:s', strtotime($this->fconsulta[$i])),
					'id_asu_um' => $id_asu_um,
					
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
				if($this->peso[$i]!="")
				{
					$result7 = $this->db->insert('cns_control_nutricional', $data7);
					if (!$result7)
					{
						$this->msg_error_usr = "Error actualizando Nutricion.";
						$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
					}
				}
			}
			
			if ($this->db->delete('cns_persona_x_afiliacion', array('id_persona' => $this->id)))
			for($i=0;$i<sizeof($this->afiliacion);$i++)
			{
				$data8 = array(
					// afiliacion
					'id_persona' => $this->id,
					'id_afiliacion' => $this->afiliacion[$i],
					
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
		return $this->id;
	}
	/**
	 *Hace update de la tableta que este sincronizando dependiendo del resultado
	 *se recibe el parametro $mac de tipo String que representa la tableta 
	 *parametro $status String que recibe en ese momento la tableta
	 *$version String version de la tableta
	 *$fecha datetime fecha del vento
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
	 *Este metodo retorna el ist de las personas enroladas
	 *se recibe el parametro $keywords de tipo String que representa la busqueda
	 *@return el resultado de la consulta 
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
			$this->db->like('curp', $keywords);
			$this->db->or_like('nombre', $keywords);
			$this->db->or_like('apellido_paterno', $keywords);
			$this->db->or_like('apellido_materno', $keywords);
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
	 *Devuelve el numero de filas en la tabla cns_persona
	 *se recibe el parametro $keywords de tipo String que representa la busqueda
	 *@return numero de filas
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
			$this->db->like('curp', $keywords);
			$this->db->or_like('nombre', $keywords);
			$this->db->or_like('apellido_paterno', $keywords);
			$this->db->or_like('apellido_materno', $keywords);
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
	 *Hace un select de las tablas cns_persona para general el view
	 *se recibe el parametro $id de tipo int que representa el id de la persona
	 *@return los datos de la persona
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
	 *Hace select de las alergias asociadas a una perssona
	 *se recibe el parametro $id de tipo int que representa el id de la persona
	 *@return result
	 *
	 */
	public function getAlergia($id = '')
	{
		$this->db->select('a.id, a.descripcion');
		$this->db->from('cns_persona_x_alergia p');
		$this->db->join('cns_alergia a', 'a.id = p.id_alergia','left');
		$this->db->where('p.id_persona', $id);
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
	 *Hace select  las afiliaciones asociadas a una persona
	 *se recibe el parametro $id de tipo int que representa el identificado de la persona
	 *@return result
	 *
	 */
	public function getAfiliaciones($id = '')
	{
		$this->db->select('a.id, a.descripcion');
		$this->db->from('cns_persona_x_afiliacion p');
		$this->db->join('cns_afiliacion a', 'a.id = p.id_afiliacion','left');
		$this->db->where('p.id_persona', $id);
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
	 *Hace select de los catalogos que tengan relacion con una persona para mostrarlos en el view
	 *se recibe el parametro $catalog de tipo String que representa la tabla 
	 *el parametro $id tipo int contiene el id de la persona  
	 *@return result
	 */
	public function get_catalog_view($catalog,$id)
	{
		$this->db->select('a.id, a.descripcion, p.fecha');
		$this->db->from('cns_control_'.$catalog.' p');
		$this->db->join('cns_'.$catalog.' a', 'a.id = p.id_'.$catalog,'left');
		$this->db->where('p.id_persona', $id);
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
	 *Hace select de las tabla
	 *se recibe el parametro $id de tipo int 
	 *@return result
	 *
	 */
	public function get_control_nutricional($id)
	{
		$this->db->select('*');
		$this->db->from('cns_control_nutricional');
		$this->db->where('id_persona', $id);
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
	 *Hace select de las tablas cns_x que representa a los catalogos
	 *se recibe el parametro $catalog de tipo String que representa la tabla 
	 *el parametro $campo tipo string contiene un campo para el where si haci se requiere
	 *el parametro $id tipo string contiene el valor para hacer el where
	 * y el parametro orden para incluir un ordenamiento representa un campo de la tabla
	 *@return result
	 */
	public function get_catalog($catalog,$campo="",$id="",$orden="")
	{
		$this->db->select('*');
		$this->db->from($catalog);
		if($id!="")
		$this->db->where($campo, $id);
		$this->db->where('activo', 1);
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
	 *obtiene el count de una tabla
	 *se recibe el parametro $catalog de tipo String que representa la tabla 
	 *@return un entero
	 *
	 */
	public function get_catalog_count($catalog)
	{
		return $this->db->count_all($catalog);
	}
	
	/**
	 *Hace select de las tablas que se le pasen como parametro
	 *se recibe el parametro $catalog de tipo String que representa la tabla 
	 *el parametro $campo1 y $campo2 tipo string son campos dentro de esa tabla para hacer el where
	 *el parametro id1 y id2 son el valor para hacer el where
	 *y el los parametros l1 y l2 son para hacer el limite de una consulta
	 *@return result
	 */
	public function get_catalog2($catalog,$campo1="",$id1="",$campo2="",$id2="",$l1="",$l2="")
	{
		if($catalog=="tes_notificacion")
		$this->db->select('id,titulo,contenido,fecha_inicio,fecha_fin');
		else if($catalog=="asu_arbol_segmentacion")
		$this->db->select('id,grado_segmentacion,id_padre,orden, visible, descripcion');
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
	 *obtiene catalogos relevante x entorno para la sincronizacion
	 *
	 *@return result
	 *
	 */
	public function get_catalog_relevante()
	{
		$this->db->select('*');
		$this->db->from('cns_catalogo_relevante_x_entorno r');
		$this->db->join('cns_tabla_catalogo c', 'c.id = r.id_tabla_catalogo','left');
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
	 *Obtiene las transacciones relevante spara la sincronizacion
	 *
	 *@return result
	 *
	 */
	public function get_transaction_relevante()
	{
		$this->db->select('*');
		$this->db->from('cns_transaccion_relevante_x_entorno r');
		$this->db->join('cns_tabla_transaccion c', 'c.id = r.id_tabla_transaccion','left');
		$query = $this->db->get(); //echo $this->db->last_query();
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
	 *obtiene cual es la ultima version de apk de la tableta
	 *
	 *@retun result 
	 *
	 */
	public function get_version()
	{
		$this->db->select('host');
		$this->db->select_max('version');
		$this->db->from('tes_version');
		$query = $this->db->get(); //echo $this->db->last_query();
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
	 *obtiene informacion del tutor
	 *se recibe el parametro $curp de tipo String 
	 *@retun result
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
	 *obtiene informacion del tutor para genberar el autocomplete
	 *se recibe el parametro $keywords de tipo String para hacer la busqueda
	 *@return result
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
	 *valida que no se repita la curp en personas y tutor
	 *se recibe el parametro $tabla de tipo String que representa la tabla 
	 *el parametro $curp tipo string para el where y el id de la persona
	 *@return result
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
	public function getMsgError($value = 'usr')
	{
		if ($value == 'log')
			return $this->msg_error_log;
		return $this->msg_error_usr;
	}	
}
?>