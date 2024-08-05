<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_auth
{
	private $CI;
	public $session_auid = '';
	public $session_auname = '';
	public $session_auemail = '';
	public $session_aurid = '';
	public $session_company_profile_id = '';
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library('session');
		$this->CI->load->helper('url');
		$this->CI->load->model('secureRegions/Admin_common_model');
		$this->CI->load->model('Common_model');

		$this->session_auid = $this->CI->session->userdata('session_auid');
		$this->session_auname = $this->CI->session->userdata('session_auname');
		$this->session_auemail = $this->CI->session->userdata('session_auemail');
		$this->session_aurid = $this->CI->session->userdata('session_aurid');
		$this->session_company_profile_id = $this->CI->session->userdata('session_company_profile_id');
	}



	/****************************************************************
	 *HELPERS
	 ****************************************************************/

	//using
	function unset_only()
	{


		// Get all user data from session
		$user_data = $this->CI->session->all_userdata();

		// Loop through user data and unset all except essential keys
		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->CI->session->unset_userdata($key);
			}
		}
	}

	/****************************************************************
	 ****************************************************************/


	/**
	 * The check_user_status method verifies if the current user is logged in and active. It retrieves the user's data from the database. If the user is blocked or there is an issue retrieving the data, it clears the session, sets an error message, and redirects to the login page. If the user is logged in but the session is invalid, it also clears the session, sets an error message, and redirects to the login page. Additionally, if a screen lock is set, it redirects to the screen lock page. The method finally returns the user's data if all checks are passed
	 */
	//using
	function check_user_status()
	{


		// Initialize user_data variable
		$this->data['user_data'] = '';



		// Check if the session user ID is greater than 0 and the session name is not empty
		if ($this->session_auid > 0 && !empty($this->session_auname)) {


			// Retrieve admin user data
			$this->data['user_data'] = $this->CI->Admin_common_model->get_admin_user_data(array());


			// Check if user data is retrieved
			if (!empty($this->data['user_data'])) {
				// Check if the user status is not active (1)
				if ($this->data['user_data']->status != 1) {
					// Clear specific session data and redirect to login page
					$this->unset_only();
					$this->CI->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="icon fas fa-ban"></i> You are blocked by Management.
									</div>');
					$this->CI->session->unset_userdata('session_auid');
					REDIRECT(MAINSITE_Admin . 'login');
				}
			} else {
				// Clear specific session data and redirect to login page
				$this->unset_only();

				$this->CI->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again.
							</div>');
				$this->CI->session->unset_userdata('session_auid');
				REDIRECT(MAINSITE_Admin . 'login');
			}
		} else {



			// Clear specific session data and redirect to login page
			$this->unset_only();
			$this->CI->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-ban"></i> Session Out Please Try Again.
					</div>');
			$this->CI->session->unset_userdata('session_auid');
			REDIRECT(MAINSITE_Admin . 'login');
		}

		// Check if the screen lock session data is set and redirect if necessary
		$screen_lock = $this->CI->session->userdata('screen_lock');
		if (!empty($screen_lock)) {
			REDIRECT(MAINSITE_Admin . 'Screen-lock');
		}


		// Return the user_data
		return $this->data['user_data'];
	}



	//using
	function get_left_menu($is_master, $params = array(), $module_id = 0)
	{
		if (!empty($is_master) || true) {
			$menu = $this->CI->Admin_common_model->get_left_menu(array("is_master" => $is_master, "module_id" => $module_id));
			//$menu[] = $menu[0]->submenu;
			$display_menu = "";

			foreach ($menu as $m) {
				if (!empty($m->submenu)) {
					$display_menu .= $this->get_main_menu_html($m, $params);
				} else {
					$display_menu .= $this->get_sub_menu_html($m, $params);
				}
			}

			return $display_menu;
		} else {
			return false;
		}
	}


	//using
	function get_main_menu_html($obj, $params)
	{
		$active = "";
		$is_menu = '';
		//if() menu-open active
		$link = MAINSITE_Admin . $obj->class_name . '/' . $obj->function_name;

		$html = '<li class="nav-item has-treeview ' . $is_menu . '">
			<a href="#" class="nav-link ' . $active . '">
				<i class="nav-icon fas fa-circle"></i>
				<p>
				' . $obj->module_name;
		$html .= '<i class="right fas fa-angle-left"></i>';
		if (!empty($obj->data_count)) {
			$html .= '<span class="badge badge-info right">' . $obj->data_count . '</span>';
		}
		$html .= '</p></a><ul class="nav nav-treeview">';
		foreach ($obj->submenu as $s) {
			$html .= $this->get_sub_menu_html($s, $params);
		}
		$html .= "</ul></li>";
		return $html;
	}


	//using
	function get_sub_menu_html($obj, $params)
	{
		$active = "";
		if (!empty($params['page_module_id'])) {
			if ($params['page_module_id'] == $obj->module_id) {
				$active = "active";
			}
		}
		$link = MAINSITE_Admin . $obj->class_name . '/' . $obj->function_name;
		$html = '<li class="nav-item"><a href="' . $link . '" class="nav-link ' . $active . '">';
		if (!empty($obj->icon)) {
			$nav_icon = $obj->icon;
			$nav_icon = str_replace('#mainsite#', base_url(), $nav_icon);
			$html .= $nav_icon;
		} else {
			$html .= '<i class="far fa-circle nav-icon"></i>';
		}
		$html .= '<p>' . $obj->module_name;
		if (!empty($obj->data_count)) {
			$html .= '<span class="badge badge-info right">' . $obj->data_count . '</span>';
		}
		$html .= "</p></a></li>";
		return $html;
	}


	/**
	 * this method actualy run by Admin_common_model for checking the admin user access
	 */
	function check_user_access($params = array())
	{
		// Check if parameters are provided
		if (!empty($params)) {
			// Call the method in Admin_common_model to check user access based on provided parameters
			$menu = $this->CI->Admin_common_model->check_user_access($params);
			return $menu; // Return the result
		} else {
			return false; // If no parameters are provided, return false
		}
	}

	// function getData($params = array())
	// {
	// 	$this->CI->db->select($params['select']);
	// 	$this->CI->db->from($params['from']);
	// 	$this->CI->db->where("($params[where])");
	// 	if (!empty($params['limit'])) {
	// 		$this->CI->db->limit($params['limit']);
	// 	}
	// 	if (!empty($params['order_by'])) {
	// 		$this->CI->db->order_by($params['order_by']);
	// 	}
	// 	$query_get_list = $this->CI->db->get();
	// 	return $query_get_list->result();
	// }

	// function add_operation($params = array())
	// {
	// 	if (empty($params))
	// 		return false;
	// 	$status = $this->CI->db->insert($params['table'], $params['data']);
	// 	if ($status) {
	// 		$status = $status = $this->CI->db->insert_id();
	// 	}
	// 	return $status;
	// }

	// public function getFiscalYear()
	// {
	// 	$result = array();
	// 	$start = '';
	// 	$end = '';
	// 	$s_start = '';
	// 	$s_end = '';
	// 	if (date('m') < 4) {//Upto march 
	// 		$start = date('Y') - 1;
	// 		$end = date('Y');
	// 		$s_start = date('y') - 1;
	// 		$s_end = date('y');
	// 		//$financial_year = (date('Y')-1) . '-' . date('Y');
	// 	} else {//form April 
	// 		$start = date('Y');
	// 		$end = date('Y') + 1;
	// 		$s_start = date('y');
	// 		$s_end = date('y') + 1;
	// 		//$financial_year = date('Y') . '-' . (date('Y') + 1);
	// 	}

	// 	$work_year = date('Y');
	// 	$result['work_year'] = $work_year;
	// 	$result['start'] = $start;
	// 	$result['end'] = $end;
	// 	$result['short_start'] = $s_start;
	// 	$result['short_end'] = $s_end;
	// 	$result['financial_year'] = $work_year;
	// 	$result['short_financial_year'] = $s_start . '-' . $s_end;

	// 	return (object) $result;
	// }


}
