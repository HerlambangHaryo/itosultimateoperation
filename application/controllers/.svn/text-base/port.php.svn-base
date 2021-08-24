<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Port extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('general_helper','url','form'));
		$this->load->library('form_validation');
		$this->load->model('master');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/port/vw_port_grid', $data);
	}
	
	//filter paging short
	public function data_port(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->master->get_port($paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function form_addPort(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/port/vw_port_formAdd', $data);
	}

	public function form_editport($port_code,$tab_id){
		
		$data['tab_id'] = $tab_id;
		$data['port']	= $this->master->get_port_by_code($port_code);
		// var_dump($data['port']);die();

		$this->load->view('templates/port/vw_port_formEdit', $data);
	}
	
	public function save_port(){

		$data['id_user'] 	= $this->session->userdata('id_user');
		$data['port_code'] 	= strtoupper(trim($_POST['PORT_CODE']));
		$data['port_name'] 	= strtoupper(trim($_POST['PORT_NAME']));
		$data['foreground'] = strtoupper(trim($_POST['FOREGROUND_COLOR']));
		$data['background'] = strtoupper(trim($_POST['BACKGROUND_COLOR']));
		$data['is_active'] 	= strtoupper(trim($_POST['IS_ACTIVE']));

		$data['foreground'] = str_replace('#', '', $data['foreground']);
		$data['background'] = str_replace('#', '', $data['background']);

		// debux($data);die;

		$respose = $this->master->save_port($data);
		
		header('Content-Type: application/json');
		echo json_encode($respose);
	}

	public function edit_port()
	{
		$data['id_user'] 	= $this->session->userdata('id_user');
		$data['port_code'] 	= strtoupper(trim($_POST['PORT_CODE']));
		$data['port_name'] 	= strtoupper(trim($_POST['PORT_NAME']));
		$data['foreground'] = strtoupper(trim($_POST['FOREGROUND_COLOR']));
		$data['background'] = strtoupper(trim($_POST['BACKGROUND_COLOR']));
		$data['is_active'] 	= strtoupper(trim($_POST['IS_ACTIVE']));

		$data['foreground'] = str_replace('#', '', $data['foreground']);
		$data['background'] = str_replace('#', '', $data['background']);

		// debux($data);die;

		$respose = $this->master->edit_port($data);
		
		header('Content-Type: application/json');
		echo json_encode($respose);
	}
	
	public function enabled_or_disabled_port(){
		
		$port_code = strtoupper(trim($_POST['PORT_CODE']));
		$isActive = strtoupper(trim($_POST['IS_ACTIVE']));
		
		$data = $this->master->enabled_or_disabled_port($port_code, $isActive);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}