<?php
/**
 * Modelo Usuario
 *
 * @author     	Eliecer
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
	 * @variablas
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
	
	// direccion
	private $calle;
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
	
	// vacunacioin
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
	
	
	// inserta la informacion de una persona enrolada nueva
	public function insert()
	{
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
			'fecha_nacimiento' => $this->fnacimiento,
			
			// civil
			'fecha_registro' => $this->fechacivil,
			'id_asu_um_tratante' => $this->lugarcivil,
			
			// direccion
			'calle_domicilio' => $this->calle,
			'colonia_domicilio' => $this->colonia,
			'id_asu_localidad_domicilio' => $this->localidad,
			'numero_domicilio' => $this->numero,
			'cp_domicilio' => $this->cp,
			
			'telefono_domicilio' => $this->telefono,
			'id_operadora_celular' => $this->compania,
			'celuar' => $this->celular,
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
			$this->id=$this->db->insert_id();
			if($this->idtutor=="")
			{
				$data0 = array(
					// tutor
					'nombre' => $this->nombreT,
					'apellido_paterno' => $this->paternoT,
					'apellido_materno' => $this->maternoT,
					'curp' => $this->curpT,
					'sexo' => $this->sexoT,
					
					'telefono' => $this->telefonoT,
					'id_operadora_celular' => $this->companiaT,
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
					$this->setidtutor($this->db->insert_id());
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
					'id_ece_alergia' => $this->alergias[$i],
					
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
					// alergias
					'id_persona' => $this->id,
					'id_vacuna' => $this->vacuna[$i],
					'fecha' => $this->fvacuna[$i],
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
					// alergias
					'id_persona' => $this->id,
					'id_ira' => $this->ira[$i],
					'fecha' => $this->fira[$i],
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
					// alergias
					'id_persona' => $this->id,
					'id_eda' => $this->eda[$i],
					'fecha' => $this->feda[$i],
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
					// alergias
					'id_persona' => $this->id,
					'id_consulta' => $this->consulta[$i],
					'fecha' => $this->fconsulta[$i],
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
					// alergias
					'id_persona' => $this->id,
					'id_accion_nutricional' => $this->accion_nutricional[$i],
					'fecha' => $this->faccion_nutricional[$i],
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
			
			for($i=0;$i<sizeof($this->accion_nutricional);$i++)
			{
				$data7 = array(
					// alergias
					'id_persona' => $this->id,
					'peso' => $this->peso[$i],
					'altura' => $this->altura[$i],
					'talla' => $this->talla[$i],
					'fecha' => $this->faccion_nutricional[$i],
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
		return $result;
	}
	
	// actualiza la informacion del paciente
	public function update()
	{
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
			'fecha_nacimiento' => $this->fnacimiento,
			
			// civil
			'fecha_registro' => $this->fechacivil,
			'id_asu_um_tratante' => $this->lugarcivil,
			
			// direccion
			'calle_domicilio' => $this->calle,
			'colonia_domicilio' => $this->colonia,
			'id_asu_localidad_domicilio' => $this->localidad,
			'numero_domicilio' => $this->numero,
			'cp_domicilio' => $this->cp,
			
			'telefono_domicilio' => $this->telefono,
			'id_operadora_celular' => $this->compania,
			'celuar' => $this->celular,
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
			
			$data0 = array(
				// tutor
				'nombre' => $this->nombreT,
				'apellido_paterno' => $this->paternoT,
				'apellido_materno' => $this->maternoT,
				'curp' => $this->curpT,
				'sexo' => $this->sexoT,
				
				'telefono' => $this->telefonoT,
				'id_operadora_celular' => $this->companiaT,
				'celular' => $this->celularT,
				);
			$this->db->where('id' , $this->idtutor);
			$result0 = $this->db->update('cns_tutor', $data0);
			if (!$result0)
			{
				$this->msg_error_usr = "No se actualizo Tutor.";
				$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			}
			
			$data01 = array(
				'id_persona' => $this->id,
				'id_tutor' => $this->idtutor,						
				);
			$result0x = $this->db->delete('cns_persona_x_tutor', array('id_persona' => $this->id));
			if ($result0x)
			{
				$result01 = $this->db->insert('cns_persona_x_tutor', $data01);
				if (!$result01)
				{
					$this->msg_error_usr = "Error actualizando Tutor.";
					$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
				}
			}
			
			
			$id_asu_um=1;
			$result0x = $this->db->delete('cns_persona_x_alergia', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->alergias);$i++)
			{
				$data1 = array(
					// alergias
					'id_persona' => $this->id,
					'id_ece_alergia' => $this->alergias[$i],
					
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
			$result0x = $this->db->delete('cns_control_vacuna', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->vacuna);$i++)
			{
				$data2 = array(
					// alergias
					'id_persona' => $this->id,
					'id_vacuna' => $this->vacuna[$i],
					'fecha' => $this->fvacuna[$i],
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
			
			$result0x = $this->db->delete('cns_control_ira', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->ira);$i++)
			{
				$data3 = array(
					// alergias
					'id_persona' => $this->id,
					'id_ira' => $this->ira[$i],
					'fecha' => $this->fira[$i],
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
			$result0x = $this->db->delete('cns_control_eda', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->eda);$i++)
			{
				$data4 = array(
					// alergias
					'id_persona' => $this->id,
					'id_eda' => $this->eda[$i],
					'fecha' => $this->feda[$i],
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
			
			$result0x = $this->db->delete('cns_control_consulta', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->consulta);$i++)
			{
				$data5 = array(
					// alergias
					'id_persona' => $this->id,
					'id_consulta' => $this->consulta[$i],
					'fecha' => $this->fconsulta[$i],
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
			
			$result0x = $this->db->delete('cns_control_accion_nutricional', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->accion_nutricional);$i++)
			{
				$data6 = array(
					// alergias
					'id_persona' => $this->id,
					'id_accion_nutricional' => $this->accion_nutricional[$i],
					'fecha' => $this->faccion_nutricional[$i],
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
			
			$result0x = $this->db->delete('cns_control_nutricional', array('id_persona' => $this->id));
					if ($result0x)
			for($i=0;$i<sizeof($this->accion_nutricional);$i++)
			{
				$data7 = array(
					// alergias
					'id_persona' => $this->id,
					'peso' => $this->peso[$i],
					'altura' => $this->altura[$i],
					'talla' => $this->talla[$i],
					'fecha' => $this->faccion_nutricional[$i],
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
			
			$result0x = $this->db->delete('cns_persona_x_afiliacion', array('id_persona' => $this->id));
					if ($result0x)
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
		return $result;
	}
	// devuelve la lista de usuarios enrolados
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
	// devuelve el numero de resultados para la paginacion
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
	// obtener datos de un usuario enrolado
	public function getById($id)
	{
		//$query = $this->db->get_where('cns_persona', array('id' => $id));
		
		$this->db->select('p.*,s.id as sangre, s.descripcion as tsangre, n.id as nacionalidadid, n.descripcion as nacionalidad, o.id as operadoraid,o.descripcion as operadora, t.curp as curpT, t.nombre as nombreT, t.apellido_paterno as paternoT, t.apellido_materno as maternoT, t.sexo as sexoT, t.telefono as telefonoT, t.celular as celularT,o1.id as operadoraTid, o1.descripcion as operadoraT');
		$this->db->from('cns_persona p');
		$this->db->join('cns_nacionalidad n', 'n.id = p.id_nacionalidad','left');
		$this->db->join('cns_tipo_sanguineo s', 's.id = p.id_tipo_sanguineo','left');
		$this->db->join('cns_operadora_celular o', 'o.id = p.id_operadora_celular','left');
		$this->db->join('cns_persona_x_tutor pt', 'pt.id_persona = p.id','left');
		$this->db->join('cns_tutor t', 't.id = pt.id_tutor','left');
		$this->db->join('cns_operadora_celular o1', 'o1.id = t.id_operadora_celular','left');
		$this->db->where('p.id', $id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if (!$query){
			$this->msg_error_usr = "Servicio temporalmente no disponible.";
			$this->msg_error_log = "(". __METHOD__.") => " .$this->db->_error_number().': '.$this->db->_error_message();
			throw new Exception(__CLASS__);
		}
		else
			return $query->row();
		return;
	}
	// get alergias por persona
	public function getAlergia($id = '')
	{
		$this->db->select('a.id, a.descripcion');
		$this->db->from('cns_persona_x_alergia p');
		$this->db->join('cns_alergia a', 'a.id = p.id_ece_alergia');
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
	
	// get tipo afiliacion
	public function getAfiliaciones($id = '')
	{
		$this->db->select('a.id, a.descripcion');
		$this->db->from('cns_persona_x_afiliacion p');
		$this->db->join('cns_afiliacion a', 'a.id = p.id_afiliacion');
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
	// optiene informacion catalago persona
	public function get_catalog_view($catalog,$id)
	{
		$this->db->select('a.id, a.descripcion, p.fecha');
		$this->db->from('cns_control_'.$catalog.' p');
		$this->db->join('cns_'.$catalog.' a', 'a.id = p.id_'.$catalog);
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
	// optiene informacion control_nutricional
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
	// trae el catalogo seleccionado
	public function get_catalog($catalog)
	{
		$query = $this->db->get($catalog);
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
	// obtiene informacion del tutor
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
	// valida que no se repita curp
	public function getByCurp($curp,$tabla)
	{
		$query = $this->db->get_where($tabla, array('curp' => $curp));echo $this->db->last_query();
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