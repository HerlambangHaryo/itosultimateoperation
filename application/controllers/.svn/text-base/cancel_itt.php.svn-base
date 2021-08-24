<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Cancel_itt extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/cancel_itt/cancel_itt_panel', $data);
		}
	}
	
	public function data_cancel_itt_container(){
		$id_ves_voyage = $_REQUEST['id_ves_voyage'];
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->container->get_data_cancel_itt_container_list($id_ves_voyage, $container_list, $paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function cancel_itt_container(){
		$data = $this->container->cancel_itt_container($_POST['no_container'], $_POST['point']);
		echo json_encode($data);
	}
}