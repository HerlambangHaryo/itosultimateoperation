<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Machine_specification extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('general_helper','url','form'));
		$this->load->library('form_validation');
		$this->load->model(array('master','gtools','machine'));
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/machine_specification/vw_mch_spec_grid', $data);
	}
	
	//filter paging short
	public function data_mch_spec(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->machine->get_machine_specification($paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function form_addMch(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/machine_specification/vw_mch_spec_formAdd', $data);
	}

	public function form_editMch($id_machine){
		
		$data['tab_id'] = $_GET['tab_id'];
		$data['machine'] = $this->machine->get_mch_spec_by_id($id_machine);
//		echo '<pre>';print_r($data['machine']);echo '</pre>';exit;
		$this->load->view('templates/machine_specification/vw_mch_spec_formEdit', $data);
	}
	
	public function check_mch(){
		$mch_name = $_POST['mch_name'];
		$retval = $this->machine->check_mch($mch_name);
		echo $retval;
	}
	
	public function save_mch(){

		$data['CREATE_USER']  = $this->session->userdata('id_user');
		$data['MCH_NAME']  = trim($_POST['MCH_NAME']);
		$data['MCH_TYPE'] = trim($_POST['MCH_TYPE']);
		$data['MCH_SUB_TYPE'] = trim($_POST['MCH_SUB_TYPE']);
		$data['SIZE_CHASSIS'] = trim($_POST['SIZE_CHASSIS']);
		$data['STANDARD_BCH'] = trim($_POST['STANDARD_BCH']);
		$data['BG_COLOR'] = trim($_POST['BG_COLOR']);
		$data['ID_TERMINAL'] = $this->gtools->terminal();
//		echo '<pre>';print_r($data);echo '</pre>';exit;
		$response = $this->machine->save_mch($data);
		
		header('Content-Type: application/json');
//		echo '<pre>';print_r($response);echo '</pre>';exit;
		echo json_encode($response);
	}

	public function edit_mch()
	{
	    $data['MODIFY_USER']  = $this->session->userdata('id_user');
	    $data['ID_MACHINE']  = trim($_POST['ID_MACHINE']);
	    $data['MCH_NAME']  = trim($_POST['MCH_NAME']);
//	    $data['MCH_TYPE'] = trim($_POST['MCH_TYPE']);
//	    $data['MCH_SUB_TYPE'] = trim($_POST['MCH_SUB_TYPE']);
	    $data['SIZE_CHASSIS'] = trim($_POST['SIZE_CHASSIS']);
	    $data['STANDARD_BCH'] = trim($_POST['STANDARD_BCH']);
	    $data['BG_COLOR'] = trim($_POST['BG_COLOR']);
	    $data['ID_TERMINAL'] = $this->gtools->terminal();

	    $respose = $this->machine->edit_mch($data);

	    header('Content-Type: application/json');
	    echo json_encode($respose);
	}
	
	public function delete_mch(){
	    $id_machine 	= strtoupper(trim($_POST['ID_MACHINE']));
	    $mch_name 	= strtoupper(trim($_POST['MCH_NAME']));
	    $response = $this->machine->delete_mch($id_machine,$mch_name);

	    header('Content-Type: application/json');
	    echo json_encode($response);
	}
	
	public function form_assignPool(){
	    $data['tab_id'] = $_GET['tab_id'];
	    $data['id_pool'] = $id_pool = $_POST['id_pool'];
	    $data['pool_name'] = $_POST['pool_name'];
	    $data['pool_description'] = $_POST['pool_description'];
	    $data['pool_type'] = $_POST['pool_type'];
//	    $data['data']	= $this->master->get_pool_itv($id_pool);
//		echo '<pre>';print_r($data);echo '</pre>';exit;
//	    exit;
	    $this->load->view('templates/machine_specification/vw_mch_spec_formTruckAssignment', $data);
	    
	}
	
	public function get_pool_itv($id_pool){
	    echo json_encode($this->master->get_pool_itv($id_pool));
	}
	
	public function save_pool_assigment(){
//	    echo '<pre>';print_r($_POST['id_pool']);echo '</pre>';
//	    echo '<pre>';print_r(json_decode($_POST['dataMachine']));echo '</pre>';exit;
	    
	    $id_pool = $_POST['id_pool'];
	    $arr_machine = json_decode($_POST['dataMachine']);
	    
	    $result = $this->master->save_pool_assigment($id_pool, $arr_machine);
	    
	    echo json_encode($result);
	}
}