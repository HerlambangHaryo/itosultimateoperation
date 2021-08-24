<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Outstanding_job extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/outstanding_job/export_file', $data);
		$this->load->view('templates/outstanding_job/job_list', $data);
	}
	
	public function data_job_list(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->container->get_data_outstanding_job($paging, $sort, $filters);
		echo json_encode($retval);
	}
}