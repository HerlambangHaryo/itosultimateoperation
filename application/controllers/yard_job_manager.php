<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_job_manager extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('machine');
		$this->load->model('yard');
		$this->load->model('user');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index()
	{
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_job_manager/job_list', $data);
	}
	
	public function data_job_list()
	{
	    $paging 	= array(
		    'page'		=> $_REQUEST['page'],
		    'start'		=> $_REQUEST['start'],
		    'limit'		=> $_REQUEST['limit']
	    );

	    $arr_filter 		= array();
	    $sort 				= isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
	    $filters 			= isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
	    $job_filter 		= isset($_REQUEST['job_filter']) ? json_decode($_REQUEST['job_filter']) : false;
	    $filterbyminute 	= isset($_REQUEST['filterbyminute']) ? json_decode($_REQUEST['filterbyminute']) : false;
	    $filterbyQC 	= isset($_REQUEST['filterbyQC']) ? json_decode($_REQUEST['filterbyQC']) : false;
	    $filterbyYC 	= isset($_REQUEST['filterbyYC']) ? json_decode($_REQUEST['filterbyYC']) : false;
	    $ssort 				= array('prop' => $_REQUEST['sort']);

	    if($filters != false)
	    {
			$arr_filter = $filters;
	    }

	    if($job_filter != false)
	    {
			array_push($arr_filter, $job_filter[0]);
	    }

	    if($filterbyminute != false)
	    {
			array_push($arr_filter, $filterbyminute[0]);
	    }

		if($filterbyYC['0']->{'value'}=='-- All --'){
			$filterbyYC="";
		}
		if($filterbyQC['0']->{'value'}=='-- All --'){
			$filterbyQC="";
		}

	    if($filterbyQC != false)
	    {
			array_push($arr_filter, $filterbyQC[0]);
	    }

	    if($filterbyYC != false)
	    {
			array_push($arr_filter, $filterbyYC[0]);
	    }

		// echo '<pre>';print_r($filters);echo '</pre>';
		// echo '<pre>';print_r($arr_filter);echo '</pre>';
		// echo '<pre>';print_r($arr_filter);echo '</pre>';exit;
	    $this->session->set_userdata($ssort);
		
	    $retval 		= $this->container->get_data_yard_job_list($paging, $sort, $arr_filter);
	    // var_dump($retval);
	    // die();
	    echo json_encode($retval);
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
	
	public function data_all_machine(){
		$data = $this->container->get_data_yard_machine_qc_list();
		// debux($data);die;
		echo json_encode($data);
	}
	
	public function data_all_machine_yc(){
		$data = $this->container->get_data_yard_machine_yc_list();
		// debux($data);die;
		echo json_encode($data);
	}

	public function validate_yard_block(){
		$data['id_block'] = $_POST['id_block'];
		$data['slot'] = $_POST['slot'];
		$data['row'] = $_POST['row'];
		$data['tier'] = $_POST['tier'];
		$data['id_ves_voyage'] = $_POST['tier'];

		$result 	= $this->yard->get_yard_by_block($data['id_block']);
		$response   = ($result=='' || $result==0 || $result==null) ? "NOT" : null;

		echo json_encode($response);
	}

	public function excel_Yard_job_manager(){

	    $arr_filter 		= array();
	    $sort 				= false;
	    $job_filter 		= isset($_POST['JOB']) ? $_POST['JOB'] : false;
	    $filterbyminute 	= isset($_POST['CMPLT_DT']) ? $_POST['CMPLT_DT'] : false;
	    $filterbyQC 	= isset($_POST['QC']) ? $_POST['QC'] : false;
	    $filterbyYC 	= isset($_POST['EQ']) ? $_POST['EQ'] : false;
	    // $sort 				= array('prop' => $sort);

	    if($job_filter != false)
	    {
			$arr_filterj 		= array();
			$arr_filterj['type'] = 'string';
			$arr_filterj['value'] = "$job_filter";
			$arr_filterj['field'] = 'JOB';
			array_push($arr_filter, $arr_filterj);
	    }

	    if($filterbyminute != false)
	    {
			$arr_filterm 		= array();
			$arr_filterm['type'] = 'min';
			$arr_filterm['value'] = "$filterbyminute";
			$arr_filterm['field'] = 'CMPLT_DT';
			array_push($arr_filter, $arr_filterm);
	    }

		if($filterbyYC=='-- All --'){
			$filterbyYC="";
		}
		if($filterbyQC=='-- All --'){
			$filterbyQC="";
		}

	    if($filterbyQC != false)
	    {
			$arr_filterq 		= array();
			$arr_filterq['type'] = 'QCPLAN';
			$arr_filterq['value'] = "$filterbyQC";
			$arr_filterq['field'] = 'QC';
			array_push($arr_filter, $arr_filterq);
	    }

	    if($filterbyYC != false)
	    {
			$arr_filtery 		= array();
			$arr_filtery['type'] = 'string';
			$arr_filtery['value'] = "$filterbyYC";
			$arr_filtery['field'] = 'EQ';
			array_push($arr_filter, $arr_filtery);
	    }
		// debux(json_encode($arr_filter));die();
		$retval['data_detail'] = $this->container->get_data_yard_job_list_report($sort, $arr_filter);
		
		$this->load->view('templates/yard_job_manager/job_list_excel', $retval);
	}
	
	public function popup_change_PA(){
	    $data['tab_id'] = $_GET['tab_id'];
	    $data['id_yard'] = $_POST['id_yard'];
	    $data['id_block'] = $_POST['id_block'];
	    $data['slot'] = $_POST['slot'];
	    $data['row'] = $_POST['row'];
	    $data['tier'] = $_POST['tier'];
	    $data['no_container'] = $_POST['no_container'];
	    $data['point'] = $_POST['point'];

	    $this->load->view('templates/yard_job_manager/popup_change_PA', $data);
	}
	
	public function popup_change_equipment(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['list_container'] = $_POST['list_container'];
		
		$this->load->view('templates/yard_job_manager/popup_change_equipment', $data);
	}
	
	public function data_yard_list(){
		$data	= $this->yard->get_yard_list();
		echo json_encode($data);
	}
	
	public function data_block_list()
	{
		if (isset($_GET['query'])){
			$id_yard = $_GET['query'];
			$data	= $this->yard->get_block_list($id_yard);
			echo json_encode($data);
		}		
	}
	
	public function data_slot_list(){
		if (isset($_GET['query']))
		{
			$id_block = $_GET['query'];
			$data	= $this->yard->get_slot_list_by_block($id_block);
			echo json_encode($data);
		}
	}
	
	public function data_row_list(){
		if (isset($_GET['query'])){
			$id_block = $_GET['query'];
			$data	= $this->yard->get_row_list_by_block($id_block);
			echo json_encode($data);
		}
	}
	
	public function data_tier_list(){
		if (isset($_GET['query'])){
			$id_block = $_GET['query'];
			$data	= $this->yard->get_tier_list_by_block($id_block);
			echo json_encode($data);
		}
	}
	
	public function save_change_PA(){
		$retval = $this->yard->save_change_PA($_POST);
		echo json_encode($retval);
	}
	
	public function save_change_equipment(){
		$retval = $this->yard->save_change_equipment($_POST);
		echo $retval;
	}
	
	public function popup_machine()
	{
		$data['tab_id'] 		= $_GET['tab_id'];
		$data['no_container'] 	= $_POST['no_container'];
		$data['point'] 			= $_POST['point'];
		$data['id_op_status'] 	= $_POST['id_op_status'];
		$data['event'] 			= $_POST['event'];
		$data['block_name'] 	= $_POST['block_name'];
		$data['id_block'] 		= $_POST['id_block'];
		$data['slot'] 			= $_POST['slot'];
		$data['row'] 			= $_POST['row'];
		$data['tier'] 			= $_POST['tier'];
		$data['yard_placement'] = $_POST['yard_placement'];
		$data['id_machine'] 	= $_POST['id_machine'];
		$data['machine'] 		= $_POST['machine'];
		$data['id_class_code'] 		= $_POST['id_class_code'];
		$data['iditv'] 		= $_POST['iditv'];
		$data['itv'] = $_POST['itv'];
		$data['job'] 		= $_POST['job'];
		$data['id_pool'] = $_POST['id_pool'];
		
		$this->load->view('templates/yard_job_manager/popup_machine', $data);
	}
	
	public function data_yard_machine()
	{
		$data = $this->machine->get_data_machine('YARD');
		echo json_encode($data);
	}
	
	public function data_yc_operator()
	{
		$data = $this->user->get_data_operator('ROLE_VMT');
		echo json_encode($data);
	}
	
	public function yard_placement_submit(){
		$id_user = $this->session->userdata('id_user');
		$no_container = $_POST['no_container'];
		$point = $_POST['point'];
		$id_op_status = $_POST['id_op_status'];
		$event = $_POST['event'];
		$id_machine = $_POST['id_machine'];
		$id_class_code = $_POST['id_class_code'];
		$iditv = $_POST['iditv'];
		$driver_id = $_POST['driver_id'];
		
		if ($event=='P'){
			$yard_position = array(
				'BLOCK_NAME'=>$_POST['block_name'],
				'BLOCK'=>$_POST['id_block'],
				'SLOT'=>$_POST['slot'],
				'ROW'=>$_POST['row'],
				'TIER'=>$_POST['tier']
			);
		}else{
			$temp = explode('^', $_POST['yard_placement']);
			$yard_position = array(
				'BLOCK_NAME'=>$temp[0],
				'BLOCK'=>$_POST['id_block'],
				'SLOT'=>$temp[1],
				'ROW'=>$temp[2],
				'TIER'=>$temp[3]
			);
		}

		// $result = $_POST;
		// print_r($result);
		//		echo '<pre>';print_r($_POST);echo '</pre>';
		//		echo '<pre>';print_r($yard_position);echo '</pre>';

		$response 				= $this->machine->validation_data_itv_class($id_class_code,$iditv,$no_container);

		if($response == 'full')
		{
			//ada isi
			$retval 			= array('NOT OK', 'Container is Full');
		}
		else
		{
			if(!empty($iditv)){
				$retval = $this->container->yard_placement_submit($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id, $iditv);
			}else{
				$retval = $this->container->yard_placement_submit($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id);
			}
		}
			// $retval = $this->container->yard_placement_submit($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id);
		// $arrat = array($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id);
		// var_dump($retval);var_dump($arrat);
		// die();
		echo json_encode($retval);
	}

	public function byPass(){
		$update = $this->container->byPass(json_decode($_POST['list_container']));
		echo json_encode(array('success' => $update));

	}
}