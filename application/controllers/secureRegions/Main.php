<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

	function __construct()
	{



		parent::__construct();

		//db
		$this->load->database();

		//libraries
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('User_auth');


		//helpers
		$this->load->helper('url');
		$this->load->helper('form');

		//models
		$this->load->model('Common_model');
		$this->load->model('secureRegions/Admin_common_model');
		$this->load->model('secureRegions/Admin_model');



		//session data
		$session_auid = $this->data['session_auid'] = $this->session->userdata('session_auid');
		$this->data['session_auname'] = $this->session->userdata('session_auname');
		$this->data['session_auemail'] = $this->session->userdata('session_auemail');
		$this->data['session_aurid'] = $this->session->userdata('session_aurid');



		//checking logged in admin user status
		$this->data['User_auth_obj'] = new User_auth();

		$this->data['user_data'] = $this->data['User_auth_obj']->check_user_status();


		$this->data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);


	}


	/****************************************************************
	 *HELPERS
	 ****************************************************************/

	function unset_only()
	{
		$user_data = $this->session->all_userdata();
		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->session->unset_userdata($key);
			}
		}
	}

	/****************************************************************
	 ****************************************************************/

	/**
	 * loads the header.
	 */

	function get_header()
	{
		$this->load->view('secureRegions/inc/header', $this->data);
	}




	function get_left_nav()
	{
		$page_is_master = "";
		$page_parent_module_id = "";
		$page_module_id = "";
		//print_r($this->data['user_access']);
		if (!empty($this->data['page_is_master'])) {
			$page_is_master = $this->data['page_is_master'];
		}

		if (!empty($this->data['page_parent_module_id'])) {
			$page_parent_module_id = $this->data['page_parent_module_id'];
		}

		if (!empty($this->data['page_module_id'])) {
			$page_module_id = $this->data['page_module_id'];
		}

		$params_arr = array(
			"page_is_master" => $page_is_master,
			"page_parent_module_id" => $page_parent_module_id,
			"page_module_id" => $page_module_id
		);
		//print_r($params_arr);



		$modules = $this->Admin_common_model->get_role_modules(array("session_aurid" => $this->data['session_aurid']));


		$module_list_data = $modules;

		foreach ($module_list_data as $item) {
			$html = $this->data['User_auth_obj']->get_left_menu($item->is_master, $params_arr);
			$item->module_data = $html;
		}

		$this->data["module_list_data"] = $module_list_data;




		// $this->data['left_menu_master'] = $this->data['User_auth_obj']->get_left_menu(1, $params_arr);
		// $this->data['left_menu_employee'] = $this->data['User_auth_obj']->get_left_menu(2, $params_arr);
		// $this->data['left_menu_company_profile'] = $this->data['User_auth_obj']->get_left_menu(3, $params_arr);
		// $this->data['left_menu_catalog'] = $this->data['User_auth_obj']->get_left_menu(4, $params_arr);
		// $this->data['left_menu_enquiry'] = $this->data['User_auth_obj']->get_left_menu(5, $params_arr);
		// $this->data['left_menu_user_employee'] = $this->data['User_auth_obj']
		// 	->get_left_menu(11, $params_arr);

		// $this->data['left_menu_ip_address'] = $this->data['User_auth_obj']
		// 	->get_left_menu(12, $params_arr);
		// $this->data['left_menu_banner'] = $this->data['User_auth_obj']->get_left_menu(6, $params_arr);
		// $this->data['left_menu_gallery'] = $this->data['User_auth_obj']->get_left_menu(7, $params_arr);
		// $this->data['left_menu_videos'] = $this->data['User_auth_obj']->get_left_menu(9, $params_arr);
		// $this->data['left_menu_working_method'] = $this->data['User_auth_obj']->get_left_menu(10, $params_arr);
		// $this->data['left_menu_orders'] = $this->data['User_auth_obj']->get_left_menu(13, $params_arr);
		// $this->data['left_menu_customers'] = $this->data['User_auth_obj']->get_left_menu(8, $params_arr);

		$this->load->view('secureRegions/inc/left_nav', $this->data);
	}


	function get_left_nav1()
	{
		$page_is_master = "";
		$page_parent_module_id = "";
		$page_module_id = "";
		//print_r($this->data['user_access']);
		if (!empty($this->data['page_is_master'])) {
			$page_is_master = $this->data['page_is_master'];
		}

		if (!empty($this->data['page_parent_module_id'])) {
			$page_parent_module_id = $this->data['page_parent_module_id'];
		}

		if (!empty($this->data['page_module_id'])) {
			$page_module_id = $this->data['page_module_id'];
		}

		$params_arr = array(
			"page_is_master" => $page_is_master,
			"page_parent_module_id" => $page_parent_module_id,
			"page_module_id" => $page_module_id
		);
		$modules = $this->Admin_common_model->get_role_modules(array("session_aurid" => $this->data['session_aurid']));




		$all_modules_list = '';
		if (!empty($modules)) {
			foreach ($modules as $module) {
				$all_modules_list .= $this->data['User_auth_obj']->get_left_menu($module->is_master, $params_arr);
				// echo "<pre>";
				// print_r($all_modules_list);
				// echo "</pre>";
				// echo "string";
				// die;
			}
			//	die;
		}
		//	print_r($all_modules_list);die;
		$this->data['left_menu_module_list'] = $all_modules_list;
		$this->load->view('secureRegions/inc/left_nav', $this->data);
	}

	/**
	 * Generates the folter and loads the view.
	 */
	function get_footer()
	{
		$this->load->view('secureRegions/inc/footer', $this->data);
	}


}
