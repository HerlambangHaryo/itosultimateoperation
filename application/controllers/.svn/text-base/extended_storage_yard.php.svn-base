<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class extended_storage_yard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('extended_storage_yard_model');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/extended_storage_yard/extended_storage_yard_panel', $data);
		}
	}
	
	public function data_lini2()
	{
		$data	= $this->extended_storage_yard_model->get_data_lini2();
		echo json_encode($data);
	}	
	
	public function data_extended_storage_yard(){
		$id_ves_voyage = $_REQUEST['id_ves_voyage'];
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->extended_storage_yard_model->get_data_extended_storage_yard($id_ves_voyage, $container_list, $paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function save_extended_storage_yard(){
		$id_user = $this->session->userdata('id_user');
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$id_and_name_yard = $_POST['ID_YARD'];
		/*$viayard = $_POST['IS_VIA_YARD'];*/
		
		$data = $this->extended_storage_yard_model->save_extended_storage_yard($_POST,$id_user,$id_ves_voyage,$id_and_name_yard,'N');
		
		echo json_encode($data);
	}
}