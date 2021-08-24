<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Vessel_particular extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/vessel_particular/vw_vesparticular_grid', $data);
	}
	
	public function detail_panel(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['ID_VESSEL'] = $_POST['data_id'];
		$data_vessel = $this->vessel->get_info_vessel($data['ID_VESSEL']);
		$data['vessel_detail'] = json_encode($data_vessel[0]);
		
		$this->load->view('templates/vessel_particular/vw_vesparticular_detail_panel', $data);
	}
	
	public function data_vesparticular(){

		if(isset($_GET['ves_name'])){
			$data	= $this->vessel->get_vessel_particular_filtered($this->input->get('ves_name'));
		}else{
			$data	= $this->vessel->get_vessel_particular();
		}

		echo json_encode($data);
	}
	
	public function form_addParticular(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/vessel_particular/vw_vesparticular_formAdd', $data);
	}
	
	public function data_country(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_country_list($filter);
		echo json_encode($data);
	}
	
	public function data_operator(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_operator_list($filter);
		echo json_encode($data);
	}
	
	public function add_vesparticular(){
		$data['vscode'] = $_POST['VESSEL_CODE'];
		$data['csg'] = $_POST['CALL_SIGN'];
		$data['opr']  = $_POST['OPERATOR'];
		$data['vesselnm'] = $_POST["VESSEL_NAME"];
		$data['cncode'] = $_POST["COUNTRY_CODE"];
		// $data['v_fl_small_vessel'] = $_POST["FL_SMALL_VESSEL"];
		$data['gross'] = $_POST["GROSS"];
		$data['net_ton'] = $_POST["NET"];
		$data['ht_ton'] = $_POST["HATCH"];
		$data['lng_ton'] = $_POST["LENGTH"];
		$data['depth'] = $_POST["DEPTH"];
		$data['draft'] = $_POST["DRAFT"];

		$id_user = $this->session->userdata('id_user');
		$operator_nm = $this->vessel->get_operator_name($data['opr']);
		foreach ($operator_nm as $row_operator)
		{
			$opr_nm = $row_operator['OPERATOR_NAME'];
		}		
		// $mch_vv = $this->vessel->insert_particular($data['vscode'],$data['csg'],$data['opr'],$data['vesselnm'],$data['cncode'],$data['gross'],$data['net_ton'],$data['ht_ton'],$data['lng_ton'],$data['depth'],$data['draft'],$data['v_fl_small_vessel'],$opr_nm);

		$mch_vv = $this->vessel->insert_particular(
			$data['vscode'],
			$data['csg'],
			$data['opr'],
			$data['vesselnm'],
			$data['cncode'],
			$data['gross'],
			$data['net_ton'],
			$data['ht_ton'],
			$data['lng_ton'],
			$data['depth'],
			$data['draft'],
			$opr_nm);
		
		$data = array(
			'success'=>false,
			'errors'=>$mch_vv
		);
		
		if ($mch_vv){
			$data['success']=true;
			$data['errors']=$mch_vv;
		}
		
		echo json_encode($data);
	}
	
	public function update_vesparticular(){
		$data['vscode'] = $_POST['vescode'];
		$data['csg'] = $_POST['callsg'];
		$data['opr']  = $_POST['opr'];
		$data['vesselnm'] = $_POST["vesnm"];
		$data['cncode'] = $_POST["countrycd"];
		$data['gross'] = $_POST["grt"];
		$data['net_ton'] = $_POST["net"];
		$data['ht_ton'] = $_POST["ht"];
		$data['lng_ton'] = $_POST["lng"];
		$data['depth'] = $_POST["dpt"];
		$data['draft'] = $_POST["dft"];

		// if ($_POST["tk"] == 'true') {
		// 	$data['v_fl_small_vessel'] =  'Y';
		// }
		// else{
		// 	$data['v_fl_small_vessel'] =  'N';
		// }
		
		// $retval = $this->vessel->update_particular($data['vscode'],$data['csg'],$data['opr'],$data['vesselnm'],$data['cncode'],$data['gross'],$data['net_ton'],$data['ht_ton'],$data['lng_ton'],$data['depth'],$data['draft'],$data['v_fl_small_vessel']);

		$retval = $this->vessel->update_particular($data['vscode'],$data['csg'],$data['opr'],$data['vesselnm'],$data['cncode'],$data['gross'],$data['net_ton'],$data['ht_ton'],$data['lng_ton'],$data['depth'],$data['draft']);
		
		echo $retval;
	}
	
	public function delete_vesparticular(){
		$retval = $this->vessel->delete_vessel_particular($_POST['id_vessel']);
		echo $retval;
	}
}