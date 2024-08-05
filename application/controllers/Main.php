<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		//models
		$this->load->model('Common_model');

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->data['active_left_menu'] = '';

		$this->data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);




	}


	public function get_header($pageName, $data)
	{
		$this->data = $data;
		if (empty($this->data['js'])) {
			$this->data['js'] = array();
		}
		// $this->data['check_screen'] = $this->Common_model->checkScreen();
		$this->load->view("inc/$pageName", $this->data);
	}

	public function get_footer($pageName, $data)
	{
		$this->data = $data;
		$this->load->view("inc/$pageName", $this->data);
	}







}
