<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class EDIServices extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('vessel');
		$this->load->model('gtools');
		$this->load->model('mailer');
	}
	
	public function send_email($cc=null, $bcc=null){
		echo "BEGIN\n";
		
		$count = 0;
		// get list vessel operator active for edi service
		$list_ves_operator = $this->vessel->get_edi_service_config();
		foreach ($list_ves_operator as $vessel_operator){
			$count += 1;
			echo $count." --START \n";
			
			$ves_operator = $vessel_operator['VES_OPERATOR'];
			$email = $vessel_operator['EMAIL_ADDRESS'];
			echo $ves_operator."\n";
			echo $email."\n";
			
			// variable store attachment
			$attachment = array();
			
			// get file codeco
			$retval_codeco = $this->container->edi_codeco_service('-1', $ves_operator);
			// echo "\tflag: ".$retval_codeco['flag']."\n";
			// echo "\tfiles: \n";
			// print_r($retval_codeco['files']);
			if ($retval_codeco['flag']=='1'){
				foreach ($retval_codeco['files'] as $file_to_send){
					array_push($attachment, './edifact/codeco/'.$file_to_send);
				}
			}
			
			// get file coarri
			$retval_coarri = $this->container->edi_coarri_service('-1', $ves_operator);
			// echo "\tflag: ".$retval_coarri['flag']."\n";
			// echo "\tfiles: \n";
			// print_r($retval_coarri['files']);
			if ($retval_coarri['flag']=='1'){
				foreach ($retval_coarri['files'] as $file_to_send){
					array_push($attachment, './edifact/coarri/'.$file_to_send);
				}
			}
			
			$flag = $this->mailer->send_email(
			'it@indonesiaport.co.id',
			$email,
			'EDI '.$ves_operator,
			'EDI of '.$ves_operator.' sent from ITOS',
			$cc,
			$bcc,
			$attachment);
			if ($flag==1){
				$this->container->edi_service_flag_send(2);
				echo "sent edi to: ".$email."\n";
			}else{
				$this->container->edi_service_flag_send(0);
				echo "failed edi, message: ".$flag."\n";
			}
			
			echo $count." --FINISH \n";
		}
		
		echo "END\n";
	}
}