<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Inbound_list extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->helper('general_helper');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];

		// $query_cekTongkang = "
		// 	SELECT
		// 		FL_TONGKANG
		// 	FROM
		// 		VES_VOYAGE
		// 	WHERE
		// 		ID_VES_VOYAGE = '".$data['id_ves_voyage']."'
		// ";
		// $rs_cekTongkang = $this->db->query($query_cekTongkang);
		// $row_cekTongkang = $rs_cekTongkang->row_array();
		// $data['fl_tongkang'] = $row_cekTongkang['FL_TONGKANG'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/inbound_list/upload_baplie', $data);
			$this->load->view('templates/inbound_list/inbound_list', $data);
		}
	}
	
	public function data_inbound_list(){
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$id_ves_voyage = $_GET['id_ves_voyage'];
		
		$retval = $this->container->get_data_inbound_outbound_list($id_ves_voyage, 'I', $paging, $sort, $filters, $container_list);
		echo json_encode($retval);
	}

	public function export_excel($id_ves_voyage){
		$data['result'] 		= $this->container->get_data_by_id_ves_voyage($id_ves_voyage);
		$data['id_ves_voyage']	= $id_ves_voyage;
		$data['vesvoy'] = $this->vessel->getVesvoy($id_ves_voyage);
//		debux($data);die;
		$this->load->view('templates/report/ete_report_inbound_list', $data);
	}
	
	public function update_list_detail($no_container, $id_ves_voyage, $no_container_old, $point){

		$data = $_POST;

		if (!isset($_POST['WEIGHT']) &&  $this->vessel->getFlTongkang($id_ves_voyage) == 'Y') {
			$data['WEIGHT'] = 0;
		}
		if ($data['UNNO']!='' || $data['IMDG']!=''){
			$data['HAZARD'] = 'Y';
		}else{
			$data['HAZARD'] = 'N';
		}
		$data['ID_VES_VOYAGE'] = $id_ves_voyage;
		$id_user = $this->session->userdata('id_user');
		if ($no_container_old=='-'){
			if ($data['ID_CLASS_CODE']==''){
				$data['ID_CLASS_CODE'] = 'I';
			}
			$data['POINT'] = $this->container->get_max_container_point($data['NO_CONTAINER']);
			$retval = $this->container->insert_listcont_detail($id_ves_voyage, $data, $id_user);
		}else{
			$data['POINT'] = $point;
			if ($no_container_old==$no_container){
				$retval = $this->container->update_listcont_detail($no_container, $point, $data, $id_ves_voyage);
			}else{
				$retval = $this->container->update_listcont_detail($no_container_old, $point, $data, $id_ves_voyage);
			}
		}
		echo $retval;
	}
	
	public function delete_list_detail($no_container_old, $point){
		if ($no_container_old!='-'){
			$retval = $this->container->delete_listcont_detail($no_container_old, $point);
			echo $retval;
		}else{
			echo 1;
		}
	}
	
	public function upload_baplie_import(){
		$id_user = $this->session->userdata('id_user');
		//print_r('tes');die;
		$retval = $this->container->insert_baplie_import($_POST, $_FILES, $id_user);
		
		$data['success']=$retval['flag'];
		$data['errors']=$retval['msg'];

		//debux($data);
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

	public function get_dataImdg($unno){
		$data	= $this->container->get_dataImdg($unno);
		echo json_encode($data);
	}

	public function get_dataUnno($imdg){
		$data	= $this->container->get_dataUnno($imdg);
		echo json_encode($data);
	}
}