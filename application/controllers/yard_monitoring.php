<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_monitoring extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('vessel');
        $this->load->model('gtools');
		$this->load->model('monitoring');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $_GET['id_yard'];
        if ($data['id_yard']){
            $data['filter_data'] = Array(
                'pod' => $this->monitoring->get_constacking_pod($data['id_yard']),
                'vessel' => $this->monitoring->get_constacking_vessel($data['id_yard']),
                'carrier' => $this->monitoring->get_constacking_carrier($data['id_yard']),
                'ei' => Array('E' => 'Export', 'I' => 'Import')
            );
        }
		$data['yard_list'] = $this->yard->get_yard_list();
		$this->load->view('templates/yard_monitoring/yard_monitoring_header', $data);
	}
    
    public function load_data($tab_id, $id_yard, $pod, $ves, $carr, $ei){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $_GET['id_yard'];
		$data['filter_pod'] = $_GET['pod'];
        $data['filter_vessel'] = $_GET['ves'];
        $data['filter_carrier'] = $_GET['carr'];
        $data['filter_ei'] = $_GET['ei'];
        
        //$vessel = $this->vessel->get_vessel_berthing();
        //$berth = $this->vessel->get_berth_meter();
        $xml_string = $this->monitoring->extract_yard_monitoring($data['id_yard']);
        $equipment_result = $this->monitoring->get_equipment_monitoring($data['id_yard']);
        //$category_legend = $this->yard->get_list_plan_category();
        //$equipment_legend = $this->yard->get_list_equipment();

        $data_yard = simplexml_load_string($xml_string);
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
        $data['max_row'] 	= explode(",", $data_yard->max_row);
        $data['orientation']= explode(",", $data_yard->orientation);
        $block_name_cell	= $data_yard->block_name; 
        $data['block_name'] = explode(",", $block_name_cell);
        $label_cell			= $data_yard->label;
        $data['label'] 		= explode(",", $label_cell);
        $label_text_cell	= $data_yard->label_text;
        $data['label_text'] = explode(",", $label_text_cell);
        $data['vessel'] = $vessel;
        $data['berth'] = $berth;
        $data['equipment']  = $equipment_result;
        $data['category_legend']  = $category_legend;
        $data['equipment_legend']  = $equipment_legend;
        
        $this->load->view('templates/yard_monitoring/yard_monitoring_panel', $data);
    }
    
    public function load_yard_stacking_data($id_yard, $block, $id_slot, $size){
        $configs = $this->monitoring->get_slot_configuration($id_yard, $block);
        $raw_data = $this->monitoring->get_container_in_yard_data($id_yard, $block, $id_slot, $size);
        $block_data = $this->monitoring->get_list_block_slot($id_yard);
        
        $cy_data = Array(); $cy_data_idx = Array($id_slot);
        foreach($raw_data as $datavalue){
            $cy_data[$datavalue['YD_SLOT']][] = array( 
                'YD_ROW' => $datavalue['YD_ROW'], 
                'YD_TIER' => $datavalue['YD_TIER'],
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
                'ID_OPERATOR' => $datavalue['ID_OPERATOR'],
                'WEIGHT' => $datavalue['WEIGHT'],
                'ID_COMMODITY' => $datavalue['ID_COMMODITY'],
                'HAZARD' => $datavalue['HAZARD']
            );
            if (!in_array($datavalue['YD_SLOT'], $cy_data_idx)){
                array_push($cy_data_idx, $datavalue['YD_SLOT']);
            }
        }
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
                'filter_block' => $filter_data
        ));
    }
    
    public function test(){
        echo rand() . "\n";
        var_dump( $this->monitoring->extract_yard_monitoring(2) );
    }
    
    public function test2(){
        $this->load->view('templates/yard_monitoring/yard_monitoring_test');
    }
}