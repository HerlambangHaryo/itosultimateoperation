<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Stowage_instruction_summary extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->model('yard');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$this->load->view('templates/stowage_instruction_summary/summary', $data);
		}
	}

	public function get_data_stowage_summary($id_ves_voyage){	
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);	
		$arr_filter = array();
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$filter_pod = isset($_REQUEST['filter_pod']) ? json_decode($_REQUEST['filter_pod']) : false;
		
		$sfil = array(
			'prop' => $_REQUEST['sort'],
			'filter_pod' => $_REQUEST['filter_pod']
		);

		if($filters != false)
		    $arr_filter = $filters;
		if($filter_pod != false)
		    array_push($arr_filter, $filter_pod[0]);
		$retval = $this->yard->stowage_summary_group($paging, $sort, $arr_filter,$id_ves_voyage);
		$data['id_ves_voyage']  = $id_ves_voyage;
		// debux($data);

		echo json_encode($retval);

	}
}