<?php
class Location_model extends CI_Model
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
	function get_location_data($params = array())
	{
		$result = '';
		if (!empty($params['search_for'])) {
			$this->db->select("count(ft.location_id) as counts");
		} else {
			$this->db->select("ft.*,s.state_id,s.state_name,ci.city_id,ci.city_name ");
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.added_by) as added_by_name ");
			$this->db->select("(select au.admin_user_name from admin_user as  au where au.admin_user_id = ft.updated_by) as updated_by_name ");
		}

		$this->db->from("location as ft");
		$this->db->join("state as s", "s.state_id = ft.state_id");
		$this->db->join("city as ci", "ci.city_id = ft.city_id");


		// Conditional logic for ordering results
		if (!empty($params['order_by'])) {
			$this->db->order_by($params['order_by']);
		} else {
			$this->db->order_by("ft.location_id desc"); // Default order by admin_user_id descending
		}

		if (!empty($params['location_id'])) {
			$this->db->where("ft.location_id", $params['location_id']);
		}

		if (!empty($params['start_date'])) {
			$temp_date = date('Y-m-d', strtotime($params['start_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') >= DATE_FORMAT('$temp_date', '%Y%m%d')");
		}

		if (!empty($params['end_date'])) {
			$temp_date = date('Y-m-d', strtotime($params['end_date']));
			$this->db->where("DATE_FORMAT(ft.added_on, '%Y%m%d') <= DATE_FORMAT('$temp_date', '%Y%m%d')");
		}

		if (!empty($params['record_status'])) {
			if ($params['record_status'] == 'zero') {
				$this->db->where("ft.status = 0");
			} else {
				$this->db->where("ft.status", $params['record_status']);
			}
		}


		if (!empty($params['is_display'])) {
			if ($params['is_display'] == 'zero') {
				$this->db->where("ft.is_dipslay = 0");
			} else {
				$this->db->where("ft.is_dipslay", $params['is_display']);
			}
		}



		if (!empty($params['field_value']) && !empty($params['field_name'])) {
			$this->db->where("$params[field_name] like ('%$params[field_value]%')");
		}

		if (!empty($params['limit']) && !empty($params['offset'])) {
			$this->db->limit($params['limit'], $params['offset']);
		} else if (!empty($params['limit'])) {
			$this->db->limit($params['limit']);
		}

		$query_get_list = $this->db->get();
		$result = $query_get_list->result();

		return $result;
	}
}

?>