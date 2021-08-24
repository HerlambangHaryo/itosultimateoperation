<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Yard_builder extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('yard','gtools'));
	}

	public function index(){
		$data['tab_id'] = $_GET['tab_id'];

		$this->load->view('templates/yard_builder/builder_config', $data);
	}

	public function builder_panel(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['width']  = $_POST["width"];
		$data['height'] = $_POST["height"];

		$this->load->view('templates/yard_builder/builder_panel', $data);
	}

	public function create_yard(){
		$xml_str = $_POST['xml_'];
		$name = $_POST['yard_name'];
		$north_orientation = $_POST['north_orientation'];
		$sea_position = $_POST['sea_position'];
		//print_r($_POST);die;
		// echo $xml_str."<br/>";
		// echo $name."<br/>";
		$retval = $this->yard->insert_yard($xml_str, $name, $north_orientation, $sea_position);
		echo $retval;
	}
}
