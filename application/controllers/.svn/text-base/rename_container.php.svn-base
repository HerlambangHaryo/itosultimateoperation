<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Rename_container extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->model('gtools');
		$this->load->model('container');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/rename_container/main_panel', $data);
	}
	
	public function loadContents($a,$b){
		$data['tab_id'] = $b;
		if($a=="null")
		{
			$data['containerSrc']='';
		}
		else
		{
			$data['containerSrc']=$a;
		}
		
		$data['rowsDetail']=$this->container->getContainerDetail($a);
		//echo $data['rowsDetail'];
		$this->load->view('templates/rename_container/content_panel', $data);
	}
	
	public function saveRename(){
		$a=$_POST['OLDCONT'];
		//CLASSPOINT
		$b=$_POST['CLASSPOINT'];
		$c=$_POST['NEWCONT'];
		$id_user = $this->session->userdata('id_user');
		$data=$this->container->saveRenameContainer($a,$b,$c,$id_user);
		//echo $data['rowsDetail'];
		echo json_encode($data);
	}
}
