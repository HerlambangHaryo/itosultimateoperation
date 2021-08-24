<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class cancel_transhipment_container extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('extended_storage_yard_model');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->model('gtools');
		//$this->load->model('yard_model');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/transhipment_container/cancel_transhipment_container', $data);
		}
	}
	
	public function data_transhipment_container(){
		$id_ves_voyage = $_REQUEST['id_ves_voyage'];
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->extended_storage_yard_model->get_data_cancel_transhipment_container($id_ves_voyage, $container_list, $paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function save_cancel_transhipment_container(){
		$id_user = $this->session->userdata('id_user');
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$data = $this->extended_storage_yard_model->save_cancel_transhipment_container($_POST,$id_user,$id_ves_voyage);
		
		//buat ke placement
		/*$no_container = $_POST['container_data'][0]->NO_CONTAINER;
		$point 		  = $_POST['POINT'];
		$id_op_status = $_POST['ID_OP_STATUS'];
		$event 		  = $_POST['EVENT'];
		$user_id 	  = $this->session->userdata('id_user');
		$yt 		  = $_POST['yt'];
		$class_code 		  = $_POST['ID_CLASS_CODE'];
		$retval = $this->yard_model->yard_placement_submit($no_container, $point, $id_op_status, $event, $user_id, $this->session->userdata('yc_driver_id'), $this->session->userdata('yc_machine_id'), $yard_position,$yt,$class_code);
*/
		echo json_encode($data);
	}
}