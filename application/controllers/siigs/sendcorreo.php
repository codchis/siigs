<?php
class Sendcorreo extends CI_Controller 
{
    public function __construct()
	{
        parent::__construct();
		$this->load->helper('url');
        $this->load->library('Correo');
    }
	function index()
	{
		$this->load->helper('form');     
		$this->load->helper('url');
		$data['title'] = 'Recordar Datos';
		$data['info'] = '';
		$this->template->write_view('content',DIR_SIIGS.'/usuario/recordar_datos',$data);	
		$this->template->render();
	}
	public function recordar()
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
			$this->load->model(DIR_SIIGS.'/Recordar_datos_model');
			$datos=$this->Recordar_datos_model->verificar_datos(strtoupper($this->input->post('nombre_usuario')),$this->input->post('correo'));

			if(sizeof($datos)==0)
			{
				$data["info"]="No se pudo comprobar sus datos";
				$this->template->write_view('content',DIR_SIIGS.'/usuario/recordar_datos',$data);
	 			$this->template->render();
			}
			else
			{
				
				$todos = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
				$valor="";
				for($i=0;$i<64;$i++) 
				$valor.= substr($todos,rand(0,62),1);								
				
				$url="http://www.siigs.com/siigs/sendcorreo/stamp/$valor";
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
				$fp = fopen("recuperalog.siigs", "a");
				fputs($fp, "[$fecha][$valor][$correo]\r\n");
				fclose($fp); 
				$envio=$this->send_mail($subject,$body,$from,$rto,$correo,$CC,$CCO,$adj);
				if($envio)
					$data["info"]="Se ha enviado un correo con la informacion necesaria para restablecer sus datos";
				else
					$data["info"]="No se pudo enviar el correo con la informacion necesaria para restablecer sus datos";
				$this->template->write_view('content',DIR_SIIGS.'/usuario/enviar_correo',$data);
				$this->template->render();
			}

		}
	}
	public function stamp($str)
	{
		$variable="";
		
		$file = fopen("recuperalog.siigs", "r") or exit("No se puedo abri!");
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
			$this->load->model(DIR_SIIGS.'/Recordar_datos_model');
			$datos=$this->Recordar_datos_model->verificar_stamp($correo);
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
		
			$file = fopen("recuperalog.siigs", "r") or exit("No se puedo abri!");
			while(!feof($file))
			{
				$variable.=fgets($file);
			}
			fclose($file);
			if(stripos($variable,$str))
			{
				$this->load->model(DIR_SIIGS.'/Recordar_datos_model');
				$datos=$this->Recordar_datos_model->verificar_datos(strtoupper($this->input->post('nombre_usuario')),$this->input->post('correo'));
		
				if(sizeof($datos)==0)
				{
					$data["error"]="No se pudo comprobar sus datos";
					$this->template->write_view('content',DIR_SIIGS.'/usuario/reset_pass',$data);
					$this->template->render();
				}
				else
				{
					$this->Recordar_datos_model->actualizar_pass($this->input->post('pass'),$datos->id);															
				}
				$pt=stripos($variable,$str);
				$cad=substr($variable,0,$pt);
				$cad.=substr($variable,($pt+64),strlen($variable)-($pt+64));
				$fp = fopen("recuperalog.siigs", "w");
				fwrite($fp, $cad);
				fclose($fp); 
				$data["info"]="Se ha restablecido la contraseña";
				$this->template->write_view('content',DIR_SIIGS.'/usuario/enviar_correo',$data);
				$this->template->render();
			}
			else
				show_error('', 403, 'El enlace ha caducado');
		}
	}
}