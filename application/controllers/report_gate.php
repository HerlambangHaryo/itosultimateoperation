<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Report_gate extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('monitoring');
		$this->load->model('gtools');
		$this->load->model('vessel');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/report/report_gate', $data);
	}

    public function data_vessel(){
		$data	= $this->vessel->get_vessel_schedule();
	            
        $return = array();
        foreach($data['data'] as $item){
            $return[] = array(
                'ID_VESSEL' => $item['ID_VESSEL'],
                'ID_VES_VOYAGE' => $item['ID_VES_VOYAGE'],
                'VESSEL_NAME' => $item['VESSEL_NAME'].'-'.$item['VOY_IN'].'/'.$item['VOY_OUT']
            );
        }
		echo json_encode($return);
	}

	public function get_ves_voyage()
	{
		
		$ves 	= explode('-', $_POST['VESSEL']);
		$voyage = explode('/', $ves[1]);

		$data['VESSEL_NAME']  = $ves[0];
		$data['VOY_IN']    	  = $voyage[0];
		$data['VOY_OUT']   	  = $voyage[1];

		$respose = $this->vessel->get_ves_voyage($data);
		echo json_encode($respose);
		
	}
        
    public function data_pbm(){
		$data	= $this->vessel->get_stevedoring_companies();
		echo json_encode($data);
	}
        
	public function get_data_gate(){
		$paging = '';
		$date_gate_in  = $this->input->get('DATE_GATE_IN');
		$date_gate_out = $this->input->get('DATE_GATE_OUT');
		$kegiatan      = $this->input->get('KEGIATAN');
		//$shipping_line = substr($this->input->get('SHIPPING_LINE'),0,1);
		$esy = $this->input->get('ESY');
		$gate = $this->input->get('GATE');
		$vessel = $this->input->get('VESSEL');

		//debux($vessel);die;
		$pbm = $this->input->get('pbm');

		$retvaldata = $this->monitoring->get_data_report_gate($date_gate_in, $date_gate_out, $kegiatan, $esy, $gate, $vessel, $pbm, $paging);
		$retval['data_detail']=$retvaldata['data'];
		$retval['COMPANY_NAME']=$pbm;
		
		/*$sumTRT = 0;
		$in = 0;
		foreach ($retval['data_detail'] as $value) {
			$sumTRT = $sumTRT + $value['TRTMIN'];
			$in += 1;
		}
		$retval['data_trt'] = round($sumTRT/$in);*/

//		echo '<pre>sum : ';print_r($sumTRT); echo '</pre>';
//		echo '<pre>in : ';print_r($in); echo '</pre>';
//		echo '<pre>';print_r($retval['data_trt']); echo '</pre>';exit;
		$this->load->view('templates/report/ete_report_gate', $retval);
	}
        
	public function get_data_gate_show(){
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
			$date_gate_in  = $sd->DATE_GATE_IN;
			$date_gate_out = $sd->DATE_GATE_OUT;
			$kegiatan      = $sd->KEGIATAN;
			//$shipping_line = substr($sd->SHIPPING_LINE'),0,1);
			$esy = $sd->ESY;
			$gate = $sd->GATE;
			$vessel = $sd->VESSEL;
			$pbm = $sd->pbm;
		}else{
			$date_gate_in = '';
			$date_gate_out = '';
			$kegiatan = '';
			$esy = '';
			$gate = '';
			$vessel = '';
			$pbm = '';
		}

			//debux($vessel);die;

		$retval = $this->monitoring->get_data_report_gate($date_gate_in, $date_gate_out, $kegiatan, $esy, $gate, $vessel, $pbm, $paging);
		
		/*$sumTRT = 0;
		$in = 0;
		foreach ($retval['data_detail'] as $value) {
			$sumTRT = $sumTRT + $value['TRTMIN'];
			$in += 1;
		}
		$retval['data_trt'] = round($sumTRT/$in);*/

//		echo '<pre>sum : ';print_r($sumTRT); echo '</pre>';
//		echo '<pre>in : ';print_r($in); echo '</pre>';
//		echo '<pre>';print_r($retval['data_trt']); echo '</pre>';exit;
		echo json_encode($retval);
	}
}