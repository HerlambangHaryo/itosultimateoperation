<?php
class Mailer extends CI_Model {
	public function __construct(){
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => '10.10.33.126',
			'smtp_port' => 25,
			'smtp_user' => 'it', 
			'smtp_pass' => 'Bsiemailp0rtal', 
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		$this->load->library('email', $config);
		$this->load->database();
	}
	
	function send_email($from, $to, $subject, $message, $cc=null, $bcc=null, $attachment=null){
		$this->email->clear(true);
		$this->email->from($from);
		$this->email->to($to); 
		$this->email->subject($subject);
		$this->email->message($message);
		if ($cc){
			$this->email->cc($cc); 
		}
		if ($bcc){
			$this->email->bcc($bcc);
		}
		if ($attachment){
			foreach ($attachment as $file){
				$this->email->attach($file);
			}
		}
		if ($this->email->send()){
			$this->log($to, $message, 1);
			return 1;
		}else{
			$this->log($to, $this->email->print_debugger(), 0);
			return $this->email->print_debugger();
		}
	}
	
	function log($email, $message, $status){
		$param = array($email, $message, $status);
		$query = "INSERT INTO MAIL_LOG
					(EMAIL_ADDRESS, LOG_MESSAGE, STATUS)
					VALUES (?,?,?)";
		$this->db->query($query, $param);
	}
}
?>