<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once (APPPATH . "controllers/secureRegions/Main.php");
class Admin_user_module extends Main
{

	function __construct()
	{
		parent::__construct();

		//db
		$this->load->database();

		//libraries
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->library('User_auth');

		//helpers
		$this->load->helper('url');

		//models
		$this->load->model('Common_model');
		$this->load->model('secureRegions/human_resource/Admin_user_model');

		//session data
		$session_auid = $this->data['session_auid'] = $this->session->userdata('session_auid');
		$this->data['session_auname'] = $this->session->userdata('session_auname');
		$this->data['session_auemail'] = $this->session->userdata('session_auemail');
		$this->data['session_aurid'] = $this->session->userdata('session_aurid');

		$this->data['page_module_name'] = 'Employee';
		$this->data['table_name'] = 'admin_user';
		$this->data['page_module_id'] = 6;

		//admin status
		$this->data['User_auth_obj'] = new User_auth();
		$this->data['user_data'] = $this->data['User_auth_obj']->check_user_status();

		//headers
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");

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


	function index()
	{

		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/human_resource/Admin_user_module/list', $this->data);
		parent::get_footer();
	}

	function list()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));



		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}




		$search = array();
		$field_name = '';
		$field_value = '';
		$end_date = '';
		$start_date = '';
		$record_status = "";
		$country_id = "";
		$state_id = "";
		$city_id = "";
		$admin_user_role_id = "";
		$designation_id = "";

		if (!empty($_REQUEST['field_name']))
			$field_name = $_POST['field_name'];
		else if (!empty($field_name))
			$field_name = $field_name;

		if (!empty($_REQUEST['field_value']))
			$field_value = $_POST['field_value'];
		else if (!empty($field_value))
			$field_value = $field_value;

		if (!empty($_POST['end_date']))
			$end_date = $_POST['end_date'];

		if (!empty($_POST['start_date']))
			$start_date = $_POST['start_date'];

		if (!empty($_POST['record_status']))
			$record_status = $_POST['record_status'];

		if (!empty($_POST['country_id']))
			$country_id = $_POST['country_id'];

		if (!empty($_POST['state_id']))
			$state_id = $_POST['state_id'];

		if (!empty($_POST['city_id']))
			$city_id = $_POST['city_id'];

		if (!empty($_POST['admin_user_role_id']))
			$admin_user_role_id = $_POST['admin_user_role_id'];

		if (!empty($_POST['designation_id']))
			$designation_id = $_POST['designation_id'];



		//this is useful to retian values inside search panel after selecting country or any other options
		$this->data['field_name'] = $field_name;
		$this->data['field_value'] = $field_value;
		$this->data['end_date'] = $end_date;
		$this->data['start_date'] = $start_date;
		$this->data['record_status'] = $record_status;
		$this->data['country_id'] = $country_id;
		$this->data['state_id'] = $state_id;
		$this->data['city_id'] = $city_id;
		$this->data['admin_user_role_id'] = $admin_user_role_id;
		$this->data['designation_id'] = $designation_id;

		$search['end_date'] = $end_date;
		$search['start_date'] = $start_date;
		$search['field_value'] = $field_value;
		$search['field_name'] = $field_name;
		$search['record_status'] = $record_status;
		$search['country_id'] = $country_id;
		$search['state_id'] = $state_id;
		$search['city_id'] = $city_id;
		$search['admin_user_role_id'] = $admin_user_role_id;
		$search['designation_id'] = $designation_id;
		$search['search_for'] = "count";



		//getting count
		$data_count = $this->Admin_user_model->get_admin_user_data($search);
		$r_count = $this->data['row_count'] = $data_count[0]->counts;


		//deleting count parameter
		unset($search['search_for']);

		$offset = (int) $this->uri->segment(5); //echo $offset;
		if ($offset == "") {
			$offset = '0';
		}

		$per_page = _all_pagination_;

		$this->load->library('pagination');
		$config['base_url'] = MAINSITE_Admin . $this->data['user_access']->class_name . '/' . $this->data['user_access']->function_name . '/';
		$config['total_rows'] = $r_count;
		$config['uri_segment'] = '5';
		$config['per_page'] = $per_page;
		$config['num_links'] = 4;
		$config['first_link'] = '&lsaquo; First';
		$config['last_link'] = 'Last &rsaquo;';
		$config['prev_link'] = 'Prev';
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
		$config['attributes'] = array('class' => 'paginationClass');


		$this->pagination->initialize($config);

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;

		$search['details'] = 1;

		$search['limit'] = $per_page;
		$search['offset'] = $offset;


		$this->data['country_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'country', 'where' => "country_id > 0", "order_by" => "country_name ASC"));
		$this->data['users_role_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'admin_user_role', 'where' => "admin_user_role_id > 0", "order_by" => "admin_user_role_name ASC"));
		$this->data['designation_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'designation', 'where' => "designation_id > 0", "order_by" => "designation_name ASC"));

		$this->data['admin_user_data'] = $this->Admin_user_model->get_admin_user_data($search);


		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/human_resource/Admin_user_module/list', $this->data);
		parent::get_footer();
	}

	function list_export()
	{
		$this->data['page_type'] = "list";
		$this->data['page_module_id'] = 12;
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));
		//print_r($this->data['user_access']);
		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		if ($this->data['user_access']->export_data != 1) {
			$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Export " . $user_access->module_name);
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}
		$search = array();
		$field_name = '';
		$field_value = '';
		$end_date = '';
		$start_date = '';
		$record_status = "";
		$country_id = "";
		$state_id = "";

		if (!empty($_REQUEST['field_name']))
			$field_name = $_POST['field_name'];
		else if (!empty($field_name))
			$field_name = $field_name;

		if (!empty($_REQUEST['field_value']))
			$field_value = $_POST['field_value'];
		else if (!empty($field_value))
			$field_value = $field_value;

		if (!empty($_POST['end_date']))
			$end_date = $_POST['end_date'];

		if (!empty($_POST['start_date']))
			$start_date = $_POST['start_date'];

		if (!empty($_POST['record_status']))
			$record_status = $_POST['record_status'];

		if (!empty($_POST['country_id']))
			$country_id = $_POST['country_id'];

		if (!empty($_POST['state_id']))
			$state_id = $_POST['state_id'];


		$this->data['field_name'] = $field_name;
		$this->data['field_value'] = $field_value;
		$this->data['end_date'] = $end_date;
		$this->data['start_date'] = $start_date;
		$this->data['record_status'] = $record_status;
		$this->data['country_id'] = $country_id;
		$this->data['state_id'] = $state_id;

		$search['end_date'] = $end_date;
		$search['start_date'] = $start_date;
		$search['field_value'] = $field_value;
		$search['field_name'] = $field_name;
		$search['record_status'] = $record_status;
		$search['country_id'] = $country_id;
		$search['state_id'] = $state_id;
		$search['details'] = 1;

		$this->data['admin_user_data'] = $this->Admin_user_model->get_admin_user_data($search);


		$this->load->view('secureRegions/human_resource/Admin_user_module/list_export', $this->data);
	}

	function view($admin_user_id = "")
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));


		if (empty($admin_user_id)) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close"
			 data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. </div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}


		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}


		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;


		$this->data['admin_user_data'] = $this->Admin_user_model->get_admin_user_data(array("admin_user_id" => $admin_user_id, "details" => 1));


		//if there is no data for given admin_user_id show page not found page
		if (empty($this->data['admin_user_data'])) {
			REDIRECT(MAINSITE_Admin . "wam/page_not_found");
			exit;
		}


		if (empty($admin_user_id)) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" 
			aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. </div>';
			$this->session->set_flashdata('alert_message', $alert_message);

			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}

		$this->data['admin_user_data'] = $this->data['admin_user_data'][0];



		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/human_resource/Admin_user_module/view', $this->data);
		parent::get_footer();
	}

	function edit($admin_user_id = "")
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		if (empty($admin_user_id)) {
			if ($user_access->add_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Add " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		if (!empty($admin_user_id)) {
			if ($user_access->update_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		$this->data['country_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'country', 'where' => "country_id > 0", "order_by" => "country_name ASC"));

		$this->data['users_role_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'admin_user_role', 'where' => "admin_user_role_id > 0", "order_by" => "admin_user_role_name ASC"));

		$this->data['company_profile_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'company_profile', 'where' => "company_profile_id > 0 and status = 1", "order_by" => "company_unique_name ASC"));

		$this->data['designation_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'designation', 'where' => "designation_id > 0", "order_by" => "designation_name ASC"));
		// $this->data['document_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'document', 'where' => "document_id > 0", "order_by" => "document_name ASC"));

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;


		if (!empty($admin_user_id)) {
			$this->data['admin_user_data'] = $this->Admin_user_model->get_admin_user_data(array("admin_user_id" => $admin_user_id, "details" => 1));
			if (empty($this->data['admin_user_data'])) {
				$this->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-ban"></i> Record Not Found. 
				  </div>');
				REDIRECT(MAINSITE_Admin . $user_access->class_name . '/' . $user_access->function_name);
			}
			$this->data['admin_user_data'] = $this->data['admin_user_data'][0];
		}

		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/human_resource/Admin_user_module/edit', $this->data);
		parent::get_footer();
	}



	function do_edit()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		if (empty($_POST['admin_user_name']) && empty($_POST['country_id']) && empty($_POST['state_id']) && empty($_POST['designation_id']) && empty($_POST['users_role_id']) && empty($_POST['address1']) && empty($_POST['email']) && empty($_POST['mobile_no']) && empty($_POST['show_password'])) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" 
			class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i>
			Something Went Wrong. Please Try Again.</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}

		$admin_user_id = $_POST['admin_user_id'];

		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		if (empty($admin_user_id)) {
			if ($user_access->add_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Add " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}


		if (!empty($admin_user_id)) {
			if ($user_access->update_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		$email = trim($_POST['email']);
		$admin_user_name = trim($_POST['admin_user_name']);

		$country_id = $_POST['country_id'];
		$state_id = $_POST['state_id'];
		$city_id = $_POST['city_id'];

		$status = $_POST['status'];

		$is_exist = $this->Common_model->get_data(
			array(
				'select' => '*',
				'from' => 'admin_user',
				'where' => "email = '$email' and admin_user_id != $admin_user_id"
			)
		);

		if (!empty($is_exist)) {

			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" 
			aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Human Resource already exist in database.</div>';

			$this->session->set_flashdata('alert_message', $alert_message);

			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/employee-edit/" . $admin_user_id);
			exit;
		}

		$enter_data['designation_id'] = $_POST['designation_id'];
		$enter_data['fax_no'] = $_POST['fax_no'];
		$enter_data['email'] = $_POST['email'];
		$enter_data['show_password'] = $_POST['show_password'];
		$enter_data['password'] = md5($_POST['show_password']);
		$enter_data['first_name'] = trim($_POST['first_name']);
		$enter_data['last_name'] = trim($_POST['last_name']);
		$enter_data['name'] = $name;
		$enter_data['address1'] = trim($_POST['address1']);
		$enter_data['address2'] = trim($_POST['address2']);
		$enter_data['address3'] = trim($_POST['address3']);
		$enter_data['mobile_no'] = trim($_POST['mobile_no']);
		$enter_data['alt_mobile_no'] = trim($_POST['alt_mobile_no']);
		$enter_data['pincode'] = trim($_POST['pincode']);
		$enter_data['country_id'] = $country_id;
		$enter_data['state_id'] = $state_id;
		$enter_data['city_id'] = $city_id;
		$enter_data['status'] = $_POST['status'];

		$enter_data['joining_date'] = date("Y-m-d", strtotime($_POST['joining_date']));
		if (!empty($_POST['termination_date']))
			$enter_data['termination_date'] = date("Y-m-d", strtotime($_POST['termination_date']));

		// $enter_data['user_role_id'] = $_POST['user_role_id'];
		$enter_data['approval_access'] = $_POST['approval_access'];
		$enter_data['data_view'] = $_POST['data_view'];

		$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong Please Try Again. </div>';
		if (!empty($admin_user_id)) {
			$enter_data['updated_on'] = date("Y-m-d H:i:s");
			$enter_data['updated_by'] = $this->data['session_auid'];

			$insertStatus = $this->Common_model->update_operation(array('table' => 'admin_user', 'data' => $enter_data, 'condition' => "admin_user_id = $admin_user_id"));
			if (!empty($insertStatus)) {
				$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" 
				aria-hidden="true">×</button><i class="icon fas fa-check"></i> Record Updated Successfully </div>';
			}

		} else {
			$enter_data['added_on'] = date("Y-m-d H:i:s");
			$enter_data['added_by'] = $this->data['session_auid'];

			$admin_user_id = $insertStatus = $this->Common_model->add_operation(array('table' => 'admin_user', 'data' => $enter_data));
			if (!empty($insertStatus)) {
				$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check"></i> New Record Added Successfully </div>';
			}
		}


		if ($insertStatus > 0) { // Checking if $insertStatus is greater than 0 (indicating successful insertion)
			$user_role_id_arr = $_POST['user_role_id']; // Retrieving user_role_id array from POST data


			// For single company
			$company_profile_id_arr = $_POST['company_profile_id']; // Retrieving company_profile_id array from POST data

			$this->upload_multi_files_uekf($admin_user_id);
			// Checking if both user_role_id_arr and company_profile_id_arr are not empty
			if (!empty($user_role_id_arr) && !empty($company_profile_id_arr)) {
				// Deleting existing admin user roles associated with $admin_user_id
				$this->Common_model->delete_operation(
					array(
						'table' => 'admin_user_role',
						'where' => "admin_user_id = $admin_user_id"
					)
				);

				// Looping through user_role_id_arr and company_profile_id_arr arrays
				for ($i = 0; $i < count($user_role_id_arr); $i++) {
					if (!empty($user_role_id_arr)) {
						// Constructing data for insertion into admin_user_role table
						$admin_user_role_data['admin_user_id'] = $admin_user_id;
						$admin_user_role_data['user_role_id'] = $user_role_id_arr[$i];
						$admin_user_role_data['company_profile_id'] = $company_profile_id_arr[$i];

						// Adding admin user roles into admin_user_role table
						$this->Common_model->add_operation(
							array(
								'table' => 'admin_user_role',
								'data' => $admin_user_role_data
							)
						);
					}
				}
			}
		}

		$this->session->set_flashdata('alert_message', $alert_message);

		if (!empty($_POST['redirect_type'])) {
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/employee-edit");
		}

		REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
	}



	function do_update_status()
	{



		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		$task = $_POST['task'];


		$id_arr = $_POST['sel_recds'];




		if (empty($user_access)) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}




		if ($user_access->update_module == 1) {

			$this->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-ban"></i> Something Went Wrong Please Try Again. 
				  </div>');
			$update_data = array();
			if (!empty($id_arr)) {
				$action_taken = "";
				$ids = implode(',', $id_arr);


				if ($task == "active") {
					$update_data['status'] = 1;
					$action_taken = "Activate";
				}
				if ($task == "block") {
					$update_data['status'] = 0;
					$action_taken = "Blocked";
				}
				$update_data['updated_on'] = date("Y-m-d H:i:s");
				$update_data['updated_by'] = $this->data['session_auid'];
				$response = $this->Common_model->update_operation(array('table' => "admin_user", 'data' => $update_data, 'condition' => "admin_user_id in ($ids)"));
				if ($response) {
					$this->session->set_flashdata('alert_message', '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<i class="icon fas fa-check"></i> Records Successfully ' . $action_taken . ' 
						</div>');
				}
			}
			REDIRECT(MAINSITE_Admin . $user_access->class_name . '/' . $user_access->function_name);
		} else {
			$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}
	}

	public function addNewLine_uekf()
	{
		if (!empty($_POST['append_id_uekf'])) {
			$this->data['append_id_uekf'] = $_POST['append_id_uekf'];
		}
		// $this->data['document_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'document', 'where' => "document_id > 0", "order_by" => "document_name ASC"));
		$template = $this->load->view('secureRegions/human_resource/Admin_user_module/template/file_line_add_more_uekf', $this->data, true);
		echo json_encode(array("template" => $template));
	}

	function upload_multi_files_uekf($idf)
	{

		$table_name = "admin_user_file";
		$idp_column = "admin_user_file_id";
		$idf_column = "admin_user_id";
		$input_file_name = "file_name_uekf";
		$input_text_name = "file_title_uekf";
		$target_file_column = "file_name";
		$target_text_column = "file_title";
		$prefix = "admin_user_file_";
		$target_folder_name = "admin_user_file";
		$logo_file_name = "";
		$count = 0;



		if (!empty($_FILES[$input_file_name]['name'])) {
			if (!is_dir(_uploaded_temp_files_ . $target_folder_name)) {
				mkdir('./' . _uploaded_temp_files_ . $target_folder_name, 0777, TRUE);
			}

			$file_title2 = $_POST[$input_text_name];



			for ($i = 0; $i < count($_FILES[$input_file_name]['name']); $i++) {
				if (isset($_FILES[$input_file_name]['name'][$i]) && !empty($_FILES[$input_file_name]['name'][$i])) {

					$img_data[$target_text_column] = $file_title2[$i];
					$img_data[$idf_column] = $idf;
					// $img_data['added_on'] = date("Y-m-d H:i:s");
					// $img_data['added_by'] = $this->data['session_auid'];
					$idp = $this->Common_model->add_operation(array('table' => $table_name, 'data' => $img_data));

					$count++;

					$timg_name = $_FILES[$input_file_name]['name'][$i];
					$temp_var = explode(".", strtolower($timg_name));
					$timage_ext = end($temp_var);
					$timage_name_new = $prefix . $idp . "." . $timage_ext;
					$update_img_data[$target_file_column] = $timage_name_new;

					$idp = $this->Common_model->update_operation(array('table' => $table_name, 'data' => $update_img_data, 'condition' => "$idp_column = $idp"));
					if ($idp > 0) {
						move_uploaded_file($_FILES[$input_file_name]['tmp_name'][$i], _uploaded_temp_files_ . $target_folder_name . "/" . $timage_name_new);
						$logo_file_name = $timage_name_new;
					}
				}
			}
		}
	}

	public function addNewLine_auf()
	{
		if (!empty($_POST['append_id_auf'])) {
			$this->data['append_id_auf'] = $_POST['append_id_auf'];
		}
		$template = $this->load->view('secureRegions/human_resource/Admin_user_module/template/file_line_add_more_auf', $this->data, true);
		echo json_encode(array("template" => $template));
	}
	function upload_multi_files_auf($idf)
	{
		$table_name = "admin_user_file";
		$idp_column = "admin_user_file_id";
		$idf_column = "admin_user_id";
		$input_file_name = "file_name_auf";
		$input_text_name = "file_title_auf";
		$target_file_column = "file_name";
		$target_text_column = "file_title";
		$prefix = "admin_user_file_";
		$target_folder_name = "admin_user_file";
		$logo_file_name = "";
		$count = 0;

		if (!empty($_FILES[$input_file_name]['name'])) {
			if (!is_dir(_uploaded_temp_files_ . $target_folder_name)) {
				mkdir('./' . _uploaded_temp_files_ . $target_folder_name, 0777, TRUE);
			}

			$file_title2 = $_POST[$input_text_name];
			for ($i = 0; $i < count($_FILES[$input_file_name]['name']); $i++) {
				if (isset($_FILES[$input_file_name]['name'][$i]) && !empty($_FILES[$input_file_name]['name'][$i])) {

					$img_data[$target_text_column] = $file_title2[$i];
					$img_data[$idf_column] = $idf;
					$img_data['added_on'] = date("Y-m-d H:i:s");
					$img_data['added_by'] = $this->data['session_auid'];
					$idp = $this->Common_Model->add_operation(array('table' => $table_name, 'data' => $img_data));

					$count++;

					$timg_name = $_FILES[$input_file_name]['name'][$i];
					$temp_var = explode(".", strtolower($timg_name));
					$timage_ext = end($temp_var);
					$timage_name_new = $prefix . $idp . "." . $timage_ext;
					$update_img_data[$target_file_column] = $timage_name_new;
					$idp = $this->Common_Model->update_operation(array('table' => $table_name, 'data' => $update_img_data, 'condition' => "$idp_column = $idp"));
					if ($idp > 0) {
						move_uploaded_file($_FILES[$input_file_name]['tmp_name'][$i], _uploaded_temp_files_ . $target_folder_name . "/" . $timage_name_new);
						$logo_file_name = $timage_name_new;
					}
				}
			}
		}
	}




}
