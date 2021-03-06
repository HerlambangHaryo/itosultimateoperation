<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_dl_productivity extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('machine');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_dl_productivity', $data);
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
	
	public function get_data_dl_productivity(){
		$id_ves_voyage = $_GET['vessel_voyage_id'];

		$data['id_ves_voyage'] = $id_ves_voyage;

		$data['vesvoy'] = $this->vessel->getDetailVesVoy($id_ves_voyage);
		$data['crane'] = $this->machine->getDataCrane($id_ves_voyage);

		$crane = $this->machine->getCraneByVesvoy($id_ves_voyage);

//		$i=0;
//		$temp = array();
//		foreach ($crane as $key) {
//			//echo $key['MCH_NAME']."<br />";
//			$temp[$i]['detail'] = $this->machine->getSummaryCrane($id_ves_voyage,$key['MCH_NAME']);
//
//			$i++;
//		}

//		$data['summary'] = $temp;
		
		//debux($data);

		$this->load->view('templates/report/ete_report_dl_productivity', $data);	
	}
	
	public function get_data_dl_productivity_show(){
		$id_ves_voyage = $_POST['vessel_voyage_id'];

		$data['id_ves_voyage'] = $id_ves_voyage;

		$data['vesvoy'] = $this->vessel->getDetailVesVoy($id_ves_voyage);
		$data['crane'] = $this->machine->getDataCrane($id_ves_voyage);

		$crane = $this->machine->getCraneByVesvoy($id_ves_voyage);
		$this->load->view('templates/report/ete_report_dl_productivity_show', $data);	
	}
}