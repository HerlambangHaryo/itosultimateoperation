<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Operator_assignment extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->helper('general_helper');
		$this->load->model('master');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/operator_assignment/main_panel', $data);
	}
	
	public function data_alat(){
		$data	= $this->master->getMachineList();
		debux($data);die();
		echo json_encode($data);
	}
	
	public function data_operator(){
		$data	= $this->master->getOperatorList($a);
		echo json_encode($data);
	}
	
	
	
	
	public function popup_assign_operator(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['list_container'] = $_POST['list_container'];
		
		$this->load->view('templates/operator_assignment/popup_assign_operator', $data);
	}
	
	public function popup_finish_job(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['no_container'] = $_POST['no_container'];
		$data['point'] = $_POST['point'];
		$data['id_op_status'] = $_POST['id_op_status'];
		$data['event'] = $_POST['event'];
		$data['block_name'] = $_POST['block_name'];
		$data['id_block'] = $_POST['id_block'];
		$data['slot'] = $_POST['slot'];
		$data['row'] = $_POST['row'];
		$data['tier'] = $_POST['tier'];
		$data['yard_placement'] = $_POST['yard_placement'];
		
		$this->load->view('templates/operator_assignment/popup_finish_job', $data);
	}
	
	public function list_operator(){
		$data = $this->master->getOperatorList();
		echo json_encode($data);
	}
	
}
