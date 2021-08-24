<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Vessel_service extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');

		$this->load->model('vessel');
		$this->load->library('session');
	}

	public function index(){
		$data['tab_id'] = $_GET['tab_id'];

		$this->load->view('templates/vessel_service/main_panel', $data);
	}

	public function loadContents($b){
		$data['tab_id'] = $b;
		$field = $_POST['field'];
		$a=$_POST['name'];

		if($a=="null")
		{
			$data['containerSrc']='';
		}
		else
		{
			$data['containerSrc']=$a;
		}

		$data['rowsDetail']=$this->vessel->getVesServc($a, $field);
		//echo $data['rowsDetail'];
		$this->load->view('templates/vessel_service/content_panel', $data);
	}

	public function masterOperator($a,$b){
		$data['tab_id'] = $b;
		$data['id_ves_svc']=$a;
		$this->load->view('templates/vessel_service/masterOpr', $data);
	}

	public function masterPort($a,$b){
		$data['tab_id'] = $b;
		$data['id_ves_svc']=$a;
		$this->load->view('templates/vessel_service/masterPrt', $data);
	}

	public function AddServiceLane($b){
		$data['tab_id'] = $b;
		$this->load->view('templates/vessel_service/vesServiceAdd', $data);
	}

	public function RenameServiceLane($tab){
		$data['tab_id'] = $tab;
		$data['id_service'] = $_POST['id'];
		$data['service_name'] = $_POST['name'];
		$this->load->view('templates/vessel_service/vesServiceRename', $data);
	}

	public function addOperatorCt(){
		$idserv=$_POST['ID_SERVICE'];
		$idopr=$_POST['ID_OPERATOR'];
		$data=$this->vessel->saveOprSrv($idserv, $idopr);
		echo json_encode($data);
	}

	public function addServiceLaneSave(){
		$idopr=$_POST['SERVNAME'];
		$data=$this->vessel->saveSrvLane($idopr);
		echo json_encode($data);
	}

	public function renameServiceLaneSave(){
		$nameopr=$_POST['SERVNAME'];
		$idopr=$_POST['ID'];
		$data=$this->vessel->saveSrvLaneRename($idopr,$nameopr);
		echo json_encode($data);
	}

	public function addOperatorPrt(){
		$idserv=$_POST['ID_SERVICE'];
		$idopr=$_POST['ID_PORT'];
		$colorport=$_POST['COLOR'];
		$data=$this->vessel->savePrtSrv($idserv, $idopr,$colorport);
		echo json_encode($data);
	}

	public function delOperatorCt(){
		$idserv=$_POST['ID_SERVICE'];
		$idopr=$_POST['ID_OPERATOR'];
		$data=$this->vessel->delOprSrv($idserv, $idopr);
		echo json_encode($data);
	}

	public function delOperatorPrt(){
		$idserv=$_POST['ID_SERVICE'];
		$idopr=$_POST['ID_PORT'];
		$data=$this->vessel->delOprPrt($idserv, $idopr);
		echo json_encode($data);
	}

	public function masterOperatorCt($a,$b){
		$data['tab_id'] = $b;
		$data['id_ves_svc']=$a;
		$data['rowsDetail']=$this->vessel->getServOp($a);
		$this->load->view('templates/vessel_service/content_masterOpr', $data);
	}

	public function masterPortCt($a,$b){
		$data['tab_id'] = $b;
		$data['id_ves_svc']=$a;
		$data['rowsDetail']=$this->vessel->getServPr($a);
		$this->load->view('templates/vessel_service/content_masterPrt', $data);
	}
}
