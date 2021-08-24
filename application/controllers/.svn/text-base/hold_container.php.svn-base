<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Hold_container extends CI_Controller {
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
		$this->load->view('templates/hold_container/hold_container_panel', $data);
	}
	
	public function data_container(){
	    $container_list = $_REQUEST['container_list'];
	    $paging = array(
		    'page'=>$_REQUEST['page'],
		    'start'=>$_REQUEST['start'],
		    'limit'=>$_REQUEST['limit']
	    );
	    $sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
	    $filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;

	    $retval = $this->container->get_data_hold_container_list($container_list, $paging, $sort, $filters);
	    echo json_encode($retval);
	}
	
	public function save_hold_container(){
		$id_user = $this->session->userdata('id_user');
//		echo '<pre>';print_r($_POST);echo '</pre>';exit;
		$data = $this->container->save_hold_container($_POST,$id_user);
		
		echo json_encode($data);
	}
}