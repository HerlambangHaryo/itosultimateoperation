<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Multiple_correction extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/multiple_correction/multiple_correction_panel', $data);
	}
	
	public function data_multiple_correction(){
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->container->get_data_multiple_correction_list($container_list, $paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function update_list_detail($no_container, $point){
		$data = $_POST;
		$id_user = $this->session->userdata('id_user');
		$retval = $this->container->save_multiple_correction($no_container, $point, $id_user, $data);
		echo json_encode($retval);
	}
	
	public function data_vessel_schedule_autocomplete(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_vessel_schedule_list($filter);
		echo json_encode($data);
	}

	public function data_port($alias){
		$filter = $_GET['query'];
		$id_ves_voyage = $_GET['id_ves_voyage'];
		$data	= $this->container->get_port_list_multiple($filter,$id_ves_voyage);
		for ($i=0; $i<sizeof($data); $i++){
			$data[$i][$alias] = $data[$i]['ID_POD'];
		}
		echo json_encode($data);
	}
}