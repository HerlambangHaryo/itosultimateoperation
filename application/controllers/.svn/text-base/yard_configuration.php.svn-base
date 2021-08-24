<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_configuration extends CI_Controller {
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
		
		$this->load->view('templates/yard_configuration/yard_list', $data);
	}
	
	public function data_yard_list(){
		$data = $this->yard->get_yard_list();
		echo json_encode($data);
	}
	
	public function data_block_list(){
		$data = $this->yard->get_block_list($_GET['id_yard']);
		echo json_encode($data);
	}
	
	public function editor_panel(){
		$data['id_yard'] = $_GET['id_yard'];
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_configuration/editor_panel', $data);
	}
	
	public function update_block_detail($id_yard, $id_block){
		$tier = $_POST['TIER_'];
		$orientation = $_POST['ORIENTATION'];
		$retval = $this->yard->change_block_tier_count($id_yard, $id_block, $tier);
		if ($orientation){
			$retval = $this->yard->change_block_slot_row_orientation($id_yard, $id_block, $orientation);
		}
		echo $retval;
	}
}