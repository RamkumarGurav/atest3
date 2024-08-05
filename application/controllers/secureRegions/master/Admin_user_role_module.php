<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once (APPPATH . "controllers/secureRegions/Main.php");
class Admin_user_role_module extends Main
{

	function __construct()
	{

		parent::__construct();

		//db
		$this->load->database();

		//libraries
		$this->load->library('session');
		$this->load->library('User_auth');
		$this->load->library('pagination');

		//helpers
		$this->load->helper('url');

		//models
		$this->load->model('Common_model');
		$this->load->model('secureRegions/master/Admin_user_role_model');


		//session data
		$session_auid = $this->data['session_auid'] = $this->session->userdata('session_auid');
		$this->data['session_auname'] = $this->session->userdata('session_auname');
		$this->data['session_auemail'] = $this->session->userdata('session_auemail');
		$this->data['session_aurid'] = $this->session->userdata('session_aurid');


		$this->data['page_module_name'] = 'Role';
		$this->data['table_name'] = 'admin_user_role';
		$this->data['page_module_id'] = 1;




		$this->data['User_auth_obj'] = new User_auth();
		$this->data['user_data'] = $this->data['User_auth_obj']->check_user_status();

		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");

		//THIS IS USED IN "view" and "edit" pages to dispaly the master module name inside the bracked 
		// $this->data['master_name'] = array(
		// 	"1" => "Master",
		// 	"2" => "Human Resource",
		// 	"3" => "Company",
		// 	"4" => "Catalog",
		// 	"5" => "Enquiry",
		// 	"6" => "Banner",
		// 	"7" => "Gallery",
		// 	"8" => "Customers",
		// 	"9" => "Invoice",
		// 	"10" => "Customer Reports",
		// 	"11" => "Employee Attendance",
		// 	"12" => "Delivery Note",
		// 	"13" => "Orders"
		// );

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
		$this->load->view('secureRegions/master/Admin_user_role_module/list', $this->data);
		parent::get_footer();
	}


	/**
	 * LOADS THE LIST OF ROLES VIEW
	 */
	function list()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		// If user does not have access, redirect to the access denied page
		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		// Initialize search parameters
		$search = array();
		$field_name = '';
		$field_value = '';
		$end_date = '';
		$start_date = '';
		$record_status = "";

		// Get the field name from the request if available
		if (!empty($_REQUEST['field_name'])) {
			$field_name = $_POST['field_name'];
		} else if (!empty($field_name)) {
			$field_name = $field_name;
		}

		// Get the field value from the request if available
		if (!empty($_REQUEST['field_value'])) {
			$field_value = $_POST['field_value'];
		} else if (!empty($field_value)) {
			$field_value = $field_value;
		}

		// Get the end date from the request if available
		if (!empty($_POST['end_date'])) {
			$end_date = $_POST['end_date'];
		}

		// Get the start date from the request if available
		if (!empty($_POST['start_date'])) {
			$start_date = $_POST['start_date'];
		}

		// Get the record status from the request if available
		if (!empty($_POST['record_status'])) {
			$record_status = $_POST['record_status'];
		}

		// Assign search parameters to data array
		$this->data['field_name'] = $field_name;
		$this->data['field_value'] = $field_value;
		$this->data['start_date'] = $start_date;
		$this->data['end_date'] = $end_date;
		$this->data['record_status'] = $record_status;

		// Assign search parameters to search array
		$search['field_value'] = $field_value;
		$search['field_name'] = $field_name;
		$search['start_date'] = $start_date;
		$search['end_date'] = $end_date;
		$search['record_status'] = $record_status;
		$search['search_for'] = "count";

		// Get the count of users role master based on search parameters
		$data_count = $this->Admin_user_role_model->get_admin_user_role_data($search);
		$r_count = $this->data['row_count'] = $data_count[0]->counts;

		// Remove 'search_for' from search array
		unset($search['search_for']);

		// Get the offset from the URI segment
		$offset = (int) $this->uri->segment(5);
		if ($offset == "") {
			$offset = '0';
		}

		// Set the number of items per page for pagination
		$per_page = _all_pagination_;

		// Load the pagination library
		$this->load->library('pagination');

		// Configure pagination settings
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

		// Initialize pagination
		$this->pagination->initialize($config);

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;

		// Set the limit and offset for fetching data
		$search['limit'] = $per_page;
		$search['offset'] = $offset;

		// Get the users role master data based on search parameters
		$this->data['admin_user_role_data'] = $this->Admin_user_role_model->get_admin_user_role_data($search);

