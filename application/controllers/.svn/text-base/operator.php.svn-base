<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Operator extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('master');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/operator/vw_operator_grid', $data);
	}
	
	public function data_operator(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->master->get_operator($paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function form_addoperator(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/operator/vw_operator_formAdd', $data);
	}
	
	public function form_editoperator(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['ID_OPERATOR'] = $_GET['ID_OPERATOR'];
		
		$data_vessel = $this->master->get_info_operator_by_id_operator($data['ID_OPERATOR']);
		$data['vessel_detail'] = json_encode($data_vessel[0]);		
		$this->load->view('templates/operator/vw_operator_formEdit', $data);
	}
	
	public function save_operator(){
		$id_user = $this->session->userdata('id_user');
		$id_operator = strtoupper(trim($_POST['ID_OPERATOR']));
		$operator_name = strtoupper(trim($_POST['OPERATOR_NAME']));
		
		$data = $this->master->save_operator($id_operator, $operator_name, $id_user);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function enabled_or_disabled_operator(){
		//$id_user = $this->session->userdata('id_user');
		$id_operator = strtoupper(trim($_POST['ID_OPERATOR']));
		$isActive = strtoupper(trim($_POST['IS_ACTIVE']));
		
		$data = $this->master->enabled_or_disabled_operator($id_operator, $isActive);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}