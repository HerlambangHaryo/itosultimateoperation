<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_equipment_group extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('machine');
		$this->load->model('yard');
		$this->load->model('gtools');
	}
	
	public function index(){
	    $data['tab_id'] = $_GET['tab_id'];

	    $this->load->view('templates/yard_equipment_group/group_grid', $data);
	}
	
	public function get_machine(){
	    $data = $this->machine->get_all_machines();
	    echo json_encode($this->machine->get_all_machines());
	}
	
	public function data_yard_equipment_group(){
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
//		echo '<pre>';print_r($filters);echo '</pre>';
		$data	= $this->machine->get_yard_equipment_group($filters);
//		exit;
		echo json_encode($data);
	}
	
	public function delete_yard_equipment_group(){
		$id_mch_plan = $_POST['id_mch_plan'];
		$retval	= $this->machine->delete_yard_equipment_group($id_mch_plan);
		echo $retval;
	}
	
	public function popup_change_equipment(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_mch_plan'] = $_POST['id_mch_plan'];
		
		$this->load->view('templates/yard_equipment_group/popup_change_equipment', $data);
	}
	
	public function data_yard_machine(){
		$data = $this->machine->get_data_machine('YARD');
		echo json_encode($data);
	}
	
	public function save_change_equipment_plan(){
		$retval = $this->yard->save_change_equipment_plan($_POST);
		echo $retval;
	}
}