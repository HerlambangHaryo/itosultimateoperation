<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Main extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('vessel','master','gtools'));
		$this->load->model('user');
		$this->load->library('session');
	}
	
	public function index(){
		$data['title'] = 'MyTOS Terminal';
		
		$this->load->view('templates/header', $data);
		if ($this->session->userdata('login')){
			$this->load->view('templates/main_layout', $data);
		}else{
			$this->load->view('templates/login', $data);
		}
		$this->load->view('templates/footer', $data);
	}
	
	public function menu_toolbar(){
		$menu = $this->user->menu_toolbar($this->session->userdata('username'));
		$user_detail = $this->user->get_user_detail($this->session->userdata('id_user'));
		
		$data['full_name'] = $user_detail['FULL_NAME'];
		$data['main_menu'] = $menu['main_menu'];
		$data['child_menu'] = $menu['child_menu'];
		//debux($data['child_menu']);die;
		$data['terminal_name'] = $this->gtools->terminal_name();
		$this->load->view('templates/menu_toolbar', $data);
	}
	
	public function vessel_schedule_grid(){
		$this->load->view('templates/vessel_schedule_grid', $data);
	}
	
	public function data_vessel_schedule(){
		$paging = array(
			'page'=>$_REQUEST['page'],
			'start'=>$_REQUEST['start'],
			'limit'=>$_REQUEST['limit']
		);
		$sort = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : false;
		$filters = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter']) : false;
		$data	= $this->vessel->get_vessel_schedule('', $paging, $sort, $filters);
		echo json_encode($data);
	}
	
	public function login(){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$terminal = $_POST['terminal'];
		$terminal_name = $_POST['terminal_name'];
		$retval	= $this->user->login($username, $password);
		
		$data = array(
			'success'=>false,
			'errors'=>'login error'
		);
		
		if ($retval){
			//cek assign terminal
			$isTerminalAssign = $this->user->user_terminal($retval['ID_USER']);
			
			if(in_array($terminal, $isTerminalAssign)){
//			$terminal = $this->master->get_terminal($retval['ID_TERMINAL']);
			    $activate_terminal = $this->user->activate_user_terminal($retval['ID_USER'],$terminal);
			    if($activate_terminal['IsSuccess']){
				$this->session->set_userdata('login', 1);
				$this->session->set_userdata('id_user', $retval['ID_USER']);
				$this->session->set_userdata('username', $username);
				$this->session->set_userdata('terminal', $terminal);
				$this->session->set_userdata('terminal_name', $terminal_name);
				$data['success']=true;
			    }else{
				$data = array(
					'success'=>false,
					'errors'=>$activate_terminal['Message']
				);
			    }
			}else{
			    $data = array(
				    'success'=>false,
				    'errors'=>'Anda tidak diizinkan mengakses terminal '.$terminal_name
			    );
			}
		}
		
		echo json_encode($data);
	}
	
	public function logout(){
	    $id_user = $this->session->userdata('id_user');
	    $deactivate = $this->user->deactivate_user_terminal($id_user);
	    if($deactivate['IsSuccess']){
		$this->session->sess_destroy();
	    }
	}
	
	public function terminal_list(){
	    echo json_encode($this->master->get_all_terminal());
	}
	
	public function group_list(){
	    echo json_encode($this->master->get_all_group());
	}
	
	public function get_active_vessel(){
		$data	= $this->vessel->get_active_vessel();
		echo json_encode($data);
	}
	public function get_terminal(){
	    echo json_encode($this->master->get_terminal());
	}
}