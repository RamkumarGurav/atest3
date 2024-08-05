<?php
class Country_model extends CI_Model
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
	 * getting countries with search ,pagination  and sorting using params ,if your using this method to find single country data with its country_id then it will be present int the 0th index of the resultant array
	 */
	function get_country_data($params = array())
	{
		$result = ''; // Initialize the variable to store the result

		// Check if 'search_for' parameter is provided
		if (!empty($params['search_for'])) {
			// If searching for count, select the count of country_id
			$this->db->select("count(ft.country_id) as counts");
		} else {
			// Otherwise, select all fields from the country table
			$this->db->select("ft.*");
			// Select the name of the user who added the country
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.added_by) as added_by_name "); // Select added_by user's name
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.updated_by) as updated_by_name "); // Select updated_by user's name
		}

		// Set the table to select data from
		$this->db->from("country as ft");



		// Conditional logic for ordering results
		if (!empty($params['order_by'])) {
			$this->db->order_by($params['order_by']);
		} else {
			$this->db->order_by("ft.country_id desc"); // Default order by admin_user_id descending
		}

		// Check if country_id parameter is provided
		if (!empty($params['country_id'])) {
			// Add a condition to select data only for the specified country_id
			$this->db->where("ft.country_id", $params['country_id']);
		}

		// Check if start_date parameter is provided
		if (!empty($params['start_date'])) {
			// Convert start_date to proper format and add condition to select data added on or after start_date
			$temp_date = date('Y-m-d', strtotime($params['start_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') >= DATE_FORMAT('$temp_date', '%Y%m%d')");
		}

		// Check if end_date parameter is provided
		if (!empty($params['end_date'])) {
			// Convert end_date to proper format and add condition to select data added on or before end_date
			$temp_date = date('Y-m-d', strtotime($params['end_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') <= DATE_FORMAT('$temp_date', '%Y%m%d')");
		}

		// Check if record_status parameter is provided
		if (!empty($params['record_status'])) {
			// Add condition based on record_status value
			if ($params['record_status'] == 'zero') {
				// If record_status is 'zero', select data where status is 0
				$this->db->where("ft.status = 0");
			} else {
				// Otherwise, select data for the specified record_status
				$this->db->where("ft.country_id", $params['record_status']);
			}
		}

		// Check if field_name and field_value parameters are provided for searching
		if (!empty($params['field_value']) && !empty($params['field_name'])) {
			// Add a condition to select data where the specified field contains the specified value
			$this->db->where("$params[field_name] like ('%$params[field_value]%')");
		}

		// Check if limit and offset parameters are provided for pagination
		if (!empty($params['limit']) && !empty($params['offset'])) {
			// Limit the number of results based on the provided limit and offset
			$this->db->limit($params['limit'], $params['offset']);
		} else if (!empty($params['limit'])) {
			// If only limit is provided, limit the number of results without offset
			$this->db->limit($params['limit']);
		}

		// Execute the query and retrieve the result
		$query_get_list = $this->db->get();
		$result = $query_get_list->result();

		// Return the result
		return $result;
	}

}

?>