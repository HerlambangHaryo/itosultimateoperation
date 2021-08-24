<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Undeparture_vessel_voyage extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vessel');
		$this->load->model('gtools');
	}

	public function index(){
		$data['tab_id'] = $_GET['tab_id'];

		$this->load->view('templates/undeparture_vessel_voyage/editor_panel', $data);
	}

	public function data_vessel_departure(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$data	= $this->vessel->get_vessel_departure_list($paging, $sort, $filters);
		echo json_encode($data);
	}

	public function set_undeparture_vessel_voyage($id_ves_voyage){
		$retval = $this->vessel->set_undeparture_vessel_voyage($id_ves_voyage);
		echo $retval;
	}
}
