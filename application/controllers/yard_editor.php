<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_editor extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('yard');
		$this->load->model('gtools');
		$this->load->model('user');
		$this->load->library('session');
	}

	public function index(){
		$data['tab_id'] = $_GET['tab_id'];

		$this->load->view('templates/yard_editor/yard_list', $data);
	}

	public function data_yard_list(){
		$data = $this->yard->get_yard_list();
		echo json_encode($data);
	}

	public function editor_panel(){
		$data['id_yard'] = $_GET['id_yard'];
		$data['tab_id'] = $_GET['tab_id'];

		$xml_string = $this->yard->extract_yard($data['id_yard']);
		// echo $xml_string;
		$data_yard = simplexml_load_string($xml_string);
		$data['width']  	= $data_yard->width;
		$data['height'] 	= $data_yard->height;
		$data['name'] 		= $data_yard->name;
		$stack_cell			= $data_yard->index;
		$data['index'] 		= explode(",", $stack_cell);
		$slot_cell			= $data_yard->slot;
		$data['slot_'] 		= explode(",", $slot_cell);
		$row_cell			= $data_yard->row;
		$data['row_'] 		= explode(",", $row_cell);
		$data['block'] 		= $data_yard->block;
		$data['block_sum'] 	= count($data['block']);
		$data['yard'] = $this->yard->get_yard_by_id($data['id_yard']);

		$this->load->view('templates/yard_editor/editor_panel', $data);
	}

	public function modify_yard(){
		$id_yard = $_GET['id_yard'];
		$xml_str = $_POST['xml_'];
		$name = $_POST['yard_name'];
		$north_orientation = $_POST['north_orientation'];
		// echo $xml_str."<br/>";
		// echo $name."<br/>";
		$retval = $this->yard->update_yard($id_yard, $xml_str, $name, $north_orientation);
		echo $retval;
	}

	public function delete_yard(){
		$id_yard = $_POST['id_yard'];
		$retval = $this->yard->delete_yard($id_yard);
		echo $retval;
	}
	
	public function login_unset(){
		$password = $_POST['password'];
		$terminal = $this->session->userdata('terminal');
		$terminal_name = $this->session->userdata('terminal_name');
		$retval	= $this->user->login($this->session->userdata('username'), $password);
		
		$data = array(
			'success'=>false,
			'errors'=>'Password salah'
		);
		
		if ($retval){
			//cek assign terminal
			$isTerminalAssign = $this->user->user_terminal($retval['ID_USER']);
			
			if(in_array($terminal, $isTerminalAssign)){
			    $activate_terminal = $this->user->activate_user_terminal($retval['ID_USER'],$terminal);
			    if($activate_terminal['IsSuccess']){
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
}
