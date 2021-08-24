<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Truck extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('general_helper','url','form'));
		$this->load->library('form_validation');
		$this->load->model(array('master','gtools'));
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/truck/vw_truck_grid', $data);
	}
	
	//filter paging short
	public function data_truck(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->master->get_truck($paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function form_addTruck(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/truck/vw_truck_formAdd', $data);
	}

	public function form_editTruck(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_truck'] = $id_truck = $_POST['id_truck'];
		$data['truck']	= $this->master->get_truck_by_id($id_truck);
//		echo '<pre>';print_r($data['truck']);echo '</pre>';exit;
		$this->load->view('templates/truck/vw_truck_formEdit', $data);
	}
	
	public function check_tid(){
		$tid = $_POST['tid'];
		$no_pol = $_POST['no_pol'];
		$retval = $this->master->check_tid($tid,$no_pol);
		echo $retval;
	}
	
	public function save_truck(){

		$data['id_user'] 	= $this->session->userdata('id_user');
		$data['ID_TRUCK'] 	= strtoupper(trim($_POST['ID_TRUCK']));
		$data['TID']		= strtoupper(trim($_POST['TID']));
		$data['NO_POL']		= strtoupper(trim($_POST['NO_POL']));

		$response = $this->master->save_truck($data);
		
//		header('Content-Type: application/json');
//		echo '<pre>';print_r($response);echo '</pre>';exit;
		echo json_encode($response);
	}

	public function edit_truck()
	{
	    $data['id_user'] 	= $this->session->userdata('id_user');
	    $data['ID_TRUCK'] 	= strtoupper(trim($_POST['ID_TRUCK']));
	    $data['TID'] 	= strtoupper(trim($_POST['TID']));
	    $data['NO_POL'] 	= strtoupper(trim($_POST['NO_POL']));

	    $respose = $this->master->edit_truck($data);

	    header('Content-Type: application/json');
	    echo json_encode($respose);
	}
	
	public function delete_truck(){
	    $id_truck 	= strtoupper(trim($_POST['ID_TRUCK']));
	    $tid 	= strtoupper(trim($_POST['TID']));
	    $respose = $this->master->delete_truck($id_truck,$tid);

	    header('Content-Type: application/json');
	    echo json_encode($respose);
	}
}