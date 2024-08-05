<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');


include_once ('Main.php');
class User extends Main
{

  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Kolkata");

    //models
    $this->load->model('Common_Model');


    //headers
    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
    $this->output->set_header("Pragma: no-cache");

  }



  public function index()
  {

    ;



    $this->data['meta_title'] = _project_name_;
    $this->data['meta_description'] = _project_name_;
    $this->data['meta_keywords'] = _project_name_;
    $this->data['meta_others'] = "";




    parent::get_header('header', $this->data);
    $this->load->view('home', $this->data);
    parent::get_footer('footer', $this->data);




  }







  public function pageNotFound()
  {
    $this->data['meta_title'] = _project_name_ . " - Page Not Found";
    $this->data['meta_description'] = _project_name_ . " - Page Not Found";
    $this->data['meta_keywords'] = _project_name_ . " - Page Not Found";
    $this->data['meta_others'] = "";

    $this->load->view('pageNotFound', $this->data);
  }

  public function found404()
  {
    $this->data['meta_title'] = _project_name_ . " - 404 found";
    $this->data['meta_description'] = _project_name_ . " - 404 found";
    $this->data['meta_keywords'] = _project_name_ . " - 404 found";
    $this->data['meta_others'] = "";

    parent::get_header('header', $this->data);
    $this->load->view('404found', $this->data);
    parent::get_footer('footer', $this->data);
  }



}
