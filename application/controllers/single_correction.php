<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Single_correction extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/single_correction/single_correction_panel', $data);
	}
	
	public function data_single_correction(){
		$point = false;
		if (isset($_POST['point'])) {
			$point = $_POST['point'];
		}

		//debux($point);die;
		$retval = $this->container->get_data_single_correction($_POST['no_container'], $point);

		//debux($retval);die();


		$query1 = "SELECT 
			   		A.ID_OP_STATUS,
			   		A.ID_VES_VOYAGE
			   	   	FROM CON_LISTCONT A
			   	WHERE A.NO_CONTAINER = '".$_POST['no_container']."'
			   	AND A.POINT = '".$point."'";
		$ress  		  = $this->db->query($query1)->row();
		$id_op_status = $ress->ID_OP_STATUS;

		$text_message = "";
		if($id_op_status=='SLY'){
			$text_message = "Container Already Loaded";
		}elseif($id_op_status=='OYS'){
			$text_message = "Container Onchasis";
		}elseif($id_op_status=='YSY'){
			$text_message = "Container Stacking";
		}else{
			$text_message = "Error";
		}


		if($retval['ID_CLASS_CODE']=='I'){
			#container inbound
			$validasi_req = $this->container->validasi_req_sp2($_POST['no_container'], $point);
			if($validasi_req>0){
				$data = array(
					'success'=>false,
					'errors'=>'container already request'
				);
				echo json_encode($data);
				exit;
			}
			
			if($id_op_status=='YSY'){ #SDY
				$data = array(
					'success'=>false,
					'errors'=>$text_message
				);
				echo json_encode($data);
				exit;
			}

		}else{
			#container outbound
			if($id_op_status=='SLY' || $id_op_status=='OYS'){
				$data = array(
					'success'=>false,
					'errors'=> $text_message
				);
				echo json_encode($data);
				exit;
			}

		}
		
		$data = array(
			'success'=>false,
			'errors'=>'container not found error'
		);
		
		if ($retval){
			$data['success']=true;
			$data['errors']='';
			$data['data']=json_encode($retval);
		}
		
		echo json_encode($data);
	}
	
	public function save_single_correction(){
		$id_user = $this->session->userdata('id_user');
		//debux($_POST);die;
		$data = $this->container->save_single_correction($_POST,$id_user);
		
		echo json_encode($data);
	}
	
	public function list_of_point(){
		$data = $this->container->get_container_list_of_point($_GET['no_container']);
		echo json_encode($data);
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
	
	public function data_port($alias){
		$filter = $_GET['query'];
		$id_ves_voyage = $_GET['id_ves_voyage'];
		$data	= $this->container->get_port_list($filter,$id_ves_voyage);
		for ($i=0; $i<sizeof($data); $i++){
			$data[$i][$alias] = $data[$i]['PORT_CODE'];
		}
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
	
	public function data_cont_commodity(){
		$data	= $this->container->get_cont_commodity_list();
		echo json_encode($data);
	}
	
	public function data_cont_iso_code(){
		$filter = $_GET['query'];
		$data	= $this->container->get_cont_iso_code_list($filter);
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
	
	public function data_cont_class_code(){
		$filter = $_GET['query'];
		$data	= $this->container->data_class_code($filter);
		echo json_encode($data);
	}
	
	public function data_vessel_schedule_autocomplete(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_vessel_schedule_list($filter);
		echo json_encode($data);
	}
}