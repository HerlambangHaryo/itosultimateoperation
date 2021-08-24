<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Gate_operation_admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		//$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('user');
		$this->load->model('gtools');
		$this->load->model('notification');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/gate_operation_admin/abvPanel', $data);
	}
	
	public function data_dmg()
	{
		$data	= $this->container->getDamageCode();
		echo json_encode($data);
	}
	
	public function data_dmgLoc()
	{
		$data	= $this->container->getDamageLocation();
		echo json_encode($data);
	}
	
	public function data_container_inquiry(){
		$typeGate=$_POST['typeInOut'];
		$recDelGate=$_POST['typeRecDel'];
		$nocontainer=$_POST['cont_inquiry'];
	
		$retval = $this->container->get_data_container_inquiryGate($nocontainer, $typeGate, $recDelGate);
		$data = array(
			'success'=>false,
			'errors'=>'container not found error'
		);
		
		if ($retval){
			$data['success']=true;
			$data['errors']='';
			$data['data']=json_encode($retval);
		}
		echo json_encode($data);
	}
	
	public function saveGate(){
		$truckJob=$_POST['TR_JOB'];
		$nocontainer=$_POST['NO_CONTAINER'];
		$pointcontainer=$_POST['POINT'];
		$date_gate=$_POST['DATE_GATE'].' '.str_pad($_POST['HOUR_GATE'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['MIN_GATE'],2,'0',STR_PAD_LEFT);
		$idvesvoyage=$_POST['ID_VES_VOYAGE'];
		$trucknumber=$_POST['TRUCK_NUMBER'];
		$sealid=$_POST['SEAL_ID'];
		$weight=$_POST['WEIGHT'];
		$EI=$_POST['EI'];
		$dmg=$_POST['damageCont'];
		$dmgLoc=$_POST['damageContLoc'];
		$remarks=$_POST['REMARKS'];
		$userid=$this->session->userdata('id_user');
		//$userid='1';
		$data = $this->container->saveContainerGateAdmin($nocontainer, $pointcontainer, $date_gate, $truckJob, $EI, $idvesvoyage,$trucknumber, $sealid, $weight, $userid, $dmg, $dmgLoc, $remarks);
		
		echo json_encode($data);
	}
	
	public function send_notification(){
		$module_name = $_POST['module_name'];
		$recipients = $this->notification->get_data_recipient($module_name);
		$userid = $this->session->userdata('id_user');
		$user = $this->user->get_user_detail($userid);
		$full_name = $user['FULL_NAME'];
		$no_container = $_POST['no_container'];
		$vessel_voyage = $_POST['vessel_voyage'];
		$valid_date = $_POST['valid_date'];
		$date_gate = $_POST['date_gate'];
		$remarks = $_POST['remarks'];
		
		$replace_template = array(
			'$full_name' => $full_name,
			'$no_container' => $no_container,
			'$vessel_voyage' => $vessel_voyage,
			'$valid_date' => $valid_date,
			'$date_gate' => $date_gate,
			'$remarks' => $remarks
		);
		
		foreach ($recipients as $recipient){
			$email = strtr($recipient['EMAIL_TEMPLATE'],$replace_template);
			$sms = strtr($recipient['SMS_TEMPLATE'],$replace_template);
			if ($recipient['EMAIL_RECIPIENT']!=''){
				$this->notification->send_email_notification($recipient['EMAIL_RECIPIENT'], $email, $email, $recipient['EMAIL_SUBJECT']);
			}
			if ($recipient['SMS_RECIPIENT']!=''){
				$this->notification->send_sms_notification($recipient['SMS_RECIPIENT'], $sms);
			}
		}
	}
}