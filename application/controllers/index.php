<?php

class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
            $this->template->write_view('content','home');
		$this->template->render();
	}
}