<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class External_truck_monitoring extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('machine');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/external_truck_monitoring/truck_list', $data);
	}
	
	public function data_truck_list(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->machine->get_data_external_truck_list($paging, $sort, $filters);
		echo json_encode($retval);
	}
}