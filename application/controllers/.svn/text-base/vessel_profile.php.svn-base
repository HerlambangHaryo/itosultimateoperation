<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Vessel_profile extends CI_Controller {
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
		$data['vs_code'] = $_GET['ves_code'];
		$data['id_user'] = $this->session->userdata('id_user');
		// echo $_GET['ves_code'];

		if ($data['vs_code']!=NULL){
			$data['vessel_code'] = $this->vessel->get_vessel_code();
			$data_vessel = $this->vessel->get_info_vessel($data['vs_code']);
			$flg_vespro = $this->vessel->get_flag_vespro($data['vs_code']);
			foreach ($flg_vespro as $row_flg)
			{
				$flg = $row_flg['PROFILE'];
				$data['flag_profile'] = $flg;
			}
			$this->load->view('templates/vessel_profile/viewer_panel', $data);

			if($flg=="Y")
			{
				foreach ($data_vessel as $row_ves)
				{
					$ves = $row_ves['VESSEL_NAME'];
					$opr = $row_ves['OPERATOR'];
					$callsign = $row_ves['CALL_SIGN'];
					$lngth = $row_ves['LENGTH'];
					$grs = $row_ves['GROSS_TONAGE'];
					$htc = $row_ves['HATCH_COVER'];
				}

				$data['vessel'] = $ves;
				$data['callsign'] = $callsign;
				$data['opr'] = $opr;
				$data['lngth'] = $lngth;
				$data['grs'] = $grs;
				$data['htch'] = $htc;

				//load attribute display profile
				$data['vesselinfo'] = $this->vessel->get_vesselprofile_info($data['vs_code']);
				$data['infobay'] = $this->vessel->get_bay_info($data['vs_code']);
				$data['max_row'] = $this->vessel->get_max_row($data['vs_code']);
				$data['max_tier_on'] = $this->vessel->get_max_tier_on($data['vs_code']);
				$data['max_tier_under'] = $this->vessel->get_max_tier_under($data['vs_code']);

				$data['bay_label'] = $this->vessel->get_bay_numb_profile($data['vs_code']);
				$data['profile_abv'] = $this->vessel->get_vessel_above_profile($data['vs_code']);
				$data['profile_blw'] = $this->vessel->get_vessel_below_profile($data['vs_code']);

				//debux($data);

				$this->load->view('templates/vessel_profile/vw_load_profile', $data);
			}
			else
			{
				$this->load->view('templates/vessel_profile/vw_create_profile', $data);
			}
		}else{
			$data['vessel_code'] = $this->vessel->get_vessel_code();

			$this->load->view('templates/vessel_profile/viewer_panel', $data);
		}
	}

	public function vesproContent($vescode,$tab_id)
	{
		$data['tab_id'] = $tab_id;
		$data['vs_code'] = $vescode;
		$data_vessel = $this->vessel->get_info_vessel($vescode);
		foreach ($data_vessel as $row_ves)
		{
			$ves = $row_ves['VESSEL_NAME'];
			$opr = $row_ves['OPERATOR'];
			$callsign = $row_ves['CALL_SIGN'];
			$lngth = $row_ves['LENGTH'];
			$grs = $row_ves['GROSS_TONAGE'];
			$htc = $row_ves['HATCH_COVER'];
		}

		$data['vessel'] = $ves;
		$data['callsign'] = $callsign;
		$data['opr'] = $opr;
		$data['lngth'] = $lngth;
		$data['grs'] = $grs;
		$data['htch'] = $htc;

		//load attribute display profile
		$data['vesselinfo'] = $this->vessel->get_vesselprofile_info($data['vs_code']);
		$data['infobay'] = $this->vessel->get_bay_info($data['vs_code']);
		$data['max_row'] = $this->vessel->get_max_row($data['vs_code']);
		$data['max_tier_on'] = $this->vessel->get_max_tier_on($data['vs_code']);
		$data['max_tier_under'] = $this->vessel->get_max_tier_under($data['vs_code']);

		$data['bay_label'] = $this->vessel->get_bay_numb_profile($data['vs_code']);
		$data['profile_abv'] = $this->vessel->get_vessel_above_profile($data['vs_code']);
		$data['profile_blw'] = $this->vessel->get_vessel_below_profile($data['vs_code']);

		$this->load->view('templates/vessel_profile/vw_load_profileContent', $data);
	}

	public function set_occupy()
	{
		$data['tab_id'] = $_GET['tab_id'];
		$data['vs_code'] = $_GET['vescd'];

		$data['idbay'] = $_POST["ID_BAY"];
		$data['status'] = $_POST["STAT"];

		$id_user = $this->session->userdata('id_user');
		$msg = $this->vessel->set_vesbay_occupy($data['vs_code'],$data['idbay'],$data['status'],$id_user);
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

	public function reset_vessel_profile()
	{
		$data['id_user'] = $_POST["IDUSER"];
		$data['vs_code'] = $_POST["VSCD"];

		$msg = $this->vessel->reset_vespro($data['vs_code'],$data['id_user']);

		echo $msg;
	}

	public function editProfile($vessel_code,$id_bay,$tab_id){
		$data['tab_id']=$tab_id;
		$data['vessel_code']=$vessel_code;
		$data['id_bay']=$id_bay;

		$row = $this->vessel->get_vessel_profile_bayArea($vessel_code,$id_bay);
		$data['bay'] = $row[0];
		
		$this->load->view('templates/vessel_profile/vw_editorProfile', $data);
	}

	public function create_profile(){
		$data['vs_code'] = $_GET['vscode'];
		$data['tab_id'] = $_GET['tab_id'];
		$data['jmlbay']  = $_POST["jmlbay"];
		$data['jmlrow'] = $_POST["jmlrow"];
		$data['jmltier_on'] = $_POST["jmltier_on"];
		$data['jmltier_un'] = $_POST["jmltier_un"];
		$data['jmlht'] = $_POST["jmlht"];

		$id_user = $this->session->userdata('id_user');
		$this->vessel->create_vespro($data['vs_code'],$data['jmlbay'],$data['jmlrow'],$data['jmltier_on'],$data['jmltier_un'],$data['jmlht'],$id_user);

		$data['vessel_code'] = $this->vessel->get_vessel_code();
		$flg_vespro = $this->vessel->get_flag_vespro($data['vs_code']);
		foreach ($flg_vespro as $row_flg)
		{
			$flg = $row_flg['PROFILE'];
			$data['flag_profile'] = $flg;
		}
		$this->load->view('templates/vessel_profile/viewer_panel', $data);

		//get data vessel
		$data_vessel = $this->vessel->get_info_vessel($data['vs_code']);
		foreach ($data_vessel as $row_ves)
		{
			$ves = $row_ves['VESSEL_NAME'];
			$opr = $row_ves['OPERATOR'];
			$callsign = $row_ves['CALL_SIGN'];
			$lngth = $row_ves['LENGTH'];
			$grs = $row_ves['GROSS_TONAGE'];
		}

		$data['vessel'] = $ves;
		$data['callsign'] = $callsign;
		$data['opr'] = $opr;
		$data['lngth'] = $lngth;
		$data['grs'] = $grs;

		//load attribute display profile
		$data['vesselinfo'] = $this->vessel->get_vesselprofile_info($data['vs_code']);
		$data['infobay'] = $this->vessel->get_bay_info($data['vs_code']);
		$data['max_row'] = $this->vessel->get_max_row($data['vs_code']);
		$data['max_tier_on'] = $this->vessel->get_max_tier_on($data['vs_code']);
		$data['max_tier_under'] = $this->vessel->get_max_tier_under($data['vs_code']);

		$this->load->view('templates/vessel_profile/vw_load_profile', $data);
	}

	public function generate_rowtier_bay(){
		$data['vs_code'] = $_GET['vscd'];
		$data['tab_id'] = $_GET['tab_id'];

		$data['idbay']  = $_POST["ID_BAY"];
		$data['jmlrow'] = $_POST["JMLROW"];
		$data['jmltier_abv'] = $_POST["JMLTIERD"];
		$data['jmltier_blw'] = $_POST["JMLTIERH"];
		$data['abv_stat'] = $_POST["ABV_STAT"];
		$data['blw_stat'] = $_POST["BLW_STAT"];

		$id_user = $this->session->userdata('id_user');
		$msg = $this->vessel->generateBay_rowTier($data['vs_code'],$data['idbay'],$data['jmlrow'],$data['jmltier_abv'],$data['jmltier_blw'],$data['abv_stat'],$data['blw_stat'],$id_user);
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

	public function set_broken_space(){
		$id_vessel = $_POST['id_vessel'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->vessel->edit_vessel_profile_set_broken_space($id_vessel, $id_bay, $xml_str);
		echo $retval;
	}

	public function unset_broken_space(){
		$id_vessel = $_POST['id_vessel'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->vessel->edit_vessel_profile_unset_broken_space($id_vessel, $id_bay, $xml_str);
		echo $retval;
	}

	public function set_reefer_racking(){
		$id_vessel = $_POST['id_vessel'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->vessel->edit_vessel_profile_set_reefer_racking($id_vessel, $id_bay, $xml_str);
		echo $retval;
	}

	public function unset_reefer_racking(){
		$id_vessel = $_POST['id_vessel'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->vessel->edit_vessel_profile_unset_reefer_racking($id_vessel, $id_bay, $xml_str);
		echo $retval;
	}

	public function set_hatch()
	{
		$data['tab_id'] = $_GET['tab_id'];
		$data['vs_code'] = $_GET['vescd'];
		$data['idbay'] = $_POST["ID_BAY"];
		$data['idht'] = $_POST["HATCH_ID"];

		$id_user = $this->session->userdata('id_user');
		$msg = $this->vessel->set_vesbay_hatch($data['vs_code'],$data['idbay'],$data['idht'],$id_user);

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

	public function data_hatch_list($vscode)
	{
		$filter = $_GET['query'];
		$data	= $this->vessel->get_hatch_list($filter,$vscode);
		echo json_encode($data);
	}
}
