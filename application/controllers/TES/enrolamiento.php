<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enrolamiento extends CI_Controller {

	public function index()
	{
		$this->load->helper('form');     
		$this->load->helper('url');
		$data["title"]="TES";
		$data["titulo"]="Enrolamiento";
		
		//$this->template->write_view('header',DIR_TES.'/header.php');
		//$this->template->write_view('menu',DIR_TES.'/menu.php');
		$this->template->write_view('content',DIR_TES.'/enrolamiento/enrolamiento.php',$data);
		//$this->template->write_view('footer',DIR_TES.'/footer.php');	
		$this->template->render();
	}
}