<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

session_start();

class Users extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->helper(array('general_helper', 'url', 'form'));
	$this->load->library('form_validation');
	$this->load->model(array('user','master'));
	$this->load->library('session');
    }

    public function index() {
	$data['tab_id'] = $_GET['tab_id'];
	$this->load->view('templates/master_users/vw_user_grid', $data);
    }

    //filter paging short
    public function data_user() {
	$paging = array(
	    'page' => $_REQUEST['page'],
	    'start' => $_REQUEST['start'],
	    'limit' => $_REQUEST['limit']
	);
	$sort = isset($_REQUEST['sort']) ? json_decode($_REQUEST['sort']) : false;
	$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;

	$retval = $this->user->get_users($paging, $sort, $filters);
	echo json_encode($retval);
    }

    public function form_addUser() {
	$data['tab_id'] = $_GET['tab_id'];
	$data['terminal_list'] = $this->master->get_all_terminal();
	$this->load->view('templates/master_users/vw_user_formAdd', $data);
    }

    public function form_editUser() {
	$data['tab_id'] = $_GET['tab_id'];
	$data['id_user'] = $id_user = $_POST['id_user'];
	$data['user'] = $this->user->get_user_detail($id_user);
	$data['terminal_list'] = $this->master->get_all_terminal();
	$data['terminal_assign'] = $this->user->user_terminal($id_user);
//		echo '<pre>';print_r($data['truck']);echo '</pre>';exit;
	$this->load->view('templates/master_users/vw_user_formEdit', $data);
    }

    public function check_username() {
	$retval = $this->user->check_username($_POST['username']);
	echo $retval;
    }

    public function save_user() {
	$_POST['CREATE_USER'] = $this->session->userdata('id_user');
	$response = $this->user->save_user($_POST);

	header('Content-Type: application/json');
	echo json_encode($response);
    }

    public function edit_user() {
	$_POST['MODIFY_USER'] = $this->session->userdata('id_user');
	
	$response = $this->user->edit_user($_POST);

	header('Content-Type: application/json');
	echo json_encode($response);
    }

    public function delete_user() {
	$id_user = trim($_POST['id_user']);
	$full_name = trim($_POST['full_name']);
	$respose = $this->user->delete_user($id_user, $full_name);

	header('Content-Type: application/json');
	echo json_encode($respose);
    }

    public function reset_password() {
	$_POST['tab_id'] = $_GET['tab_id'];
//	echo "<pre>";print_r($_POST);echo '</pre>';exit;
	$this->load->view('templates/master_users/vw_user_formResetPassword', $_POST);
    }

    public function save_reset_password() {
	$_POST['MODIFY_USER'] = $this->session->userdata('id_user');
	
	$response = $this->user->save_reset_password($_POST);

	header('Content-Type: application/json');
	echo json_encode($response);
    }

}
