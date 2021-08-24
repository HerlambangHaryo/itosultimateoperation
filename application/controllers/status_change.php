<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Status_change extends CI_Controller {
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
		
		$this->load->view('templates/status_change/abvPanel', $data);
	}
	
	public function data_container_inquiry(){
		$inbOutb=$_GET['inbOutb'];			
		$nocontainer=$_GET['cont_inquiry'];
	
		$retval = $this->container->getDataContainerStatusChange($nocontainer, $inbOutb);

		if($retval[0]['ID_CLASS_CODE'] != $inbOutb){
			if($inbOutb !='T' && $retval[0]['ID_CLASS_CODE'] != 'TE' && $retval[0]['ID_CLASS_CODE'] != 'TI'){
				$retval=array();
			}
		} 
		
		echo json_encode($retval);
	}
	
	public function postDataCombo()
	{
		$parampost=$_POST['PARAMPOST'];
		$retval = $this->container->getDataDetailStatusChange($parampost);
		
		$data = array(
			'success'=>0,
			'errors'=>'container not found error'
		);
		
		if ($retval){
			$data['success']=1;
			$data['errors']='';
			$data['data']=json_encode($retval);
		}
		
		echo json_encode($data);
	}
	
	public function history_status_detail(){
		$data = $this->container->get_container_history_statusChange($_GET['no_container'], $_GET['point']);
		echo json_encode($data);
	}
	
	public function saveChange(){
		$nocontainer=$_POST['NO_CONTAINER'];
		$pointcontainer=$_POST['POINTS'];
		$idvesvoyage=$_POST['ID_VES_VOYAGE'];
		$EI=$_POST['EI'];
		$lastStatus=$_POST['LOCATION_CHG'];
		$userid=$this->session->userdata('id_user');
		//$userid='1';
		//echo $nocontainer;die;
		$data = $this->container->saveChange($nocontainer, $pointcontainer, $EI, $idvesvoyage,$lastStatus, $userid);
		
		echo json_encode($data);
	}
}