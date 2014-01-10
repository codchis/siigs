<?php

class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
        if(!$this->session->userdata(USER_LOGGED)) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->library('form_validation');
            $this->template->write_view('content',DIR_SIIGS.'/usuario/login');
        }
		$this->template->render();
	}
}