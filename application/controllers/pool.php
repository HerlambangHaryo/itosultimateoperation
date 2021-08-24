<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start(); 

class Pool extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('general_helper','url','form'));
		$this->load->library('form_validation');
		$this->load->model(array('master','gtools'));
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/pool/vw_pool_grid', $data);
	}
	
	//filter paging short
	public function data_pool(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->master->get_pool($paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function form_addPool(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/pool/vw_pool_formAdd', $data);
	}

	public function form_editPool(){
		
		$data['tab_id'] = $_GET['tab_id'];
		$data['pool']	= $this->master->get_pool_by_id($_POST['id_pool']);
//		echo '<pre>';print_r($data['pool']);echo '</pre>';exit;
		$this->load->view('templates/pool/vw_pool_formEdit', $data);
	}
	
	public function check_pool(){
		$pool_name = $_POST['pool_name'];
		$retval = $this->master->check_pool($pool_name);
		echo $retval;
	}
	
	public function save_pool(){

		$data['id_user'] 	= $this->session->userdata('id_user');
		$data['POOL_NAME'] 	= strtoupper(trim($_POST['POOL_NAME']));
		$data['POOL_DESCRIPTION']		= strtoupper(trim($_POST['POOL_DESCRIPTION']));
		$data['POOL_TYPE']		= strtoupper(trim($_POST['POOL_TYPE']));

		$response = $this->master->save_pool($data);
		
//		header('Content-Type: application/json');
//		echo '<pre>';print_r($response);echo '</pre>';exit;
		echo json_encode($response);
	}

	public function edit_pool()
	{
	    $data['id_user'] 	= $this->session->userdata('id_user');
	    $data['ID_POOL'] 	= strtoupper(trim($_POST['ID_POOL']));
	    $data['POOL_NAME'] 	= strtoupper(trim($_POST['POOL_NAME']));
	    $data['POOL_DESCRIPTION']		= strtoupper(trim($_POST['POOL_DESCRIPTION']));
	    $data['POOL_TYPE']		= strtoupper(trim($_POST['POOL_TYPE']));

	    $respose = $this->master->edit_pool($data);

	    header('Content-Type: application/json');
	    echo json_encode($respose);
	}
	
	public function delete_pool(){
	    $id_pool 	= strtoupper(trim($_POST['ID_POOL']));
	    $pool_name 	= strtoupper(trim($_POST['POOL_NAME']));
	    $respose = $this->master->delete_pool($id_pool,$pool_name);

	    header('Content-Type: application/json');
	    echo json_encode($respose);
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
	    $this->load->view('templates/pool/vw_pool_formTruckAssignment', $data);
	    
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