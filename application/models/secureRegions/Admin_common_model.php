<?php
class Admin_common_model extends CI_Model
{
	public $session_auid = '';
	public $session_auname = '';
	public $session_auemail = '';
	public $session_aurid = '';
	public $session_company_profile_id = '';

	function __construct()
	{
		$this->load->database();

		$this->model_data = array();

		$this->db->query("SET sql_mode = ''");

		$this->session_auid = $this->session->userdata('session_auid');
		$this->session_auname = $this->session->userdata('session_auname');
		$this->session_auemail = $this->session->userdata('session_auemail');
		$this->session_aurid = $this->session->userdata('sess_current_aurid');
		$this->session_company_profile_id = $this->session->userdata('session_company_profile_id');


	}



	/**
	 * The get_admin_user_data method retrieves data related to an admin user from the database. It first fetches basic information about the user from the admin_user table, including details like username, email, etc. Then, it fetches additional details such as the user's roles and associated company profile information by joining multiple tables (admin_user_role, users_role_master, and company_profile). Finally, it organizes this data and returns it, including the user's roles and associated company profile information.
	 */
	//USING
	function get_admin_user_data($params = array())
	{
		$result = ''; // Initialize the result variable

		// Select fields from the admin_user table
		$this->db->select("ft.* ");
		$this->db->from("admin_user as ft");
		$this->db->where("ft.admin_user_id", $this->session_auid); // Filter by session user ID
		$this->db->limit(1); // Limit to 1 result

		// Execute the query
		$query_get_list = $this->db->get();
		$result = $query_get_list->result(); // Get the query result as an array of objects


		// If result is not empty
		if (!empty($result)) {
			// Loop through each result
			foreach ($result as $r) {
				$this->db->select("ft.*, aur.admin_user_role_name, cp.company_unique_name");
				$this->db->from("join_au_cp_aur as ft");
				$this->db->join("admin_user_role as aur", "aur.admin_user_role_id = ft.admin_user_role_id");
				$this->db->join("company_profile as cp", "cp.company_profile_id = ft.company_profile_id");
				$this->db->where("ft.admin_user_id", $r->admin_user_id);

				$r->roles = $this->db->get()->result(); // Assign the query result to roles property of each result object
			}

			$result = $result[0]; // Get the first element of the result array

			// Loop through each role
			foreach ($result->roles as $r) {
				// If the session company profile ID matches the role's company profile ID
				if ($this->session_company_profile_id == $r->company_profile_id) {
					// Set user role name and ID
					$result->admin_user_role_name = $r->admin_user_role_name;
					$result->admin_user_role_id = $r->admin_user_role_id;
				}
			}
		}

		return $result; // Return the result
	}


	//using
	function get_left_menu($params = array())
	{
		$result = '';
		$this->db->select("ft.* , mp.*");
		$this->db->from("module as ft");
		$this->db->join("module_permission as mp", "ft.module_id = mp.module_id");
		//$this->db->join("users_role_master as urm" , "urm.user_role_id = mp.user_role_id");
		//$this->db->join("admin_user as au" , "mp.user_role_id = au.user_role_id");
		$this->db->join("join_au_cp_aur as jacas", "mp.admin_user_role_id = jacas.admin_user_role_id");
		$this->db->where("jacas.company_profile_id", $this->session_company_profile_id);
		$this->db->where("jacas.admin_user_id", $this->session_auid);
		$this->db->where("ft.is_display", 1);

		$this->db->where("ft.status", 1);

		$this->db->order_by("ft.position ASC");

		if (!empty($params['module_id'])) {
			$this->db->where("ft.module_id", $params['module_id']);
		}
		if (!empty($params['is_master'])) {
			if ($params['is_master'] == "zero") {
				$this->db->where("ft.is_master", 0);
			} else {
				$this->db->where("ft.is_master", $params['is_master']);
			}
		}

		if (!empty($params['module_id'])) {
			$this->db->where("ft.module_id", $params['module_id']);
		}

		if (!empty($params['parent_module_id'])) {
			$this->db->where("ft.parent_module_id", $params['parent_module_id']);
		}

		$query_get_list = $this->db->get();
		$result = $query_get_list->result();

		if (!empty($result)) {
			foreach ($result as $r) {
				if (!empty($r->direct_db_count) && !empty($r->table_name)) {
					$this->db->select('count(*) as row_count');
					$this->db->from("$r->table_name");
					if (!empty($r->count_function_name)) {
						$this->db->where("$r->count_function_name");
					}
					if ($r->is_company_profile_id == 1) {
						$this->db->where("company_profile_id", $this->session_company_profile_id);
					}
					$row_count_result = $this->db->get()->result();
					$r->data_count = $row_count_result[0]->row_count;
				}
				$r->submenu = $this->get_left_sub_menu(array("is_master" => "zero", "parent_module_id" => $r->module_id));
			}
		}
		//print_r($result);
		return $result;
	}


