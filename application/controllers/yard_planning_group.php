<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_planning_group extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_planning_group/group_grid', $data);
	}
	
	public function data_yard_plan_group(){
		$id_ves_voyage = isset($_REQUEST['ves_voyage']) ? $_REQUEST['ves_voyage'] : '';
		$id_category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
		$data	= $this->yard->get_yard_plan_group($id_ves_voyage,$id_category);
		echo json_encode($data);
	}
	
	public function delete_yard_plan_group(){
		$id_yard_plan = $_POST['id_yard_plan'];
		$retval	= $this->yard->delete_yard_plan_group($id_yard_plan);
		echo $retval;
	}

	public function delete_yard_plan_group_mutiple()
	{
		$data = $this->input->post('response');
		$response = $this->yard->delete_yard_plan_group_mutiple($data);
		echo $response;
	}
	
	public function form_editYardPlan(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard_plan'] = $id_yard_plan = $_POST['id_yard_plan'];
		$data['group_plan']	= $this->yard->get_yard_plan_group_by_id($id_yard_plan);
//		echo '<pre>';print_r($data['truck']);echo '</pre>';exit;
		$this->load->view('templates/yard_planning_group/vw_yard_plan_group_formEdit', $data);
	}
	
	public function edit_yard_plan(){
	    $data['id_user'] 	= $this->session->userdata('id_user');
	    $data['ID_YARD_PLAN'] 	= strtoupper(trim($_POST['ID_YARD_PLAN']));
	    $data['START_SLOT'] 	= strtoupper(trim($_POST['START_SLOT']));
	    $data['END_SLOT'] 	= strtoupper(trim($_POST['END_SLOT']));
	    $data['START_ROW'] 	= strtoupper(trim($_POST['START_ROW']));
	    $data['END_ROW'] 	= strtoupper(trim($_POST['END_ROW']));

	    $respose = $this->yard->edit_yard_plan_group($data);

	    header('Content-Type: application/json');
	    echo json_encode($respose);
	}
	
	public function get_yard_plan_group_category_list(){
	    $id_ves_voyage = isset($_REQUEST['ves_voyage']) ? $_REQUEST['ves_voyage'] : '';
	    $data	= $this->yard->get_yard_plan_group_category_list($id_ves_voyage);
	    echo json_encode($data);
	}
}