<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Reefer_job_manager extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/reefer_job_manager/main_panel', $data);
	}
}