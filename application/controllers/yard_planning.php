<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_planning extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('yard');
		$this->load->model('container');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $_GET['id_yard'];
		$data['id_yard_plan'] = $_GET['id_yard_plan'];
		$data['id_category'] = $_GET['id_category'];
		$data['tab_id_ypg'] = $_GET['tab_id_ypg'];
		$data['act'] = isset($_GET['act']) && $_GET['act'] != '' ? $_GET['act'] : 'add' ;
		$data['yard_list'] = $this->yard->get_yard_list();
		// debux($data);die;
		$this->load->view('templates/yard_planning/viewer_panel', $data);
		
		if ($data['id_yard']){
//		    echo 'id yard : '.$data['id_yard'];
			$xml_string = $this->yard->extract_yard_plan($data['id_yard']);
            //debux($xml_string);die;
			$data_yard = simplexml_load_string($xml_string);

			//debux($data_yard->plan);die;
			$data['total_stack'] = $this->yard->yard_stacking_info($data['id_yard']);
			$data['width']		= $data_yard->width;
			$data['height']		= $data_yard->height;
			$data['north_orientation']		= $data_yard->north_orientation;
			$stack_cell			= $data_yard->index; 
			$data['index'] 		= explode(",", $stack_cell);
			$plan_cell			= $data_yard->plan; 
			$data['plan'] 		= explode(",", $plan_cell);
			$taken_cell			= $data_yard->taken; 
			$data['taken'] 		= explode(",", $taken_cell);
			$placement_cell		= $data_yard->placement; 
			$data['placement'] 	= explode(",", $placement_cell);
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
			$bgcolor_cell	= $data_yard->bgcolor;
			$data['bgcolor'] = explode(",", $bgcolor_cell);
			// debux($data);die;
			
			$this->load->view('templates/yard_planning/viewer_panel_content', $data);
		}
	}
	
	public function popup_new_category(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_planning/popup_new_category', $data);
	}
	
	public function popup_master_paweight(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_planning/popup_master_paweight', $data);
	}

	public function popup_master_del_paweight(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/yard_planning/popup_master_del_paweight', $data);
	}
	
	public function data_paweight(){
		$data	= $this->container->data_paweight();
		echo json_encode($data);
	}
	
	public function get_datapaWeightD($id_paweight){
		$data	= $this->container->get_datapaWeightD($id_paweight);
		echo json_encode($data);
	}

	
	public function popup_existing_category(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['dtbl'] = $_GET['dtbl'];
		$data['rtbl'] = $_GET['rtbl'];
		$data['id_category'] = $_POST['id_category'];
		$data['category_name'] = isset($_POST['id_category']) && $_POST['id_category'] != '' ? $this->yard->get_plan_category_by_id($_POST['id_category']) : '';
		$data['edit_mode'] = $_POST['edit_mode'];
		
		$this->load->view('templates/yard_planning/popup_existing_category', $data);
	}
	
	public function data_vessel_schedule(){
		$data	= $this->vessel->get_vessel_schedule();
		echo json_encode($data);
	}
	
	public function data_category(){
		$data	= $this->container->get_category_list();
		echo json_encode($data);
	}
	
	public function data_category_existing(){
		$data	= $this->container->get_name_category_existing();
		echo json_encode($data);
	}
	
	public function data_category_detail($category_id){
		$data	= $this->container->get_category_detail($category_id);
		echo json_encode($data);
	}
	
	public function insert_category(){
		$category_name = $_POST['name'];
		$category_detail = json_decode($_POST['detail']);
		$retval = $this->container->insert_category($category_name, $category_detail);
		echo $retval;
	}
	
	public function insert_masterweight(){
		$category_name = $_POST['name'];
		$param = $_POST['param'];
		
		$retval = $this->container->insert_masterweight($category_name, $param);
		echo $retval;
	}
	
	public function insert_category_detail($category_id){
		$detail = $_POST;
		$retval = $this->container->insert_category_detail($category_id, $detail);
		echo $retval;
	}
	
	public function update_category_detail($category_id, $detail_id){
		$data = $_POST;
		$retval = $this->container->update_category_detail($category_id, $detail_id, $data);
		echo $retval;
	}

	public function delete_masterweight(){
		$category_name = $_POST['name'];

		$retval = $this->container->delete_masterweight($category_name);
		echo $retval;
		//debux($category_name);die;
	}
	
	public function delete_category_detail($category_id, $detail_id){
		$retval = $this->container->delete_category_detail($category_id, $detail_id);
		echo $retval;
	}
	
	public function delete_category_plan(){
		$category_id = $_POST['category_id'];
		$retval = $this->container->delete_category_plan($category_id);
		echo $retval;
	}
	
	public function data_cont_size(){
		$data	= $this->container->get_cont_size_list();
		echo json_encode($data);
	}
	
	public function data_cont_type(){
		$data	= $this->container->get_cont_type_list();
		echo json_encode($data);
	}
	
	public function data_cont_status(){
		$data	= $this->container->get_cont_status_list();
		echo json_encode($data);
	}
	
	public function data_port(){
		$filter = $_GET['query'];
		$id_ves_voyage = $_GET['id_ves_voyage'];
		$data	= $this->container->get_port_list($filter,$id_ves_voyage);
		echo json_encode($data);
	}
	
	public function data_vessel_schedule_autocomplete(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_vessel_schedule_list($filter);
		echo json_encode($data);
	}

	public function data_active_vessel(){
		$data	= $this->vessel->get_active_vessel();
		echo json_encode($data);
	}
	
	public function data_operator(){
		$filter = $_GET['query'];
		$id_ves_voyage = $_GET['id_ves_voyage'];
		$data	= $this->container->get_operator_list($filter,$id_ves_voyage);
		echo json_encode($data);
	}
	
	public function data_cont_height(){
		$data	= $this->container->get_cont_height_list();
		echo json_encode($data);
	}
	
	public function data_unno_list(){
		$filter = $_GET['query'];
		$data	= $this->container->get_unno_list($filter);
		echo json_encode($data);
	}

	public function data_imdg_list(){
		$filter = $_GET['query'];
		$data	= $this->container->get_imdg_list($filter);
		echo json_encode($data);
	}

	public function get_dataImdg($unno){
		$data	= $this->container->get_dataImdg($unno);
		echo json_encode($data);
	}

	public function get_dataUnno($imdg){
		$data	= $this->container->get_dataUnno($imdg);
		echo json_encode($data);
	}
	
	public function plan_yard(){
		$id_yard = $_GET['id_yard'];
		$act = isset($_GET['act']) && $_GET['act'] != '' ? $_GET['act'] : 'add' ;
		$xml_str = $_POST['xml_'];
//		 echo $xml_str."<br/>";die;
		$retval = $this->yard->insert_plan_yard($id_yard, $xml_str, $act);
		echo $retval;
	}
	
	public function popup_rename_category(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['category_id'] = $_POST['category_id'];
		$data['category_name'] = $_POST['category_name'];
		
		$this->load->view('templates/yard_planning/popup_rename_category', $data);
	}
	
	public function rename_category_plan(){
		$category_id = $_POST['category_id'];
		$category_name = $_POST['category_name'];
		$retval = $this->container->rename_category_plan($category_id, $category_name);
		echo $retval;
	}
	
	public function getSlotCategory(){
	    $category = $_POST['category'];
	    $retval = $this->container->get_slot_category($category);
	    echo json_encode($retval);
	}
}