<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once (APPPATH . "controllers/secureRegions/Main.php");
class City_module extends Main
{

	function __construct()
	{
		parent::__construct();

		$this->load->database();

		$this->load->library('session');
		$this->load->library('User_auth');
		$this->load->helper('url');

		$this->load->model('Common_model');
		$this->load->model('secureRegions/master/City_model');

		$this->load->library('pagination');


		//session data
		$session_auid = $this->data['session_auid'] = $this->session->userdata('session_auid');
		$this->data['session_auname'] = $this->session->userdata('session_auname');
		$this->data['session_auemail'] = $this->session->userdata('session_auemail');
		$this->data['session_aurid'] = $this->session->userdata('session_aurid');

		$this->data['page_module_name'] = 'City';
		$this->data['table_name'] = 'city';
		$this->data['page_module_id'] = 4;


		$this->data['User_auth_obj'] = new User_auth();
		$this->data['user_data'] = $this->data['User_auth_obj']->check_user_status();

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
		$this->load->view('secureRegions/master/City_module/list', $this->data);
		parent::get_footer();
	}

	//using
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
		$search['search_for'] = "count";

		$data_count = $this->City_model->get_city_data($search);
		$r_count = $this->data['row_count'] = $data_count[0]->counts;

		unset($search['search_for']);

		$offset = (int) $this->uri->segment(5); //echo $offset;
		if ($offset == "") {
			$offset = '0';
		}
		$per_page = _all_pagination_;

		$this->load->library('pagination');
		//$config['base_url'] =MAINSITE.'secure_region/reports/DispatchedOrders/'.$module_id.'/';
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

		$search['limit'] = $per_page;
		$search['offset'] = $offset;

		$this->data['country_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'country', 'where' => "country_id > 0", "order_by" => "country_name ASC"));
		$this->data['city_data'] = $this->City_model->get_city_data($search);

		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/master/City_module/list', $this->data);
		parent::get_footer();
	}

