<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Qc_working_plan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('machine');
		$this->load->model('gtools');
		$this->load->model('container');
		$this->load->library('session');
	}
	
	public function index(){
		// echo 'kucing';exit;
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->vessel->recalculate_working_sequence($data['id_ves_voyage']);
			$this->load->view('templates/qc_working_plan/qc_working_plan_panel', $data);
		}
	}

	public function refresh_index($tab_id, $id_ves_voyage){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_GET['id_ves_voyage'];
		if ($data['id_ves_voyage']!=''){
			$this->vessel->recalculate_working_sequence($data['id_ves_voyage']);
			$this->load->view('templates/qc_working_plan/qc_working_plan_panel', $data);
		}
	}
	
	public function deleteSequenceCwp()
	{
		$v_idmchwkplan	= $_POST['IDMACHINE'];
		$v_seq			= $_POST['SEQUENCE'];
		$v_bay			= $_POST['BAY'];
		$v_act			= $_POST['ACTIVITY'];
		$v_deck			= $_POST['DECK'];
		$id_vsbvoy 		= $_POST['ID_VSBVOY'];
//		echo '<pre>';print_r($_POST);echo '</pre>';exit;
		$data	= $this->machine->deleteSequenceCwp($v_idmchwkplan,$v_seq,$v_bay,$v_act,$v_deck,$id_vsbvoy);
		echo json_encode($data);
	}
	
	public function cwpContent($id_vesvoy,$tab_id)
	{
		$data['id_ves_voyage'] = $id_vesvoy;
		$data['tab_id'] = $tab_id;
		$data['along_side'] = $this->vessel->get_along_side_orientation($id_vesvoy);
		$data['bay_label'] = $this->vessel->get_bay_numb($data['along_side'], $id_vesvoy);
		$data['profile_abv'] = $this->vessel->get_vessel_above($data['along_side'], $id_vesvoy);
		$data['profile_blw'] = $this->vessel->get_vessel_below($data['along_side'], $id_vesvoy);
//		echo '<pre>';print_r($data).'</pre>';
		$data['ves_voyage'] = $this->vessel->get_info_vesvoy($id_vesvoy);
		$mulai = $this->machine->get_start_cwp($id_vesvoy);
		$akhir = $this->machine->get_end_cwp($id_vesvoy);
		$parameterJam = $this->machine->getParameterJam($id_vesvoy);
		$data['dataSeq'] = $this->machine->getSequenceCWP($id_vesvoy);
		foreach ($mulai as $row_mulai)
		{
			$data['start_sequence'] = $row_mulai['DAY_MULAI'];
		}

		foreach ($akhir as $row_akhir)
		{
			$data['end_sequence'] = $row_mulai['DAY_AKHIR'];
		}
		
		foreach ($parameterJam as $row)
		{
			$data['startx'] = $row['STARTX'];
			$data['endx'] = $row['ENDX'];
			$data['selisihx'] = $row['SELISIHX'];
			$data['selisihy'] = $row['SELISIHY'];
		}
		$data['cwp_machine'] = $this->machine->get_cwp_vesvoy($id_vesvoy);
//		$data['cwp_machinedeck'] = $this->machine->get_cwp_vesvoydeck($id_vesvoy);
//		$data['cwp_machinehatch'] = $this->machine->get_cwp_vesvoyhatch($id_vesvoy);
		//debux($data);die;
		$this->load->view('templates/qc_working_plan/qc_working_plan_AddContent', $data);
	}
	public function data_machine_cwp($id_vesvoy)
	{
		$filter = $_GET['query'];
		$data	= $this->machine->get_machine_cwp($filter,$id_vesvoy);
		echo json_encode($data);
	}

	public function get_machine_mst($id_vesvoy)
	{
		$filter = $_GET['query'];
		$data	= $this->machine->data_machine_mst($filter,$id_vesvoy);
		echo json_encode($data);
	}	

	public function get_active_mch($id_vesvoy){
		$data= $this->machine->data_active_mch($id_vesvoy);
		
		for($i=0;$i<count($data);$i++)
		{
			$idmch=$data[$i]['ID_MACHINE'];
			$data[$i]['TOTALDATA']= $this->machine->get_total_qc_working_list($id_vesvoy,$idmch);
			$ID_MCH_WORKING_PLAN=$data[$i]['ID_MCH_WORKING_PLAN'];
			$mchname=$data[$i]['MCH_NAME'];
			$bch=$data[$i]['BCH'];
			$BG_COLOR=$data[$i]['BG_COLOR'];
			$startwork=$data[$i]['START_WORK'];
			$endwork=$data[$i]['END_WORK'];	
			if($data[$i]['TOTALDATA']!='0' and $data[$i]['TOTALDATA']!=''){
				$data[$i]['REMAIN']= $data[$i]['TOTALDATA'] - $data[$i]['COMPLETED'];
			}else{
				$data[$i]['REMAIN']= '0';
			}
			$data[$i]['ACTION']="<button onclick='updatemchstt(\"$id_vesvoy\",\"$idmch\",\"$mchname\",\"$bch\",\"$startwork\",\"$endwork\")' >Edit</button> <button onclick='deletemchstt(\"$ID_MCH_WORKING_PLAN\",\"$mchname\")' >Delete</button>";
			
		}
		echo json_encode($data);
	}
	
	public function delete_mch(){
	    $ID_MCH_WORKING_PLAN 	= strtoupper(trim($_POST['ID_MCH_WORKING_PLAN']));
	    $MCH_NAME 	= strtoupper(trim($_POST['MCH_NAME']));
	    $response = $this->machine->delete_machine_qc_working_plan($ID_MCH_WORKING_PLAN,$MCH_NAME);

	    header('Content-Type: application/json');
	    echo json_encode($response);
	}

	public function get_classcode()
	{
		$filter = $_GET['query'];
		$data	= $this->container->data_class_code($filter);
		echo json_encode($data);
	}

	public function assign_mch_cwp()
	{
		$data['id_vesvoy'] = $_GET['vsvoy_id'];
		$data['id_ves_voyage'] = $data['id_vesvoy'];
		$data['tab_id'] = $_GET['tab_id'];
		
		$data['nm_mch'] = $_POST['MACHINE_NAME'];
		$data['idbay'] = $_POST["ID_BAY"];
		$data['pssbay'] = $_POST["BAY_POSITION"];
		$data['class'] = $_POST["CLASSCODE"];

		$vs_code = substr($data['id_vesvoy'],0,4);
		$id_user = $this->session->userdata('id_user');
		$msg = $this->machine->create_cwp_assign($vs_code,$data['id_vesvoy'],$data['nm_mch'],$data['idbay'],$data['pssbay'],$data['class'],$id_user);
		// echo $msg."<br/><br/>";

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
	
	public function qc_summary(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		$data_disch = $this->machine->get_machine_dsc($_POST['data_id']);
		$data_load = $this->machine->get_machine_load($_POST['data_id']);
		$etd = $this->machine->get_etd_vvd($data['id_ves_voyage']);
		

		//================= load data summary ==================//
		foreach ($data_disch as $row_dsc)
		{
			$completed_dsc = $row_dsc['SUM_COMPLETED'];
			$planned_dsc = $row_dsc['SUM_PLANNED'];
			$remained_dsc = $row_dsc['SUM_REMAINED'];
			$qc_dsc = $row_dsc['QC_UNASSIGNED'];
			$ttl_dsc = $row_dsc['TOTAL'];
		}
		$data['detd'] = $etd['DETD'];
		$data['hetd'] = $etd['HETD'];
		$data['minetd'] = $etd['MINETD'];
		
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

		$this->load->view('templates/qc_working_plan/qc_summary_panel', $data);
	}

	public function save_machine_vesvoy($id_ves_voyage){
		$data['start_work'] = $_POST['START_DATE'].' '.str_pad($_POST['START_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['START_MIN'],2,'0',STR_PAD_LEFT);
		$data['end_work'] = $_POST['END_DATE'].' '.str_pad($_POST['END_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['END_MIN'],2,'0',STR_PAD_LEFT);
		$data['mch_id'] = $_POST['MACHINE_NAME'];
//		$data['bch'] = $_POST['BCH'];
		// print_r($data);

		$id_user = $this->session->userdata('id_user');
		$msg = $this->machine->save_machine_vesvoy($data,$id_ves_voyage,$id_user);
		
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
	
	public function update_machine_vesvoy($id_ves_voyage){
		$data['start_work'] = $_POST['START_DATE'].' '.str_pad($_POST['START_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['START_MIN'],2,'0',STR_PAD_LEFT);
		$data['end_work'] = $_POST['END_DATE'].' '.str_pad($_POST['END_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['END_MIN'],2,'0',STR_PAD_LEFT);
		$data['mch_id'] = $_POST['MACHINE_NAME'];
		$data['mch_idplan'] = $_POST['ID_MACHINE'];
//		$data['bch'] = $_POST['BCH'];
		#print_r($data);

		$id_user = $this->session->userdata('id_user');
		$mch_vv = $this->machine->update_machine_vesvoy($data,$id_ves_voyage,$id_user);
		
		$data = array(
			'success'=>false,
			'errors'=>'update error'
		);
		
		if ($mch_vv){
			$data['success']=true;
		}
		
		echo json_encode($data);
	}	
}