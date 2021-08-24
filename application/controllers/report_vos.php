<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_vos extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('vessel','machine','gtools'));
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_bch', $data);
	}
	
	public function data_vesselvoyage_list(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_vessel_schedule_report($filter);
		echo json_encode($data);
	}

	public function check_vessel_voyage(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$retval = $this->vessel->check_vessel_voyage($id_ves_voyage);
		echo $retval;
	}
	
	public function get_data_bch(){
		$id_ves_voyage = $_GET['id_ves_voyage'];
		
		$retval['data_old'] = $this->vessel->get_data_bch($id_ves_voyage);

		$retval['data_header'] = $this->vessel->get_data_bch_head($id_ves_voyage);

		date_default_timezone_set("Asia/Jakarta");
		
		$data_disch = $this->machine->get_machine_dsc($id_ves_voyage);
		$data_load  = $this->machine->get_machine_load($id_ves_voyage);
		$etd 		= $this->machine->get_etd_vvd($id_ves_voyage);
		$retval['equip_deploy'] = $this->machine->get_equipment_deployment($id_ves_voyage);
		$retval['data_mch'] = $this->machine->get_detail_equipment_activity($id_ves_voyage);
		$retval['data_summary'] = $this->machine->bch_summary($id_ves_voyage);
		//================= load data summary ==================//
		foreach ($data_disch as $row_dsc)
		{
			$completed_dsc = $row_dsc['SUM_COMPLETED'];
			$planned_dsc   = $row_dsc['SUM_PLANNED'];
			$remained_dsc  = $row_dsc['SUM_REMAINED'];
			$qc_dsc 	   = $row_dsc['QC_UNASSIGNED'];
			$ttl_dsc 	   = $row_dsc['TOTAL'];
		}
		$retval['data_detail']['detd'] 	 = $etd['DETD'];
		$retval['data_detail']['hetd'] 	 = $etd['HETD'];
		$retval['data_detail']['minetd'] = $etd['MINETD'];
		
		$retval['data_detail']['dsc_completed'] = $completed_dsc;
		$retval['data_detail']['dsc_planned'] 	= $planned_dsc;
		$retval['data_detail']['dsc_remained'] 	= $remained_dsc;
		$retval['data_detail']['dsc_qc'] 		= $qc_dsc;
		$retval['data_detail']['dsc_total'] 	= $ttl_dsc;

		foreach ($data_load as $row_load)
		{
			$completed_load = $row_load['SUM_COMPLETED'];
			$planned_load 	= $row_load['SUM_PLANNED'];
			$remained_load 	= $row_load['SUM_REMAINED'];
			$qc_load    = $row_load['QC_UNASSIGNED'];
			$ttl_load  = $row_load['TOTAL'];
		}
				
		$retval['data_detail']['load_completed'] = $completed_load;
		$retval['data_detail']['load_planned'] 	 = $planned_load;
		$retval['data_detail']['load_remained']  = $remained_load;
		$retval['data_detail']['load_qc'] = $qc_load;
		$retval['data_detail']['load_total'] = $ttl_load;
		//================= load data summary ==================//

//		$retval['data_mch']   = $this->machine->data_active_mch($id_ves_voyage);
//                debux($retval['data_mch']);die;

		//debux($retval);die;
		$this->load->view('templates/report/ete_report_bch', $retval);	
	}
}