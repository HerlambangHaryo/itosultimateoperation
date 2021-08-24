<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Dashboard_monitoring extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/dashboard_monitoring/dashboard_monitoring_panel', $data);
	}
}