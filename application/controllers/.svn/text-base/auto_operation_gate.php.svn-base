<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Auto_operation_gate extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		//$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/auto_operation_gate/abvPanel', $data);
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
		$recDelGate=$_POST['typeRecDel'];
		$typeGate='IN';
		
			
		$nocontainer=$_POST['cont_inquiry'];
	
		$retval = $this->container->get_data_container_inquiryGateAuto($nocontainer, $typeGate, $recDelGate);
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
		$idvesvoyage=$_POST['ID_VES_VOYAGE'];
		$trucknumber=$_POST['TRUCK_NUMBER'];
		$sealid=$_POST['SEAL_ID'];
		$weight=$_POST['WEIGHT'];
		$EI=$_POST['EI'];
		$dmg=$_POST['damageCont'];
		$dmgLoc=$_POST['damageContLoc'];
		$userid=$this->session->userdata('id_user');
		//$userid='1';
		$data = $this->container->saveContainerAutoGate($nocontainer, $pointcontainer, $truckJob, $EI, $idvesvoyage,$trucknumber, $sealid, $weight,$userid,$dmg, $dmgLoc);
		
		echo json_encode($data);
	}
	
}