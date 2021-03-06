<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_yor extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_yor', $data);
	}
	
	public function get_data_yor(){
		$filter_block = $_GET['filter_block'];
		// echo $retval['filter_block'];die();

		$yard = $this->yard->getAllYard();

		$i=0;
		$temp = array();
		foreach($yard as $key){
			if($key['ID_YARD'] != 0){
				$temp[$i]['detail'] = $this->yard->get_data_yor($filter_block,$key['ID_YARD']);
			}
			$i++;
		}
		
		$retval['data_detail'] = $temp;
		
		//debux($retval);

		$this->load->view('templates/report/ete_report_yor', $retval);
	}
	
	public function get_data_yor_show(){
		$filter_block = $_POST['filter_block'];
		// echo $retval['filter_block'];die();

		$yard = $this->yard->getAllYard();

		$i=0;
		$temp = array();
		foreach($yard as $key){
			if($key['ID_YARD'] != 0){
				$temp[$i]['detail'] = $this->yard->get_data_yor($filter_block,$key['ID_YARD']);
			}
			$i++;
		}
		$retval['data_detail'] = $temp;
		
		//debux($retval);

		$this->load->view('templates/report/ete_report_yor_show', $retval);
	}

	public function get_data_yor_blockname(){
		$data	= $this->yard->get_blockname();
		// var_dump($data);die();
		echo json_encode($data);
	}

	public function get_data_vessel(){
		$data = $this->yard->get_vessel();
		echo json_encode($data);
	}
}