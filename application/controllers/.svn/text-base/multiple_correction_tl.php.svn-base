<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Multiple_correction_tl extends CI_Controller {
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
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/multiple_correction_tl/multiple_correction_tl_panel', $data);
		}
	}
	
	public function data_multiple_correction_tl(){
		$id_ves_voyage = $_REQUEST['id_ves_voyage'];
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->container->get_data_multiple_correction_tl($id_ves_voyage, $container_list, $paging, $sort, $filters);
//		exit;
		echo json_encode($retval);
	}
	
	public function save_multiple_correction_tl(){
		$id_user = $this->session->userdata('id_user');
		$data = $this->container->save_multiple_correction_tl($_POST,$id_user);
		
		echo json_encode($data);
	}
}