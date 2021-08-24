<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Roles extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('general_helper','url','form'));
		$this->load->library('form_validation');
		$this->load->model('user');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/master_roles/vw_role_grid', $data);
	}
	
	//filter paging short
	public function data_roles(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		
		$retval = $this->user->get_roles($paging, $sort, $filters);
		echo json_encode($retval);
	}
	
	public function form_addRole(){
		$data['tab_id'] = $_GET['tab_id'];
		$this->load->view('templates/master_roles/vw_role_formAdd', $data);
	}

	public function form_editRole(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_group'] = $id_group = $_POST['id_group'];
		$data['group']	= $this->user->get_group_by_id($id_group);
//		echo '<pre>';print_r($data['truck']);echo '</pre>';exit;
		$this->load->view('templates/master_roles/vw_role_formEdit', $data);
	}
	
	public function check_group(){
		$group_name = $_POST['group_name'];
		$retval = $this->user->check_group($group_name);
		echo $retval;
	}
	
	public function save_group(){

		$data['CREATE_USER'] 	= $this->session->userdata('id_user');
		$data['ID_GROUP'] 	= strtoupper(trim($_POST['ID_GROUP']));
		$data['GROUP_NAME']	= strtoupper(trim($_POST['GROUP_NAME']));
		
		$response = $this->user->save_group($data);
		
		header('Content-Type: application/json');
//		echo '<pre>';print_r($response);echo '</pre>';exit;
		echo json_encode($response);
	}

	public function edit_group()
	{
	    $data['MODIFY_USER'] 	= $this->session->userdata('id_user');
	    $data['ID_GROUP'] 	= strtoupper(trim($_POST['ID_GROUP']));
	    $data['GROUP_NAME'] 	= strtoupper(trim($_POST['GROUP_NAME']));
	    
	    $response = $this->user->edit_group($data);

	    header('Content-Type: application/json');
	    echo json_encode($response);
	}
	
	public function delete_group(){
	    $id_group 	= strtoupper(trim($_POST['ID_GROUP']));
	    $group_name 	= strtoupper(trim($_POST['GROUP_NAME']));
	    $response = $this->user->delete_group($id_group,$group_name);

	    header('Content-Type: application/json');
	    echo json_encode($response);
	}
	
	public function assign_menu_form(){
	    $data['tab_id'] = $_GET['tab_id'];
	    $data['id_group'] = $_POST['ID_GROUP'];
	    $data['group_name'] = $_POST['GROUP_NAME'];
	    $data['menu'] = $this->user->get_menu_list();
	    
	    
	    $this->load->view('templates/master_roles/vw_role_formAssignMenu', $data);
	}
	
	public function save_assign_menu(){
//	    echo '<pre>';print_r($_POST);echo '</pre>';exit;
	    
	    $response = $this->user->save_assign_menu($_POST);
	    header('Content-Type: application/json');
	    echo json_encode($response);
	}
}