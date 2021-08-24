<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class vmt_monitoring extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('machine');
		$this->load->model('user');
		$this->load->model('gtools');
		$this->load->model('vessel');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/vmt_monitoring/alat', $data);
	}
	
	public function data_alat(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$arr_filter = array();
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$filter_nama_alat = isset($_REQUEST['filter_nama_alat']) ? json_decode($_REQUEST['filter_nama_alat']) : false;
		
		$sfil = array(
			'prop' => $_REQUEST['sort'],
			'filter_nama_alat' => $_REQUEST['filter_nama_alat']
		);

		if($filters != false)
		    $arr_filter = $filters;
		if($filter_nama_alat != false)
		    array_push($arr_filter, $filter_nama_alat[0]);
		$retval = $this->container->get_data_alat_vmt_monitoring($paging, $sort, $arr_filter);
//		echo '<pre>';print_r($retval);echo '</pre>';exit;
		echo json_encode($retval);
	}
}