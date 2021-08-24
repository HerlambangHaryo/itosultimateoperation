<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Coarri extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$data_vessel = $this->vessel->get_vesvoy($data['id_ves_voyage']);
			foreach ($data_vessel as $row_ves)
			{
				$ves = $row_ves['VESVOY'];
			}
			$data['vessel'] = $ves;			
			$this->load->view('templates/coarri/generate_coarri', $data);
			$this->load->view('templates/coarri/coarri_list', $data);
		}
	}
	
	public function data_coarri_list(){
		$id_ves_voyage = $_GET['id_ves_voyage'];
		$data = $this->container->get_data_coarri_list($id_ves_voyage);
		echo json_encode($data);
	}
	
	public function generate_coarri(){
		$id_user = $this->session->userdata('id_user');
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$typefile = $_POST['typefile'];
		
		if ($typefile=='edifact'){
			$retval = $this->container->edi_coarri($id_ves_voyage, $id_user);
			$data['success']=$retval['flag'];
			$data['errors']=$retval['msg'];
		}else{
			$data['success']='success';
			$data['errors']=' ';
		}
		
		echo json_encode($data);
	}

	public function save_coarri_page($file_name){
		$data['file_name'] = strtoupper($file_name);
		$this->load->view('templates/coarri/coarri_generate_view', $data);
	}		

}