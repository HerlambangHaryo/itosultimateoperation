<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Itt_container extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('yard');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/itt_container/itt_container_panel', $data);
		}
	}
	
	public function data_itt_container(){
		$id_ves_voyage = $_REQUEST['id_ves_voyage'];
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->container->get_data_itt_container_list($id_ves_voyage, $container_list, $paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function save_itt_container(){
		$id_user = $this->session->userdata('id_user');
		$data = $this->container->save_itt_container($_POST,$id_user);
		
		echo json_encode($data);
	}
	
	public function data_yard_lini2_autocomplete(){
		$filter = $_GET['query'];
		$data	= $this->yard->get_yard_lini2_list($filter);
		echo json_encode($data);
	}
}