		// Load header, left navigation, and the view for role manager list
		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/master/Admin_user_role_module/list', $this->data);
		parent::get_footer();
	}

	function list_export()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		if ($this->data['user_access']->export_data != 1) {
			$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Export " . $this->data['user_access']->module_name);
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}




		$search = array();
		$field_name = '';
		$field_value = '';
		$end_date = '';
		$start_date = '';
		$record_status = "";

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


		$this->data['field_name'] = $field_name;
		$this->data['field_value'] = $field_value;
		$this->data['start_date'] = $start_date;
		$this->data['end_date'] = $end_date;
		$this->data['record_status'] = $record_status;

		$search['field_name'] = $field_name;
		$search['field_value'] = $field_value;
		$search['start_date'] = $start_date;
		$search['end_date'] = $end_date;
		$search['record_status'] = $record_status;

		$this->data['admin_user_role_data'] = $this->Admin_user_role_model->get_admin_user_role_data($search);


		$this->load->view('secureRegions/master/Admin_user_role_module/list_export', $this->data);
	}

	function view($admin_user_role_id = "")
	{



		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		// If admin_user_role_id is not provided or is empty
		if (empty($admin_user_role_id)) {
			// Set an alert message indicating an error
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. anubhav</div>';
			// Store the alert message in session flash data
			$this->session->set_flashdata('alert_message', $alert_message);
			// Redirect to the same function of the same class to reload the page
			REDIRECT(MAINSITE_Admin . $this->data['user_access']->class_name . "/" . $this->data['user_access']->function_name);
			exit; // Exit the function
		}

		// If the user access data is empty, redirect to access denied page
		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;

		// Retrieve user role master data for the provided admin_user_role_id
		$this->data['admin_user_role_data'] = $this->Admin_user_role_model->get_admin_user_role_data(array("admin_user_role_id" => $admin_user_role_id));

		// If admin_user_role_id is empty after retrieval, set an alert message and redirect
		if (empty($admin_user_role_id)) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" 
        data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. anubhav</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $this->data['user_access']->class_name . "/" . $this->data['user_access']->function_name);
			exit; // Exit the function
		}

		// Retrieve module data from the database
		$this->data['module_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'module', 'where' => "module_id >0"));


		// Retrieve module permissions for the provided admin_user_role_id
		$this->data['module_permission_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'module_permission', 'where' => "admin_user_role_id = $admin_user_role_id"));





		// Set the user role master data to the first element of the retrieved data
		$this->data['admin_user_role_data'] = $this->data['admin_user_role_data'][0];



		// Load the header of the page
		parent::get_header();
		// Load the left navigation of the page
		parent::get_left_nav();
		// Load the view for role manager with the retrieved data
		$this->load->view('secureRegions/master/Admin_user_role_module/view', $this->data);
		// Load the footer of the page
		parent::get_footer();




	}

	function edit($admin_user_role_id = 0)
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}
		if (empty($admin_user_role_id)) {
			if ($user_access->add_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Add " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}
		if (!empty($admin_user_role_id)) {
			if ($user_access->update_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		$this->data['module_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'module', 'where' => "module_id >0"));
		$this->data['module_permission_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'module_permission', 'where' => "admin_user_role_id = $admin_user_role_id"));

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;


		if (!empty($admin_user_role_id)) {
			$this->data['admin_user_role_data'] = $this->Admin_user_role_model->get_admin_user_role_data(array("admin_user_role_id" => $admin_user_role_id));
			if (empty($this->data['admin_user_role_data'])) {
				$this->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-ban"></i> Record Not Found. 
				  </div>');
				REDIRECT(MAINSITE_Admin . $user_access->class_name . '/' . $user_access->function_name);
			}
			$this->data['admin_user_role_data'] = $this->data['admin_user_role_data'][0];
		}






		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/master/Admin_user_role_module/edit', $this->data);
		parent::get_footer();
	}


	//ACTUAL EDIT OR NEW ADDITION OF USER ROLE
	function do_edit()
	{

		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));

		// Check if the user role name is provided in the POST data
		if (empty($_POST['admin_user_role_name'])) {
			// If not, set an error message and redirect to the relevant page
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" 
						class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. anubhav</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}

		$admin_user_role_id = $_POST['admin_user_role_id'];

		// If user access data is empty, redirect to access denied page
		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		// Check if the user is allowed to add or update roles based on the admin_user_role_id
		if (empty($admin_user_role_id)) {
			if ($user_access->add_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Add " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}
		if (!empty($admin_user_role_id)) {
			if ($user_access->update_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		$admin_user_role_name = trim($_POST['admin_user_role_name']);
		$status = trim($_POST['status']);

		// 1) For adding a new user role:
// - $name is obtained from the form input.
// - $admin_user_role_id is set to "0".(in the view page we set default 0 as admin_user_role_id)
// - We check if a user role with the same name exists in the database.
// - If such a role exists and its admin_user_role_id is not "0", it means the role already exists.

		// 2) For updating an existing user role:
// - $name is obtained from the form input.
// - $admin_user_role_id is set to the ID of the role being updated.
// - We check if a user role with the same name exists in the database.
// - If such a role exists and its admin_user_role_id is different from the current one, it means another role with the same name exists.
		$is_exist = $this->Common_model->
			get_data(
				array(
					'select' => '*',
					'from' => 'admin_user_role',
					'where' => "admin_user_role_name = '$admin_user_role_name' and admin_user_role_id != $admin_user_role_id"

				)
			);



		// If role name exists, set an error message and redirect to the edit page
		if (!empty($is_exist)) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" 
			aria-hidden="true">×</button><i class="icon fas fa-ban"></i> User Role already exist in database.</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/edit/" . $admin_user_role_id);
			exit;
		}


		//{{{{{{{ THIS PART HANDLES THE CREATE/INSERT OF USER_ROLE RECORD ONLY
		// Prepare data to insert/update in the database
		$enter_data['admin_user_role_name'] = $admin_user_role_name;
		$enter_data['status'] = $status;

		$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong Please Try Again. </div>';
		$insertStatus = 0;

		// Update existing user role if admin_user_role_id is provided
		if (!empty($admin_user_role_id)) {
			$enter_data['updated_on'] = date("Y-m-d H:i:s");
			$enter_data['updated_by'] = $this->data['session_auid'];
			$insertStatus = $this->Common_model->update_operation(array('table' => 'admin_user_role', 'data' => $enter_data, 'condition' => "admin_user_role_id = $admin_user_role_id"));
			if (!empty($insertStatus)) {
				$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check"></i> Record Updated Successfully </div>';
			}
		} else {
			// Add new user role if admin_user_role_id is not provided
			$enter_data['added_on'] = date("Y-m-d H:i:s");
			$enter_data['added_by'] = $this->data['session_auid'];
			$admin_user_role_id = $insertStatus = $this->Common_model->add_operation(array('table' => 'admin_user_role', 'data' => $enter_data));
			if (!empty($insertStatus)) {
				$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check"></i> New Record Added Successfully </div>';
			}
		}


		//}}}}}}}}}} THIS PART HANDLES THE CREATE/INSERT OF USER_ROLE RECORD ONLY


		//{{{{{{{ THIS PART HANDLES CREATE/INSERT OF MODULE_PERMISSIONS RECORDS ONLY BASED ON USER ROLE ONLY IF THERE IS A SUCCESSFUL CREATE/INSERT
		if ($insertStatus > 0) {
			// First, we delete all existing module_permissions based on the admin_user_role_id
			// This ensures that any previous permissions are removed before adding new ones
			$this->Common_model->delete_operation(array('table' => 'module_permission', 'where' => "admin_user_role_id = $admin_user_role_id"));

			// Check if there are module IDs provided in the POST request
			if (!empty($_POST['module_ids'])) {
				// Get the array of module IDs from the POST request
				$module_id_arr = $_POST['module_ids'];

				// Loop through each module ID to create new permissions
				foreach ($module_id_arr as $module_id) {
					// Initialize the permission data for the current module
					$is_insert = false; // Flag to check if any permission is set for the current module
					$module_permission_data['module_id'] = $module_id;
					$module_permission_data['admin_user_role_id'] = $admin_user_role_id;
					$module_permission_data['view_module'] = 0; // Initialize view permission to 0 (no access)
					$module_permission_data['add_module'] = 0; // Initialize add permission to 0 (no access)
					$module_permission_data['update_module'] = 0; // Initialize update permission to 0 (no access)
					$module_permission_data['delete_module'] = 0; // Initialize delete permission to 0 (no access)
					$module_permission_data['approval_module'] = 0; // Initialize approval permission to 0 (no access)
					$module_permission_data['import_data'] = 0; // Initialize import permission to 0 (no access)
					$module_permission_data['export_data'] = 0; // Initialize export permission to 0 (no access)

					// Check if view permission is set for the current module in the POST request
					if (!empty($_POST['view_' . $module_id])) {
						$module_permission_data['view_module'] = 1; // Set view permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// Check if add permission is set for the current module in the POST request
					if (!empty($_POST['add_' . $module_id])) {
						$module_permission_data['add_module'] = 1; // Set add permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// Check if update permission is set for the current module in the POST request
					if (!empty($_POST['update_' . $module_id])) {
						$module_permission_data['update_module'] = 1; // Set update permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// Check if delete permission is set for the current module in the POST request
					if (!empty($_POST['delete_' . $module_id])) {
						$module_permission_data['delete_module'] = 1; // Set delete permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// Check if approval permission is set for the current module in the POST request
					if (!empty($_POST['approve_' . $module_id])) {
						$module_permission_data['approval_module'] = 1; // Set approval permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// Check if import permission is set for the current module in the POST request
					if (!empty($_POST['import_' . $module_id])) {
						$module_permission_data['import_data'] = 1; // Set import permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// Check if export permission is set for the current module in the POST request
					if (!empty($_POST['export_' . $module_id])) {
						$module_permission_data['export_data'] = 1; // Set export permission to 1 (access granted)
						$is_insert = true; // Mark that we need to insert this permission
					}

					// If any permission is set for the current module, insert the permission data into the database
					if ($is_insert) {
						$this->Common_model->add_operation(array('table' => 'module_permission', 'data' => $module_permission_data));
					}
				}
			}
		}
		//}}}}}}} THIS PART HANDLES CREATE/INSERT OF MODULE_PERMISSIONS RECORDS ONLY BASED ON USER ROLE

		$this->session->set_flashdata('alert_message', $alert_message);

		if (!empty($_POST['redirect_type'])) {
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/role-manager-edit");
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
				$response = $this->Common_model->update_operation(array('table' => "admin_user_role", 'data' => $update_data, 'condition' => "admin_user_role_id in ($ids)"));
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





}
