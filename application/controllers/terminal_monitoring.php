<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Terminal_monitoring extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('vessel');
		$this->load->model('monitoring');
		$this->load->model('container');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $_GET['id_yard'];
		if ($data['id_yard']){
			$data['filter_data'] = Array(
				'pod' => $this->monitoring->get_constacking_pod($data['id_yard']),
				'vessel' => $this->monitoring->get_constacking_vessel($data['id_yard']),
				'carrier' => $this->monitoring->get_constacking_carrier($data['id_yard']),
				'ei' => Array('E' => 'Inbound', 'I' => 'Outbound')
			);
		}
		$data['yard_list'] = $this->yard->get_yard_list();
		$this->load->view('templates/terminal_monitoring/terminal_monitoring_header', $data);
	}
	
	public function test(){
		// echo json_encode($this->vessel->get_vessel_berthing());
		echo json_encode($this->vessel->get_vessel_berthing_monitoring());
	}
	
	public function load_data($tab_id, $id_yard, $pod, $ves, $carr, $ei){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $_GET['id_yard'];
		$data['filter_pod'] = $_GET['pod'];
		$data['filter_vessel'] = $_GET['ves'];
		$data['filter_carrier'] = $_GET['carr'];
		$data['filter_ei'] = $_GET['ei'];
		
		// $vessel = $this->vessel->get_vessel_berthing();
		$vessel = $this->vessel->get_vessel_berthing_monitoring($data['filter_vessel']);		
		$berth = $this->vessel->get_berth_meter();
		$xml_string = $this->monitoring->extract_yard_monitoring($data['id_yard']);
		$equipment_result = $this->monitoring->get_equipment_monitoring($data['id_yard']);
		
		$vessel_bay_numb = array();
		for ($i=0; $i<sizeof($vessel); $i++){
			$vessel[$i]['vessel_profile'] = $this->vessel->get_vesselprofile_info($vessel[$i]['ID_VESSEL']);
		}

		//debux($vessel);

		$data_yard = simplexml_load_string($xml_string);
//		echo '<pre>';print_r($data_yard);echo '</pre>';exit;
		$data['width']  	= $data_yard->width;
		$data['height'] 	= $data_yard->height;
		$stack_cell			= $data_yard->index; 
		$data['index'] 		= explode(",", $stack_cell);
		$placement_cell		= $data_yard->placement; 
		$data['placement'] 	= explode(",", $placement_cell);
		$slot_cell			= $data_yard->slot; 
		$data['slot_'] 		= explode(",", $slot_cell);
		$row_cell			= $data_yard->row; 
		$data['row_'] 		= explode(",", $row_cell);
		$data['max_slot'] 	= explode(",", $data_yard->max_slot);
		$data['max_row'] 	= explode(",", $data_yard->max_row);
		$data['position']= explode(",", $data_yard->position);
		$data['orientation']= explode(",", $data_yard->orientation);
		$block_name_cell	= $data_yard->block_name; 
		$data['block_name'] = explode(",", $block_name_cell);
		$block_cell	= $data_yard->block; 
		$data['block'] = explode(",", $block_cell);
		$label_cell			= $data_yard->label;
		$data['label'] 		= explode(",", $label_cell);
		$label_text_cell	= $data_yard->label_text;
		$data['label_text'] = explode(",", $label_text_cell);
		$data['vessel'] = $vessel;
		$data['berth'] = $berth;
		$data['equipment']  = $equipment_result;
		$data['category_legend']  = $category_legend;
		$data['equipment_legend']  = $equipment_legend;
		
		$this->load->view('templates/terminal_monitoring/terminal_monitoring_panel', $data);
	}
	
	public function load_vessel_berth_stevedoring_info(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$data = $this->vessel->get_vessel_stevedoring_status($id_ves_voyage);
		echo json_encode($data);
	}
	
	public function load_yard_stacking_data($id_yard, $id_block, $block, $id_slot, $size){
//		echo '<pre>id_yard : '.$id_yard.'</pre>';
//		echo '<pre>block : '.$block.'</pre>';
//		echo '<pre>id_slot : '.$id_slot.'</pre>';
//		echo '<pre>size : '.$size.'</pre>';exit;
		$configs = $this->monitoring->get_slot_configuration($id_yard, urldecode($block));
//		$raw_data = $this->monitoring->get_container_in_yard_data($id_yard, urldecode($block), $id_slot, $size);
		$raw_data = $this->yard->get_stack_profile_slotInfo($id_yard, $id_block, $id_slot, '');
		$block_data = $this->monitoring->get_list_block_slot($id_yard);
		$category_list = $this->yard->get_yard_plan_category_per_slot($id_yard, $id_block, $id_slot);
		$cy_data = Array(); $cy_data_idx = Array($id_slot);
		foreach($raw_data as $datavalue){
		    if($datavalue['NO_CONTAINER'] != ''){
			$cy_data[$datavalue['SLOT_']][] = array( 
				'YD_ROW' => $datavalue['ROW_'], 
				'YD_TIER' => $datavalue['TIER_'],
				'NO_CONTAINER' => $datavalue['NO_CONTAINER'],
				'POINT' => $datavalue['POINT'],
				'ID_CLASS_CODE' => $datavalue['ID_CLASS_CODE'],
				'ID_VES_VOYAGE' => $datavalue['ID_VES_VOYAGE'],
				'ID_VESSEL' => $datavalue['ID_VESSEL'],
				'ID_ISO_CODE' => $datavalue['ID_ISO_CODE'],
				'CONT_SIZE' => $datavalue['CONT_SIZE'],
				'CONT_TYPE' => $datavalue['CONT_TYPE'],
				'CONT_STATUS' => $datavalue['CONT_STATUS'],
				'CONT_HEIGHT' => $datavalue['CONT_HEIGHT'],
				'ID_POD' => $datavalue['ID_POD'],
				'BACKGROUND_COLOR' => $datavalue['BACKGROUND_COLOR'],
				'FOREGROUND_COLOR' => $datavalue['FOREGROUND_COLOR'],
				'ID_OPERATOR' => $datavalue['ID_OPERATOR'],
				'WEIGHT' => $datavalue['WEIGHT'],
				'ID_COMMODITY' => $datavalue['ID_COMMODITY'],
				'HAZARD' => $datavalue['HAZARD'],
				'ID_SPEC_HAND' => $datavalue['ID_SPEC_HAND'],
				'IMDG' => $datavalue['IMDG']
			);
		    }
			if (!in_array($datavalue['SLOT_'], $cy_data_idx)){
				array_push($cy_data_idx, $datavalue['SLOT_']);
			}
		}
//		foreach($raw_data as $datavalue){
//			$cy_data[$datavalue['YD_SLOT']][] = array( 
//				'YD_ROW' => $datavalue['YD_ROW'], 
//				'YD_TIER' => $datavalue['YD_TIER'],
//				'NO_CONTAINER' => $datavalue['NO_CONTAINER'],
//				'POINT' => $datavalue['POINT'],
//				'ID_CLASS_CODE' => $datavalue['ID_CLASS_CODE'],
//				'ID_VES_VOYAGE' => $datavalue['ID_VES_VOYAGE'],
//				'ID_VESSEL' => $datavalue['ID_VESSEL'],
//				'ID_ISO_CODE' => $datavalue['ID_ISO_CODE'],
//				'CONT_SIZE' => $datavalue['CONT_SIZE'],
//				'CONT_TYPE' => $datavalue['CONT_TYPE'],
//				'CONT_STATUS' => $datavalue['CONT_STATUS'],
//				'CONT_HEIGHT' => $datavalue['CONT_HEIGHT'],
//				'ID_POD' => $datavalue['ID_POD'],
//				'ID_OPERATOR' => $datavalue['ID_OPERATOR'],
//				'WEIGHT' => $datavalue['WEIGHT'],
//				'ID_COMMODITY' => $datavalue['ID_COMMODITY'],
//				'HAZARD' => $datavalue['HAZARD'],
//				'ID_SPEC_HAND' => $datavalue['ID_SPEC_HAND'],
//				'IMDG' => $datavalue['IMDG']
//			);
//			if (!in_array($datavalue['YD_SLOT'], $cy_data_idx)){
//				array_push($cy_data_idx, $datavalue['YD_SLOT']);
//			}
//		}
		$filter_data = Array();
		foreach($block_data as $datavalue){
			$filter_data[$datavalue['BLOCK_NAME']]= array(
				'ID_BLOCK' => $datavalue['ID_BLOCK'],
				'SLOT' => $datavalue['SLOT']
			);
		}
		
		header('Content-Type: application/json');
		echo json_encode(Array(
				'configs' => $configs,
				'data_idx' => $cy_data_idx,
				'data' => $cy_data,
				'filter_block' => $filter_data,
				'category_list' => $category_list
		));
	}
	
	public function load_ship_container_list(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$id_class_code = $_POST['id_class_code'];
		
		$data = $this->container->get_data_inbound_outbound_list($id_ves_voyage, $id_class_code);
		echo json_encode($data);
	}
	
	public function load_ship_confirm_data(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$bay = $_POST['bay'];
		
		$configs = $this->monitoring->get_bay_configuration(substr($id_ves_voyage, 0, 4), $bay);
		$bay_data = $this->monitoring->get_list_vessel_bay(substr($id_ves_voyage, 0, 4));
		
		$filter_data = array();
		foreach($bay_data as $datavalue){
			$filter_data[$datavalue['BAY']] = array(
				'ID_BAY' => $datavalue['ID_BAY'],
				'BAY' => $datavalue['BAY']
			);
		}
		
		echo json_encode(
			array(
				'configs' => $configs,
				'filter_bay' => $filter_data
			)
		);
	}

	public function get_block_text(){
		$id_block  = $_POST['id_block'];
		$id_yard   = $_POST['id_yard'];
		$data      = $this->monitoring->get_block_name($id_block,$id_yard);
		echo json_encode($data);
	}
}