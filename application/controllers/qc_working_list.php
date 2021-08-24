<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

session_start();

class Qc_working_list extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->helper('url');
	$this->load->helper('form');
	$this->load->library('form_validation');
	$this->load->model('vessel');
	$this->load->model('machine');
	$this->load->model('gtools');
	
	$this->gtools->update_terminal();
    }

    public function index() {
	$data['tab_id'] = $_GET['tab_id'];
	$data['id_ves_voyage'] = $_POST['data_id'];

	if ($data['id_ves_voyage'] != '') {
	    $this->load->view('templates/qc_working_list/qc_working_list_panel', $data);
	}
    }

    public function data_qc_working_list() {
	$data = $this->machine->get_qc_working_list($_GET['id_ves_voyage'], $_GET['type']);
	echo json_encode($data);
    }

    public function activate() {
	$id_ves_voyage = $_GET['id_ves_voyage'];
	$bay = $_GET['bay'];
	$deck_hatch = $_GET['location'];
	$activity = $_GET['activity'];
	$param = array(
	    array('name' => ':id_ves_voyage', 'value' => $id_ves_voyage, 'length' => 15),
	    array('name' => ':bay', 'value' => $bay, 'length' => 2),
	    array('name' => ':deck_hatch', 'value' => $deck_hatch, 'length' => 1),
	    array('name' => ':activity', 'value' => $activity, 'length' => 1),
	    array('name' => ':v_terminal', 'value' => $this->gtools->terminal(), 'length' => 22),
	    array('name' => ':msg', 'value' => &$msg, 'length' => 500)
	);
//		 print_r($param);exit;
	$sql = "BEGIN PROC_ACTIVATE_WORK_SEQUENCE(:id_ves_voyage, :bay, :deck_hatch, :activity, :v_terminal, :msg); END;";
	$this->db->exec_bind_stored_procedure($sql, $param);
//	print_r($msg);die;
	echo ($msg);
    }

    public function deactivate() {
	$id_ves_voyage = $_GET['id_ves_voyage'];
	$bay = $_GET['bay'];
	$deck_hatch = $_GET['location'];
	$activity = $_GET['activity'];
	$param = array(
	    array('name' => ':id_ves_voyage', 'value' => $id_ves_voyage, 'length' => 15),
	    array('name' => ':bay', 'value' => $bay, 'length' => 2),
	    array('name' => ':deck_hatch', 'value' => $deck_hatch, 'length' => 1),
	    array('name' => ':activity', 'value' => $activity, 'length' => 1),
	    array('name' => ':out_message', 'value' => &$msg, 'length' => 100)
	);
	$sql = "BEGIN PROC_DEACTIVATE_WORK_SEQUENCE(:id_ves_voyage, :bay, :deck_hatch, :activity, :out_message); END;";
	$this->db->exec_bind_stored_procedure($sql, $param);
	echo $msg;
    }

}
