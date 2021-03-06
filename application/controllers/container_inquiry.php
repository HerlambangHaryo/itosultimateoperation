<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Container_inquiry extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('gtools');
		
		$this->gtools->update_terminal();
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data_id = isset($_POST['data_id']) ? $_POST['data_id'] : '';
		$arr = explode(':', $data_id);
		$data['no_container'] = $arr[0] == 'no_container' ? $arr[1] : '';
		
//		echo '<pre>fuck</pre>';
		$this->load->view('templates/container_inquiry/container_inquiry_panel', $data);
	}
	
	public function data_container_inquiry(){ 
		$point = false;
		if (isset($_POST['point'])) {
			$point = $_POST['point'];
		}
		$retval = $this->container->get_data_container_inquiry($_POST['no_container'], $point);
		//print_r($retval);die;
		
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
	
	public function popup_history_status(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$data['no_container'] = $_POST['no_container'];
		$data['point'] = $_POST['point'];
		$this->load->view('templates/container_inquiry/popup_history_status', $data);
	}
	
	public function history_status_detail(){
		$data = $this->container->get_container_history_status($_GET['no_container'], $_GET['point']);
		echo json_encode($data);
	}

	public function popup_history_data_change(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$data['no_container'] = $_POST['no_container'];
		$data['point'] = $_POST['point'];
		$this->load->view('templates/container_inquiry/popup_history_data_change', $data);
	}

	public function history_data_change_detail(){
		$data = $this->container->get_container_history_statusChange($_GET['no_container'], $_GET['point']);
		echo json_encode($data);
	}
	
	public function list_of_point(){
		$data = $this->container->get_container_list_of_point($_GET['no_container']);
		echo json_encode($data);
	}
}