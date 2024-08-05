<?php
class Ajax_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Kolkata");
  }

  function get_state($params = array())
  {
    $this->db
      ->select('ft.*')
      ->from('state as ft')
      ->where('ft.status', 1)
      ->order_by('ft.state_name ASC');
    if (!empty($params['country_id']))
      $this->db->where('ft.country_id', $params['country_id']);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $result = $result->result();
      return $result;
    } else {
      return false;
    }
  }

  function get_city($params = array())
  {
    $this->db
      ->select('ft.*')
      ->from('city as ft')
      ->where('ft.status', 1)
      ->order_by('ft.city_name ASC');
    if (!empty($params['state_id']))
      $this->db->where('ft.state_id', $params['state_id']);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $result = $result->result();
      return $result;
    } else {
      return false;
    }
  }



}

?>