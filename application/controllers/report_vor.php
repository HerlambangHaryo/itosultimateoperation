<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_vor extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->model('yard');
		$this->load->model('vessel');
		$this->load->model('machine');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_vor', $data);
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

	public function get_data_vor(){
	    $this->load->library('pdf');
		$id_ves_voyage = $_GET['id_ves_voyage'];

		$retval['id_ves_voyage'] = $id_ves_voyage;
		$retval['vesvoy'] = $this->vessel->getDetailVesVoy($id_ves_voyage);
		$retval['crane'] = $this->machine->getDataCrane($id_ves_voyage);
		$retval['suspend_detail'] = $this->machine->getSuspendDetail($id_ves_voyage);

		$crane = $this->machine->getDataCraneByVesvoy($id_ves_voyage);

		$i=0;
		$temp = array();
		foreach ($crane as $key) {
			//echo $key['MCH_NAME']."<br />";
			$temp[$i]['suspend'] = $this->machine->getSuspendSummary($id_ves_voyage,$key['MCH_NAME']);

			$i++;
		}

		$retval['suspend_summary'] = $temp;
		//debux($temp);
		
		// generate_pdf("vor_reports.pdf", 'landscape' ,"templates/report/ete_report_vor_pdf", $retval);
		$this->load->view('templates/report/ete_report_vor_pdf', $retval);	
	}

	public function get_data_vor_show(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$retval['tab_id'] = $_POST['tab_id'];

		$retval['id_ves_voyage'] = $id_ves_voyage;
		$retval['vesvoy'] = $this->vessel->getDetailVesVoy($id_ves_voyage);
		$retval['crane'] = $this->machine->getDataCrane($id_ves_voyage);
		$retval['suspend_detail'] = $this->machine->getSuspendDetail($id_ves_voyage);

		$crane = $this->machine->getDataCraneByVesvoy($id_ves_voyage);

		$i=0;
		$temp = array();
		foreach ($crane as $key) {
			//echo $key['MCH_NAME']."<br />";
			$temp[$i]['suspend'] = $this->machine->getSuspendSummary($id_ves_voyage,$key['MCH_NAME']);

			$i++;
		}

		$retval['suspend_summary'] = $temp;
		//debux($temp);
		
		$this->load->view('templates/report/ete_report_vor_show', $retval);	
	}

	//$retval['suspend_summary'] = $this->machine->getSuspendSummary($id_ves_voyage);

		//$retval['outage'] = $this->machine->getTotalOutage($id_ves_voyage);
		

		//debux($retval);
}