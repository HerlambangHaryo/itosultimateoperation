<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Virtual_block_view extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('yard','user','machine','container'));
	}
	
	public function index(){
	    $data['tab_id'] = $_GET['tab_id'];
	    $data['id_ves_voyage'] = $_POST['data_id'];
	    $this->load->view('templates/virtual_block_view/virtual_block_grid', $data);
	}
	
	//filter paging short
	public function data_virtual_block(){
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$id_ves_voyage = $_GET['id_ves_voyage'];
		
		$retval = $this->container->get_container_virtual_block($id_ves_voyage,$paging, $sort, $filters);
		echo json_encode($retval);
	}
}