	//using
	function get_left_sub_menu($params = array())
	{
		$result = '';
		$this->db->select("ft.* , mp.*");
		$this->db->from("module as ft");
		$this->db->join("module_permission as mp", "ft.module_id = mp.module_id");
		//$this->db->join("users_role_master as urm" , "urm.user_role_id = mp.user_role_id");
		//$this->db->join("admin_user as au" , "mp.user_role_id = au.user_role_id");
		$this->db->join("join_au_cp_aur as jacas", "mp.admin_user_role_id = jacas.admin_user_role_id");
		$this->db->where("jacas.company_profile_id", $this->session_company_profile_id);
		$this->db->where("jacas.admin_user_id", $this->session_auid);
		$this->db->where("ft.is_display", 1);
		$this->db->where("ft.status", 1);

		if (!empty($params['is_master'])) {
			if ($params['is_master'] == "zero") {
				$this->db->where("ft.is_master = 0");
			} else {
				$this->db->where("ft.is_master", $params['is_master']);
			}

		}

		if (!empty($params['parent_module_id'])) {
			$this->db->where("ft.parent_module_id", $params['parent_module_id']);
		}


		$query_get_list = $this->db->get();
		$result = $query_get_list->result();

		if (!empty($result)) {
			foreach ($result as $r) {
				if (!empty($r->direct_db_count) && !empty($r->table_name)) {
					$this->db->select('count(*) as row_count');
					$this->db->from("$r->table_name");
					$row_count_result = $this->db->get()->result();
					$r->data_count = $row_count_result[0]->row_count;
				}
			}
		}

		return $result;
	}







	//using
	function get_role_modules($params = array())
	{
		//echo "111";
		$result = '';
		$this->db->select("ft.* , mp.*");
		$this->db->from("module as ft");
		$this->db->join("module_permission as mp", "ft.module_id = mp.module_id");
		//$this->db->join("users_role_master as urm" , "urm.user_role_id = mp.user_role_id");
		//$this->db->join("user as au" , "mp.user_role_id = au.user_role_id");
		$this->db->join("join_au_cp_aur as jacas", "mp.admin_user_role_id = jacas.admin_user_role_id");
		$this->db->where("jacas.company_profile_id", $this->session_company_profile_id);
		$this->db->where("jacas.admin_user_id", $this->session_auid);
		$this->db->where("ft.is_display", 1);
		$this->db->where("ft.is_master !=", 0);
		$this->db->where("ft.status", 1);
		$this->db->group_by("ft.is_master");
		// $this->db->order_by("ft.is_master asc, ft.position asc");
		$this->db->order_by("ft.position", 'desc');

		if (!empty($params['is_master'])) {
			if ($params['is_master'] == "zero") {
				$this->db->where("ft.is_master = 0");
			} else {
				$this->db->where("ft.is_master", $params['is_master']);
			}

		}

		if (!empty($params['parent_module_id'])) {
			$this->db->where("ft.parent_module_id", $params['parent_module_id']);
		}
		if (!empty($params['session_aurid'])) {
			$this->db->where("mp.admin_user_role_id", $params['session_aurid']);
		}

		$query_get_list = $this->db->get();
		//echo $this->db->last_query();
		$result = $query_get_list->result();
		if (!empty($result)) {
			foreach ($result as $r) {
				if (!empty($r->direct_db_count) && !empty($r->table_name)) {
					$this->db->select('count(*) as row_count');
					$this->db->from("$r->table_name");
					$row_count_result = $this->db->get()->result();
					$r->data_count = $row_count_result[0]->row_count;
				}
			}
		}

		return $result;
	}


	/**
	 * Checks user access rights based on provided parameters.
	 * @param array $params Parameters for checking access.
	 * @return mixed Access result.
	 */
	//using
	function check_user_access($params = array())
	{
		$result = ''; // Initialize the result variable.

		// Select fields from the 'module_master' and 'module_permissions' tables.
		$this->db->select("ft.* , mp.*");
		$this->db->from("module as ft");
		$this->db->join("module_permission as mp", "ft.module_id = mp.module_id");
		$this->db->join("admin_user_role as aur", "aur.admin_user_role_id = mp.admin_user_role_id");
		$this->db->join("join_au_cp_aur as jacas", "mp.admin_user_role_id = jacas.admin_user_role_id");

		// Filter by company profile ID and admin user ID from the session.
		$this->db->where("jacas.company_profile_id", $this->session_company_profile_id);
		$this->db->where("jacas.admin_user_id", $this->session_auid);

		// Filter by status of module and user role master.
		$this->db->where("ft.status", 1);
		$this->db->where("aur.status", 1);

		// Additional filtering based on provided parameters.

		if (!empty($params['is_master'])) {
			if ($params['is_master'] == "zero") {
				$this->db->where("ft.is_master", 0);
			} else {
				$this->db->where("ft.is_master", $params['is_master']);
			}
		}

		if (!empty($params['parent_module_id'])) {
			$this->db->where("ft.parent_module_id", $params['parent_module_id']);
		}


		//we get this $params['module_id'] from the functions in  Module controllers 
		//eg country_list() method in controllers/secureRegions/Country_Module 
		if (!empty($params['module_id'])) {
			$this->db->where("ft.module_id", $params['module_id']);
		}

		// Execute the query and get the result.
		$query_get_list = $this->db->get();
		$result = $query_get_list->result();

		// If result is not empty, assign the first result to $result.
		if (!empty($result)) {
			$result = $result[0];
		}




		return $result; // Return the access result.
	}



}

?>