<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_reports extends CI_Controller {
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
		$this->load->view('templates/yard_reports/vw_yard_reports', $data);
	}

	public function data_stacking(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);



		if($_REQUEST['show_data'] != NULL) {
			$show_data = isset($_REQUEST['show_data']) ? json_decode($_REQUEST['show_data'])   : false;
			for ($i=0;$i<count($show_data);$i++){
				$sd = $show_data[$i];
			}
			// $sl = $sd->sl;
			// $io = $sd->io;
			// $lc = $sd->lc;
			$dfd = $sd->dfd;
			// $dfh = $sd->dfh;
			// $dfm = $sd->dfm;
			$dtd = $sd->dtd;
			// $dth = $sd->dth;
			// $dtm = $sd->dtm;
			if($dfd != NULL || $dtd != NULL){
				$dw_from = $dfd." ".$sd->dfh.":".$sd->dfm;
				$dw_to = $dtd." ".$sd->dth.":".$sd->dtm;
			}

			if($sd->sl != NULL){ $sl = $sd->sl; }
			if($sd->io != NULL){ $io = $sd->io; }
			if($sd->lc != NULL){ $lc = $sd->lc; }

		}else{
			$dw_from = '';
			$dw_to = '';
			$sl = '';
			$io = '';
			$lc = '';
		}

		//debux($_REQUEST);die;
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;

		$retval = $this->master->get_data_stacking($paging, $sort, $filters, $dw_from, $dw_to, $sl, $io, $lc);
		echo json_encode($retval);
	}

	public function get_data_yard(){
		$retval['data_detail'] = $this->master->get_data_yard();
		//debux($retval);die;
		$this->load->view('templates/yard_reports/ete_yard_reports', $retval);
	}

	public function get_data_shippingline(){
		$data = $this->master->get_shippingline();
		echo json_encode($data);
	}

	public function get_data_location(){
		$data = $this->master->get_location();
		echo json_encode($data);
	}
}
