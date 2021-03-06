<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Vessel_production_volume extends CI_Controller { 
	public function __construct(){
		parent::__construct();

		$this->load->helper(array('url','form','general'));
		$this->load->model(array('yard','vessel','machine','gtools'));
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_vpv', $data);
	}

	public function data_vessel_year(){
		$response = $this->vessel->data_vessel_year();
		echo json_encode($response);
	}

	public function get_data_vpv(){
		
		$year 					= $this->input->get('year');
		$data['vessel1'] 		= $this->vessel->data_vessel_by_year($year);
		$data['all_machine']	= $this->vessel->get_all_machines();
		$data['year']			= $year;

		$this->load->view('templates/report/ete_report_vpv', $data);	

	}

	public function get_data_vpv_show(){
		
		$year 					= $_POST['year'];
		$data['vessel1'] 		= $this->vessel->data_vessel_by_year($year);
		$data['all_machine']	= $this->vessel->get_all_machines();
		$data['year']			= $year;

		$this->load->view('templates/report/ete_report_vpv_show', $data);	

	}

}