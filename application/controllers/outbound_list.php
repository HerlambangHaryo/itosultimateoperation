<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Outbound_list extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('gtools');
		$this->load->model('vessel');
		$this->load->library('session');
		
		$this->gtools->update_terminal();
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/outbound_list/generate_baplie', $data);
			//$this->load->view('templates/outbound_list/generate_npe', $data);
			$this->load->view('templates/outbound_list/outbound_list', $data);
		}
	}
	
	public function data_outbound_list(){
		$container_list = $_REQUEST['container_list'];
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$id_ves_voyage = $_GET['id_ves_voyage'];
		
		$retval = $this->container->get_data_inbound_outbound_list($id_ves_voyage, 'E', $paging, $sort, $filters, $container_list);

		//debux($retval);die;
		echo json_encode($retval);
	}
	
	public function update_list_detail($no_container, $id_ves_voyage, $no_container_old, $point)
	{
		$data = $_POST;
		// echo '<pre>';print_r($data);echo '</pre>';

		$id_user = $this->session->userdata('id_user');
		if ($no_container_old=='-')
		{
			$data['NO_CONTAINER'] = $no_container;

			if ($data['ID_CLASS_CODE']=='')
			{
				$data['ID_CLASS_CODE'] = 'E';
			}
			// echo '<pre>insert</pre>';exit;
			$retval = $this->container->insert_listcont_detail($id_ves_voyage, $data, $id_user);
		}
		else
		{
			/*
				Arya,
				Ditambahkan :
				1. parameter $id_ves_voyage karena ketika update outboundlist $id_ves_voyage dianggap kosong
				2. NO_CONTAINER_UPDATE_OUTBOUND_LIST untuk bypass outbound list update spec handling
					karena tidak bisa melewati pengecekan no container, commoddity dan update di datanya
			*/

			$data['NO_CONTAINER_UPDATE_OUTBOUND_LIST'] = $no_container;

			if ($no_container_old == $no_container)
			{
				// echo '<pre>update</pre>';exit;
				$retval = $this->container->update_listcont_detail($no_container, $point, $data, $id_ves_voyage);
			}
			else
			{
				// echo '<pre>update old</pre>';exit;
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
	
	public function generate_baplie_export(){
		$id_user = $this->session->userdata('id_user');
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$modefile = $_POST['modefile'];
		$typefile = $_POST['typefile'];
		$filename = $_FILES['file']['name'];
//		echo '<pre>modefile : '.$modefile.'</pre>';
//		echo '<pre>typefile : '.$typefile.'</pre>';
		if ($modefile=='Upload NPE')
		{
			if ($filename!='')
			{
				$retval = $this->container->upload_npe($_POST, $_FILES, $id_user);
				
				$data['success']=$retval['flag'];
				$data['errors']=$retval['msg'];			
			}
			else
			{
				$data['success']=0;
				$data['errors']='file not found';				
			}
		}
		else
		{
			if ($typefile=='edifact'){
				$retval = $this->container->edi_baplie_outbound($id_ves_voyage, $id_user);
				$data['success']=$retval['flag'];
				$data['errors']=$retval['msg'];
			}else{
				$data['success']='success';
				$data['errors']=' ';
			}			
		}
//		echo '<pre>';print_r($data);echo '</pre>';exit;
		echo json_encode($data);
	}
	
	public function save_baplie_page(){
		$data['file_name'] = $_POST['data_id'];
		$this->load->view('templates/outbound_list/baplie_export_view', $data);
	}
	
	public function print_baplie($id_ves_voyage, $filetype){
		$data['id_ves_voyage'] = $id_ves_voyage;
		$data['username'] = $this->session->userdata('username');
		
//		$dataheader=$this->container->headerLoadingList($id_ves_voyage);
//		foreach($dataheader as $rowh){
//			$data['vsname']=$rowh['VESSEL_NAME'];
//			$data['ves_id']=$rowh['ID_VESSEL'];
//			$data['voyg']=$rowh['VOYG'];
//			
//			$data['rta']=$rowh['RTA'];
//			$data['berth']=$rowh['RTB'];
//			$data['rtd']=$rowh['RTD'];
//			$data['str']=$rowh['STW'];
//			$data['end']=$rowh['ENW'];
//		}
		
//		$data['datadetail']=$this->container->detailLoadingList($id_ves_voyage);

		/*$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;*/
		// $id_ves_voyage = $_GET['id_ves_voyage'];


		// $data['datadetail']=$this->container->get_data_inbound_outbound_list($id_ves_voyage, 'E');
		$data['datadetail']=$this->container->get_data_inbound_outbound_list($id_ves_voyage, 'E');


//		echo '<pre>';print_r($data['datadetail']);echo '</pre>';exit;
		$data['corporate_name']='PT. PELABUHAN TANJUNG PRIOK aka PTP';
		$data['vesvoy'] = $this->vessel->getVesvoy($id_ves_voyage);
		$data['date']=date('d-M-Y H:i');
		
		if ($filetype=='pdf'){
			$this->load->view('templates/outbound_list/baplie_export_pdf', $data);
		}else if ($filetype=='xls'){
			$this->load->view('templates/outbound_list/baplie_export_xls', $data);
		}
	}
	
	public function data_cont_size(){
		$data	= $this->container->get_cont_size_list();
		echo json_encode($data);
	}
	
	public function data_cont_type(){
		$data	= $this->container->get_cont_type_list();
		echo json_encode($data);
	}
	
	public function data_cont_spec_hand(){
		$data	= $this->container->data_cont_spec_hand();
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
	
    public function popup_upload_npe(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$data['no_container'] = $_POST['no_container'];
		$data['point'] = $_POST['point'];
		$this->load->view('templates/outbound_list/popup_upload_npe', $data);
	}
}