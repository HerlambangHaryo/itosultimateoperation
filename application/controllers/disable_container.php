<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Disable_container extends CI_Controller {
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
		
		$this->load->view('templates/disable_container/disable_container_panel', $data);
	}
	
	public function data_container_inquiry(){
		$point = false;
		if (isset($_POST['point'])) {
			$point = $_POST['point'];
		}
		$retval = $this->container->get_data_single_correction($_POST['no_container'], $point);
		
		$data = array(
			'success'=>false,
			'errors'=>'container not found error'
		);
		
		if ($retval){
			$data['success']=true;
			$data['errors']='';
			$data['data']=json_encode($retval);
		}
		
		echo json_encode($data);
	}
	
	public function list_of_point(){
		$data = $this->container->get_container_list_of_point($_GET['no_container']);
		echo json_encode($data);
	}
	
	public function save_disable_container(){
		$id_user = $this->session->userdata('id_user');
		$data = $this->container->save_disable_container($_POST,$id_user);
		
		echo json_encode($data);
	}
}