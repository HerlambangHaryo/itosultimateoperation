<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Gate_job_manager extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('gtools');
		$this->load->model('container');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/gate_job_manager/job_list', $data);
	}
	
	public function data_job_list(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$sfil = array('ifil' => $_REQUEST['filter']);
		if(isset($_REQUEST['filter'])){
			$this->session->set_userdata($sfil);	
		}

		//debux($_REQUEST);die;

		$retval = $this->container->get_data_gate_job_list($paging, $sort, $filters);
		//debux($retval);die;
		echo json_encode($retval);
	}

	public function excel_Gate_job_manager(){
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = json_decode($this->session->userdata('ifil'));
		$retval['data_detail'] = $this->container->get_data_gate_job_list_report($sort, $filters);

		//debux($retval);die;
		
		$this->load->view('templates/gate_job_manager/job_list_excel', $retval);
		$this->session->unset_userdata('ifil');
	}
}