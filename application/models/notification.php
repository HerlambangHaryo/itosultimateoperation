<?php
class Notification extends CI_Model {
	public function __construct(){
		$this->load->database();
	}
	
	function get_data_recipient($module_name){
		$param		= array($module_name);
		$query 		= "SELECT A.EMAIL_SUBJECT, A.EMAIL_TEMPLATE, A.SMS_TEMPLATE, B.EMAIL_RECIPIENT, B.SMS_RECIPIENT
						FROM M_NOTIFICATION_H A
							INNER JOIN 
							M_NOTIFICATION_D B
							ON A.MODULE_NAME=B.MODULE_NAME
						WHERE A.MODULE_NAME=?";
		$rs 		= $this->db->query($query,$param);
		$data 		= $rs->result_array();
		return $data;
	}
	
	function send_email_notification($recipient, $html, $text, $subject){
		$dbibis = $this->load->database('ibis', true);
		$param		= array($recipient, $html, $text, $subject);
		$query 		= "INSERT INTO EMAIL_LG 
						(TO_EMAIL,HTML_DATA,TEXT_DATA,SUBJECT_EMAIL)
						VALUES
						(?,?,?,?)";
		$dbibis->query($query,$param);
	}
	
	function send_sms_notification($mobile, $text){
		$dbibis = $this->load->database('ibis', true);
		$param		= array($mobile, $text);
		$query 		= "INSERT INTO SMS_LG 
						(MSISDN,TEXT)
						VALUES
						(?,?)";
		$dbibis->query($query,$param);
	}
}
?>