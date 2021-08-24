<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Qc_assignment_plan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('machine');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$data_disch = $this->machine->get_machine_dsc($_POST['data_id']);
			$data_load = $this->machine->get_machine_load($_POST['data_id']);

			//================= load data summary ==================//
			foreach ($data_disch as $row_dsc)
			{
				$completed_dsc = $row_dsc['SUM_COMPLETED'];
				$planned_dsc = $row_dsc['SUM_PLANNED'];
				$remained_dsc = $row_dsc['SUM_REMAINED'];
				$qc_dsc = $row_dsc['QC_UNASSIGNED'];
				$ttl_dsc = $row_dsc['TOTAL'];
			}
					
			$data['dsc_completed'] = $completed_dsc;
			$data['dsc_planned'] = $planned_dsc;
			$data['dsc_remained'] = $remained_dsc;
			$data['dsc_qc'] = $qc_dsc;
			$data['dsc_total'] = $ttl_dsc;

			foreach ($data_load as $row_load)
			{
				$completed_load = $row_load['SUM_COMPLETED'];
				$planned_load = $row_load['SUM_PLANNED'];
				$remained_load = $row_load['SUM_REMAINED'];
				$qc_load = $row_load['QC_UNASSIGNED'];
				$ttl_load = $row_load['TOTAL'];
			}
					
			$data['load_completed'] = $completed_load;
			$data['load_planned'] = $planned_load;
			$data['load_remained'] = $remained_load;
			$data['load_qc'] = $qc_load;
			$data['load_total'] = $ttl_load;
			//================= load data summary ==================//

			$this->load->view('templates/qc_assignment_plan/qc_assignment_panel', $data);
		}
	}
	
	public function get_machine_mst($id_vesvoy)
	{
		$filter = $_GET['query'];
		$data	= $this->machine->data_machine_mst($filter,$id_vesvoy);
		echo json_encode($data);
	}	

	public function get_active_mch($id_vesvoy){
		$data	= $this->machine->data_active_mch($id_vesvoy);
		echo json_encode($data);
	}

	public function save_machine_vesvoy($id_ves_voyage){
		$data['start_work'] = $_POST['START_DATE'].' '.str_pad($_POST['START_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['START_MIN'],2,'0',STR_PAD_LEFT);
		$data['end_work'] = $_POST['END_DATE'].' '.str_pad($_POST['END_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['END_MIN'],2,'0',STR_PAD_LEFT);
		$data['mch_id'] = $_POST['MACHINE_NAME'];
		$data['bch'] = $_POST['BCH'];
		
		$id_user = $this->session->userdata('id_user');
		$msg = $this->machine->save_machine_and_seq_vesvoy($data,$id_ves_voyage,$id_user);
				
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
	
	public function delete_qc_assignment(){
		$id_mch_working_plan = $_POST['ID_MCH_WORKING_PLAN'];
		$retval = $this->machine->delete_qc_assignment($id_mch_working_plan);
		echo $retval;
	}
}