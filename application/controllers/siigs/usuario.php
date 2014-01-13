<?php
/**
 * Controlador Usuario
 *
 * @author     	Rogelio
 * @created		2013-09-25
 */
class Usuario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		try{
			$this->load->helper('url');
			$this->load->library('Correo');
		}
		catch(Exception $e)
		{
	 		$this->template->write("content", $e->getMessage());
 			$this->template->render();
		}
	}

	/**
	 * Ofrece el inicio de sesión 
	 *
	 * @access		public
	 * @return 		void
	 */
	public function login()
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			$data['title'] = 'Inicio de sesión';
			$this->load->helper('form');
			$this->load->helper('url');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'trim|required');
			$this->form_validation->set_rules('clave', 'Clave', 'trim|required|md5');
			$data['msgResult'] = $this->session->flashdata('msgResult');
			$data['clsResult'] = $this->session->flashdata('clsResult');
			
			if ($this->form_validation->run() === FALSE)
			{
				$this->template->write_view('content',DIR_SIIGS.'/usuario/login', $data);
				$this->template->render();
				return;
			}
			else
			{
				$rowUser = $this->Usuario_model->authenticate($this->input->post('nombre_usuario'), $this->input->post('clave'));
				if ($rowUser)
				{
					if (!$rowUser->activo)
					{
						$data['msgResult'] = 'La cuenta de usuario proporcionada se encuentra inactiva.';
						$data['clsResult'] = 'warning';
					}
					else
					{
						// almacena en session las variables necesarias
						$this->session->set_userdata(USERNAME, strtoupper($rowUser->nombre_usuario));
						$this->session->set_userdata(USER_LOGGED, $rowUser->id);
						$this->session->set_userdata(GROUP_ID, strtoupper($rowUser->id_grupo));
						// obtiene los permisos del grupo al que pertenece el usuario logueado
						$this->load->model(DIR_SIIGS.'/Entorno_model');
						$this->session->set_userdata(PERMISSIONS, $this->Entorno_model->getPermissionsByGroup($rowUser->id_grupo));
						//var_dump($this->session->userdata(PERMISSIONS)); return;
						Bitacora_model::insert(DIR_SIIGS.'::'.__CLASS__.'::index', 'Sesion iniciada: '.strtoupper($rowUser->nombre_usuario));
						// redirige a la url de donde provino o a la predeterminada del sistema
						if (!$this->session->userdata(REDIRECT_TO))
						{
							$this->session->set_flashdata('msgResult', 'Inicio de sesión exitoso');
							$this->session->set_flashdata('clsResult', 'success');
							redirect(DIR_SIIGS.'/usuario','refresh'); // aca se debe poner la pagina HOME
						}
						else
							redirect($this->session->userdata(REDIRECT_TO, 'refresh'));
					}
				}
				else
				{
					$data['msgResult'] = 'Nombre de usuario o clave incorrecta.';
					$data['clsResult'] = 'warning';
				}
			}
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}
		$this->template->write_view('content',DIR_SIIGS.'/usuario/login', $data);
		$this->template->render();
	
	}

	/**
	 * Termina la sesión 
	 *
	 * @access		public
	 * @return 		void
	 */
	public function logout()
	{
		$this->load->helper('url');
		if ($this->session->userdata(USERNAME))
			Bitacora_model::insert(DIR_SIIGS.'::'.__CLASS__.'::index', 'Sesion finalizada: '.$this->session->userdata(USERNAME));
		// destruye la sesión y redirige al login
		$this->session->sess_destroy();
		redirect(DIR_SIIGS.'/usuario/login','refresh');
	}
	
	/**
	 * 1) Visualiza los usuarios existentes para su interacción CRUD
	 * 2) En caso de detectar un texto a buscar se filtran los usuarios existentes acorde a la búsqueda
	 *
	 * @access		public
	 * @param		int		$pag	número de página a visualizar (paginación)
	 * @return 		void
	 */
	public function index($pag = 0)
	{
		try{
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Catálogo de Usuarios';
			$this->load->helper('form');
			$this->load->library('pagination');
			
			$data['pag'] = $pag;
            $data['msgResult'] = $this->session->flashdata('msgResult');
            $data['clsResult'] = $this->session->flashdata('clsResult');
			
			// Configuración para el Paginador
			$configPag['base_url']   = '/'.DIR_SIIGS.'/usuario/index/';
			$configPag['first_link'] = 'Primero';
			$configPag['last_link']  = '&Uacute;ltimo';
			$configPag['uri_segment'] = '4';
			$configPag['total_rows'] = $this->Usuario_model->getNumRows($this->input->post('busqueda'));
			$configPag['per_page']   = REGISTROS_PAGINADOR;
			$this->pagination->initialize($configPag);
			if ($this->input->post('busqueda'))
				$data['users'] = $this->Usuario_model->getOnlyActives($this->input->post('busqueda'), FALSE, $configPag['per_page'], $pag);
			else 
				$data['users'] = $this->Usuario_model->getOnlyActives('', FALSE, $configPag['per_page'], $pag);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}
		//$this->load->view('usuario/index', $data);
 		$this->template->write_view('content',DIR_SIIGS.'/usuario/index', $data);
 		$this->template->render();

	}

	/**
	 * Visualiza los datos del usuario recibido
	 *
	 * @access		public
	 * @param		int 		$id 	id del usuario a visualizar
	 * @return 		void
	 */
	public function view($id)
	{
		try {
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$data['title'] = 'Ver detalles de usuario';
			$data['user_item'] = $this->Usuario_model->getById($id, true);
		}
		catch(Exception $e){
			$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
			$data['clsResult'] = 'error';
		}
 		$this->template->write_view('content',DIR_SIIGS.'/usuario/view', $data);
 		$this->template->render();
	}

	/**
	 * 1) Prepara el formulario para la inserción de un usuario nuevo
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @return 		void
	 */
	public  function insert()
	{
		if (empty($this->Usuario_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Crear un nuevo usuario';
		$this->load->model(DIR_SIIGS.'/grupo_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_grupo', 'Grupo', 'is_natural_no_zero');
		$this->form_validation->set_message('is_natural_no_zero', 'Debe seleccionar un grupo válido');
		$this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'trim|xss_clean|required|min_length[5]|max_length[15]|callback__ifUserExists');
		$this->form_validation->set_rules('clave', 'Clave', 'trim|xss_clean|required|min_length[5]|max_length[12]|matches[repiteclave]|md5');
		$this->form_validation->set_rules('repiteclave', 'Repetir Clave', 'trim|xss_clean|required');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[40]');
		$this->form_validation->set_rules('apellido_paterno', 'Apellido Paterno', 'trim|xss_clean|required|max_length[25]');
		$this->form_validation->set_rules('apellido_materno', 'Apellido Materno', 'trim|xss_clean|max_length[25]');
		$this->form_validation->set_rules('correo', 'Email', 'trim|required|valid_email|xss_clean|max_length[50]');
		$arrGrupos = $this->grupo_model->getAll();
		$data['grupos'][''] = '-- Seleccione una opción --';
		foreach ($arrGrupos as $grupo) 
		{
			$data['grupos'][$grupo->id] = $grupo->nombre;
		}
		
		if ($this->form_validation->run() === FALSE)
		{
	 		$this->template->write_view('content',DIR_SIIGS.'/usuario/insert', $data);
	 		$this->template->render();
		}
		else
		{
			try {
				$this->Usuario_model->setNombreUsuario(strtoupper($this->input->post('nombre_usuario')));
				$this->Usuario_model->setClave($this->input->post('clave'));
				$this->Usuario_model->setNombre($this->input->post('nombre'));
				$this->Usuario_model->setApellidoPaterno($this->input->post('apellido_paterno'));
				$this->Usuario_model->setApellidoMaterno($this->input->post('apellido_materno'));
				$this->Usuario_model->setCorreo($this->input->post('correo'));
				$this->Usuario_model->setActivo(true);
				$this->Usuario_model->setIdGrupo($this->input->post('id_grupo'));
				$this->Usuario_model->insert();
				$this->session->set_flashdata('msgResult', 'Registro agregado exitosamente');
				$this->session->set_flashdata('clsResult', 'success');
				Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario agregado: '.strtoupper($this->input->post('nombre_usuario')));
				redirect(DIR_SIIGS.'/usuario','refresh');
			}
			catch (Exception $e){
				$data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/usuario/insert', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * 1) Prepara el formulario para la modificación de un usuario existente
	 * 2) Realiza las validaciones necesarias sobre cada campo del registro
	 *
	 * @access		public
	 * @param		int 		$id 	id del usuario a modificar
	 * @return 		void
	 */
	public function update($id)
	{
		if (empty($this->Usuario_model))
			return false;
		if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
			show_error('', 403, 'Acceso denegado');
		$data['title'] = 'Modificar usuario';
		$this->load->model(DIR_SIIGS.'/grupo_model');
 		$this->load->helper('form');
 		$this->load->library('form_validation');
 		$this->form_validation->set_rules('id_grupo', 'Grupo', 'is_natural_no_zero');
 		$this->form_validation->set_message('is_natural_no_zero', 'Debe seleccionar un grupo válido');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required|max_length[40]');
		$this->form_validation->set_rules('apellido_paterno', 'Apellido Paterno', 'trim|xss_clean|required|max_length[25]');
		$this->form_validation->set_rules('apellido_materno', 'Apellido Materno', 'trim|xss_clean|max_length[25]');
		$this->form_validation->set_rules('correo', 'Email', 'trim|xss_clean|required|valid_email|max_length[50]');
		$arrGrupos = $this->grupo_model->getAll();
		$data['grupos'][''] = '-- Seleccione una opcion --';
		foreach ($arrGrupos as $grupo) 
		{
			$data['grupos'][$grupo->id] = $grupo->nombre;
		}
		$data['user_item'] = $this->Usuario_model->getById($id);
				
		if ($this->form_validation->run() === FALSE)
		{
			$this->template->write_view('content',DIR_SIIGS.'/usuario/update', $data);
			$this->template->render();
		}
		else
		{
			try {
				$this->Usuario_model->setId($id);
				$this->Usuario_model->setNombre($this->input->post('nombre'));
				$this->Usuario_model->setApellidoPaterno($this->input->post('apellido_paterno'));
				$this->Usuario_model->setApellidoMaterno($this->input->post('apellido_materno'));
				$this->Usuario_model->setCorreo($this->input->post('correo'));
				$this->Usuario_model->setActivo(0);
				if ($this->input->post('activo') == 'on')
					$this->Usuario_model->setActivo(1);
				$this->Usuario_model->setIdGrupo($this->input->post('id_grupo'));
				$this->Usuario_model->update();
				$this->session->set_flashdata('msgResult', 'Registro actualizado exitosamente');
				$this->session->set_flashdata('clsResult', 'success');
				Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario actualizado: '.$id);
				redirect(DIR_SIIGS.'/usuario','refresh');
			}
			catch (Exception $e){
				$data['clsResult'] = 'error';
				$data['msgResult'] = Errorlog_model::save($e->getMessage(), __METHOD__);
				$this->template->write_view('content',DIR_SIIGS.'/usuario/update', $data);
				$this->template->render();
			}
		}
	}

	/**
	 * Solicita la eliminación del usuario recibido
	 *
	 * @access		public
	 * @param		int 		$id 	id del usuario a eliminar
	 * @return 		void
	 */
	public function delete($id)
	{
		try {
			if (empty($this->Usuario_model))
				return false;
			if (!Usuario_model::checkCredentials(DIR_SIIGS.'::'.__METHOD__, current_url()))
				show_error('', 403, 'Acceso denegado');
			$this->Usuario_model->setId($id);
			$this->Usuario_model->delete();
			$this->session->set_flashdata('msgResult', 'Registro eliminado exitosamente');
			$this->session->set_flashdata('clsResult', 'success');
			Bitacora_model::insert(DIR_SIIGS.'::'.__METHOD__, 'Usuario eliminado: '.$id);
		}
		catch (Exception $e){
			$this->session->set_flashdata('clsResult', 'error');
			$this->session->set_flashdata('msgResult', Errorlog_model::save($e->getMessage(), __METHOD__));
		}
	    redirect(DIR_SIIGS.'/usuario','refresh');
	}
	
	/**
	 * Callback para validar que un nombre de usuario no se duplique
	 *
	 * @access		public
	 * @param		string		$username	nombre de usuario a validar
	 * @return 		boolean					false si el nombre de usuario ya existe, true si el nombre de usuario está disponible
	 */
	public function _ifUserExists($username) 
	{
		if (empty($this->Usuario_model))
			return false;
		$is_exist = null;
		try {
			$is_exist = $this->Usuario_model->getByUsername($username);
		}
		catch(Exception $e){
		}
		if ($is_exist) 
		{
			$this->form_validation->set_message(
					'_ifUserExists', 'El nombre de usuario seleccionado ya existe, intente con otro.'
			);
			return false;
		} 
		else 
		{
			if (!$this->Usuario_model->getMsgError())
				return true;
			else{
				$this->form_validation->set_message(
						'_ifUserExists', $this->Usuario_model->getMsgError()
				);
				return false;
			}
		}
	}
	
	//////////////// Recuperar contraseña y modificar datos de usuario
	
	function form_init()
	{
		$this->load->helper('form');     
		$this->load->helper('url');
		$data['title'] = 'Recordar Datos';
		$data['info'] = '';
		$this->template->write_view('content',DIR_SIIGS.'/usuario/recordar_datos',$data);	
		$this->template->render();
	}
	public function remember()
	{		
		$this->load->helper('form');     
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'trim|required');
		$this->form_validation->set_rules('correo', 'Correo', 'trim|required');
		$data['title'] = 'Recordar Datos';
		$data['info'] = '';
		if ($this->form_validation->run() === FALSE)
		{			
	 		$this->template->write_view('content',DIR_SIIGS.'/usuario/recordar_datos',$data);
	 		$this->template->render();
		}	
		else
		{
			$this->load->model(DIR_SIIGS.'/usuario_model');
			$datos=$this->usuario_model->check_data(strtoupper($this->input->post('nombre_usuario')),$this->input->post('correo'));

			if(sizeof($datos)==0)
			{
				$data['infoclass']= 'error';
				$data['msgResult']= 'No se pudo comprobar sus datos';
				
				$this->template->write_view('content',DIR_SIIGS.'/usuario/recordar_datos',$data);
	 			$this->template->render();
			}
			else
			{
				
				$todos = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
				$valor="";
				for($i=0;$i<64;$i++) 
				$valor.= substr($todos,rand(0,62),1);								
				
				$url="http://www.siigs.com/siigs/usuario/token/$valor";
				$subject="Cómo restablecer su contraseña de SIIGS";
				$adj[0]="resources/images/logo.png";
				$body="<html>
		<head>
		<title></title>
		<style type='text/css'>
			.ph, .pf{color:#666666;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding-top:15px;padding-left:20px;margin-bottom:0px;}
			.p{color:#666666;font-family:Arial, Helvetica, sans-serif;font-size:13px; margin-bottom:0px;}
			#content{width:650px;height:400px;}
			#imgheader{display:block;border:none;width:650px;height:50px;}
			#imgfooter{display:block;border:none;width:650px;height:50px;margin-top:10px;}
			#content_campos{width:650px;height:auto;min-width:650px;}
			ul {margin-top:0px;margin-bottom:0px;padding-left: 2.5em;line-height: 1.2em; font-size:13px}
			ul li{color:#666666;font-family:Tahoma, Geneva, sans-serif !important;font-size:13px}
            a {color:#333;}
		</style>
		</head>
		<body>
		<div id='content'>
			<img id='imgheader' src='logo.png' />
			<div id='content_campos'>
			  <p class='ph'>
				Hola, ".$datos->nombre." ".$datos->apellido_paterno." ".$datos->apellido_materno.":<br><br>

Para recuperar su cuenta del sistema SIIGS, deberá crear una nueva contraseña.
			  </p>
			  
				<table style='width:600px; margin-top:5px; margin-left:20px;'>					
					<tr><td class='ph'>
                    <b>Es fácil:</b><br><br>

1. Haga clic en el siguiente vínculo para abrir una ventana del navegador segura.<br>
2. Confirme que usted es el propietario de la cuenta y, a continuación, siga las instrucciones.
                    </td></tr>					
				</table>
                <br />
				
				<table style='width:600px; margin-top:5px; margin-left:20px;'>
					<tr><td><p class='p'><b>Restablecer su contraseña ahora::</b> </p></td></tr>
					<tr><td>
						<ul>
							<li><a href='$url'>Restablecer</a></li>							
						</ul>
						</td>
					</tr>
				</table>

				<table style='width:600px; margin-top:5px; margin-left:20px;'>
				  <tr>
				    <td align='left'><p class='pf'>No responda este correo electrónico, ya que no estamos controlando esta bandeja de entrada. Para comunicarse con nosotros, inicie sesión en su cuenta y haga clic en «Contáctenos» en la parte inferior de cualquier página.

Copyright © 2013. Todos los derechos reservados.</p></td>
			      </tr>
				  
			  </table>
				
			<br />
			<img id='imgfooter' src='footer_mail.png' />
		</div>
		</body>
		</html>";
				$from="SIIGS@siigs.com";
				$rto="";
				$correo=$datos->correo;
				$CC="";
				$CCO="";				
				
				$fecha=date("d/m/Y H:i:s");
				$fp = fopen(APPPATH."logs/recuperalog.siigs", "a");
				fputs($fp, "[$fecha][$valor][$correo]\r\n");
				fclose($fp); 
				$envio=$this->send_mail($subject,$body,$from,$rto,$correo,$CC,$CCO,$adj);
				if($envio)
				{
					$data['infoclass']= 'success';
					$data['msgResult']= 'Se ha enviado un correo con la informacion necesaria para restablecer sus datos';
				}
				else
				{
					$data['infoclass']= 'error';
					$data['msgResult']= 'No se pudo enviar el correo con la informacion necesaria para restablecer sus datos';
				}
				$this->template->write_view('content',DIR_SIIGS.'/usuario/enviar_correo',$data);
				$this->template->render();
			}

		}
	}
	public function token($str)
	{
		$variable="";
		
		$file = fopen(APPPATH."logs/recuperalog.siigs", "r") or exit("No se puedo abri!");
		while(!feof($file))
		{
			$variable.=fgets($file);
		}
		fclose($file);
		if(stripos($variable,$str))
		{
			$cad=substr($variable,stripos($variable,$str)+strlen($str),100);
			if(stripos($cad,'['))
			$cad=substr($cad,1,100);

			$correo=substr($cad,stripos($cad,"[")+1,stripos($cad,']')-1);
			$correo=str_replace("[","",$correo);
			$correo=str_replace("]","",$correo);
			$this->load->helper('form');     
			$this->load->helper('url');
			$this->load->library('form_validation');
			$this->load->model(DIR_SIIGS.'/usuario_model');
			$datos=$this->usuario_model->check_token($correo);
			$data["error"]="";
			if(sizeof($datos)!=0)
			{
				$data["title"]="Reset Contraseña";
				$data["info"]="1.- Compruebe sus datos ";
				$data["info2"]="2.- Escriba su nueva contraseña";
				$data["c"]=$str;
				$this->template->write_view('content',DIR_SIIGS.'/usuario/reset_pass',$data);
	 			$this->template->render();
			}
		}
		else
			show_error('', 403, 'El enlace ha caducado');
	}
    public function send_mail($subject,$body,$from,$rto,$correo,$CC,$CCO,$adj) 
	{
        $mail = new PHPMailer();
        $mail->IsSMTP();                                    // establecemos que utilizaremos SMTP
        $mail->SMTPAuth   = true;                           // habilitamos la autenticación SMTP
        $mail->SMTPSecure = "ssl";                          // establecemos el prefijo del protocolo 		
        $mail->Host       = "smtp.gmail.com";               // establecemos nuestro servidor 
        $mail->Port       = 465;                            // establecemos el puerto
        $mail->Username   = "ehlhihehchehrh@gmail.com";     // la cuenta de correo para el envio
        $mail->Password   = "mimoj98i";                     // password de la cuenta 
		$mail->CharSet    = 'utf-8';                        //Especifica el tipo de codificacion de caracteres.
		$mail->Timeout    = 20;                             //Tiempo maximo de espera para envio de correo.
	    $mail->IsHTML(true);                                //El correo esta en formato html.
       
        $mail->Subject    = $subject;                       // Asunto del mensaje
        $mail->Body       = $body;                          // Mensaje
		$mail->SetFrom   ($from);                           // Nombre a mostrar en el envio
        $mail->AddReplyTo($rto);                            // Si hay respuesta
        $mail->AddAddress($correo);                         // Destinatario
		if($CC!="")
			$mail->AddCC($CC);                              // Con copia a
		if($CCO!="")
			$mail->AddBCC($CCO);                            // Con copia oculta a
		if(sizeof($adj)>0)
		{
			for($i=0;$i<sizeof($adj);$i++)
        		$mail->AddAttachment($adj[$i]);             // Añade archivos adjuntos        
		}

        $exito = $mail->Send();
    	return $exito;
    }	
	public function reset()
	{		
		$data["error"]="";
		$str=$this->input->post('c');
		$data["c"]=$str;
		$this->load->helper('form');     
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'trim|required');
		$this->form_validation->set_rules('correo', 'Correo', 'trim|required');
		$this->form_validation->set_rules('pass','Contraseña', 'trim|required|min_length[5]|max_length[12]|matches[repiteclave]|md5');
		$data['title'] = 'Reset Contraseña';
		$data["info"]="1.- Compruebe sus datos ";
		$data["info2"]="2.- Escriba su nueva contraseña";
				
		if ($this->form_validation->run() === FALSE)
		{			
	 		$this->template->write_view('content',DIR_SIIGS.'/usuario/reset_pass',$data);
	 		$this->template->render();
		}	
		else
		{
			$variable="";
		
			$file = fopen(APPPATH."logs/recuperalog.siigs", "r") or exit("No se puedo abri!");
			while(!feof($file))
			{
				$variable.=fgets($file);
			}
			fclose($file);
			if(stripos($variable,$str))
			{
				$this->load->model(DIR_SIIGS.'/usuario_model');
				$datos=$this->usuario_model->check_data(strtoupper($this->input->post('nombre_usuario')),$this->input->post('correo'));
		
				if(sizeof($datos)==0)
				{
					$data["error"]="";
					$data['infoclass']= 'error';
					$data['msgResult']= 'No se pudo comprobar sus datos';
					$this->template->write_view('content',DIR_SIIGS.'/usuario/reset_pass',$data);
					$this->template->render();
				}
				else
				{
					$this->usuario_model->update_pass($this->input->post('pass'),$datos->id);															
				
					$pt=stripos($variable,$str);
					$cad=substr($variable,0,$pt);
					$cad.=substr($variable,($pt+64),strlen($variable)-($pt+64));
					$fp = fopen(APPPATH."logs/recuperalog.siigs", "w");
					fwrite($fp, $cad);
					fclose($fp);
				} 
				$data["info"]="";
				$data['infoclass']= 'success';
				$data['msgResult']= 'Se ha restablecido la contraseña';
				$this->template->write_view('content',DIR_SIIGS.'/usuario/enviar_correo',$data);
				$this->template->render();
			}
			else
				show_error('', 403, 'El enlace ha caducado');
		}
	}
	
	///////////// modificar datos de usuario
	
	public function load_update()
	{
		$this->load->helper('url');		
		$grupo=$this->session->userdata(GROUP_ID);
		$name=$this->session->userdata(USERNAME);
		$id=$this->session->userdata(USER_LOGGED);
		if ($id == "")
		{
			$this->template->write_view('content',DIR_SIIGS.'/usuario/login', $data);
			$this->template->render();
			return;
		}
		else
		{
			$this->load->model(DIR_SIIGS.'/usuario_model');
			$datos=$this->usuario_model->getuser($id);			
			$grup=$this->usuario_model->getgrupo($grupo);			
			$this->load->helper('form');     
			$this->load->helper('url');
			$data['title'] = 'Cambiar Datos';
			$data['info'] = '';
			$data['error'] = '';
			$data['correo'] = $datos->correo;
			$data['grupo'] = $grup->nombre;
			$data['nombre'] = $datos->nombre." ".$datos->apellido_paterno." ".$datos->apellido_materno;
			$this->template->write_view('content',DIR_SIIGS.'/usuario/cuenta_usuario',$data);	
			$this->template->render();
		}
	}
	public function update_info()
	{
		$this->load->model(DIR_SIIGS.'/usuario_model');
		$grupo=$this->session->userdata(GROUP_ID);
		$name=$this->session->userdata(USERNAME);
		$id=$this->session->userdata(USER_LOGGED);
		$datos=$this->usuario_model->getuser($id);			
		$grup=$this->usuario_model->getgrupo($grupo);
		
		$this->load->helper('form');     
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('correo', 'Correo', 'trim|required|valid_email|max_length[50]');
		$this->form_validation->set_rules('pass', 'Contraseña Actual', 'trim|required');
		$this->form_validation->set_rules('newpass','Nueva Contraseña', 'trim|required|min_length[5]|max_length[12]|matches[repiteclave]|md5');
		$data['title'] = 'Cambiar Datos';
		$data['info'] = '';
		$data['error'] = '';
		$data['correo'] = '';
		$data['grupo'] = $grup->nombre;
		$data['nombre'] = $datos->nombre." ".$datos->apellido_paterno." ".$datos->apellido_materno;
		if ($this->form_validation->run() === FALSE)
		{			
	 		$this->template->write_view('content',DIR_SIIGS.'/usuario/cuenta_usuario',$data);
	 		$this->template->render();
		}	
		else
		{
			$pass = md5($this->input->post('pass')); 
			$data['title'] = 'Cambiar Datos';
			$data['info'] = '';			
			$data['correo'] = '';
			$data['grupo'] = $grup->nombre;
			$data['nombre'] = $datos->nombre." ".$datos->apellido_paterno." ".$datos->apellido_materno;
			if($datos->clave==$pass)
			{
				$update=$this->usuario_model->update_user($id,$this->input->post('newpass'),$this->input->post('correo'));				
				if($update)
				{
					$data['error'] = '';
					$data['infoclass']= 'success';
					$data['msgResult']= 'Actualización correcta';
				}
				else
				{
					$data['error']='';
					$data['infoclass']= 'error';
					$data['msgResult']= 'Ocurrio un error al actualizar';
				}
			}
			else
			{
				$data['error'] = '';	
				$data['infoclass']= 'warning';
				$data['msgResult']= 'No coincide la contraseña actual';
			}
			$this->template->write_view('content',DIR_SIIGS.'/usuario/cuenta_usuario',$data);	
			$this->template->render();
		}
	}

	/**
	 *Acción para servir un array de objetos con los usuarios activos por grupo
	 *AJAX y devuelve un objeto JSON
	 *
	 * @param  int $grupo
	 * @return Object JSON
	 */
	public function getActivesByGroup($grupo)
	{
		try {
			if ($this->input->is_ajax_request())
			{
				$data['usuarios'] = $this->Usuario_model->getActivesByGroup($grupo);
				echo json_encode($data['usuarios']);
				exit;
			}
			else echo 'Acceso denegado';
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
}