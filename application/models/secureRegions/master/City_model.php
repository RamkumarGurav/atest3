<?php
class City_model extends CI_Model
{
	public $session_auid = '';
	public $session_auname = '';
	public $session_auemail = '';
	public $session_aurid = '';

	function __construct()
	{
		$this->load->database();

		$this->db->query("SET sql_mode = ''");

		$this->session_auid = $this->session->userdata('session_auid');
		$this->session_auname = $this->session->userdata('session_auname');
		$this->session_auemail = $this->session->userdata('session_auemail');
		$this->session_aurid = $this->session->userdata('session_aurid');
	}


	/**
	 * getting cities with search ,pagination  and sorting using params ,if your using this method to find single city data with its city_id then it will be present int the 0th index of the resultant array
	 */
	function get_city_data($params = array())
	{
		$result = '';

		// Check if the 'search_for' parameter is present in the $params array
		if (!empty($params['search_for'])) {
			// If 'search_for' is set, only select the count of cities
			$this->db->select("count(ft.city_id) as counts");
		} else {
			// Otherwise, select detailed information about the city including state and country details
			$this->db->select("ft.*, s.state_name, c.country_name, c.country_short_name, c.dial_code");
			// Select the name of the user who added the city
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.added_by) as added_by_name "); // Select added_by user's name
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.updated_by) as updated_by_name "); // Select updated_by user's name
		}

		// From the city table (aliased as ft)
		$this->db->from("city as ft");

		$this->db->join("country as c", "c.country_id = ft.country_id");
		$this->db->join("state as s", "s.state_id = ft.state_id");


		// Conditional logic for ordering results
		if (!empty($params['order_by'])) {
			$this->db->order_by($params['order_by']);
		} else {
			$this->db->order_by("ft.city_id desc"); // Default order by admin_user_id descending
		}

		// If 'city_id' is set in the $params array, filter results by city_id
		if (!empty($params['city_id'])) {
			$this->db->where("ft.city_id", $params['city_id']);
		}

		// If 'country_id' is set in the $params array, filter results by country_id
		if (!empty($params['country_id'])) {
			$this->db->where("ft.country_id", $params['country_id']);
		}
		// If 'state_id' is set in the $params array, filter results by state_id
		if (!empty($params['state_id'])) {
			$this->db->where("ft.state_id", $params['state_id']);
		}

		// If 'start_date' is set in the $params array, filter results to include only cities added on or after the start date
		if (!empty($params['start_date'])) {
			$temp_date = date('Y-m-d', strtotime($params['start_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') >= DATE_FORMAT('$temp_date', '%Y%m%d')");
		}

		// If 'end_date' is set in the $params array, filter results to include only cities added on or before the end date
		if (!empty($params['end_date'])) {
			$temp_date = date('Y-m-d', strtotime($params['end_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') <= DATE_FORMAT('$temp_date', '%Y%m%d')");
		}

		// If 'record_status' is set in the $params array, filter results by status
		if (!empty($params['record_status'])) {
			if ($params['record_status'] == 'zero') {
				// If record_status is 'zero', filter by status = 0
				$this->db->where("ft.status = 0");
			} else {
				// Otherwise, filter by city_id equal to record_status
				$this->db->where("ft.status", $params['record_status']);
			}
		}


		if (!empty($params['is_display'])) {
			if ($params['is_display'] == 'zero') {
				// If is_display is 'zero', filter by status = 0
				$this->db->where("ft.is_display = 0");
			} else {
				// Otherwise, filter by city_id equal to is_display
				$this->db->where("ft.is_display", $params['is_display']);
			}
		}

		// If both 'field_value' and 'field_name' are set in the $params array, filter results where the field contains the value
		if (!empty($params['field_value']) && !empty($params['field_name'])) {
			$this->db->where("$params[field_name] like ('%$params[field_value]%')");
		}

		// If 'limit' and 'offset' are set in the $params array, limit the number of results and set the offset
		if (!empty($params['limit']) && !empty($params['offset'])) {
			$this->db->limit($params['limit'], $params['offset']);
		}
		// If only 'limit' is set, just limit the number of results
		else if (!empty($params['limit'])) {
			$this->db->limit($params['limit']);
		}

		// Execute the query and get the results
		$query_get_list = $this->db->get();
		// Store the results in the $result variable
		$result = $query_get_list->result();

		// Return the result
		return $result;
	}
}

?>