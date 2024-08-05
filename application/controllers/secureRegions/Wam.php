<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once ("Main.php");
class Wam extends Main
{

	function __construct()
	{




		parent::__construct();



		//db
		$this->load->database();

		//libraries
		$this->load->library('session');
		$this->load->library('User_auth');


		//helpers
		$this->load->helper('url');

		//models
		$this->load->model('Common_model');
		$this->load->model('secureRegions/Admin_common_model');
		$this->load->model('secureRegions/Admin_model');
		$this->load->model('secureRegions/human_resource/Admin_user_model');
		//session data
		$session_auid = $this->data['session_auid'] = $this->session->userdata('session_auid');
		$this->data['session_auname'] = $this->session->userdata('session_auname');
		$this->data['session_auemail'] = $this->session->userdata('session_auemail');
		$this->data['session_aurid'] = $this->session->userdata('session_aurid');
		$this->data['session_company_profile_id'] = $this->session->userdata('session_company_profile_id');

		// getting user data from User_auth
		$this->data['User_auth_obj'] = new User_auth();
		$this->data['user_data'] = $this->data['User_auth_obj']->check_user_status();

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
	 * LOADS DASHBOARD
	 */
	function index()
	{






		if (empty($this->data['session_auid']) && empty($this->data['session_auname']) && empty($this->data['session_auemail'])) {




			// Redirect to the main admin page(default dashboard) if the user is already logged in and the screen is not locked
			REDIRECT(MAINSITE_Admin . "login");
		}





		parent::get_header();

		parent::get_left_nav();

		$this->load->view('secureRegions/dashboard', $this->data);
		parent::get_footer();
	}

	function dashboard()
	{
		parent::get_header();
		parent::get_left_nav();
		$this->load->view('welcome_message');
		parent::get_footer();
	}


	// ADMIN lOGOUT METHOD
	function logout()
	{
		// Call the unset_only method to clear specific session data (details of this method are not provided)
		$this->unset_only();
		$alert_message = '<div class="alert alert-success alert-dismissible"><div type="button" class="close" 
		data-dismiss="alert" aria-hidden="true">×</div><i class="icon fas fa-check"></i> You Are Successfully Logout. </div>';
		// Set a flash data message indicating successful logout, INSED "secureRegions/Login.php"'s index() method we set this to 	$this->data['alert_message'] 
		$this->session->set_flashdata('alert_message', $alert_message);

		// Unset the session data for 'sess_current_uid' to remove the user ID from the session
		$this->session->unset_userdata('session_auid');

		// Redirect to the login page
		REDIRECT(MAINSITE_Admin . 'login');
	}


	/**
	 * Loads the admin profile,
	 */
	function view_profile()
	{










		$this->data['tab_type'] = 'profile';

		if (!empty($_POST['update_password_button'])) {
			$this->data['tab_type'] = $_POST['tab_type'];
			$old_password = $_POST['old_password'];
			$new_password = $_POST['new_password'];
			$re_new_password = $_POST['re_new_password'];

			if ($new_password === $re_new_password) {
				if ($this->data['user_data']->password == md5($old_password)) {
					$enter_data['updated_on'] = date("Y-m-d H:i:s");
					$enter_data['updated_by'] = $this->data['session_auid'];
					$enter_data['show_password'] = $new_password;
					$enter_data['password'] = md5($new_password);
					$insertStatus = $this->Common_model->update_operation(array('table' => 'admin_user', 'data' => $enter_data, 'condition' => "admin_user_id = " . $this->data['session_auid']));
					if (!empty($insertStatus)) {
						$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check"></i> Pasword Updated Successfully </div>';
						$this->session->set_flashdata('alert_message', $alert_message);
						REDIRECT(MAINSITE_Admin . "wam/view-profile");
					} else {
						$this->data['profile_alert_massage'] = '<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<i class="icon fas fa-check"></i> Something Went Wrong Please Try Again.
						</div>';
					}
				} else {
					$this->data['profile_alert_massage'] = '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-check"></i> You Entered Wrong Password.
					</div>';
				}
			} else {
				$this->data['profile_alert_massage'] = '<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<i class="icon fas fa-check"></i> Password And Re Enter Password Doesnot Match.
				</div>';

			}

		}

		if (!empty($_POST['update_username_button'])) {
			$this->data['tab_type'] = $_POST['tab_type'];
			$password = $_POST['password'];
			$username = $_POST['user_name'];

			if ($this->data['user_data']->password == md5($password)) {
				$is_exist = $this->Common_model->get_data(array('select' => '*', 'from' => 'admin_user', 'where' => "username = '$username' and admin_user_id != " . $this->data['session_auid']));

				if (empty($is_exist)) {
					$enter_data['updated_on'] = date("Y-m-d H:i:s");
					$enter_data['updated_by'] = $this->data['session_auid'];
					$enter_data['username'] = $username;
					$insertStatus = $this->Common_model->update_operation(array('table' => 'admin_user', 'data' => $enter_data, 'condition' => "admin_id = " . $this->data['session_auid']));
					if (!empty($insertStatus)) {
						$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check"></i> User Name Updated Successfully </div>';
						$this->session->set_flashdata('alert_message', $alert_message);
						REDIRECT(MAINSITE_Admin . "wam/view-profile");
					} else {
						$this->data['profile_alert_massage'] = '<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<i class="icon fas fa-check"></i> Something Went Wrong Please Try Again.
						</div>';
					}
				} else {
					$this->data['profile_alert_massage'] = '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-check"></i> Username You Entered Is Not Available, Please Try Again.
					</div>';
				}

			} else {
				$this->data['profile_alert_massage'] = '<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<i class="icon fas fa-check"></i> You Entered Wrong Password.
				</div>';
			}

		}

		$this->data['admin_user_data'] = $this->Admin_user_model->get_admin_user_data(array("admin_user_id" => $this->data['session_auid'], 'details' => 1));
		$this->data['admin_user_data'] = $this->data['admin_user_data'][0];

		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/view_profile', $this->data);
		parent::get_footer();
	}


	/**
	 * LOCK THE SCREEN button action
	 */
	function lock_screen()
	{
		$this->session->set_userdata('screen_lock', "lock_the_screen");
		REDIRECT(MAINSITE_Admin . 'Screen-Lock');
	}

	function set_company()
	{
		$this->session->set_userdata('session_company_profile_id', $_POST['session_company_profile_id']);
		REDIRECT(MAINSITE_Admin . 'wam');
	}





	function access_denied()
	{
		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/access_denied', $this->data);
		parent::get_footer();
	}


	function page_not_found()
	{
		$this->load->view('secureRegions/page_not_found', $this->data);
	}






}
