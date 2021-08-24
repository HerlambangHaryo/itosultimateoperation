<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_equipment_plan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('machine');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $_GET['id_yard'];
		$data['yard_list'] = $this->yard->get_yard_list();
		$this->load->view('templates/yard_equipment_plan/viewer_panel', $data);
		
		if ($data['id_yard']){
			$xml_string = $this->yard->extract_yard_equipment_plan($data['id_yard']);
			// echo $xml_string;
			$data_yard = simplexml_load_string($xml_string);
			$data['width']  	= $data_yard->width;
			$data['height'] 	= $data_yard->height;
			$stack_cell			= $data_yard->index; 
			$data['index'] 		= explode(",", $stack_cell);
			$plan_cell			= $data_yard->plan; 
			$data['plan'] 		= explode(",", $plan_cell);
			$id_mch_cell		= $data_yard->id_machine; 
			$data['id_machine'] = explode(",", $id_mch_cell);
			$mch_name_cell		= $data_yard->mch_name; 
			$data['mch_name'] 	= explode(",", $mch_name_cell);
			$mch_color_cell		= $data_yard->mch_color; 
			$data['mch_color'] 	= explode(",", $mch_color_cell);
			$slot_cell			= $data_yard->slot; 
			$data['slot_'] 		= explode(",", $slot_cell);
			$row_cell			= $data_yard->row; 
			$data['row_'] 		= explode(",", $row_cell);
			$tier_cell			= $data_yard->tier; 
			$data['tier_'] 		= explode(",", $tier_cell);
			$title_cell			= $data_yard->title; 
			$data['title'] 		= explode(",", $title_cell);
			$block_id_cell		= $data_yard->block_id; 
			$data['block_id']	= explode(",", $block_id_cell);
			$orientation_cell	= $data_yard->orientation; 
			$data['orientation']= explode(",", $orientation_cell);
			$position_cell		= $data_yard->position; 
			$data['position'] 	= explode(",", $position_cell);
			$label_cell			= $data_yard->label;
			$data['label'] 		= explode(",", $label_cell);
			$label_text_cell	= $data_yard->label_text;
			$data['label_text'] = explode(",", $label_text_cell);

			/*debux($data['mch_name']);
			debux($data['mch_color']);*/

			/*$result = array();
			foreach ($data['mch_name'] as $element) {
			    $result[$element] = $element;
			}*/

			//debux($result);die;
			
			$this->load->view('templates/yard_equipment_plan/viewer_panel_content', $data);
		}
	}
	
	public function popup_machine(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_equipment_plan/popup_machine', $data);
	}
	
	public function data_yard_machine(){
		$data = $this->machine->get_data_machine('YARD');
		echo json_encode($data);
	}
	
	public function plan_yard_equipment(){
		$id_yard = $_GET['id_yard'];
		$xml_str = $_POST['xml_'];
		//echo $xml_str."<br/>";die();
		$retval = $this->machine->insert_plan_yard_equipment($id_yard, $xml_str);
		echo $retval;
	}
}