	//using
	function list_export()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));


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

		$this->data['city_data'] = $this->City_model->get_city_data($search);


		$this->load->view('secureRegions/master/City_module/list_export', $this->data);
	}

	//using
	function view($city_id = "")
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));


		// If no city_id is provided
		if (empty($city_id)) {
			// Set an alert message for the session
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. anubhav</div>';
			$this->session->set_flashdata('alert_message', $alert_message);

			// Redirect to a different page and exit the function
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}

		// If user_access is not set
		if (empty($this->data['user_access'])) {
			// Redirect to an access-denied page
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;

		// Get city data using the provided city_id
		$this->data['city_data'] = $this->City_model->get_city_data(array("city_id" => $city_id));

		// Check again if no city_id is provided (redundant check)
		if (empty($city_id)) {
			// Set an alert message for the session
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again. anubhav</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			// Redirect to a different page and exit the function
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}

		// Extract the first element from city_data and store it
		$this->data['city_data'] = $this->data['city_data'][0];

		// Load header, left navigation, and footer templates
		parent::get_header();
		parent::get_left_nav();
		// Load the city view with the city data
		$this->load->view('secureRegions/master/City_module/view', $this->data);
		parent::get_footer();
	}

	//using
	//method that loads the view of add city and update city page
	function edit($city_id = "")
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));


		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}
		if (empty($city_id)) {
			if ($user_access->add_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Add " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}
		if (!empty($city_id)) {
			if ($user_access->update_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}



		$this->data['country_data'] = $this->Common_model->get_data(array('select' => '*', 'from' => 'country', 'where' => "country_id > 0", "order_by" => "country_name ASC"));

		// Assigning additional data for the view
		$this->data['page_is_master'] = $this->data['user_access']->is_master;//this is for making left menu active
		$this->data['page_parent_module_id'] = $this->data['user_access']->parent_module_id;


		if (!empty($city_id)) {
			$this->data['city_data'] = $this->City_model->get_city_data(array("city_id" => $city_id));
			if (empty($this->data['city_data'])) {
				$this->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="icon fas fa-ban"></i> Record Not Found. 
				  </div>');
				REDIRECT(MAINSITE_Admin . $user_access->class_name . '/' . $user_access->function_name);
			}
			$this->data['city_data'] = $this->data['city_data'][0];
		}

		parent::get_header();
		parent::get_left_nav();
		$this->load->view('secureRegions/master/City_module/edit', $this->data);
		parent::get_footer();
	}

	//using
	//method that actually adds new city or updates the existing city
	function do_edit()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));


		// Validate essential form fields; if empty, set an error message and redirect
		if (empty($_POST['city_name']) && empty($_POST['country_id']) && empty($_POST['state_id'])) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close"
							data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> Something Went Wrong. Please Try Again.</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
			exit;
		}

		// Retrieve city_id from the form submission
		$city_id = $_POST['city_id'];


		// Redirect to access denied page if user access is not defined
		if (empty($this->data['user_access'])) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		// Check if the user has permission to add a city (if city_id is empty)
		if (empty($city_id)) {
			if ($user_access->add_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Add " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		// Check if the user has permission to update a city (if city_id is not empty)
		if (!empty($city_id)) {
			if ($user_access->update_module != 1) {
				$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
				REDIRECT(MAINSITE_Admin . "wam/access-denied");
			}
		}

		// Assign form data to variables and trim whitespace
		$city_name = trim($_POST['city_name']);
		$city_code = trim($_POST['city_code']);
		$country_id = $_POST['country_id'];
		$state_id = $_POST['state_id'];
		$status = $_POST['status'];

		// Check if a city with the same name already exists in the same country and state but with a different city_id
		$is_exist = $this->Common_model->get_data(array('select' => '*', 'from' => 'city', 'where' => "city_name = '$city_name' and city_id != $city_id and country_id = $country_id and state_id = $state_id"));

		// If the city exists, set an error message and redirect to the edit page
		if (!empty($is_exist)) {
			$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban"></i> City already exist in database.</div>';
			$this->session->set_flashdata('alert_message', $alert_message);
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/edit/" . $city_id);
			exit;
		}

		// Prepare data for insertion or update
		$enter_data['city_name'] = getCleanText($city_name);
		$enter_data['city_code'] = getCleanText($city_code);
		$enter_data['state_id'] = $state_id;
		$enter_data['country_id'] = $country_id;
		$enter_data['status'] = $_POST['status'];
		$enter_data['is_display'] = $_POST['is_display'];

		// Default alert message for errors
		$alert_message = '<div class="alert alert-danger alert-dismissible"><button type="button"
		 class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-ban">
		 </i> Something Went Wrong Please Try Again. </div>';

		// Update operation if city_id is not empty
		if (!empty($city_id)) {
			$enter_data['updated_on'] = date("Y-m-d H:i:s");
			$enter_data['updated_by'] = $this->data['session_auid'];
			$insertStatus = $this->Common_model->update_operation(array('table' => 'city', 'data' => $enter_data, 'condition' => "city_id = $city_id"));

			// Set success message if update is successful
			if (!empty($insertStatus)) {
				$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" 
				class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check">
				</i> Record Updated Successfully </div>';
			}

			// Insert operation if city_id is empty
		} else {
			$enter_data['added_on'] = date("Y-m-d H:i:s");
			$enter_data['added_by'] = $this->data['session_auid'];
			$insertStatus = $this->Common_model->add_operation(array('table' => 'city', 'data' => $enter_data));

			// Set success message if insert is successful
			if (!empty($insertStatus)) {
				$alert_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fas fa-check"></i> New Record Added Successfully </div>';
			}
		}

		// Set the flash message in the session
		$this->session->set_flashdata('alert_message', $alert_message);

		// Check if the redirect_type is set to "save-add-new"
		if (!empty($_POST['redirect_type'])) {
			// Redirect to the edit page for adding a new record
			REDIRECT(MAINSITE_Admin . $user_access->class_name . "/edit");
		}

		// Default redirect to the main function page
		REDIRECT(MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name);
	}



	//using
	function do_update_status()
	{
		$this->data['page_type'] = "list";
		$user_access = $this->data['user_access'] = $this->data['User_auth_obj']->check_user_access(array("module_id" => $this->data['page_module_id']));



		// Retrieve the task and selected record IDs from the POST data.
		$task = $_POST['task'];
		$id_arr = $_POST['sel_recds'];

		// If user access is empty, redirect to the access-denied page.
		if (empty($user_access)) {
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}

		// Check if the user has permission to update the module.
		if ($user_access->update_module == 1) {
			// Set alert message for failure (when response is false).
			$this->session->set_flashdata('alert_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="icon fas fa-ban"></i> Something Went Wrong Please Try Again. 
								</div>');

			// Initialize update data array.
			$update_data = array();

			// Process update only if selected record IDs are not empty.
			if (!empty($id_arr)) {
				$action_taken = "";
				$ids = implode(',', $id_arr);

				// Set the status and action based on the task.
				if ($task == "active") {
					$update_data['status'] = 1;
					$action_taken = "Activate";
				}
				if ($task == "block") {
					$update_data['status'] = 0;
					$action_taken = "Blocked";
				}

				// Set the updated_on and updated_by fields.
				$update_data['updated_on'] = date("Y-m-d H:i:s");
				$update_data['updated_by'] = $this->data['session_auid'];

				// Perform the update operation.
				$response = $this->Common_model->update_operation(array('table' => "city", 'data' => $update_data, 'condition' => "city_id in ($ids)"));
				// If update is successful, set success alert message.
				if ($response) {
					$this->session->set_flashdata('alert_message', '<div class="alert alert-success alert-dismissible">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<i class="icon fas fa-check"></i> Records Successfully ' . $action_taken . ' 
											</div>');
				}


			}
			// Redirect to the module page after processing.
			REDIRECT(MAINSITE_Admin . $user_access->class_name . '/' . $user_access->function_name);
		} else {
			// If user doesn't have permission, set access-denied message and redirect.
			$this->session->set_flashdata('no_access_flash_message', "You Are Not Allowed To Update " . $user_access->module_name);
			REDIRECT(MAINSITE_Admin . "wam/access-denied");
		}
	}






}
