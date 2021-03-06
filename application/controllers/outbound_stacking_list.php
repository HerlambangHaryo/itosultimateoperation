<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Outbound_stacking_list extends CI_Controller {
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
			$this->load->view('templates/outbound_stacking_list/grid_panel', $data);
		}
	}
	
	public function data_outbound_stacking_list(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$id_ves_voyage = $_REQUEST['id_ves_voyage'];
		$data	= $this->container->get_container_outbound_stacking_list($id_ves_voyage, $paging, $sort, $filters);
		echo json_encode($data);
	}
	
	public function export_to_excel($id_ves_voyage){
		$data['id_ves_voyage'] = $id_ves_voyage;
		
		$data['username'] = $this->session->userdata('username');
		$data['corporate_name']='PT. PELABUHAN INDONESIA II';
		$data['date']=date('d-M-Y H:i');
		
		$dataheader=$this->container->headerLoadingList($id_ves_voyage);
		foreach($dataheader as $rowh){
			$data['vsname']=$rowh['VESSEL_NAME'];
			$data['ves_id']=$rowh['ID_VESSEL'];
			$data['voyg']=$rowh['VOYG'];
			
			$data['rta']=$rowh['RTA'];
			$data['berth']=$rowh['RTB'];
			$data['rtd']=$rowh['RTD'];
			$data['str']=$rowh['STW'];
			$data['end']=$rowh['STW'];
		}
		
		$data['datadetail']=$this->container->getAll_container_outbound_stacking_list($id_ves_voyage);
		//debux($data);
		
		$this->load->view('templates/outbound_stacking_list/export_excel', $data);
	}
}