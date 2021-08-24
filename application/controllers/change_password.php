<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Change_password extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('user');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$id_user = $this->session->userdata('id_user');		
		$this->load->view('templates/change_password/filter_entry', $data);
	}
	
	public function update_password()
	{		
		$data['oldpassword'] = $_POST["OLD_PASSWORD"];
		$data['newpassword'] = $_POST["NEW_PASSWORD"];
		$data['cnewpassword'] = $_POST["CNEW_PASSWORD"];

		$id_user = $this->session->userdata('id_user');
		$msg = $this->user->update_password($id_user,$data['oldpassword'],$data['newpassword'],$data['cnewpassword']);
		// echo $msg."<br/><br/>";

		$data = array(
			'success'=>false,
			'errors'=>$msg
		);
		
		if ($msg=="OK")
		{
			$data['success']=true;
		}
		else
		{
			$data['success']=false;
		}
		
		echo json_encode($data);
	}
}