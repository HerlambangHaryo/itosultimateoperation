<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Single_stack_view extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('yard','user','machine','gtools'));
	}
	
	public function index($id_yard='', $id_block='', $slot=''){
		$data['tab_id'] = $_GET['tab_id'];
		$data['isRelocOn'] = $isRelocOn = isset($_GET['isRelocOn']) && $_GET['isRelocOn'] != '' ? $_GET['isRelocOn'] : 0;
		$id_ves_voyage  = $_POST['data_id'];
		// debux($_POST['data_id']);die();
		if (strpos($_POST['data_id'], '-') == true) {
    		$result 		= explode('-',$_POST['data_id']);
    		$id_block 		= $result[0];
    		$id_yard 		= $this->yard->get_yard_by_block($result[0]);
			if($slot==''){
				$slot		= $result[3];
			}
    		$totalslot		= $result[4];
    		$idpod		= $result[5];
    		$size		= $result[6];
    		$set		= $result[7];
    		$type		= $result[8];
    		$yjp		= $result[9];
    		$id_ves_voyage 	= $result[2];
		}
		
		if ($id_block!=''){
			$this->stack_viewer($id_yard, $id_block, $slot, $isRelocOn, $id_ves_voyage, $totalslot,$size,$idpod,$type,$yjp);
		}else{
			$data['idpod'] = '';
			$data['yard_list'] = $this->yard->get_yard_list();
			$this->load->view('templates/single_stack_view/viewer_panel', $data);
		}
	}
	
	public function stack_viewer($id_yard, $id_block, $slot, $isRelocOn, $id_ves_voyage='', $totalslot,$size,$idpod,$type,$yjp){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_yard'] = $id_yard;
		if($yjp=='oys'){
			$data['idpod'] = $idpod;
		}
		$data['id_block'] = $id_block;
		$data['slot'] = $slot;
		$data['id_ves_voyage'] = $id_ves_voyage;
		$data['isRelocOn'] = $isRelocOn;
		
		$data['yard_list'] = $this->yard->get_yard_list();
		$data['block_list'] = $this->yard->get_block_list($id_yard);
		$data['slot_list'] = $this->yard->get_slot_list($id_yard, $id_block);
		if($idpod!='' and $yjp=='oys'){
			$data['YD_SLOT'] = $this->yard->get_list_slot_single_stok_view($id_ves_voyage,$id_yard,$size,$idpod,$type,$id_block);
		// debux($data['YD_SLOT']);
		}
//		$data['void'] = $this->yard->get_void_list($id_yard, $id_block, $slot);
		$this->load->view('templates/single_stack_view/viewer_panel', $data);
		
		$data['row_list'] = $this->yard->get_row_list($id_yard, $id_block);
		$data['tier_list'] = $this->yard->get_tier_list($id_yard, $id_block);
		$data['void_list'] = $this->yard->get_void_list($id_yard, $id_block,$slot);
		$data['detail_void_list'] = $this->yard->get_void_list($id_yard, $id_block,$slot);
		$data['stack_profile'] = $this->yard->get_stack_profile_blockInfo($id_yard, $id_block, $slot);
		$data['stack_profile']['count_row'] = $data['stack_profile']['ROW_']-$data['stack_profile']['START_ROW_'];
		$data['category_list'] = $this->yard->get_yard_plan_category_per_slot($id_yard, $id_block, $slot);
//		$data['driver_list'] = $this->user->get_data_operator('ROLE_VMT');
//		$data['machine_list'] = $this->machine->get_data_machine('YARD');
//		echo '<pre>';print_r($data);echo '</pre>';exit;
		$this->load->view('templates/single_stack_view/stack_viewer_panel', $data);
	}
	
	public function data_block_list(){
		$id_yard = $_POST['id_yard'];
		$data = $this->yard->get_block_list($id_yard);
		
		echo json_encode($data);
	}
	
	public function data_slot_list(){
		$id_yard = $_POST['id_yard'];
		$id_block = $_POST['id_block'];
		$data = $this->yard->get_slot_list($id_yard, $id_block);
		
		echo json_encode($data);
	}
	
	public function save_void(){
	    $result = $this->yard->save_void($_POST);
	    echo $result;
	}
	
	public function yard_placement_submit(){
    	    $id_user = $this->session->userdata('id_user');
	    $act = $_POST['act'];
	    $no_container = $_POST['no_container'];
	    $point = $_POST['point'];
	    $id_op_status = $_POST['id_op_status'];
	    $event = $_POST['event'];
	    $id_machine = $_POST['id_machine'];
	    $driver_id = '';

	    $yard_position = array(
		'BLOCK_NAME'=>$_POST['block_name'],
		'BLOCK'=>$_POST['id_block'],
		'SLOT'=>$_POST['slot'],
		'ROW'=>$_POST['row'],
		'TIER'=>$_POST['tier']
	    );
//    		echo '<pre>yard_pos : ';print_r($yard_position);echo '</pre>';exit;
	    if($act == 'P'){
		$retval = $this->yard->yard_placement_submit($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id);
	    }elseif($act == 'R'){
		$retval = $this->yard->yard_relocation_submit($no_container, $point, $id_user, $yard_position,$id_machine );
	    }else{
		$retval = 'Gak pake vmt gue ya?';
	    }
	    echo json_encode($retval);
	}
	
	
	public function delete_void(){
	    $del = $_POST['del'];
	    
	    echo json_encode($this->yard->delete_void($del));
	}
}