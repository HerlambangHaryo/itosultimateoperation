<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Stowage_instruction_summary extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->model('yard');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		// $this->load->view('templates/stowage_instruction_summary/group_grid', $data);
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/stowage_instruction_summary/group_grid', $data);
		}
	}
	
	public function popup_new_category(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/stowage_instruction_summary/popup_new_category', $data);
	}
	
	
	public function insert_category(){
		$category_name = $_POST['name'];
		$category_detail = json_decode($_POST['detail']);
		$ves_voy = $_POST['ves_voy'];
		$retval = $this->vessel->insert_category($category_name, $category_detail,$ves_voy);
		echo $retval;
	}
	
	//====================================================================
	
	public function data_yard_plan_group(){
		$data	= $this->vessel->get_si_category_group();
		echo json_encode($data);
	}
	
	public function delete_yard_plan_group(){
		$id_yard_plan = $_POST['id_yard_plan'];
		$retval	= $this->yard->delete_yard_plan_group($id_yard_plan);
		echo $retval;
	}
}