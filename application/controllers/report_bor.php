<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_bor extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_bor', $data);
	}
	
	public function get_data_bor(){
		$start_period = $_GET['START_PERIOD'];
		$end_period = $_GET['END_PERIOD'];
		
		$retval['start_period'] = $start_period;
		$retval['end_period'] = $end_period;
		$retval['data_detail'] = $this->vessel->get_data_bor($start_period, $end_period);
		$retval['data_kade_period'] = $this->vessel->get_data_kade_periode($start_period, $end_period);
		
		$this->load->view('templates/report/ete_report_bor', $retval);
	}
	
	public function get_data_bor_show(){
		$start_period = $_POST['START_PERIOD'];
		$end_period = $_POST['END_PERIOD'];
		
		$retval['start_period'] = $start_period;
		$retval['end_period'] = $end_period;
		$retval['data_detail'] = $this->vessel->get_data_bor($start_period, $end_period);
		$retval['data_kade_period'] = $this->vessel->get_data_kade_periode($start_period, $end_period);
		
		$this->load->view('templates/report/ete_report_bor_show', $retval);
	}
}