<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Vessel_voyage extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model(array('vessel','gtools'));

	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		$data['id_ves_voyage'] = $_POST['data_id'];
		$data['mode'] = 'add';
		
		if ($data['id_ves_voyage']!=''){
			$data['mode'] = 'edit';
			$data['ves_voyage'] = json_encode($this->vessel->get_vessel_schedule($data['id_ves_voyage']));
		}

		$this->load->view('templates/vessel_voyage/vessel_voyage_panel', $data);
	}
	
	public function data_vessel_list(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_vessel_code($filter);
		echo json_encode($data);
	}
	
	public function data_kade(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_kade_list($filter);
		echo json_encode($data);
	}
	
	public function data_vessel_service(){
		$filter = $_GET['query'];
		$data	= $this->vessel->get_vessel_service($filter);
		echo json_encode($data);
	}
    
    public function data_stevedoring_companies(){
        $filter = $_GET['query'];
		$data	= $this->vessel->get_stevedoring_companies();
		echo json_encode($data);
    }

    public function get_vvd(){
    	// debux($_POST);
    	$vessel_name = $this->input->post('vessel_name');
    	$response 	 = $this->vessel->get_vvd($vessel_name);

    	$data = array('status' => 1,
    				  'response' => $response->LENGTH);

    	echo  json_encode($data);



    }
	
	public function save_vessel_voyage(){
		$data = $_POST;
		// echo "<pre>";print_r($_POST);echo "</pre>";
		$data['ETA'] = $_POST['ETA_DATE'].' '.str_pad($_POST['ETA_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['ETA_MIN'],2,'0',STR_PAD_LEFT);
		$data['ETB'] = $_POST['ETB_DATE'].' '.str_pad($_POST['ETB_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['ETB_MIN'],2,'0',STR_PAD_LEFT);
		$data['ETD'] = $_POST['ETD_DATE'].' '.str_pad($_POST['ETD_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['ETD_MIN'],2,'0',STR_PAD_LEFT);

		$data['CUTOFF_DATE_DOC'] = $_POST['CUTOFF_DATE_DOC'].' '.str_pad($_POST['CUTOFF_HOUR_DOC'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['CUTOFF_MIN_DOC'],2,'0',STR_PAD_LEFT);

		$data['CUTOFF_DATE'] = $_POST['CUTOFF_DATE'].' '.str_pad($_POST['CUTOFF_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['CUTOFF_MIN'],2,'0',STR_PAD_LEFT);

		$data['OPEN_STACK_DATE'] = $_POST['OPEN_STACK_DATE'].' '.str_pad($_POST['OPEN_STACK_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['OPEN_STACK_MIN'],2,'0',STR_PAD_LEFT);

		$data['EARLY_STACK_DATE'] = $_POST['EARLY_STACK_DATE'] != '' &&  $_POST['EARLY_STACK_DATE'] != 'Pick Date' ? $_POST['EARLY_STACK_DATE'].' '.str_pad($_POST['EARLY_STACK_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['EARLY_STACK_MIN'],2,'0',STR_PAD_LEFT) : '';		

        $data['STV_COMPANY_DISCHLOAD'] 	= $_POST['STV_COMPANY_DISCHLOAD'];
        $data['STV_COMPANY_LOLO'] 		= $_POST['STV_COMPANY_LOLO'];

        $data['BOOKING_STACK']			= $_POST['BOOKING_STACK'];
        $data['APP_BOOKING_STACK']		= $_POST['APP_BOOKING_STACK'];
        $data['STV_COMPANY']			= $_POST['ID_COMPANY'];

        $data['TL_RECEIVING']			= $_POST['TL_RECEIVING'];

        $data['FL_TONGKANG'] = $_POST['FL_TONGKANG'];
        if ($data['FL_TONGKANG'] == 'No') {
        	$data['FL_TONGKANG'] = 'N';
        }

		// print_r($data['TL_RECEIVING']);die; 

		if ($data['ID_VES_VOYAGE']!=''){
			$retval = $this->vessel->update_vessel_voyage($data);
		}else{
			$retval = $this->vessel->insert_vessel_voyage($data);
		}
		
		if ($retval){
			$data['success']=true;
		}
		
		echo json_encode($data);
	}
	
	public function check_voyage_number(){
		$id_ves_voyage = $_POST['id_ves_voyage'];
		$id_vessel = $_POST['id_vessel'];
		$voy_in = $_POST['voy_in'];
		$voy_out = $_POST['voy_out'];
		$retval = $this->vessel->check_voyage_number($id_vessel, $voy_in, $voy_out, $id_ves_voyage);
		echo $retval;
	}
	
	public function popup_container_operator(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$data['id_ves_voyage'] = $_POST['id_ves_voyage'];
		$this->load->view('templates/vessel_voyage/popup_container_operator', $data);
	}
	
	public function vessel_container_operator(){
		$data = $this->vessel->get_vessel_operator($_GET['id_ves_voyage']);
		echo json_encode($data);
	}
	
	public function popup_vessel_port(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$data['id_ves_voyage'] = $_POST['id_ves_voyage'];
		$this->load->view('templates/vessel_voyage/popup_vessel_port', $data);
	}
	
	public function vessel_port_list(){
		$data = $this->vessel->get_vessel_port($_GET['id_ves_voyage']);
		echo json_encode($data);
	}
}