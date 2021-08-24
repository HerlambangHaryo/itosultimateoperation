<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Activate_stevedoring_job extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('machine');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/activate_stevedoring_job/activate_job_panel', $data);
		}
	}
	
	public function activate_job($activity){
		$retval = $this->machine->activate_stevedoring_job($_POST['id_ves_voyage'], $activity);
		
		echo $retval;
	}
	
	public function deactivate_job($activity){
		$retval = $this->machine->deactivate_stevedoring_job($_POST['id_ves_voyage'], $activity);
		
		echo $retval;
	}
}