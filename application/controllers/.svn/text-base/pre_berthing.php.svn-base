<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Pre_berthing extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('master');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/monitoring/vw_progress_loading', $data);
	}
	
	public function data_pre_berthing(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$arr_filter = array();
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$fromdate = isset($_REQUEST['fromdate']) ? json_decode($_REQUEST['fromdate']) : false;
		$todate = isset($_REQUEST['todate']) ? json_decode($_REQUEST['todate']) : false;
		$etb = isset($_REQUEST['etb']) ? json_decode($_REQUEST['etb']) : false;
		if($filters != false)
		    $arr_filter = $filters;
		if($fromdate != false)
		    array_push($arr_filter, $fromdate[0]);
		if($todate != false)
		    array_push($arr_filter, $todate[0]);
		if($etb != false)
		    array_push($arr_filter, $etb[0]);
		
		// echo '<pre>';print_r($arr_filter);echo '</pre>';exit;
		$data = $this->master->get_data_progress_loading_monitoring($paging, $sort, $arr_filter);
		//debux($data);die;
	
		echo json_encode($data);
	}
	
	public function export_to_excel(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['data_detail'] = $this->master->get_data_ete_progress_loading_monitoring();
		$this->load->view('templates/monitoring/ete_progress_loading', $data);
	}

}