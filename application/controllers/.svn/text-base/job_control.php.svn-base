<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Job_control extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('general_helper','url','form'));
		$this->load->library('form_validation');
		$this->load->model(array('machine','master','gtools'));
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['pool'] = $this->master->get_pool();
		$this->load->view('templates/job_control/job_control_grid', $data);
	}
	
	//filter paging short
	public function get_data_job_control(){
	    $paging = array(
		'page'=>$_REQUEST['page'],
		'start'=>$_REQUEST['start'],
		'limit'=>$_REQUEST['limit']
	    );
	    $sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
	    $filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;

	    $retval = $this->machine->get_data_job_control($paging, $sort, $filters);
	    echo json_encode($retval);
	}
	
	public function assign_pool_mch(){
	    echo json_encode($this->machine->assign_pool_mch($_POST));
	}
}