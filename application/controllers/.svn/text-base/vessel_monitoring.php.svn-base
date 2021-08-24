<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Vessel_monitoring extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('gtools');
		$this->load->model('container');
		$this->load->model('vessel');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$data['vessel'] = $this->vessel->get_vessel_berthing();
			$this->load->view('templates/vessel_monitoring/vessel_monitoring_panel', $data);
		}
	}

	public function test(){
		var_dump( $this->vessel->get_sum_container('I', 'CAGS2014001') );
		var_dump( $this->vessel->get_sum_container_confirm('I', 'CAGS2014001') );
	}
}