<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Heap_zone extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/heap_zone/grid_panel', $data);
	}
	
	public function data_heapzone(){
		$data	= $this->yard->get_heapzone_list();
		echo json_encode($data);
	}
	
	public function add_panel(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/heap_zone/add_panel', $data);
	}
	
	public function detail_panel(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['ID_HEAPZONE'] = $_POST['data_id'];
		$data_heapzone = $this->yard->get_heapzone_detail($data['ID_HEAPZONE']);
		$data['heapzone_detail'] = json_encode($data_heapzone);
		
		$this->load->view('templates/heap_zone/detail_panel', $data);
	}
	
	public function delete_heapzone(){
		$retval = $this->yard->delete_heapzone($_POST['ID_HEAPZONE']);
		echo $retval;
	}
}