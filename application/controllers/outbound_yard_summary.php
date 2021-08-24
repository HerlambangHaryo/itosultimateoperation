<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Outbound_yard_summary extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('vessel');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] 		= $_GET['tab_id'];
		$data['id_ves_voyage']  = $_POST['data_id'];

		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/outbound_yard_summary/report_outbound_yard_summary_new', $data);
		}
	}

	public function get_data_yard(){
		$retval = $this->yard->get_data_yard();
		echo json_encode($retval);
	}

	public function get_data_yard_outbound($id_yard='',$tab_id='',$excel='',$id_ves_voyage){		
		$data['list_commodity'] = $this->yard->list_commodity();
		$data['list_general']	= $this->yard->list_refer($id_yard,'G',$id_ves_voyage);
		$data['list_refer'] 	= $this->yard->list_refer($id_yard,'R',$id_ves_voyage);
		$data['list_hz'] 		= $this->yard->list_refer($id_yard,'H',$id_ves_voyage);

		$data['list_empty'] 	= $this->yard->list_refer($id_yard,'M',$id_ves_voyage);
		$data['list_rh'] 		= $this->yard->list_refer($id_yard,'RH',$id_ves_voyage);
		$data['list_ov'] 		= $this->yard->list_refer($id_yard,'OV',$id_ves_voyage);
		
		$data['id_yard']		= $id_yard;
		$data['excel']			= $excel;
		$data['id_ves_voyage']  = $id_ves_voyage;
		$data['tab_id'] 		= $tab_id;
		// debux($data);

		$this->load->view('templates/outbound_yard_summary/outbound_yard_summary_new', $data);

	}

	public function get_slot_yard_outbound($dataslot=''){		
    		$result 		= explode('-',$dataslot);
    		$id_ves_voyage 	= $result[0];
    		$idpod 	= $result[1];
    		$size 	= $result[2];
    		$idyard 	= $result[3];
    		$type 	= $result[4];
    		$idblock 	= $result[5];
    		
		$data	= $this->yard->get_slot_single_stok_view($id_ves_voyage,$idyard,$size,$idpod,$type,$idblock);
		// debux($data);
		$total 	= $data['total'];
		$slot 	= $data['slot'];

		echo "$slot-$total-$idpod-$size-$idyard-$type-oys";
	}
}