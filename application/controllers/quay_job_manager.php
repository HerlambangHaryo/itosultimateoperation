<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Quay_job_manager extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('machine');
		$this->load->model('user');
		$this->load->model('gtools');
		$this->load->model('vessel');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/quay_job_manager/job_list', $data);
	}
	
	public function data_job_list(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$arr_filter = array();
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$job_filter = isset($_REQUEST['job_filter']) ? json_decode($_REQUEST['job_filter']) : false;
		$filterbyminute = isset($_REQUEST['filterbyminute']) ? json_decode($_REQUEST['filterbyminute']) : false;
		$filterbykapal = isset($_REQUEST['filterbykapal']) ? json_decode($_REQUEST['filterbykapal']) : false;
		$filterbyQC = isset($_REQUEST['filterbyQC']) ? json_decode($_REQUEST['filterbyQC']) : false;
		if($filterbykapal['0']->{'value'}=='-- All --'){
			$filterbykapal="";
			$_REQUEST['filterbykapal']="";
		}
		if($filterbyQC['0']->{'value'}=='-- All --'){
			$filterbyQC="";
			$_REQUEST['filterbyQC']="";
		}
		$sfil = array(
			'prop' => $_REQUEST['sort'],
			'job' => $_REQUEST['job_filter'],
			'min' => $_REQUEST['filterbyminute'],
			'kapal' => $_REQUEST['filterbykapal'],
			'qjqc' => $_REQUEST['filterbyQC']
		);

		if($filters != false)
		    $arr_filter = $filters;
		if($job_filter != false)
		    array_push($arr_filter, $job_filter[0]);
		if($filterbyminute != false)
		    array_push($arr_filter, $filterbyminute[0]);
		if($filterbykapal != false)
		    array_push($arr_filter, $filterbykapal[0]);
		if($filterbyQC != false)
		    array_push($arr_filter, $filterbyQC[0]);
		// echo '<pre>';print_r($filterbyminute['value']);die();
		$this->session->set_userdata($sfil);
		$retval = $this->container->get_data_quay_job_list($paging, $sort, $arr_filter);
//		echo '<pre>';print_r($retval);echo '</pre>';exit;
		echo json_encode($retval);
	}

	public function excel_quay_job_manager(){
		$arr_filter = array();
		$sort = json_decode($this->session->userdata('prop'));
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$job_filter = json_decode($this->session->userdata('job'));
		$filterbyminute = json_decode($this->session->userdata('min'));
		$filterbykapal = json_decode($this->session->userdata('kapal'));
		$filterbyQC = json_decode($this->session->userdata('qjqc'));
		if($filterbykapal['0']->{'value'}=='-- All --'){
			$filterbykapal="";
		}
		if($filterbyQC['0']->{'value'}=='-- All --'){
			$filterbyQC="";
		}

		if($filters != false)
		    $arr_filter = $filters;
		if($job_filter != false)
		    array_push($arr_filter, $job_filter[0]);
		if($filterbyminute != false)
		    array_push($arr_filter, $filterbyminute[0]);
		if($filterbykapal != false)
		    array_push($arr_filter, $filterbykapal[0]);
		if($filterbyQC != false)
		    array_push($arr_filter, $filterbyQC[0]);
		// echo '<pre>';print_r($arr_filter);die();

		$retval['data_detail'] = $this->container->get_data_quay_job_list_report($sort, $arr_filter);
		// echo '<pre>';var_dump($retval['data_detail']);die();

		$this->load->view('templates/quay_job_manager/job_list_excel', $retval);
	}
	
	public function popup_machine(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['no_container'] = $_POST['no_container'];
		$data['point'] = $_POST['point'];
		$data['id_class_code'] = $_POST['id_class_code'];
		$data['id_ves_voyage'] = $_POST['id_ves_voyage'];
		$data['stowage'] = $_POST['stowage'];
		$data['job'] = $_POST['job'];
		$data['id_pool'] = $_POST['id_pool'];
		$data['iditv'] = $_POST['iditv'];
		$data['itv'] = $_POST['itv'];
		$data['idqc'] = $_POST['idqc'];
		$data['qc'] = $_POST['qc'];
		$data['tl_flag'] = $_POST['tl_flag'];
		
		$this->load->view('templates/quay_job_manager/popup_machine', $data);
	}
	
	public function data_quay_machine(){
		$id_ves_voyage = $_GET['id_ves_voyage'];
		$data = $this->machine->get_data_machine_from_voyage($id_ves_voyage);
		//debux($data);die;
		echo json_encode($data);
	}
	
	public function data_all_machine(){
		$data = $this->container->get_data_quay_machine_list();
		// debux($data);die;
		echo json_encode($data);
	}
	
	public function data_all_vessel(){
		$data	= $this->vessel->get_vessel_schedule();
		// debux($data);die;
		echo json_encode($data);
	}
	
	public function data_qc_operator(){
		$data = $this->user->get_data_operator('ROLE_QC');
		echo json_encode($data);
	}
	
	public function data_itv_machine(){
		$id_pool = $_GET['id_pool'];
		$id_class_code = $_GET['id_class_code'];
		if(empty($id_pool)){
			$data = $this->machine->get_data_machine_quay('ITV',$id_class_code);
		}else{
			$data = $this->machine->get_data_machine_itv_pool_quay('ITV',$id_pool,$id_class_code);
		}
		echo json_encode($data);
	}
	
	public function tally_confirm(){
//	    echo 'fuck';exit;
		$no_container = $_POST['no_container'];
		$point = $_POST['point'];
		$id_class_code = $_POST['id_class_code'];
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$job 			= $_POST['job'];
		$stowage = array('00','00','00');
		if ($_POST['stowage']!=''){
			$stowage = array((int) substr($_POST['stowage'],-6,2),(int) substr($_POST['stowage'],-4,2),(int) substr($_POST['stowage'],-2,2));
			$str_stowage = array(substr($_POST['stowage'],0,2),substr($_POST['stowage'],2,2),substr($_POST['stowage'],4,2));
//			$location = array(substr($_POST['conloc2'],0,2),substr($_POST['conloc2'],2,2),substr($_POST['conloc2'],4,2));
		}
//		print_r($stowage);exit;
		$id_machine = $_POST['id_machine'];
		$driver_id = $_POST['driver_id'];
		$itv = $_POST['itv'];

		$response 				= $this->machine->validation_data_itv_class($id_class_code,$itv,$no_container);
		if($response=='full' and $job != 'L'){
			//ada isi
			$retval = array('NOT OK', 'Container is Full');
			echo json_encode($retval);
		}else{
			$valid_stowage = 1;
			if ($id_class_code=='E' || $id_class_code=='TE'){
				$valid_stowage = $this->check_stowage_position($id_ves_voyage, $stowage);
				if ($valid_stowage==2){
					$retval = array('NOT OK', 'Stowage Position Not Valid');
				}else if ($valid_stowage==3){
					$retval = array('NOT OK', 'Stowage Position Already Taken');
				}
			}
			
			if ($valid_stowage==1){
				$retval = $this->container->tally_confirm_submit($no_container, $point, $id_class_code, $str_stowage, $this->session->userdata('id_user'), $driver_id, $itv, $id_machine,'','','',$id_ves_voyage);
			}
			//debux($retval);die;
			echo json_encode($retval);
		}

		
	}
	
	public function check_stowage_position($id_ves_voyage, $stowage_position){
		return $this->vessel->validate_stowage_position($id_ves_voyage, $stowage_position[0], $stowage_position[1], $stowage_position[2]);
	}
}