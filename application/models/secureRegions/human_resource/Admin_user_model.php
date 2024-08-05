<?php
class Admin_user_model extends CI_Model
{
	public $session_auid = '';
	public $session_auname = '';
	public $session_auemail = '';
	public $session_aurid = '';

	function __construct()
	{
		//db
		$this->load->database();


		$this->db->query("SET sql_mode = ''");

		//session data
		$this->session_auid = $this->session->userdata('session_auid');
		$this->session_auname = $this->session->userdata('session_auname');
		$this->session_auemail = $this->session->userdata('session_auemail');
		$this->session_aurid = $this->session->userdata('session_aurid');


		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");

	}

	function get_admin_user_data($params = array())
	{
		$result = '';

		// Check if search_for parameter is provided to decide the count query
		if (!empty($params['search_for'])) {

			$this->db->select("count(ft.admin_user_id) as counts"); // Select count of records
		} else {

			// Select all required fields and additional information
			$this->db->select("ft.*, dm.designation_name , ci.city_name , s.state_name , c.country_name , c.country_short_name, c.dial_code ");
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.added_by) as added_by_name "); // Select added_by user's name
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.updated_by) as updated_by_name "); // Select updated_by user's name
		}

		// From admin_user table "ft" refers to "from table"
		$this->db->from("admin_user as ft");

		// Joins with other tables
		$this->db->join("country as  c", "c.country_id = ft.country_id");
		$this->db->join("state as  s", "s.state_id = ft.state_id");
		$this->db->join("city as  ci", "ci.city_id = ft.city_id");
		$this->db->join("designation as  dm", "dm.designation_id = ft.designation_id");

		// Conditional logic for ordering results
		if (!empty($params['order_by'])) {
			$this->db->order_by($params['order_by']);
		} else {
			$this->db->order_by("ft.admin_user_id desc"); // Default order by admin_user_id descending
		}

		// Conditions based on provided parameters
		if (!empty($params['admin_user_id'])) {
			$this->db->where("ft.admin_user_id", $params['admin_user_id']);
		}
		if (!empty($params['country_id'])) {
			$this->db->where("ft.country_id", $params['country_id']);
		}
		if (!empty($params['state_id'])) {
			$this->db->where("ft.state_id", $params['state_id']);
		}
		if (!empty($params['city_id'])) {
			$this->db->where("ft.city_id", $params['city_id']);
		}
		if (!empty($params['designation_id'])) {
			$this->db->where("ft.designation_id", $params['designation_id']);
		}

		if (!empty($params['admin_user_role_id'])) {
			$this->db->where("ft.admin_user_role_id", $params['admin_user_role_id']);
		}

		if (!empty($params['start_date'])) {
			$temp_date = date('Y-m-d', strtotime($params['start_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') >= DATE_FORMAT('$temp_date', '%Y%m%d')"); // Start date condition
		}

		if (!empty($params['end_date'])) {
			$temp_date = date('Y-m-d', strtotime($params['end_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') <= DATE_FORMAT('$temp_date', '%Y%m%d')"); // End date condition
		}

		if (!empty($params['record_status'])) {
			if ($params['record_status'] == 'zero') {
				$this->db->where("ft.status = 0"); // Status zero condition
			} else {
				$this->db->where("ft.status", $params['record_status']); // Specific status condition
			}
		}

		if (!empty($params['field_value']) && !empty($params['field_name'])) {
			$this->db->where("$params[field_name] like ('%$params[field_value]%')"); // Field name and value condition
		}

		if (!empty($params['limit']) && !empty($params['offset'])) {
			$this->db->limit($params['limit'], $params['offset']); // Limit and offset for pagination
		} else if (!empty($params['limit'])) {
			$this->db->limit($params['limit']); // Limit for number of records
		}


		// Execute query and get results
		$query_get_list = $this->db->get();
		$result = $query_get_list->result();//RESULT CONTAINS ARRAY OF ADMIN_USERS

		// If details parameter is provided, fetch additional details
		if (!empty($result) && !empty($params['details'])) {
			foreach ($result as $admin_user) {
				// Fetch roles for each admin_user
				$this->db->select("ft.* , aur.admin_user_role_name , cp.company_unique_name");
				$this->db->from("join_au_cp_aur as ft");
				$this->db->join("admin_user_role as aur", "aur.admin_user_role_id = ft.admin_user_role_id");
				$this->db->join("company_profile as  cp", "cp.company_profile_id = ft.company_profile_id");
				$this->db->where("ft.admin_user_id", $admin_user->admin_user_id);
				$admin_user->roles = $this->db->get()->result();

				// Fetch files for each admin_user
				$this->db->select("ft.*");
				$this->db->from("admin_user_file as ft");
				$this->db->where("ft.admin_user_id", $admin_user->admin_user_id);
				$admin_user->files = $this->db->get()->result();
			}
		}

		return $result; // Return the final result
	}
}

?>