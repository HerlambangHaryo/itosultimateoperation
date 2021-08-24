<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class stowage_instruction_view extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->model('container');
		$this->load->library('session');
		
		$this->gtools->update_terminal();
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		$data['filter'] = '-';
		$data['isExchangeChecked'] = 0;
		if ($data['id_ves_voyage']!=''){
			$data['vessel'] = $this->vessel->get_vessel_profile_info($data['id_ves_voyage']);
			$data['ID_VESSEL'] = $data['vessel']['ID_VESSEL'];
			$data['bay_area'] = $this->vessel->get_vessel_profile_bayArea($data['ID_VESSEL']);
			$data['class_code'] = 'E';

			$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($data['ID_VESSEL']);
			$this->load->view('templates/stowage_instruction_view/viewer_panel', $data);
			$this->load->view('templates/stowage_instruction_view/stowage_instruction_view', $data);
			
		}
	}

	public function refresh_index($tab_id, $id_ves_voyage){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_GET['id_ves_voyage'];
		$data['filter'] = '-';
		$data['isExchangeChecked'] = $_GET['isExchangeChecked'];
		if ($data['id_ves_voyage']!=''){
			$data['vessel'] = $this->vessel->get_vessel_profile_info($data['id_ves_voyage']);
			$data['ID_VESSEL'] = $data['vessel']['ID_VESSEL'];
			$data['bay_area'] = $this->vessel->get_vessel_profile_bayArea($data['ID_VESSEL']);
			$data['class_code'] = 'E';

			$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($data['ID_VESSEL']);
			$this->load->view('templates/stowage_instruction_view/viewer_panel', $data);
			$this->load->view('templates/stowage_instruction_view/stowage_instruction_view', $data);
		}
	}

	public function refresh_filter($tab_id, $id_ves_voyage, $filter){
		//debux($_GET);die;
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_GET['id_ves_voyage'];
		$data['filter'] = $_GET['filter'];
		if($data['id_ves_voyage']!=''){
			$data['vessel'] = $this->vessel->get_vessel_profile_info($data['id_ves_voyage']);
			$data['ID_VESSEL'] = $data['vessel']['ID_VESSEL'];
			$data['bay_area'] = $this->vessel->get_vessel_profile_bayArea($data['ID_VESSEL']);
			$data['class_code'] = 'E';

			$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($data['ID_VESSEL']);
			$this->load->view('templates/stowage_instruction_view/viewer_panel', $data);
			$this->load->view('templates/stowage_instruction_view/stowage_instruction_view', $data);
		}
	}
	
	public function set_sequence(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->container->insert_container_working_sequence_outbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str);
		echo json_encode($retval);
	}
	
	public function unset_sequence(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->container->delete_container_working_sequence_outbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str);
		echo json_encode($retval);
	}

	public function print_all_bay($vescode,$id_ves_voyage){
		// print_r($vescode." ");
		// print_r($id_ves_voyage." ");
		// die;
		$data['vescode'] = $vescode;
		$data['id_ves_voyage'] = $id_ves_voyage;

		$header = $this->vessel->stowage_header_print($vescode,$id_ves_voyage);
		foreach ($header as $row_header)
		{
			$data['vessel'] = $row_header['VESSEL_NAME'];
			$data['voyage'] = $row_header['VOYAGE'];
		}

		$vesinfo = $this->vessel->stowage_print_vesinfo_allbay($vescode);
		foreach ($vesinfo as $row_vesinfo)
		{
			$data['jumlah_row'] = $row_vesinfo['JML_ROW'];
			$data['jml_tier_under'] = $row_vesinfo['JML_TIER_UNDER'];
			$data['jml_tier_on'] = $row_vesinfo['JML_TIER_ON'];
			$data['width'] = $row_vesinfo['WIDTH'];
		}

		$data['blok8'] = $this->vessel->stowage_print_vesbay_list($vescode,$id_ves_voyage);

		$this->load->view('templates/stowage_instruction_view/to_print_all_bay', $data);
	}	
	
	public function print_preLoad($vescode,$id_ves_voyage,$tabid,$ei){
		
		$data['tab_id'] = $tabid;
		$data['id_ves_voyage'] = $id_ves_voyage;
		$data['vesselLD'] = $this->vessel->get_vessel_profile_info($id_ves_voyage);
		
		$data['ID_VESSEL'] = $vescode;
		$data['bay_area'] = $this->vessel->get_vessel_profile_bayAreaNew($vescode);
		$data['maxRow']=$this->vessel->getMaxRowProfile($vescode);
		
		// assign shape for machines
		$data['all_mch']=$this->vessel->get_list_machine($id_ves_voyage, $ei);
		$assigned_shape = array();
		$assigned_mch_name = array();
		$list_shape = array('rectangle', 'circle', 'triangle-up', 'triangle-down');
		$counter_shape = 0;
		foreach($data['all_mch'] as $data_mch){
			$assigned_shape[$data_mch['ID_MACHINE']] = $list_shape[$counter_shape];
			$assigned_mch_name[$counter_shape] = $data_mch['MCH_NAME'];
			$counter_shape++;
		}
		$data['assigned_shape'] = $assigned_shape;
		$data['assigned_mch_name'] = $assigned_mch_name;
		
		$data['class_code'] = $ei;
		
		$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($vescode);
		$this->load->view('templates/stowage_instruction_view/to_print_all_bayNew', $data);
	}
	
	public function print_bay($vescode,$id_ves_voyage,$idbay,$deck_hatch,$nobay,$posisibay)
	{
		$data = array('vescode' => $vescode,
			'id_ves_voyage' => $id_ves_voyage,
			'idbay' => $idbay,
			'deck_hatch' => $deck_hatch,
			'nobay' => $nobay,
			'posisibay' => $posisibay,
			'ei' => 'E'
		);
		$this->load->view('templates/stowage_instruction_view/print_partial_bay',$data);
	}
	
	public function exchange_stowage(){
	    $id_ves_voyage = $_POST['id_ves_voyage'];
	    $from = $_POST['from'];
	    $to = $_POST['to'];
	    
	    echo json_encode($this->container->exchange_stowage($id_ves_voyage,$from,$to));
	}

}