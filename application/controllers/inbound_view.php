<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Inbound_view extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('vessel','gtools'));
		$this->load->model('container');
		$this->load->library('session');
	}
	
	public function index(){
	    $data['tab_id'] = $_GET['tab_id'];
	    $data['id_ves_voyage'] = $_POST['data_id'];

	    if ($data['id_ves_voyage']!=''){
			$data['vessel'] = $this->vessel->get_vessel_profile_info($data['id_ves_voyage']);
			$data['ID_VESSEL'] = $data['vessel']['ID_VESSEL'];
			$data['bay_area'] = $this->vessel->get_vessel_profile_bayArea($data['ID_VESSEL']);
			$data['class_code'] = 'I';

			$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($data['ID_VESSEL']);
			$this->load->view('templates/inbound_view/viewer_panel', $data);
			$this->load->view('templates/inbound_view/inbound_view', $data);
	    }
	}

	public function refresh_index($tab_id, $id_ves_voyage){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_GET['id_ves_voyage'];
		if ($data['id_ves_voyage']!=''){
			$data['vessel'] = $this->vessel->get_vessel_profile_info($data['id_ves_voyage']);
			$data['ID_VESSEL'] = $data['vessel']['ID_VESSEL'];
			$data['bay_area'] = $this->vessel->get_vessel_profile_bayArea($data['ID_VESSEL']);
			$data['class_code'] = 'I';
			
			$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($data['ID_VESSEL']);
			$this->load->view('templates/inbound_view/viewer_panel', $data);
			$this->load->view('templates/inbound_view/inbound_view', $data);
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
			$data['class_code'] = 'I';

			$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($data['ID_VESSEL']);
			$this->load->view('templates/inbound_view/viewer_panel', $data);
			$this->load->view('templates/inbound_view/inbound_view', $data);
		}
	}
	
	public function set_sequence(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->container->insert_container_working_sequence_inbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str);
		echo $retval;
	}
	
	public function unset_sequence(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$id_bay = $_POST['id_bay'];
		$deck_hatch = $_POST['deck_hatch'];
		$xml_str = $_POST['xml_'];
		// echo $xml_str."<br/>";
		$retval = $this->container->delete_container_working_sequence_inbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str);
		echo $retval;
	}
	
	public function print_all_bay($vescode,$id_ves_voyage,$tabid){
		// print_r($vescode." ");
		// print_r($id_ves_voyage." ");
		// die;
		
		$data['tab_id'] = $tabid;
		$data['id_ves_voyage'] = $id_ves_voyage;
		$data['vesselLD'] = $this->vessel->get_vessel_profile_info($id_ves_voyage);
		
		$data['ID_VESSEL'] = $vescode;
		$data['bay_area'] = $this->vessel->get_vessel_profile_bayArea($vescode);
		$data['class_code'] = 'I';
		
		$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($vescode);
		$this->load->view('templates/inbound_view/to_prntAll', $data);
	}

	public function print_all_bayNew($vescode,$id_ves_voyage,$fil,$tabid,$ei){
		//debux($fil);
		$data['tab_id'] = $tabid;
		$data['id_ves_voyage'] = $id_ves_voyage;
		$data['vesselLD'] = $this->vessel->get_vessel_profile_info($id_ves_voyage);
		
		$data['ID_VESSEL'] = $vescode;
		$data['bay_area'] = $this->vessel->get_vessel_profile_bayAreaNew($vescode);
		$data['maxRow']=$this->vessel->getMaxRowProfile($vescode);
		
		// assign shape for machines
		$data['all_mch']=$this->vessel->get_list_machine($id_ves_voyage, $ei);
		$data['filter'] = $fil;
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
		// var_dump($assigned_shape);
		
		// $data['all_plan']=$this->vessel->get_machine_all_plan($id_ves_voyage, $ei);
		
		$data['class_code'] = $ei;
		
		$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($vescode);

		$data['summary_head'] = $this->vessel->get_summary_vessel_header($id_ves_voyage,$ei);
		$data['summary_body'] = $this->vessel->get_summary_vessel_body($id_ves_voyage,$ei);

		$this->load->view('templates/inbound_view/to_printAllNew', $data);
	}
	
	public function print_allhatch_bayNew($vescode,$id_ves_voyage,$tabid,$ei){
		
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
		// var_dump($assigned_shape);
		
		// $data['all_plan']=$this->vessel->get_machine_all_plan($id_ves_voyage, $ei);
		
		$data['class_code'] = $ei;
		
		$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($vescode);
		$this->load->view('templates/inbound_view/to_printAllHatchNew', $data);
	}
	
	public function print_bay($vescode,$id_ves_voyage,$idbay,$deck_hatch,$nobay,$posisibay)
	{
		$data = array('vescode' => $vescode,
			'id_ves_voyage' => $id_ves_voyage,
			'idbay' => $idbay,
			'deck_hatch' => $deck_hatch,
			'nobay' => $nobay,
			'posisibay' => $posisibay,
		);
		$this->load->view('templates/inbound_view/print_partial_bay',$data);
	}
	
	public function print_bay_all($vescode,$id_ves_voyage,$tabid,$ei)
	{
		$data['tab_id'] = $tabid;
		$data['id_ves_voyage'] = $id_ves_voyage;
		$data['vesselLD'] = $this->vessel->get_vessel_profile_info($id_ves_voyage);
		
		$data['ID_VESSEL'] = $vescode;
		$data['bay_area'] = $this->vessel->get_vessel_profile_bayAreaNew($vescode);
		$data['maxRow']=$this->vessel->getMaxRowProfile($vescode);
		
		$data['class_code'] = $ei;
		
		$data['vessel_posisi'] = $this->vessel->get_vesselposisi_toprint($vescode);
		$this->load->view('templates/inbound_view/to_printAllBayCont', $data);
	}
}