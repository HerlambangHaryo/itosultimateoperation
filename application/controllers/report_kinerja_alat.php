<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_kinerja_alat extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('monitoring');
		$this->load->model('gtools');
		$this->load->model('machine');
		$this->load->model('container');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_kinerja_alat', $data);
	}
	
	public function data_alat(){
		$data	= $this->machine->get_data_machine_yard_quay();
		$paging = array(
		    'page'=>$_REQUEST['page'],
		    'start'=>$_REQUEST['start'],
		    'limit'=>$_REQUEST['limit']
	    );
	    $sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
	    $filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
	    //$retval = $this->container->get_data_yard_job_list($paging, $sort, $filters);

	    // var_dump($data);die();
	    echo json_encode($data);
	    // echo json_encode($retval);
	}
	
	public function data_operator(){
		$data	= $this->machine->get_data_operator();
		$paging = array(
		    'page'=>$_REQUEST['page'],
		    'start'=>$_REQUEST['start'],
		    'limit'=>$_REQUEST['limit']
	    );
	    $sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
	    $filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
	    //$retval = $this->container->get_data_yard_job_list($paging, $sort, $filters);

	    // var_dump($data);die();
	    echo json_encode($data);
	    // echo json_encode($retval);
	}
	
	public function get_data_kinerja_alat(){
		$start_period = $_GET['START_PERIOD'];
		$end_period = $_GET['END_PERIOD'];
		$alat = $_GET['ALAT'];
		$id_user_operator = $_GET['id_user_operator'];
		$operator_name = $_GET['operator_name'];
		$action = $_GET['action'];
		
		$retval['data_detail'] = $this->monitoring->get_data_report_kinerja_alat($start_period, $end_period, $alat, $id_user_operator, $action);

		$machine = $this->monitoring->getMachineKinerjaAlat($start_period, $end_period, $alat, $id_user_operator, $action);

		$i=0;
		$temp=array();
		$fcl = 'FCL';
		$mty = 'MTY';
		foreach($machine as $row){
			$temp[$i]['MCH_NAME'] = $row['MCH_NAME'];
			$temp[$i]['FULL_NAME'] = $row['FULL_NAME'];
			$temp[$i]['fcl20'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 20, $fcl);
			$temp[$i]['fcl21'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 21, $fcl);
			$temp[$i]['fcl40'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 40, $fcl);
			$temp[$i]['fcl45'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 45, $fcl);
			$temp[$i]['mty20'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 20, $mty);
			$temp[$i]['mty21'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 21, $mty);
			$temp[$i]['mty40'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 40, $mty);
			$temp[$i]['mty45'] = $this->monitoring->getSummaryKinerjaAlat($start_period, $end_period, $row['MCH_NAME'], $row['FULL_NAME'], 45, $mty);

			$i++;
		}

		//debux($temp);

		$retval['summary'] = $temp;
		
		$retval['start_period'] = $_GET['START_PERIOD']."WIB";
		$retval['end_period'] = $_GET['END_PERIOD']."WIB";
		$retval['alat'] = $alat;
		$retval['id_user_operator'] = $id_user_operator;
		$retval['operator_name'] = $operator_name;
		$retval['action'] = $action;
		$this->load->view('templates/report/ete_report_kinerja_alat', $retval);
	}

	public function get_data_kinerja_alat_tampil(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		if($_REQUEST['show_data'] != NULL) {
			$show_data = isset($_REQUEST['show_data']) ? json_decode($_REQUEST['show_data'])   : false;
			for ($i=0;$i<count($show_data);$i++){
				$sd = $show_data[$i];
			}
			$start_period = $sd->start_period;
			$end_period = $sd->end_period;
			$alat = $sd->alat;
			$id_user_operator = $sd->id_user_operator;
			$action = $sd->action;
		}else{
			$start_period = '01-01-2006 00.00.00';
			$end_period = date("d-m-Y")." 23.59.00";
			$alat = '';
			$id_user_operator = '';
			$action = '';
		}
		$arr_filter = array();
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		if($filters != false){
			$arr_filter = $filters;
		}
		$retval = $this->monitoring->get_data_report_kinerja_alat_tampil($paging, $start_period, $end_period, $alat, $arr_filter, $id_user_operator, $action);	
		
		echo json_encode($retval);
	}

	// public function get_data_kinerja_alat_show(){
		
	// }
}