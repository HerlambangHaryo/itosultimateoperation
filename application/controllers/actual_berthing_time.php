<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Actual_berthing_time extends CI_Controller {
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
		$data['id_ves_voyage'] = $_POST['data_id'];
		
		if ($data['id_ves_voyage']!=''){
			$data['ves_voyage'] = json_encode($this->vessel->get_vessel_schedule($data['id_ves_voyage']));
			//debux($data);die;
			$this->load->view('templates/actual_berthing_time/actual_berthing_panel', $data);
		}
	}
	
	public function save_actual_berthing_time(){
		$data['ID_VES_VOYAGE'] = $_POST['ID_VES_VOYAGE'];
		$data['ATA'] = $_POST['ATA_DATE'].' '.str_pad($_POST['ATA_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['ATA_MIN'],2,'0',STR_PAD_LEFT);
		if ($_POST['ATB_DATE']!='Pick Date'){
			$data['ATB'] = $_POST['ATB_DATE'].' '.str_pad($_POST['ATB_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['ATB_MIN'],2,'0',STR_PAD_LEFT);
		}else{
			$data['ATB'] = '';
		}
		if ($_POST['ATD_DATE']!='Pick Date'){
			$data['ATD'] = $_POST['ATD_DATE'].' '.str_pad($_POST['ATD_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['ATD_MIN'],2,'0',STR_PAD_LEFT);
		}else{
			$data['ATD'] = '';
		}
		//lcommencedate
		if ($_POST['lcommence_DATE']!='Pick Date'){
			$data['LOAD_COMMENCE'] = $_POST['lcommence_DATE'].' '.str_pad($_POST['lcommence_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['lcommence_MIN'],2,'0',STR_PAD_LEFT);
		}else{
			$data['LOAD_COMMENCE'] = '';
		}
		//lcompletedate
		if ($_POST['lcomplete_DATE']!='Pick Date'){
			$data['LOAD_COMPLETE'] = $_POST['lcomplete_DATE'].' '.str_pad($_POST['lcomplete_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['lcomplete_MIN'],2,'0',STR_PAD_LEFT);
		}else{
			$data['LOAD_COMPLETE'] = '';
		}
		// dcommencedate
		if ($_POST['dcommence_DATE']!='Pick Date'){
			$data['DISCHARGE_COMMENCE'] = $_POST['dcommence_DATE'].' '.str_pad($_POST['dcommence_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['dcommence_MIN'],2,'0',STR_PAD_LEFT);
		}else{
			$data['DISCHARGE_COMMENCE'] = '';
		}
		// dcompletedate
		if ($_POST['dcomplete_DATE']!='Pick Date'){
			$data['DISCHARGE_COMPLETE'] = $_POST['dcomplete_DATE'].' '.str_pad($_POST['dcomplete_HOUR'],2,'0',STR_PAD_LEFT).':'.str_pad($_POST['dcomplete_MIN'],2,'0',STR_PAD_LEFT);
		}else{
			$data['DISCHARGE_COMPLETE'] = '';
		}
		
		// print_r($data);
		if($data['ATB'] == '' && $data['ATD'] != ''){
		    $data = array(
			    'success'=>false,
			    'errors'=>'ATB must be filled.'
		    );
		}else{
		    $retval = $this->vessel->update_actual_berthing_time($data);

		    $data = array(
			    'success'=>false,
			    'errors'=>'update error'
		    );

		    if ($retval){
			    $data['success']=true;
		    }
		}
		
		echo json_encode($data);
	}
	
	public function departure_vessel_voyage($id_ves_voyage){
		$retval = $this->vessel->departure_vessel_voyage($id_ves_voyage);
		echo $retval;
	}
}